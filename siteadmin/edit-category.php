<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
	if(empty($_GET['catid']))
	{
		$session->message("No Category id was provided", "error");
		redirect_to('view-category.php');
	}
	else
	{
		$category = Category::find_by_id($_GET['catid']);
	}
	if(isset($_POST['submit']))
	{
		if($_POST['title'] != $category->title)
		{
			$saved_categories = Category::find_all();
			foreach($saved_categories as $saved_category)
			{
				if($saved_category->title == $_POST['title'])
				{
					$session->message("Category Already Exists", "error");
					redirect_to('edit-category.php?catid='.$_GET['catid']);
				}
			}
																
			$url = seo_url($_POST['title']);
			$old_tablename = $category->tablename;
			$new_tablename = str_replace("-", "_", 'mh_'.$url);
						
			$category->title = $_POST['title'];
			$category->status = $_POST['status'];
			$category->url = $url;
			$category->meta_keywords = $_POST['keywords'];
			$category->meta_description = $_POST['description'];
			$category->tablename = $new_tablename;	
			if($category->save())
			{	
				$query = "RENAME TABLE `m3h`.`$old_tablename` TO `m3h`.`$new_tablename`";
				if($database->query($query))
				{
					$session->message("Category updated Successfully", "success");
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
				$message = join("<br />", $category->errors);
			}				
		}
		else
		{
			$category->meta_keywords = $_POST['keywords'];
			$category->meta_description = $_POST['description'];
			$category->status = $_POST['status'];	
			if($category->save())
			{	
				$session->message("Category updated Successfully", "success");
				redirect_to('view-category.php');			
			}
			else
			{
				$message = join("<br />", $category->errors);
			}
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
  
  <form action="edit-category.php?catid=<?php echo $category->id; ?>" method="post">
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
  	<tr>
      <td width="24%"> Category Title: </td>
      <td width="76%"> <input name="title" type="text" class="textfield" value="<?php echo $category->title; ?>" /> </td>
    </tr>
    
    <tr valign="top">
      <td width="24%"> Meta Keywords: </td>
      <td width="76%"> <textarea name="keywords" cols="" rows="" class="textarea"><?php echo $category->meta_keywords; ?></textarea> </td>
    </tr>
    
    <tr valign="top">
      <td width="24%"> Meta Description: </td>
      <td width="76%"> <textarea name="description" cols="" rows="" class="textarea"><?php echo $category->meta_description; ?></textarea> </td>
    </tr>

    <tr>
      <td width="24%"> Status: </td>
      <td width="76%"> 
      	<input name="status" type="radio" value="1" class="radio" <?php if($category->status==1) {echo "checked";} ?>/> <p> Visible </p>
        <input name="status" type="radio" value="0" class="radio" <?php if($category->status==0) {echo "checked";} ?> /> <p> Not Visible </p>
      </td>
    </tr>
    
    <tr>
      <td width="24%">&nbsp;</td>
      <td width="76%"> 
      	<input name="submit" type="submit" class="submit" value="UPDATE CATEGORY" />
        <input name="reset" type="reset" class="submit" value="CANCEL" />
      </td>
    </tr>
  </table>
  </form>
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
