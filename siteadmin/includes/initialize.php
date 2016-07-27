<?php
	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR); //DIRECTORY_SEPARATOR means:- /
	if($_SERVER['SERVER_NAME']=="localhost")
	{
		defined('SITE_ROOT') ? null : define('SITE_ROOT', 'C:'.DS.'wamp'.DS.'www'.DS.'m3h'.DS.'siteadmin');
	}
	else
	{
		defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'home'.DS.'m3s'.DS.'public_html'.DS.'siteadmin');
	}	
	defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');
	
	require_once(LIB_PATH.DS."site-constants.php");		
	require_once(LIB_PATH.DS."config.php");
	
	//load basic function next so that everything after can use them
	require_once(LIB_PATH.DS."functions.php");
	
	//load core objects
	require_once(LIB_PATH.DS."session.php");
	require_once(LIB_PATH.DS."user-session.php");
	require_once(LIB_PATH.DS."connection.php");
	require_once(LIB_PATH.DS."DatabaseObject.php");	
	require_once(LIB_PATH.DS."change-password.php");
	require_once(LIB_PATH.DS."class.relink.php");
	
	require_once(LIB_PATH.DS."cms.php");
	require_once(LIB_PATH.DS."category.php");
	require_once(LIB_PATH.DS."subcategory.php");
	require_once(LIB_PATH.DS."states.php");	
	require_once(LIB_PATH.DS."areas.php");
	require_once(LIB_PATH.DS."register.php");
	require_once(LIB_PATH.DS."classifieds.php");
	require_once(LIB_PATH.DS."response.php");
	require_once(LIB_PATH.DS."popular-search.php");
	require_once(LIB_PATH.DS."alerts.php");
	require_once(LIB_PATH.DS."rates.php");
	require_once(LIB_PATH.DS."orders.php");
	require_once(LIB_PATH.DS."access.php");
?>