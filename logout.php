<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	$usersession->logout();
	redirect_to("login.php");
?>