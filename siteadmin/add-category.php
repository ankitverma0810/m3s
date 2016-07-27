<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
?>
<?php
	$max_file_size = 1048576;	
	if(isset($_POST['submit']))
	{
		//--------------code for checking whether post category already exists or not starts from here------------//
		$saved_categories = Category::find_all();
		foreach($saved_categories as $saved_category)
		{
			if($saved_category->title == $_POST['title'])
			{
				$session->message("Category Already Exists", "error");
				redirect_to('add-category.php');
			}
		}
		//--------------code for checking whether post category already exists or not ends from here------------//
			
		$category = new Category();
		$url = seo_url($_POST['title']);
		$tablename = str_replace("-", "_", 'mh_'.$url);
		
		$category->title = $_POST['title'];
		$category->attach_file($_FILES['filename']);
		$category->status = $_POST['status'];
		$category->url = $url;
		$category->meta_keywords = $_POST['keywords'];
		$category->meta_description = $_POST['description'];
		$category->tablename = $tablename;	
		if($category->save())
		{		
			if($category->create_table($tablename))
			{
				$session->message("Category Created Successfully", "success");
				redirect_to('view-category.php');
			}
			else
			{
				$session->message("Some error occured please try again!", "error");
				redirect_to('view-category.php');
			}
		}
		else
		{
			$session->message(join("<br />", $category->errors), "error");
			redirect_to('add-category.php');
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
  
  <form action="add-category.php" method="post" enctype="multipart/form-data">
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
  	<tr>
      <td width="24%"> Category Title: </td>
      <td width="76%"> <input name="title" type="text" class="textfield" /> </td>
    </tr>
    
    <tr>
      <td width="24%"> Category Icon: </td>
      <td width="76%">
      	<input name="MAX_FILE_SIZE" type="hidden" value="<?php echo $max_file_size; ?>" />
      	<input name="filename" type="file" size="35" />
      </td>
    </tr>
    
    <tr valign="top">
      <td width="24%"> Meta Keywords: </td>
      <td width="76%"> <textarea name="keywords" cols="" rows="" class="textarea"></textarea> </td>
    </tr>
    
    <tr valign="top">
      <td width="24%"> Meta Description: </td>
      <td width="76%"> <textarea name="description" cols="" rows="" class="textarea"></textarea> </td>
    </tr>

    <tr>
      <td width="24%"> Status: </td>
      <td width="76%"> 
      	<input name="status" type="radio" value="1" class="radio" checked="checked" /> <p> Visible </p>
        <input name="status" type="radio" value="0" class="radio" /> <p> Not Visible </p>
      </td>
    </tr>
    
    <tr>
      <td width="24%">&nbsp;  </td>
      <td width="76%"> 
      	<input name="submit" type="submit" class="submit" value="ADD CATEGORY" />
        <input name="reset" type="reset" class="submit" value="CANCEL" />
      </td>
    </tr>
  </table>
  </form>
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
