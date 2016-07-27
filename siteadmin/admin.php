<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
?>
<?php
	if(isset($_POST['submit']))
	{
		$password = password::find_by_id($_SESSION['user_id']);
		$password->settings($_POST['old_password'], $_POST['new_password'], $_POST['confirm_password']);
		if($password->save())
		{
			$session->message("Password Change successfully", "success");
			redirect_to('admin.php');
		}
		else
		{
			$session->message(join("<br />", $password->errors), "error");
			redirect_to('admin.php');
		}
	}
?>
<?php include_layout_template('admin-header.php'); ?>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

<div class="sidebar">
<?php include_layout_template('admin-sidebar.php'); ?>
</div><!--sidebar-->

<div class="content">
  <h2> Change Password </h2>
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
    
  <form action="admin.php" method="post">
  	<table width="100%" cellpadding="7" cellspacing="1" class="table">
        <tr>
            <td width="21%"> Old Password: </td>
            <td width="79%"> <input name="old_password" type="password" class="textfield" /></td>
        </tr>
        
        <tr>
            <td> New Password: </td>
            <td> <input name="new_password" type="password" class="textfield" /></td>
        </tr>
        
        <tr>
            <td> Confirm Password: </td>
            <td> <input name="confirm_password" type="password" class="textfield" /></td>
        </tr>
        
        <tr>
            <td>&nbsp;  </td>
            <td> <input name="submit" type="submit" class="submit" value="SUBMIT" /> </td>
        </tr>
    </table>
  </form>
  
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
