<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	//find all active classifieds
	$categories = Category::find_all_categries();
	$count = count($categories);
	$num = 0;
	$sql = "";
	foreach($categories as $category)
	{
		$num++;
		$sql .= " SELECT * FROM ". $category->tablename;
		$sql .= " WHERE ad_status = 2";
		if($num != $count)
		{
			$sql .= " UNION ALL";
		}
	}
	$classifieds = Classifieds::find_by_sql($sql);
	
	if($classifieds)
	{				
		foreach($classifieds as $classified)
		{			
			global $database;
			
			//finding remaining days		
			$current_date = time();
			$expiry_date = strtotime(expiry_datetime_to_text($classified->expiry_date));				
			$remaining_days = $expiry_date - $current_date;
			$remaining_days = ceil($remaining_days/(60*60*24));
			
			//finding table name
			$tablename = Category::find_by_id($classified->category_id);
			$tablename = $tablename->tablename;
			
			//sending alert to users
			include_once('mail_files/htmlMimeMail.php');
			if($remaining_days <= 0 )
			{
				$sql = "UPDATE ".$tablename." SET ad_status = 4, alert_status = 3 WHERE id = {$classified->id}";
				$result = $database->query($sql);
				if($result)
				{					
					//----------------------reading the email template starts from here-----------------------------//
					$file = SITE_ROOT_URL."email-templates/expiry.html";
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
					$mail->setSubject("Listing expired at M3H.com");		 
					$mail->setHtml($emailContent);		
					$mail->send(array($emailTo),'smtp');
				}
			}
			if($remaining_days > 0 && $remaining_days <= 5)
			{
				$sql = "UPDATE ".$tablename." SET alert_status = 1 WHERE id = {$classified->id}";
				$result = $database->query($sql);
				if($result)
				{
					$uniqueid = $classified->unique_id;
					$follow_link=SITE_ROOT_URL."renew-listing.php?adid=$uniqueid&tablename=$tablename";
					
					//----------------------reading the email template starts from here-----------------------------//
					$file = SITE_ROOT_URL."email-templates/renew-reminder.html";
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
					$mail->setSubject("Renew your listing at M3H.com");		 
					$mail->setHtml($emailContent);		
					$mail->send(array($emailTo),'smtp');
				}
			}
		}
	}
?>