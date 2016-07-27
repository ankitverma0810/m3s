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
		$state->status = 2;
		$state->modified_date = strftime('%Y-%m-%d %H:%M:%S', time());
		if($state->save())
		{
			$session->message("State ".$state->title." was Deleted", "success");
			redirect_to('view-states.php');
		}
		else
		{
			$session->message("State was not deleted", "error");
			redirect_to('view-states.php');
		}
	}
?>