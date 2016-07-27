<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
	if(basename($_SERVER['REQUEST_URI']) == 'index.php')
	{
		echo "<meta http-equiv='refresh' content='60' >";
	}
	else
	{
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
	}
?>

<title>M3S</title>
<!--rich text editor files-->
<script type="text/javascript" src="filemanager_in_ckeditor/js/ckeditor/ckeditor.js"></script>
<script src="filemanager_in_ckeditor/sample.js" type="text/javascript"></script>
<link href="filemanager_in_ckeditor/sample.css" rel="stylesheet" type="text/css" />
<link href="filemanager_in_ckeditor/js/ckeditor/skins/kama/editor.css" rel="stylesheet" type="text/css" />
<!--rich text editor files-->
</head>
<body>
<div class="wrapper">
  <div class="header">
    <div class="logo">
      <img src="../images/logo.jpg" style="margin-left:10%;" />    
    </div><!--logo-->    
    
    <div class="header-right">
      <ul>
        <li> <a href="index.php"> Home </a> </li>
        <li> <a href="users.php"> Users </a> </li>
        <li> <a href="admin.php"> Admin </a> </li>
        <li> <a href="logout.php"> Logout </a> </li>
      </ul>
      <div class="clear"></div>
    </div><!--header-right-->    
    <div class="clear"></div>
  </div><!--header-->