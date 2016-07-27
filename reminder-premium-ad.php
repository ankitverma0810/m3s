<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	$orders = Orders::find_all_active();
	if($orders)
	{
		foreach($orders as $order)
		{
			//finding remaining days		
			$current_date = time();
			$expiry_date = strtotime(expiry_datetime_to_text($order->ad_expiry_date));				
			$remaining_days = $expiry_date - $current_date;
			$remaining_days = ceil($remaining_days/(60*60*24));
			
			echo $remaining_days."<br />";
					
			//finding tablename
			$tablename = str_replace('_', '-', $order->tablename);
			
			//finding classified
			$classified = Classifieds::find_by_uniqueid($order->ad_unique_id, $order->tablename);
			
			//sending alert to users
			include_once('mail_files/htmlMimeMail.php');
			if($remaining_days <= 0)
			{				
				$sql = "UPDATE mh_orders SET order_status = 3, alert_status = 3 WHERE id = {$order->id}";
				$result = $database->query($sql);
				if($result)
				{					
					//----------------------reading the email template starts from here-----------------------------//
					$file = SITE_ROOT_URL."email-templates/premium-expiry.html";
					$content = "";
					if($handle = fopen($file, 'r'))
					{
						while(!feof($handle)) //feof means until file end of read everything
						{
							$content .= fgets($handle); // get everything from this file and add into $content.
						}
						fclose($handle);
					}			
					$replaceFrom		= array("{RECIPENT_NAME}");
					$replaceTo 			= array($classified->email);
					$emailContent		= str_replace($replaceFrom,$replaceTo,$content);
					//----------------------reading the email template ends from here-----------------------------//
																	
					$mail = new htmlMimeMail();	
					$mail->setSMTPParams(SMTP_HOST,SMTP_PORT,SMTP_HELO,SMTP_AUTH,SMTP_USER,SMTP_PASS);			
					$emailTo=$classified->email;				
					$mail->setFrom(EMAIL_FROM);  
					$mail->setSubject("Premium Listing expired at M3S.in");		 
					$mail->setHtml($emailContent);		
					$mail->send(array($emailTo),'smtp');
				}						
			}
			if($remaining_days > 0 && $remaining_days <= 5)
			{				
				$sql = "UPDATE mh_orders SET alert_status = 1 WHERE id = {$order->id}";
				$result = $database->query($sql);
				if($result)
				{
					$follow_link=SITE_ROOT_URL."renew-featured-spotlight-ads.php?tablename=$tablename&adid=$order->ad_unique_id&type=$order->ad_type";
					
					//----------------------reading the email template starts from here-----------------------------//
					$file = SITE_ROOT_URL."email-templates/premium-renew-reminder.html";
					$content = "";
					if($handle = fopen($file, 'r'))
					{
						while(!feof($handle)) //feof means until file end of read everything
						{
							$content .= fgets($handle); // get everything from this file and add into $content.
						}
						fclose($handle);
					}			
					$replaceFrom		= array("{RECIPENT_NAME}","{REMAINING_DAYS}","{FOLLOW_LINK}");
					$replaceTo 			= array($classified->email,$remaining_days,$follow_link);
					$emailContent		= str_replace($replaceFrom,$replaceTo,$content);
					//----------------------reading the email template ends from here-----------------------------//
													
					$mail = new htmlMimeMail();	
					$mail->setSMTPParams(SMTP_HOST,SMTP_PORT,SMTP_HELO,SMTP_AUTH,SMTP_USER,SMTP_PASS);			
					$emailTo=$classified->email;		
					$mail->setFrom(EMAIL_FROM);  
					$mail->setSubject("Renew your Premium listing at M3S.in");		 
					$mail->setHtml($emailContent);		
					$mail->send(array($emailTo),'smtp');
				}						
			}
		}
	}
?>