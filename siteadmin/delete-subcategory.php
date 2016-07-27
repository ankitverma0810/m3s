<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
?>
<?php
	if(empty($_GET['subid']))
	{
		$session->message("No Subcategory id was provided", "error");
		redirect_to('view-subcategory.php');
	}
	else
	{
		$subcategory = Subcategory::find_by_id($_GET['subid']);
		$subcategory->status = 2;
		if($subcategory->save())
		{
			$session->message("Subcategory ".$subcategory->title." was Deleted", "success");
			redirect_to('view-subcategory.php');
		}
		else
		{
			$session->message("Subcategory was not deleted", "error");
			redirect_to('view-subcategory.php');
		}
	}
?>