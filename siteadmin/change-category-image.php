<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
?>
<?php
	if(empty($_GET['catid']))
	{
		$session->message("No Category id was provided", "error");
		redirect_to('view-category.php');
	}
	else
	{
		$category = Category::find_by_id($_GET['catid']);
	}
?>
<?php
	$max_file_size = 1048576;	
	if(isset($_POST['submit']))
	{
		$category->attach_file($_FILES['filename']);		
		if($category->save())
		{
			$session->message("Image updated Successfully", "success");
			redirect_to('view-category.php');
		}
		else
		{
			$message = join("<br />", $category->errors);
		}
	}
?>
<?php include_layout_template('admin-header.php'); ?>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

<div class="sidebar">
<?php include_layout_template('admin-sidebar.php'); ?>
</div><!--sidebar-->

<div class="content">
  <h2> Category Manager </h2>
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
  
  <form action="change-category-image.php?catid=<?php echo $category->id; ?>" method="post" enctype="multipart/form-data">
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
  
  	<tr>
      <td width="24%"> Category Icon: </td>
      <td width="76%">
      	<input name="MAX_FILE_SIZE" type="hidden" value="<?php echo $max_file_size; ?>" />
      	<input name="filename" type="file" size="35" />
      </td>
    </tr>
    
    <tr>
      <td width="24%">&nbsp;</td>
      <td width="76%"> 
      	<input name="submit" type="submit" class="submit" value="CHANGE IMAGE" />
        <input name="reset" type="reset" class="submit" value="CANCEL" />
      </td>
    </tr>
  </table>
  </form>
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
