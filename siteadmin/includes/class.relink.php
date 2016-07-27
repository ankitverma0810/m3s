<?php
	/**
	 * RELINK v.0.2.1
	 *	Copyright 2008 Benjamin Falk
	 *	Contact:	falk [at] citrosaft [dot] net

	 *	This program is free software: you can redistribute it and/or modify
	 *	it under the terms of the GNU General Public License as published by
	 *	the Free Software Foundation, either version 3 of the License, or
	 *	(at your option) any later version.

	 *	This program is distributed in the hope that it will be useful,
	 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *	GNU General Public License for more details.

	 *	You should have received a copy of the GNU General Public License
	 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
	 */
	
	error_reporting(E_ALL);
	
	class RELINK
	{
		/*
			The following variables get
			automatically filled up
		*/
		var $auto			= false;			//Gets true, when .htaccess contains #set link auto on
		var $rules			= array();			//Contains all link-rules
		var $replacements	= array();			//Contains all replacements
		var $parts			= array();			//Contains all parts of a rule
		var $linkStart		= '';				//Contains the startvalue of every link

		/*
			CONSTRUCTOR
			Reads a given .htaccess-file and adds rules
			
			(string)	$file			Contains the path of .htaccess
			(boolean)	$ignoreFile		True, if you want to ignore for example index.php in index.php?foo=bar
		*/
		function __construct($file='./.htaccess', $ignoreFile=true)
		{
			$htaccess = file($file);
			foreach ($htaccess as $line)
			{
				//Check if the auto-detection is turned on
				if ($this->auto === false)
				{
					if (strtolower(trim($line)) == '#set link auto on')
						$this->auto = true;
				}
				else
				{
					$line = trim($line);
					if (strtolower(trim($line)) == '#set link auto off')
						$this->auto = false;
					elseif (strtolower(substr($line,0,($lslen = strlen('#set link start ')))) == '#set link start ')
						$this->linkStart = substr($line,$lslen);
					elseif (substr($line,0,strlen('RewriteRule ')) == 'RewriteRule ')
					{
						//Add rule...
						$regex = $replacement = '';
						list($null, $regex, $replacement) = preg_split('/[\s]+/', $line);
						$this->addRule($regex, $replacement, $ignoreFile);
					}
				}
			}
			
			if ($this->linkStart == '')
			{
				if (isset($_SERVER['PHP_SELF']))
					$this->linkStart .= dirname($_SERVER['PHP_SELF']).'/';
			}
			if (substr($this->linkStart,-1) != '/') $this->linkStart .= '/';
		}

		/*
			(boolean) addRule
			Adds a rule for rewriting the links easier
			
			(string)	$regex			Contains the part in what the link should replaced in
										For example (.+?)\.html for main.html
			(string)	$replacement	Contains the real page, so main.html should get into index.php?page=main
		*/
		function addRule($regex, $replacement, $ignoreFile=true)
		{
			if ($regex == '' || $replacement == '') return false;
			
			//Get parts of replacement
			if ($ignoreFile === true) {
				$replacement = preg_replace('/^([\w\d\.\/]*)\?/', '', $replacement);
			}
			else
				if (substr($replacement,0,1) == '?') $replacement = substr($replacement,1);
			parse_str($replacement, $replacementParts);
			
			$availParts = array();
			foreach ($replacementParts as $key => $value)
			{
				if (substr($value,0,1) == '$')
				{
					$availParts[intval(substr($value,1))] = $key;
				}
				elseif (substr($key,0,1) == '$')
				{
					$availParts[intval(substr($key,1))] = $value;
				}
			}
			
			if (substr($regex,0,1) == '^') $regex = substr($regex,1);
			if (substr($regex,-1) == '$') $regex = substr($regex,0,-1);
			
			array_unshift($this->parts, $availParts);
			array_unshift($this->rules, $regex);
			array_unshift($this->replacements, $replacement);
			
			return true;
		}
		
		/*
			(mixed) replaceLink
			Converts a normal link such as ?foo=bar into the known
			replacement set by an .htaccess-file.
			
			(string)	$link			Contains the baselink
			(boolean)	$ignoreFile		If true, the filename of the link gets ignored and only
										the variables after the questmark gets parsed.
		*/
		function replaceLink($link, $ignoreFile=true)
		{
			if (preg_match('/(^http\:\/\/)|(^mailto\:)/i', $link)) return $link; //Ignore absolute links
			if (substr($link,0,1) == '/') return $link; //Ignore root-links like /index.php
			
			$_link = $link;
			if ($ignoreFile === true)
			{
				$link = preg_replace('/^([\w\d\.\/]*)\?/', '', $link);
			}
			else
				if (substr($link,0,1) == '?') $link = substr($link,1);
			parse_str($link, $linkParts);
			$partsCount = count($linkParts);
			
			//Get the rule with the exact parts
			foreach ($this->parts as $index => $part)
			{
				$continue = false;
				if (count($part) == $partsCount)
				{	//If the given values have the same count as the current rule...
					foreach ($linkParts as $key => $val)
					{
						if ($key !== '' && $val === '') //Ignore if there are different parts like ?var1&var2=1
							continue;
						else
						{
							//Check if the exact parts are avail
							if (!in_array($key, $part))
							{
								$continue = true;
								break;
							}
						}
					}
					
					if ($continue == true)
					{	//If you want to replace a link such as ?val1&val2: This is the part, which does that
						foreach ($linkParts as $key => $val)
						{
							//Check if the exact parts are avail
							if (in_array($val, $part))
								$continue = false;
							else
							{
								$continue = true;
								break;
							}
						}
					}
					
					if ($continue) continue;
					
					//Got ya!
					$rule = $this->rules[$index];
					$ruleParts = preg_split('/(\(.+?\))|\{.*?\}|\[.*\]/s', $rule, -1, PREG_SPLIT_DELIM_CAPTURE);
					
					$rIndex	= 0;
					$nLink	= '';
					$failed	= false;
					foreach ($ruleParts as $rulePart)
					{
						if ($rulePart == '') continue;
						
						if (substr($rulePart,0,1) == '(' && substr($rulePart,-1) == ')')
						{
							//Current one is a variable
							$rIndex++;
							if ($this->parts[$index][$rIndex] === null)
							{
								$failed = true;
								break;
							}
							
							if ($this->parts[$index][$rIndex] === '')
							{	//If the given link is like ?foo&bar, this might be the part, which is checking everything
								reset($linkParts);
								if ($rIndex == 1)
									$nLink.= addslashes(key($linkParts));
								else
								{
									$_rIndex = 1;
									while ((current($linkParts)) !== false)
									{
										next($linkParts);
										$_rIndex++;
										if ($_rIndex == $rIndex)
											$nLink.= addslashes(key($linkParts));
									}
								}
							}
							else
							{
								$rVar = $this->parts[$index][$rIndex];
								$nLink.= addslashes($linkParts[$rVar]);
							}
						}
						else
							$nLink .= $rulePart;
					}
					
					$nLink = stripslashes($nLink);
					
					if ($failed == true)	return $this->linkStart.$_link;
					else					return $this->linkStart.$nLink;
					
					break;
				}
			}
			//Nothing found...
			return $this->linkStart.$_link;
		}
	}
?>