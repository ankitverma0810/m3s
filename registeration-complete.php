<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(isset($_POST['submit']))
	{
		$user = new Register();	
		$confirm_code = md5(uniqid(rand()));
		$user_status = 3; //by deafult		
		if(empty($_POST['mobile']))
		{
			$mobile = "NULL";
		}
		else
		{
			$mobile = $_POST['mobile'];
		}		
		if(empty($_POST['landline']))
		{
			$landline = "NULL";
		}
		else
		{
			$landline = $_POST['landline'];
		}
			
		$user->add_user($_POST['email'], $_POST['conf_password'], $mobile, $landline, $user_status, $confirm_code);
		if($user->save())
		{
			$message=SITE_ROOT_URL."regconfirmation.php?passkey=$confirm_code";			
			$msg_to_include = '<table width="960px" style="margin-right:auto; margin-left:auto; font-family:Verdana, Arial, Helvetica, sans-serif; border:solid 1px #333333;">
  <tr>
    <td bgcolor="#005c8b" style="font-size:17px; color:#F7F7F7; font-weight:normal; padding:10px;"> Activate your new m3h account now </td>
  </tr>
  
  <tr>
    <td style="padding:25px 10px 0 10px; font-size:12px;"> <strong> Hello</strong>,</td>
  </tr>
  
  <tr>
    <td style="font-size:12px; padding:0 10px 0 10px;"> You have chosen to create an account with M3H. You will need to activate it first.
    To do this, just click the link below. </td>
  </tr>
  <tr>
  	<td style="font-size:12px; padding:5px 10px 0 10px;"><a href='.$message.' style="text-decoration:none;">'.$message.'</a></td>
  </tr>
  <tr>
    <td style="font-size:12px; padding:10px 10px 0 10px"> M3H helps you communicate and stay in touch with local community. Once you join m3h, you will be able to advertise, announce, sell, buy, share, plan events, and much more...explore today. </td>
  </tr>
    
  <tr>
    <td style="font-size:12px; padding:15px 10px 0 10px"> <strong> Why create an account? </strong> </td>
  </tr>
  
  <tr>
    <td>
      <ul style="margin:0 0 15px 14px; padding:0 10px 0 10px; font-size:12px; line-height:16px;">
        <li> Manage all your ads in one place </li>
        <li> One set of login details, fewer passwords to remember </li>
        <li> Quicker to post an ad </li>
      </ul>
    </td>
  </tr>
  
  <tr>
    <td style="font-size:13px; padding:0 10px 0 10px;"> <strong> Not sure why you have received this email or have a question about registration? Contact us </strong> </td>
  </tr>
  
  <tr>
    <td style="font-size:13px; padding:45px 10px 20px 10px;">
      <b> All The Best! </b> <br />
      <a href="http://www.m3h.com" style="color:#333333; text-decoration:none;"> www.m3h.com </a>
    </td>
  </tr>
  
</table>';
			
			include_once('mail_files/htmlMimeMail.php');
						
			$mail 		= 	new htmlMimeMail();	
			$mail->setSMTPParams(SMTP_HOST,SMTP_PORT,SMTP_HELO,SMTP_AUTH,SMTP_USER,SMTP_PASS); //SETTING THE SMTP CREDENTIALS
			$emailTo=$_POST['email'];
			$mail->setFrom(EMAIL_FROM);  
			$mail->setSubject("Activate your new M3H account now");		 
			$mail->setHtml($msg_to_include);	
				
			if($mail->send(array($emailTo),'smtp'))
			{
				$session->message("You have been successfully registered, please check your email to activate your account. Thank you!", "success");
				redirect_to('login.php');
			}
			else
			{
				$session->message('Some error occurs, please try again!','error');
				redirect_to('register.php');
			}
		}
		else
		{
			$session->message(join("<br />", $user->errors), "error");
			redirect_to('register.php');
		}
	}
?>