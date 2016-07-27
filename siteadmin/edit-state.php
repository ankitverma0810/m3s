<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
?>
<?php
	if(empty($_GET['stateid']))
	{
		$session->message("No State id was provided", "error");
		redirect_to('view-states.php');
	}
	else
	{
		$state = States::find_by_id($_GET['stateid']);
	}
?>
<?php
	if(isset($_POST['submit']))
	{			
		$url = seo_url($_POST['title']);		
		$state->add_states($_POST['title'], $_POST['status'], $_POST['featured'], $url, $_POST['keywords'], $_POST['description']);
		if($state->save())
		{
			$session->message("State updated Successfully", "success");
			redirect_to('view-states.php');
		}
		else
		{
			$message = join("<br />", $state->errors);
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
  
  <form action="edit-state.php?stateid=<?php echo $state->id; ?>" method="post">
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
  	<tr>
      <td width="19%"> Title: </td>
      <td width="81%"> <input name="title" type="text" class="textfield" value="<?php echo $state->title; ?>" /> </td>
    </tr>
    
    <tr valign="top">
      <td width="19%"> Meta Keywords: </td>
      <td width="81%"> <textarea name="keywords" cols="" rows="" class="textarea"><?php echo $state->meta_keywords; ?></textarea> </td>
    </tr>
    
    <tr valign="top">
      <td width="19%"> Meta Description: </td>
      <td width="81%"> <textarea name="description" cols="" rows="" class="textarea"><?php echo $state->meta_description; ?></textarea> </td>
    </tr>

    <tr>
      <td width="19%"> Status: </td>
      <td width="81%"> 
      	<input name="status" type="radio" value="1" class="radio" <?php if($state->status==1) {echo "checked";} ?>/> <p> Visible </p>
        <input name="status" type="radio" value="0" class="radio" <?php if($state->status==0) {echo "checked";} ?> /> <p> Not Visible </p>
      </td>
    </tr>
    
    <tr>
      <td width="19%"> Featured: </td>
      <td width="81%"> 
      	<input name="featured" type="radio" value="1" class="radio" <?php if($state->featured==1) {echo "checked";} ?>/> <p> Featured </p>
        <input name="featured" type="radio" value="0" class="radio" <?php if($state->featured==0) {echo "checked";} ?>/> <p> Not Featured </p>
      </td>
    </tr>
    
    <tr>
      <td width="19%">&nbsp;</td>
      <td width="81%"> 
      	<input name="submit" type="submit" class="submit" value="UPDATE STATE" />
        <input name="cancel" type="reset" class="submit" value="CANCEL" />
      </td>
    </tr>
  </table>
  </form>
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
