<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(isset($_POST['submit']))
	{
		$user = Register::find_by_email($_POST['email']);
		
		if($user)
		{
			$message=SITE_ROOT_URL."change-password.php?reguserid=$user->id";		
			$msg_to_include = '<table width="960px" cellpadding="7" cellspacing="0" style="margin-right:auto; margin-left:auto; font-family:Verdana, Arial, Helvetica, sans-serif; border:solid 1px #333333; font-size:12px; line-height:19px;">
    <tr>
    	<td> <h1 style="font-size:16px;"> Steps to Reset your Password </h1> </td>
    </tr>
    
    <tr>
      <td> <strong>Dear</strong> '.$user->email.', <br />
        This email was sent automatically by <strong>m3h.com</strong> in response to your request to reset your password. </td>
    </tr>
    
    <tr>
    	<td> <strong>Please follow these steps to reset your password and access your M3H account: </strong></td>
    </tr>
    
    <tr>
        <td> <strong>Step 1 :</strong> Click on the link below or copy and paste the link into your web browser <br />
		  <a href='.$message.' style="text-decoration:none;">'.$message.'</a><br />
          <strong>Step 2 </strong>: Fill in the required fields to reset your password and click "Change Password". Your new password 
          will take effect immediately.<br />
          <strong>Step 3 :</strong> Sign in to your M3H account using your new password. </td>
    </tr>
    
    <tr>
        <td> At M3H, we value your privacy. </td>
    </tr>
    
    <tr>
        <td> Regards, <br />
        M3H.com Team </td>
    </tr>
</table>';
			
			include_once('mail_files/htmlMimeMail.php');
			
			$mail 		= 	new htmlMimeMail();	
			$mail->setSMTPParams(SMTP_HOST,SMTP_PORT,SMTP_HELO,SMTP_AUTH,SMTP_USER,SMTP_PASS); //SETTING THE SMTP CREDENTIALS			
			$emailTo=$user->email;
			//$emailTo_bcc =	"contact@webrextech.com";			
			$mail->setFrom(EMAIL_FROM);  
			$mail->setSubject("Reset your password");		 
			$mail->setHtml($msg_to_include);			   
			//$mail->setBcc($emailTo_bcc);			
			if($mail->send(array($emailTo),'smtp'))
			{
				$session->message("To ensure the security of your account, an email has been sent to associated email id for password reterival.Please follow the instructions in the mail to change your password.", "success");
				redirect_to('forgot-password.php');
			}
			else
			{
				$session->message('Some error occurs, please try again!','error');
				redirect_to('forgot-password.php');
			}
		}
		else
		{
			$session->message('Wrong Emailid entered!', 'error');
			redirect_to('forgot-password.php');
		}		
	}
?>