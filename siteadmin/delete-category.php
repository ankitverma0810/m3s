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
		$category->status = 2;
		$category->modified_date = strftime('%Y-%m-%d %H:%M:%S', time());
		if($category->save())
		{
			$session->message("Category ".$category->title." was Deleted", "success");
			redirect_to('view-category.php');
		}
		else
		{
			$session->message("Category was not deleted", "error");
			redirect_to('view-category.php');
		}
	}
?>