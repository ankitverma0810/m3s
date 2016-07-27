<?php require_once("siteadmin/includes/initialize.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>M3H</title>
<link href="css/stylesheet.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="wrapper">
  <?php include("header.php"); ?>
  
  <div class="bread_crump">
                <a href="#">Home</a> &raquo; <a href="#">My Webrex.com</a> &raquo; 
                My Details
        </div><!--bread_crump--> 
  
  <div class="clear"></div>
  
    <div class="inner-container">
       <div class="ad-left">
          <h1>My  Details</h1>
          
          <div class="my-detail-container">
          
            <div class="my-detail-form">
               <label>Enter Your Name:</label>
               <input name="" type="text" class="textfield" />
               <p>Name Like Ankit</p>
               <div class="clear"></div>
            </div><!--ad-form-->
             
            <div class="my-detail-form">
               <label>Login Id /Email Id:</label>
              <input name="" type="text" class="textfield" />
               <div class="clear"></div>
            </div><!--ad-form-->
             
            <div class="my-detail-form">
               <label>Mobile Number:</label>
              <input name="" type="text" class="textfield" />
               <div class="clear"></div>
            </div><!--ad-form-->
            
             <div class="my-detail-form">
               <label>Website /Blog URL:</label>
              <input name="" type="text" class="textfield" />
               <div class="clear"></div>
            </div><!--ad-form-->
            
              <div class="my-detail-form">
               <label>GoogleTalk Id:</label>
              <input name="" type="text" class="textfield" />
               <div class="clear"></div>
            </div><!--ad-form-->
            
            <div class="my-detail-form">
               <label>Facebook UserName:</label>
              <input name="" type="text" class="textfield" />
               <div class="clear"></div>
            </div><!--ad-form-->
            
            <div class="my-detail-form">
               <label>&nbsp;</label>
				<input name="" type="submit" value="Submit" class="submit" />
                <input name="" type="submit" value="Reset" class="reset" />
               <div class="clear"></div>
            </div><!--ad-form-->
             
 
               
             
          </div><!--ad-form-contaimer-->
       </div><!--ad-left-->
       
        <?php include("inner-sidebar.php"); ?>
       
       <div class="clear"></div>
    </div><!--inner-container-->
    
  
  <?php include("footer.php"); ?>
</div><!--wrapper-->
</body>
</html>
