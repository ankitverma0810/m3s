<?php require_once(LIB_PATH.DS."connection.php"); ?>
<?php
	class access extends DatabaseObject
	{
		function __construct()
		{
			$this->track();
		}
		function track()
		{
			// VISIT - unique visitor / day (cookie is valid only today)
			if(substr(getUrlAddress(),0,26) == 'http://m3s.in/details/')
			{
				setcookie(getUrlAddress(), getUrlAddress(), mktime(23,59,59, date('m'), date('d'), date('Y')), '/');
			}
	
			// ip address
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$explode_ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				$ip = $explode_ip[0];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
	
			// if cookie exists, then visit = 0
			// else we check if the IP address access last 20 minutes else it is visit
			if(isset($_COOKIE[getUrlAddress()])) {
				$visit = 0;
			} else {
				$query = mysql_query('SELECT date FROM mh_access WHERE ip = "'.$ip.'" AND page_name = "'.getUrlAddress().'" AND DATE_ADD(date, INTERVAL "20" MINUTE) > NOW() LIMIT 0,1');
				$visit = ($result = mysql_fetch_array($query)) ? 0 : 1;
			}
			
			//inserting into database only unique visitore
			//inserting only listing detail page records; means only ads detail page not any other page
			if($visit == 1 && substr(getUrlAddress(),0,26) == 'http://m3s.in/details/')
			{
				mysql_query('INSERT INTO mh_access(page_name,ip,visit) VALUES("'.getUrlAddress().'","'.$ip.'","'.$visit.'")');
			}			
			/*if you want to track every function in this page then insert below query instead of above (dont use if condition)
			mysql_query('INSERT INTO mh_access(page_name,ip,visit) VALUES("'.getUrlAddress().'","'.$ip.'","'.$visit.'")');*/
			
		}
		function getVisits()
		{
			$query = mysql_query('SELECT count(aid) FROM mh_access WHERE visit = 1');
			$result = mysql_fetch_array($query);
			return $result['count(aid)'];
		}
		function getUniqueVisits()
		{
			$query = mysql_query("SELECT count(DISTINCT ip) as count_ip FROM mh_access WHERE page_name = '".getUrlAddress()."'");
			$result = mysql_fetch_array($query);
			return $result['count_ip'];
		}
		function getPageViews()
		{
			$query = mysql_query('SELECT count(aid) FROM mh_access');
			$result = mysql_fetch_array($query);
			return $result['count(aid)'];
		}
		function lastAccess()
		{
			$query = mysql_query('SELECT max(date) AS maxdate FROM mh_access');
			return ($result = mysql_fetch_array($query)) ? $result['maxdate'] : FALSE;
		}
	}
	$visits = new access();
?>