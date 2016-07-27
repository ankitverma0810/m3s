<?php
	if($_SERVER['SERVER_NAME']=="localhost")
	{
		defined('SITE_ROOT_URL') ? null : define('SITE_ROOT_URL', 'http://localhost/m3h/'); //SITE URL	
	}
	else
	{
		defined('SITE_ROOT_URL') ? null : define('SITE_ROOT_URL', 'http://www.m3s.in/'); //SITE URL
	}
	
	defined('EMAIL_FROM') ? null : define('EMAIL_FROM', 'donotreply@m3s.in'); //email constant
	
	//CONSTANTS FOR SMTP CONFIGURATIONS STARTS HERE...
	define("SMTP_HOST","mail.m3s.in");
	define("SMTP_PORT",587);
	define("SMTP_HELO","mail.m3s.in");
	define("SMTP_AUTH",true);
	define("SMTP_USER","mail@m3s.in");
	define("SMTP_PASS","123456");			
	define('SMTP','');
	ini_set(SMTP,"mail.m3s.in");
?>