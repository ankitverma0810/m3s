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
		$state = new States();				
		$url = seo_url($_POST['title']);		
		$state->add_states($_POST['title'], $_POST['status'], $_POST['featured'], $url, $_POST['keywords'], $_POST['description']);
		if($state->save())
		{
			$session->message("State Added Successfully", "success");
			redirect_to('view-states.php');
		}
		else
		{
			$session->message(join("<br />", $state->errors), "error");
			redirect_to('add-states.php');
		}
	}
?>
<?php include_layout_template('admin-header.php'); ?>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

<div class="sidebar">
<?php include_layout_template('admin-sidebar.php'); ?>
</div><!--sidebar-->

<div class="content">
  <h2> State Manager </h2>
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
  
  <form action="add-states.php" method="post">
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
  	<tr>
      <td width="19%"> Title: </td>
      <td width="81%"> <input name="title" type="text" class="textfield" /> </td>
    </tr>
    
    <tr valign="top">
      <td width="19%"> Meta Keywords: </td>
      <td width="81%"> <textarea name="keywords" cols="" rows="" class="textarea"></textarea> </td>
    </tr>
    
    <tr valign="top">
      <td width="19%"> Meta Description: </td>
      <td width="81%"> <textarea name="description" cols="" rows="" class="textarea"></textarea> </td>
    </tr>

    <tr>
      <td width="19%"> Status: </td>
      <td width="81%"> 
      	<input name="status" type="radio" value="1" class="radio" checked="checked" /> <p> Visible </p>
        <input name="status" type="radio" value="0" class="radio" /> <p> Not Visible </p>
      </td>
    </tr>
    
    <tr>
      <td width="19%"> Featured: </td>
      <td width="81%"> 
      	<input name="featured" type="radio" value="1" class="radio" /> <p> Featured </p>
        <input name="featured" type="radio" value="0" class="radio" checked="checked" /> <p> Not Featured </p>
      </td>
    </tr>
    
    <tr>
      <td width="19%">&nbsp;</td>
      <td width="81%"> 
      	<input name="submit" type="submit" class="submit" value="ADD STATE" />
        <input name="cancel" type="reset" class="submit" value="CANCEL" />
      </td>
    </tr>
  </table>
  </form>
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
