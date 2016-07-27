<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(empty($_GET['adid']) || empty($_GET['tablename']))
	{
		$session->message('Wrong Confirmation code!', 'error');
		redirect_to(SITE_ROOT_URL);
	}
	else
	{
		$classified = Classifieds::find_by_uniqueid($_GET['adid'], $_GET['tablename']);
		if($classified)
		{
			if($classified->alert_status == 0 || $classified->alert_status == 2 || $classified->alert_status == 3)
			{
				$session->message('Wrong Confirmation code!', 'error');
				redirect_to(SITE_ROOT_URL);
			}
			else
			{
				$classified->alert_status = 2;
				$classified->expiry_date = date("Y-m-d h:i:s", strtotime('now' . " +45 day"));				 
				if($classified->update($_GET['tablename']))
				{					
					include_once('mail_files/htmlMimeMail.php');
										
					$msg_to_include = 'New Expiry Date '.$classified->expiry_date;
					$mail = new htmlMimeMail();	
					$mail->setSMTPParams(SMTP_HOST,SMTP_PORT,SMTP_HELO,SMTP_AUTH,SMTP_USER,SMTP_PASS);				
					$emailTo=$classified->email;					
					$mail->setFrom(EMAIL_FROM);  
					$mail->setSubject("M3H Confirmation Mail");		 
					$mail->setHtml($msg_to_include);					   			
					if($mail->send(array($emailTo),'smtp'))
					{
						$session->message("Thank you your Ad has been successfully confirmed!!", "success");
						redirect_to(SITE_ROOT_URL);
					}
					else
					{
						$session->message('Some error occurs, please try again!','error');
						redirect_to(SITE_ROOT_URL);
					}
				}
				else
				{
					$session->message('Some error occurs please try again!!', 'error');
					redirect_to(SITE_ROOT_URL);
				}
			}
		}
		else
		{
			$session->message('Wrong Confirmation code!', 'error');
			redirect_to('index.php');
		}
	}
?>