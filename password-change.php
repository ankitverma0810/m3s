<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(!$usersession->is_userlogged_in())
	{
		redirect_to("index.php");
	}
	else
	{
		$user = Register::find_by_id($_SESSION['reguser_id']);
	}
	if(isset($_POST['submit']))
	{
		$user->change_password($_POST['new-password'], $_POST['confirm-password']);
		if($user->save())
		{
			$session->message('Password changed successfully!!', 'success');
			redirect_to('password-change.php');
		}
		else
		{
			$session->message(join("<br />", $user->errors), 'error');
			redirect_to('password-change.php');
		}
	}
?>
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
  
  <?php
  	if(!empty($message))
	{
		if($session->message_type == "error")
		{
			echo "<div class='error_message'> $message </div><!--error_message-->";
		}
		else
		{
			echo "<div class='success_message'> $message </div><!--success_message-->";
		}
	}
   ?>
  
  <div class="welcome-text">
    <span style="color:#1b387d; font-size:15px;">Welcome to M3S.in</span>, a free classified ads portal of India for all categories as Education, Automobiles, Travels, Real Estate etc.
  </div><!--welcome-text-->
  
  <div class="clear"></div>
  
    <div class="inner-container">
       <?php include("inner-sidebar.php"); ?>
       <div class="ad-left">
          <h1>Change Password</h1>
          
          <form action="password-change.php" method="post">
          <div class="ad-form-contaimer">          
             <div class="ad-form">
               <label>Login Name:</label>
               <p><?php echo $user->email; ?></p>
               <div class="clear"></div>
             </div><!--ad-form-->
             
              <div class="ad-form">
               <label><span class="red"> *</span>New Password:</label>
               <input name="new-password" type="password"  class="textfield"/>
               <div class="clear"></div>
             </div><!--ad-form-->
             
               <div class="ad-form">
               <label><span class="red"> *</span>Confirm Password:</label>
               <input name="confirm-password" type="password"  class="textfield"/>
               <div class="clear"></div>
             </div><!--ad-form-->
             
             <div class="ad-form" style="margin-top:-10px;">
               <label>&nbsp;</label>
               <input name="submit" type="submit" value="Change Password" class="submit"/>
               <div class="clear"></div>
             </div><!--ad-form-->            
          </div><!--ad-form-contaimer-->
          </form>
       </div><!--ad-left-->
       
       <div class="clear"></div>
    </div><!--inner-container-->    
  
  <?php include("footer.php"); ?>
</div><!--wrapper-->
</body>
</html>