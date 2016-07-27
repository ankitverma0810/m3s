<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(isset($_POST['edit']))
	{		
		$table_name = Category::find_by_id($_POST['category']);	
		$category_tablename = str_replace('_', '-', $table_name->tablename);
					
		$classified = Classifieds::find_by_uniqueid($_GET['edit_adid'], $_GET['tablename']);
		
		//if we find the classified ad then starts from here//
		if($classified)
		{
			$classified->state_id = $_POST['states'];
			$classified->location_id = $_POST['locality'];
			$classified->category_id = $_POST['category'];
			$classified->subcategory_id = $_POST['subcategory'];
			$classified->ad_type = $_POST['type'];
			$classified->title = $_POST['title'];
			$classified->description = $_POST['editor1'];
			$classified->email = $_POST['email'];
			$classified->mobile = $_POST['mobile'];
			if($_FILES['filename']['error'] != 4)
			{
				$classified->attach_file($_FILES['filename']);
			}
			$classified->company_name = $_POST['company'];
			$classified->website_url = $_POST['website'];
			$classified->keywords = $_POST['keywords'];
			$classified->url = seo_url($_POST['title']);
			$classified->featured_status = 0;
			$classified->spotlight_status = 0;
			$classified->ad_status = 1;
			
			//if ad is in same category as it was before then we will just update it//
			if($table_name->tablename == $_GET['tablename'])
			{
				$result = $classified->save($table_name->tablename);
			}
			//if ad is not in the same category as it was before then we will create in new table and delete from previous table//
			else
			{
				$delete_id = $classified->id;
				$classified->id = ""; //if find id already exists in new table then error will occur thats why we will empty the find id//
				$classified->modified_date = "";
				
				if($classified->create($table_name->tablename))
				{
					$result = $classified->delete($_GET['tablename'], $delete_id);
				}
				else
				{
					$session->message('Some error occurs, please try again!','error');
					redirect_to('post-free-classified-ads.php');
				}
			}								
			if($result)
			{				
				include_once('mail_files/htmlMimeMail.php');				
				$mail 		= 	new htmlMimeMail();	
				$mail->setSMTPParams(SMTP_HOST,SMTP_PORT,SMTP_HELO,SMTP_AUTH,SMTP_USER,SMTP_PASS); //SETTING THE SMTP CREDENTIALS
				
				if($classified->user_id != 1)
				{
					$user = Register::find_by_id($classified->user_id);
					$msg_to_include = '<table width="960px" style="margin-right:auto; margin-left:auto; font-family:Verdana, Arial, Helvetica, sans-serif; border:solid 1px #333333;">
				<tr>
					<td bgcolor="#005c8b" style="font-size:17px; color:#F7F7F7; font-weight:normal; padding:10px; font-weight:normal;"> Add Posted on M3H </td>
				</tr>
				
				<tr>
					<td style="padding:25px 10px 10px 10px; font-size:13px;"> <strong> Hi '.$_POST['email'].'</strong>,</td>
				</tr>
				
				<tr>
					<td style="font-size:12px; padding:0 10px 0 10px;"> Thanks for posting your ad "<strong>'.ucwords(strtolower($_POST['title'])).'</strong>" on M3H, which will appear on the site soon. It can take up to 2 to 8 hours for new ads to appear, but you can view your ad here right away.</td>
				</tr>
				
				<tr>
					<td style="font-size:12px; padding:5px 10px 0 10px;">
					  Take actions on Your Ad:
					  <ul style="margin:5px 0 5px 24px; padding:0 10px 0 10px;">						
						<li> <a href="'.SITE_ROOT_URL.'edit/'.$category_tablename.'/'.$classified->unique_id.'"> Edit your ad </a> </li>					
					    <li> <a href="'.SITE_ROOT_URL.'delete/'.$category_tablename.'/'.$classified->unique_id.'"> Delete your ad </a> </li>
					  </ul>
					</td>
				</tr>
				
				<tr>
					<td style="font-size:12px; line-height:18px; padding:5px 10px 0 10px;">
						<b> Reference number</b>: '.$classified->unique_id.' <br />
						Your ad will be live on M3H until <strong> '.$classified->expiry_date.' </strong>. If you have any questions about your ad, please visit our help section.
						
					</td>
				</tr>
				
				
				<tr>
					<td style="font-size:13px; padding:45px 10px 0px 10px;">
						<b> All The Best!  </b> <br />
						<a href="'.SITE_ROOT_URL.'" style="color:#333333; text-decoration:none;"> www.M3H.com </a>
					</td>
				</tr>
				
				<tr>
					<td style="font-size:11px; padding:0px 10px 20px 10px;">
						<em> Note: We reserve the right to refuse or delete ads that we believe are inappropriate or which breach our terms and conditions. </em>
					</td>
				</tr>
				
				</table>';
	
					$emailTo=$_POST['email'];				
					$mail->setFrom(EMAIL_FROM);  
					$mail->setSubject("Your Ad Posted Successfully");		 
					$mail->setHtml($msg_to_include);				   		
				}
				else
				{
					$msg_to_include = '<table width="960px" style="margin-right:auto; margin-left:auto; font-family:Verdana, Arial, Helvetica, sans-serif; border:solid 1px #333333;">
				  <tr>
					<td bgcolor="#005c8b" style="font-size:17px; color:#F7F7F7; font-weight:normal; padding:10px; font-weight:normal;"> Add Posted on M3H  </td>
				  </tr>
				  
				  <tr>
					<td style="padding:25px 10px 10px 10px; font-size:13px;"> <strong> Hi '.$_POST['email'].'</strong>,</td>
				  </tr>
				  
				  <tr>
					 <td style="font-size:12px; padding:0 10px 0 10px;"> Thanks for posting your ad "<strong>'.ucwords(strtolower($_POST['title'])).'</strong>" on M3H, which will appear on the site soon. It can take up to 48 hours for new ads to appear, but you can view your ad here right away. </td>
				  </tr>
				  
				  <tr>
					<td style="font-size:12px; padding:5px 10px 0 10px;">
					  Take actions on Your Ad:
					  <ul style="margin:5px 0 5px 24px; padding:0 10px 0 10px;">
						<li> <a href="'.SITE_ROOT_URL.'edit/'.$category_tablename.'/'.$classified->unique_id.'"> Edit your ad </a> </li>					
					    <li> <a href="'.SITE_ROOT_URL.'delete/'.$category_tablename.'/'.$classified->unique_id.'"> Delete your ad </a> </li>
					  </ul>
					</td>
				  </tr>
				  
				  <tr>
					<td style="font-size:12px; line-height:18px; padding:25px 10px 0 10px;">
					  <b> Reference number</b>: '.$classified->unique_id.'
					</td>
				  </tr>
				  
				  <tr>  
					<td style="font-size:12px; padding:25px 10px 0 10px;">
					  <strong> Why not create an account? </strong>
					  <ul style="margin:10px 0 15px 24px; padding:0 10px 0 10px; font-size:12px; line-height:18px;">
						<li> Manage all your ads in one place </li>
						<li> One set of login details, fewer passwords to remember </li>
						<li> Quicker to post an ad  </li>
					  </ul>
					</td>
				  </tr>
				  
				  <tr>
					<td style="font-size:12px; padding:10px 10px 0 10px;">
					  <a href="'.SITE_ROOT_URL.'register.php"> Click here </a> to create your account
					</td>
				  </tr>
				  
				   <tr>
					<td style="font-size:12px; padding:3px 10px 0 10px;">
					  Your ad will be live on Search-point until <strong> '.$classified->expiry_date.'</strong>. If you\'ve any questions about your ad, please visit our help section.
					</td>
				  </tr>
				
				  
				  <tr>
					<td style="font-size:13px; padding:45px 10px 0px 10px;">
					  <b> All The Best!  </b> <br />
					  <a href="'.SITE_ROOT_URL.'" style="color:#333333; text-decoration:none;"> www.M3H.com </a>
					</td>
				  </tr>
				  
				  <tr>
					<td style="font-size:11px; padding:0px 10px 20px 10px;">
					  <em> Note: We reserve the right to refuse or delete ads that we believe are inappropriate or which breach our terms and conditions. </em>
					</td>
				  </tr>
				  
				</table>';
	
					$emailTo=$_POST['email'];				
					$mail->setFrom(EMAIL_FROM);  
					$mail->setSubject("Your Ad Posted Successfully");		 
					$mail->setHtml($msg_to_include);
				}
				if($mail->send(array($emailTo),'smtp'))
				{
					$session->message("Your Ad has been modified successfully. Your Ad is Currently Under Review", "success");
					redirect_to('post-free-classified-ads.php');
				}
				else
				{
					$session->message('Some error occurs, please try again!','error');
					redirect_to('post-free-classified-ads.php');
				}	
			}
			else
			{
				$session->message('Some error occurs, please try again!','error');
				redirect_to('post-free-classified-ads.php');
			}	
		}		
		
		//if we are not able to find the classified ad//
		else
		{
			$session->message('Some error occurs, please try again!','error');
			redirect_to('post-free-classified-ads.php');
		}		
	}
	if(isset($_POST['submit']))
	{				
		$table_name = Category::find_by_id($_POST['category']);
		$category_tablename = str_replace('_', '-', $table_name->tablename);
		
		$classified = new Classifieds();		
		$classified->unique_id = generateUniqueId(16);	
		if(isset($_SESSION['reguser_id']))
		{
			$classified->user_id = $_SESSION['reguser_id'];
		}
		else
		{
			$classified->user_id = 1;
		}
		$classified->state_id = $_POST['states'];
		$classified->location_id = $_POST['locality'];
		$classified->category_id = $_POST['category'];
		$classified->subcategory_id = $_POST['subcategory'];
		$classified->ad_type = $_POST['type'];
		$classified->title = $_POST['title'];
		$classified->description = $_POST['editor1'];
		$classified->email = $_POST['email'];
		$classified->mobile = $_POST['mobile'];
		$classified->attach_file($_FILES['filename']);		
		$classified->company_name = $_POST['company'];
		$classified->website_url = $_POST['website'];
		$classified->keywords = $_POST['keywords'];		
		$classified->url = seo_url($_POST['title']);
		$classified->added_date = strftime('%Y-%m-%d %H:%M:%S', time());
		$classified->expiry_date = date("Y-m-d h:i:s", strtotime('now' . " +45 day"));
		$classified->featured_status = 0;
		$classified->spotlight_status = 0;
		$classified->ad_status = 1;
		$classified->alert_status = 0;
										
		if($classified->save($table_name->tablename))
		{			
			include_once('mail_files/htmlMimeMail.php');			
			$mail 		= 	new htmlMimeMail();	
			$mail->setSMTPParams(SMTP_HOST,SMTP_PORT,SMTP_HELO,SMTP_AUTH,SMTP_USER,SMTP_PASS); //SETTING THE SMTP CREDENTIALS
			
			if(isset($_SESSION['reguser_id']))
			{
				$user = Register::find_by_id($_SESSION['reguser_id']);
				$msg_to_include = '<table width="960px" style="margin-right:auto; margin-left:auto; font-family:Verdana, Arial, Helvetica, sans-serif; border:solid 1px #333333;">
			<tr>
				<td bgcolor="#005c8b" style="font-size:17px; color:#F7F7F7; font-weight:normal; padding:10px; font-weight:normal;"> Add Posted on M3H </td>
			</tr>
			
			<tr>
				<td style="padding:25px 10px 10px 10px; font-size:13px;"> <strong> Hi '.$_POST['email'].'</strong>,</td>
			</tr>
			
			<tr>
				<td style="font-size:12px; padding:0 10px 0 10px;"> Thanks for posting your ad "<strong>'.ucwords(strtolower($_POST['title'])).'</strong>" on M3H, which will appear on the site soon. It can take up to 2 to 8 hours for new ads to appear, but you can view your ad here right away.</td>
			</tr>
			
			<tr>
				<td style="font-size:12px; padding:5px 10px 0 10px;">
				  Take actions on Your Ad:
				  <ul style="margin:5px 0 5px 24px; padding:0 10px 0 10px;">					
					<li> <a href="'.SITE_ROOT_URL.'edit/'.$category_tablename.'/'.$classified->unique_id.'"> Edit your ad </a> </li>					
					<li> <a href="'.SITE_ROOT_URL.'delete/'.$category_tablename.'/'.$classified->unique_id.'"> Delete your ad </a> </li>
				  </ul>
				</td>
			</tr>
			
			<tr>
				<td style="font-size:12px; line-height:18px; padding:5px 10px 0 10px;">
					<b> Reference number</b>: '.$classified->unique_id.' <br />
					Your ad will be live on M3H until <strong> '.$classified->expiry_date.' </strong>. If you have any questions about your ad, please visit our help section.
					
				</td>
			</tr>
			
			
			<tr>
				<td style="font-size:13px; padding:45px 10px 0px 10px;">
					<b> All The Best!  </b> <br />
					<a href="'.SITE_ROOT_URL.'" style="color:#333333; text-decoration:none;"> www.M3H.com </a>
				</td>
			</tr>
			
			<tr>
				<td style="font-size:11px; padding:0px 10px 20px 10px;">
					<em> Note: We reserve the right to refuse or delete ads that we believe are inappropriate or which breach our terms and conditions. </em>
				</td>
			</tr>
			
			</table>';

				$emailTo=$_POST['email'];				
				$mail->setFrom(EMAIL_FROM);  
				$mail->setSubject("Your Ad Posted Successfully");		 
				$mail->setHtml($msg_to_include);				   		
			}
			else
			{
				$msg_to_include = '<table width="960px" style="margin-right:auto; margin-left:auto; font-family:Verdana, Arial, Helvetica, sans-serif; border:solid 1px #333333;">
			  <tr>
				<td bgcolor="#005c8b" style="font-size:17px; color:#F7F7F7; font-weight:normal; padding:10px; font-weight:normal;"> Add Posted on M3H  </td>
			  </tr>
			  
			  <tr>
				<td style="padding:25px 10px 10px 10px; font-size:13px;"> <strong> Hi '.$_POST['email'].'</strong>,</td>
			  </tr>
			  
			  <tr>
				 <td style="font-size:12px; padding:0 10px 0 10px;"> Thanks for posting your ad "<strong>'.ucwords(strtolower($_POST['title'])).'</strong>" on M3H, which will appear on the site soon. It can take up to 48 hours for new ads to appear, but you can view your ad here right away. </td>
			  </tr>
			  		  
			  <tr>
				<td style="font-size:12px; padding:5px 10px 0 10px;">
				  Take actions on Your Ad:
				  <ul style="margin:5px 0 5px 24px; padding:0 10px 0 10px;">
					<li> <a href="'.SITE_ROOT_URL.'edit/'.$category_tablename.'/'.$classified->unique_id.'"> Edit your ad </a> </li>					
					<li> <a href="'.SITE_ROOT_URL.'delete/'.$category_tablename.'/'.$classified->unique_id.'"> Delete your ad </a> </li>
				  </ul>
				</td>
			  </tr>
			  
			  <tr>
				<td style="font-size:12px; line-height:18px; padding:25px 10px 0 10px;">
				  <b> Reference number</b>: '.$classified->unique_id.'
				</td>
			  </tr>
			  
			  <tr>  
				<td style="font-size:12px; padding:25px 10px 0 10px;">
				  <strong> Why not create an account? </strong>
				  <ul style="margin:10px 0 15px 24px; padding:0 10px 0 10px; font-size:12px; line-height:18px;">
					<li> Manage all your ads in one place </li>
					<li> One set of login details, fewer passwords to remember </li>
					<li> Quicker to post an ad  </li>
				  </ul>
				</td>
			  </tr>
			  
			  <tr>
				<td style="font-size:12px; padding:10px 10px 0 10px;">
				  <a href="'.SITE_ROOT_URL.'register.php"> Click here </a> to create your account
				</td>
			  </tr>
			  
			   <tr>
				<td style="font-size:12px; padding:3px 10px 0 10px;">
				  Your ad will be live on Search-point until <strong> '.$classified->expiry_date.'</strong>. If you\'ve any questions about your ad, please visit our help section.
				</td>
			  </tr>
			
			  
			  <tr>
				<td style="font-size:13px; padding:45px 10px 0px 10px;">
				  <b> All The Best!  </b> <br />
				  <a href="'.SITE_ROOT_URL.'" style="color:#333333; text-decoration:none;"> www.M3H.com </a>
				</td>
			  </tr>
			  
			  <tr>
				<td style="font-size:11px; padding:0px 10px 20px 10px;">
				  <em> Note: We reserve the right to refuse or delete ads that we believe are inappropriate or which breach our terms and conditions. </em>
				</td>
			  </tr>
			  
			</table>';

				$emailTo=$_POST['email'];				
				$mail->setFrom(EMAIL_FROM);  
				$mail->setSubject("Your Ad Posted Successfully");		 
				$mail->setHtml($msg_to_include);
			}
			if($mail->send(array($emailTo),'smtp'))
			{
				$session->message("Your ad have been successfully submitted and will be listed after admin approval! Have patience… Thank you!", "success");
				redirect_to('post-free-classified-ads.php');
			}
			else
			{
				$session->message('Some error occurs, please try again!','error');
				redirect_to('post-free-classified-ads.php');
			}	
		}
		else
		{
			$session->message('Some error occurs, please try again!','error');
			redirect_to('post-free-classified-ads.php');
		}		
	}
	else
	{
		$session->message('Some error occurs, please try again!','error');
		redirect_to('post-free-classified-ads.php');
	}
?>