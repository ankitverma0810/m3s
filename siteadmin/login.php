<?php require_once("includes/initialize.php"); ?>
<?php
	if($session->is_logged_in())
	{
		redirect_to("index.php");
	}
?>
<?php
	if(isset($_POST['submit']))
	{			
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		$hashpassword = sha1($password);
		
		$found_user = user::authenticate($username, $hashpassword);
		
		if($found_user)
		{
			$session->login($found_user);
			//log_action('Login', "{$found_user->username} logged in. ");
			redirect_to("index.php");
		}
		else
		{
			$message = "The username/Password Combination is incorrect";
		}
	}
	else 
	{ // Form has not been submitted.
		$username = "";
		$password = "";
	}
?>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

<div class="main-wrapper">
  <div class="logo" style="padding-top:60px; float:none;">
  	<img src="../images/logo.jpg" />  </div>
  <!--logo-->
  
  <div class="clear"></div>
  	
  <div class="login-container">
    <!--code for checking whether any error or success msg exists STARTS here-->
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
  <!--code for checking whether any error or success msg exists END here-->
  
    <form action="login.php" method="post">
      <div class="login">
        <label> Username: </label>
        <input type="text" name="username" value="<?php echo htmlentities($username); ?>" class="textfield" />
        <div class="clear"></div>
      </div><!--login-->
      
      <div class="login">
        <label> Password: </label>
        <input type="password" name="password" value="<?php echo htmlentities($password); ?>" class="textfield" />
        <div class="clear"></div>
      </div><!--login-->
      
      <div class="login">
        <label> &nbsp; </label>
        <input type="submit" name="submit" value="SIGN IN" class="submit" />
        <div class="clear"></div>
      </div><!--login-->
    </form>
  </div><!--login-container-->
</div><!--main-wrapper-->