<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
?>
<?php
	if(empty($_GET['id']))
	{
		$session->message("No Page id was provided", "error");
		redirect_to('view-pages.php');
	}
	else
	{
		$page = Cms::find_by_id($_GET['id']);
		$page->status = 2;
		$page->modified_date = strftime('%Y-%m-%d %H:%M:%S', time());
		if($page->save())
		{
			$session->message("Page ".$page->title." was Deleted", "success");
			redirect_to('view-pages.php');
		}
		else
		{
			$session->message("Page was not deleted", "error");
			redirect_to('view-pages.php');
		}
	}
?>