<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	$emailer = "";
	$alerts = Alerts::find_all_pending();
	if($alerts)
	{
		foreach($alerts as $alert)
		{
			$category = Category::find_by_id($alert->category_id);
			$tablename = $category->tablename;
			$latest_tablename = str_replace('_', '-', $tablename); //for link of the ad//
			$subcategory = Subcategory::find_by_id($alert->subcategory_id);
			$state = States::find_by_id($alert->state_id);
			
			$latest_classifieds = Classifieds::find_latest_ads($tablename, $alert->subcategory_id, $alert->state_id);
			$all_emails = Classifieds::latest_classifieds_all($tablename, $alert->subcategory_id, $alert->state_id);
			
			if($latest_classifieds)
			{
				//-----------------------------adding latest ads-----------------------------------------//					
				foreach($latest_classifieds as $latest_classified)
				{
					$emailer .= "<tr>";
						$emailer .= "<td>";
							$emailer .= "<a href='".SITE_ROOT_URL."details/$latest_tablename/$latest_classified->url/$latest_classified->id'> ".str_replace("\\","",$latest_classified->title)." </a> <br />";
							$emailer .= cropText($latest_classified->description, 100)."...";
						$emailer .= "</td>";
					$emailer .= "</tr>";
				}
				
				//----------------------reading the email template starts from here-----------------------------//
				$file = SITE_ROOT_URL."email-templates/related-ads.html";
				$content = "";
				if($handle = fopen($file, 'r'))
				{
					while(!feof($handle)) //feof means until file end of read everything
					{
						$content .= fgets($handle); // get everything from this file and add into $content.
					}
					fclose($handle);
				}			
				$replaceFrom		= array("{CITY}","{CATEGORY}","{LATEST_ADS}");
				$replaceTo 			= array($state->title,$category->title,$emailer);
				$emailContent		= str_replace($replaceFrom,$replaceTo,$content);
				
				//----------------------adding all the email ids into an array except the one in the alert ad-----------------------------//
				$user_emails = array();
				foreach($all_emails as $all_email)
				{
					if($all_email->email != $alert->user_email)
					{
						$user_emails[] = $all_email->email;
					}
				}
				
				//----------------------sending latest ads to the unique emails-----------------------------//
				$emails = array_unique($user_emails);
				foreach($emails as $email)
				{
					include_once('mail_files/htmlMimeMail.php');
					$mail = new htmlMimeMail();	
					$mail->setSMTPParams(SMTP_HOST,SMTP_PORT,SMTP_HELO,SMTP_AUTH,SMTP_USER,SMTP_PASS);
					$emailTo=$email;			
					$mail->setFrom(EMAIL_FROM);  
					$mail->setSubject("Latest Ads for ".$category->title);		 
					$mail->setHtml($emailContent);		
					$mail->send(array($emailTo),'smtp');
				}
				$alert->status = 1;
				$alert->lastmailsend_date = strftime('%Y-%m-%d %H:%M:%S', time());
				$alert->update();
				$emailer = ""; //so that mailer can get empty and refill with new category ads.(otherwise it will add other categories ads in the same mailer again and again)
			}
		}
	}
?>