<?php
	function strip_zeros_from_date($marked_string)
	{
		$no_zeros = str_replace('*0', '', $marked_string);
		$cleaned_string = str_replace('*', '', $no_zeros);
		return $cleaned_string;
	}	
	
	function redirect_to($location = NULL)
	{
		if($location != NULL)
		{
			header("Location: {$location}");
			exit;	
		}
	}
		
	function __autoload($class_name)
	{
		$class = strtolower($class_name);
		$path = LIB_PATH.DS."{$class_name}.php";
		if(file_exists($path))
		{
			require_once($path);
		}
		else
		{
			die("The File {$class_name}.php could not be found");
		}
	}	
	
	function include_layout_template($temp="")
	{
		include(SITE_ROOT.DS.'layouts'.DS.$temp);
	}
		
	function log_action($action, $message="")
	{
		$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
		if($handle = fopen($logfile, 'a'))
		{
			$timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
			$content = "{$timestamp} | {$action} : {$message}\r\n";
			fwrite($handle, $content);
			fclose($handle);
		}
		else
		{
			echo "Could not open log file for writing";
		}
	}	
	
	function datetime_to_text($datetime="")
	{
		$unixdatetime = strtotime($datetime);
		//return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
		return strftime("%B %d, %Y", $unixdatetime);
	}
	
	function expiry_datetime_to_text($datetime="")
	{
		$unixdatetime = strtotime($datetime);
		//return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
		return strftime("%Y-%m-%d", $unixdatetime);
	}
	
	function cropText($text, $limit)
	{
		$croppedText = '';
		$text = html_entity_decode($text);
		$text = strip_tags($text);
		
		$descLen = strlen($text);
		if($descLen > $limit)
		{						
			for ($i=0; $i<$limit; $i++)								
			{
				$croppedText .= $text[$i];
			}
		}
		else
		{
			$croppedText = $text;
		}
		return $croppedText;
	}
	
	function string_limit_words($string, $word_limit)
	{
		$words = explode(' ', $string);
		return implode(' ', array_slice($words, 0, $word_limit));
	}
	
	function seo_url($seo_title)
	{
		//Title to friendly URL conversion
		$title = mysql_real_escape_string($seo_title);
		$urltitle=preg_replace('/[^a-z0-9]/i',' ', $title);
		$newurltitle=str_replace("\\","",$urltitle);
		$newurltitle=str_replace(" ","-",$newurltitle);
		$newurltitle=ereg_replace("[^A-Za-z0-9\-]", "", $newurltitle);
		$newurltitle=str_replace("--","-",$newurltitle);
		$newurltitle=strtolower($newurltitle);
		$newurltitle=str_replace("--","-",$newurltitle);
		return $newurltitle;
		//Inserting values into my_blog table
	}
	
	function search_name($word)
	{
		//Title to friendly URL conversion
		$title = mysql_real_escape_string($seo_title);
		$urltitle=preg_replace('/[^a-z0-9]/i',' ', $title);
		$newurltitle=str_replace("\\","",$urltitle);
		$newurltitle=str_replace(" ","-",$newurltitle);
		$newurltitle=ereg_replace("[^A-Za-z0-9\-]", "", $newurltitle);
		$newurltitle=str_replace("--","-",$newurltitle);
		$newurltitle=strtolower($newurltitle);
		$newurltitle=str_replace("--","-",$newurltitle);
		return $newurltitle;
		//Inserting values into my_blog table
	}
	
	/*function generateUniqueId($idLength='8')
	{
		//set the random id length
		$random_id_length = $idLength;
		//generate a random id encrypt it and store it in $rnd_id
		$rnd_id = crypt(uniqid(rand(),1));
		//to remove any slashes that might have come
		$rnd_id = strip_tags(stripslashes($number));
		//Removing any . or / and reversing the string
		$rnd_id = str_replace(".","",$rnd_id);
		$rnd_id = strrev(str_replace("/","",$rnd_id));
		//finally I take the first 10 characters from the $rnd_id
		$rnd_id = substr($rnd_id,0,$random_id_length);
		$rnd_id = str_replace( "$", "",$rnd_id ); // Remove $ if present from uniqID 
		return $rnd_id;
	}*/
	
	function generateUniqueId($idLength='8')
	{
		$rnd_id = substr(number_format(time() * rand(),0,'',''),0,$idLength);
		return $rnd_id;
	}
	
	//taking character before and after a particular special character (1&tablename)//
	function str_before($subject, $needle)
	{
		$p = strpos($subject, $needle);
		return substr($subject, 0, $p);
	}
	
	function str_after($subject, $needle)
	{
		$p = strpos($subject, $needle);
		if($p!==false)
		{
			return substr($subject, $p+strlen($needle));
		}
	}
	
	//get full url
	function getUrlAddress()
	{
		return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	
	//convert an object to an array
	function convert_object_to_array($Data)
	{
    if(!is_object($Data) && !is_array($Data)) return $Data;
 
    if(is_object($Data)) $Data = get_object_vars($Data);
 
    return array_map('convert_object_to_array', $Data);
	}
	
	//===============for table background color================//
	function classified_color($color)
	{
		if($color == 1)
		{
			$bgcolor = '#efffe6';
		}
		if($color == 2)
		{
			$bgcolor = '#F5F5F5';
		}
		if($color == 3)
		{
			$bgcolor = '#fffee8';
		}
		if($color == 4)
		{
			$bgcolor = '#edf0ff';
		}
		if($color == 5)
		{
			$bgcolor = '#ffe9e9';
		}
		return $bgcolor;
	}
?>