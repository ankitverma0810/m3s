<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
?>
<?php
	if(empty($_GET['areaid']))
	{
		$session->message("No Area id was provided", "error");
		redirect_to('view-areas.php');
	}
	else
	{
		$area = Areas::find_by_id($_GET['areaid']);
		$area->status = 2;
		$area->modified_date = strftime('%Y-%m-%d %H:%M:%S', time());
		if($area->save())
		{
			$session->message("Area ".$area->title." was Deleted", "success");
			redirect_to('view-areas.php');
		}
		else
		{
			$session->message("Area was not deleted", "error");
			redirect_to('view-areas.php');
		}
	}
?>