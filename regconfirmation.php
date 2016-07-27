<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(empty($_GET['passkey']))
	{
		$session->message('Wrong Confirmation code!', 'error');
		redirect_to('login.php');
	}
	else
	{
		$passkey = $_GET['passkey'];
		$sql = "SELECT * FROM mh_reg_users WHERE confirm_code = '".$passkey."'";			
		$result = $database->query($sql);
		if($result)
		{
			if($database->num_rows($result) == 1)
			{
				$user = $database->fetch_array($result);
				if($user['user_status'] == 1 || $user['user_status'] == 2 || $user['user_status'] == 4)
				{
					$session->message('Your email is already confirmed!', 'error');
					redirect_to('login.php');
				}
				if($user['user_status'] == 3)
				{
					$query = "UPDATE mh_reg_users SET user_status = 2 WHERE id = $user[id]";
					$update = $database->query($query);
					if($update)
					{
						if($database->affected_rows() == 1)
						{
							$session->message('Your email has been confirmed!', 'success');
							redirect_to('login.php');
						}
					}
					else
					{
						$session->message('Some error occurs, please try again!','error');
						redirect_to('login.php');
					}
				}
			}
			else
			{
				$session->message('Wrong Confirmation code!', 'error');
				redirect_to('login.php');
			}
		}
		else
		{
			$session->message('Wrong Confirmation code!', 'error');
			redirect_to('login.php');
		}
	}
?>