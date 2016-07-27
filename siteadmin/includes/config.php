<?php
	// Database Constants
	if($_SERVER['SERVER_NAME']=="localhost")
	{
		defined("DB_SERVER") ? null : define("DB_SERVER", "localhost");
		defined("DB_USER") ? null : define("DB_USER", "root");
		defined("DB_PASS") ? null : define("DB_PASS", "");
		defined("DB_NAME") ? null : define("DB_NAME", "m3h");
	}
	else
	{	
		defined("DB_SERVER") ? null : define("DB_SERVER", "localhost");
		defined("DB_USER") ? null : define("DB_USER", "m3s_databaseuser");
		defined("DB_PASS") ? null : define("DB_PASS", "idv5uS0LT%%a");
		defined("DB_NAME") ? null : define("DB_NAME", "m3s_db");
	}
?>