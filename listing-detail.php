<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php	
	//for htaccess file
	$htaccessFile	= 'htaccess';
	$c_relink		= new RELINK($htaccessFile);
	
	//finding category, subcategory, state, location and classified ad
	$tablename = str_replace('-', '_', $_GET['tablename']);	
	$classified = Classifieds::find_by_adid($tablename, $_GET['adid']);	
	$state = States::find_by_id($classified->state_id);
	$location = Areas::find_by_id($classified->location_id);
	$category = Category::find_by_id($classified->category_id);
	$subcategory = Subcategory::find_by_id($classified->subcategory_id);
	
	//if user send response to a particular ad
	if(isset($_POST['response']))
	{
		$response = new Response();
		$response->add_response($classified->user_id, $classified->unique_id, $classified->title, $_POST['email'], $_POST['mobile'], $_POST['message']);
		if($response->save())
		{
			include_once('mail_files/htmlMimeMail.php');
						
			//----------------------reading the email template starts from here-----------------------------//
			$file = SITE_ROOT_URL."email-templates/ad-response.html";
			$content = "";
			if($handle = fopen($file, 'r'))
			{
				while(!feof($handle)) //feof means until file end of read everything
				{
					$content .= fgets($handle); // get everything from this file and add into $content.
				}
				fclose($handle);
			}			
			$replaceFrom		= array("{RESPONSE_DATE}","{RESPONSE_EMAIL}", "{RESPONSE_MOBILE}", "{RESPONSE_MESSAGE}");
			$replaceTo 			= array(datetime_to_text($classified->added_date),$_POST['email'],$_POST['mobile'],$_POST['message']);
			$emailContent		= str_replace($replaceFrom,$replaceTo,$content);
			//----------------------reading the email template ends from here-----------------------------//
						
			$mail = new htmlMimeMail();	
			$mail->setSMTPParams(SMTP_HOST,SMTP_PORT,SMTP_HELO,SMTP_AUTH,SMTP_USER,SMTP_PASS); //SETTING THE SMTP CREDENTIALS
			$emailTo=$classified->email;			
			$mail->setFrom(EMAIL_FROM);  
			$mail->setSubject("Ad Response : ".$classified->title.",  from M3H.com");		 
			$mail->setHtml($emailContent);	
			if($mail->send(array($emailTo),'smtp'))
			{
				$session->message("Thank You! Your response has been sent to advertiser.", 'success');
				redirect_to(getUrlAddress());
			}
			else
			{
				$session->message("Some error occurs please try again!!", 'error');
				redirect_to(getUrlAddress());
			}
		}
		else
		{
			$session->message("Some error occurs please try again!!", 'error');
			redirect_to(getUrlAddress());
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $classified->title.",".$state->title." | ".$category->title.",".$subcategory->title." | M3S Classifieds"; ?></title>

<?php
	$description = strip_tags($classified->description, "");
	$description = str_replace('\\', '', $description);
	$description = cropText($description, 60);
?>
<meta name="description" content="<?php echo $classified->title." in ".$state->title.$description." M3S Free Classifieds India"; ?>" />
<meta name="keywords" content="<?php echo $classified->keywords." M3S Classifieds India"; ?>" />

<link href="<?php echo SITE_ROOT_URL; ?>css/stylesheet.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="wrapper">
  <?php include("header.php"); ?>
  
  <?php
    //code for checking whether any success or error mssg exists
  	if(!empty($message))
	{
		if($session->message_type == "error")
		{
			echo "<div class='error_message'> $message </div><!--error_message-->";
		}
		else
		{
			echo "<div class='success_message'> $message </div><!--success_message-->";
		}
	}
  ?>
  
  <div class="clear"></div>
  
        <div class="bread_crump">
            <a href="<?php echo SITE_ROOT_URL; ?>"> M3H Classified </a> &raquo; 
            <?php echo "<a href='".$c_relink->replaceLink('?stateurl='.$state->url)."'> $state->title"; ?> </a> &raquo; 
            <?php echo "<a href='".$c_relink->replaceLink('?caturl='.$category->url)."'> $category->title"; ?></a> &raquo; 
            <?php echo "<a href='".$c_relink->replaceLink('?caturl='.$category->url.'&subcaturl='.$subcategory->url)."'> $subcategory->title"; ?></a> &raquo;
            <?php echo str_replace("\\","",$classified->title); ?>
        </div><!--bread_crump-->
        
        <div class="total_visits">
        	<?php
				/*change track function saved in access class(if($visit == 1 && substr(getUrlAddress(),0,29) == 'http://localhost/m3h/details/'))
				change it if url of the page changes in future*/
            	$count_replies = count(Response::find_by_adid($classified->unique_id));
				echo "<p> <strong>Visitors:</strong> ".$visits->getUniqueVisits()." | <strong>Replies:</strong> $count_replies</p>";
			?>
        </div><!--total_visits-->
  
  <div class="clear"></div>
  
  <!--<div class="listing-ad-types">  
  </div>listing-ad-types-->
    
  <div class="container" style="margin-top:0px; position:relative; z-index:1px; padding-bottom:25px;">
    <div class="detail-container">
       <h2><?php echo str_replace("\\","",$classified->title); ?></h2>
       
        <div class="detail-text">         
            <label> <strong>Updated On</strong>:</label> <p><?php echo datetime_to_text($classified->modified_date); ?></p>
            <div class="clear"></div>
         </div><!--detail-text-->
         
         <div class="detail-text">         
            <label> <strong>AD ID</strong>:</label> <p> <?php echo $classified->unique_id; ?></p>
            <div class="clear"></div>
         </div><!--detail-text-->
         
          <div class="detail-text">         
            <label> <strong> Location</strong>:</label> <p> <?php echo "$state->title, $location->title"; ?></p>
            <div class="clear"></div>
         </div><!--detail-text-->
           
          <div class="detail-text">         
            <label> <strong> Ad Type</strong>:</label> <p> <?php echo $classified->ad_type; ?></p>
            <div class="clear"></div>
         </div><!--detail-text-->
         
         <div class="detail-text" style="margin-bottom:25px;">         
                <label> <strong> Description</strong>:</label>
                <div class="classified-description">
                	<?php 
						$text = strip_tags($classified->description, "<span><p><h4><h3><h2><strong><a><img><table><tr><td><ul><li><br>");
						$content = str_replace('\\', '', $text);
						$content = str_replace('&nbsp;', '', $content);
						echo $content;
					?>
                </div><!--classified-description-->
           <div class="clear"></div>
         </div><!--detail-text-->         
         
          <?php
		  	  if(!empty($classified->company_name))
			  {
			  	  echo "<div class='detail-text'>";
					echo "<label> <strong> Company Name</strong>:</label> <p> $classified->company_name </p>";
					echo "<div class='clear'></div>";
				  echo "</div><!--detail-text-->";
			  }
			  if(!empty($classified->website_url))
			  {
			  	  echo "<div class='detail-text'>";
					echo "<label> <strong> Website/Blog URL</strong>:</label> <p> $classified->website_url </p>";
					echo "<div class='clear'></div>";
				  echo "</div><!--detail-text-->";
			  }
			  if(!empty($classified->keywords))
			  {
			  	  echo "<div class='detail-text'>";
					echo "<label> <strong> Keywords</strong>:</label> <p> $classified->keywords </p>";
					echo "<div class='clear'></div>";
				  echo "</div><!--detail-text-->";
			  }
          	  if(!empty($classified->filename))
			  {
			  	  echo "<div class='detail-text' style='margin-top:20px;'>";
					echo "<label> <strong> Image</strong>:</label>";
					echo "<p><img src=".SITE_ROOT_URL."classified-ads/$classified->filename width='296' height='222' border='1' /></p>";
					echo "<div class='clear'></div>";
				 echo "</div><!--detail-text-->";
			  }
		  ?>
          
          <div class="detail-text" style="margin-top:20px;">         
            <label> <strong> Share</strong>:</label>
            <p>
            <!-- AddThis Button BEGIN -->
                <div class="addthis_toolbox addthis_default_style">
                    <a class="addthis_button_facebook_like" fb:like:layout="button_count"  style="margin-left:20px;"></a>
                    <a class="addthis_button_tweet"></a>
                    <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
                    <a class="addthis_counter addthis_pill_style"></a>

                    <?php echo "<a href='".$c_relink->replaceLink('?tablename='.$tablename.'&repadid='.$classified->unique_id)."'>"; ?>
                    <img src="<?php echo SITE_ROOT_URL; ?>images/report-ad.jpg" border="0" style="float:left; margin:-1px 0 0 7px;" /> </a>
                </div>
			<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4fed6db56d0f62ad"></script>
            <!-- AddThis Button END -->
           </p>
            <div class="clear"></div>
         </div><!--detail-text-->
         
         <div class="facebook-comment-container">
         	<h3> Post your comments </h3>
            <div class="clear"></div>
             <div id="fb-root"></div>
             <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
              fjs.parentNode.insertBefore(js, fjs);
             }(document, 'script', 'facebook-jssdk'));</script>             
             <div class="fb-comments" data-href="<?php echo getUrlAddress(); ?>" data-num-posts="10" data-width="700"></div>
         </div><!--facebook-comment-container-->
                    
      <h5>Related Ads that may Interest you:</h5> 
      <div class="clear"></div>
      <div style="border: solid 1px #ccc; padding:14px;">    
      <?php
	  		$listing_tablename = str_replace('_', '-', $tablename); //tablename			
      		$rel_classifieds = Classifieds::find_by_subcategory($tablename, $subcategory->id);
			foreach($rel_classifieds as $rel_classified)
			{
				 echo "<div class='related-ads'>";
					echo "<h4><a href='".$c_relink->replaceLink('?tablename='.$listing_tablename.'&adtitle='.$rel_classified->url.'&adid='.$rel_classified->id)."'>".str_replace("\\","",$rel_classified->title)."</a></h4>";
					
					echo "<p>";
						$text = strip_tags($rel_classified->description, "<strong><p><a><img><table><tr><td><h3><h2><ul><li><br>");
						$content = htmlentities(str_replace('\\', '', $text));
						echo cropText($content, 90);
					echo "</p>";
					
					echo "<label>";
						$rel_state = States::find_by_id($rel_classified->state_id);
						$rel_category = Category::find_by_id($rel_classified->category_id);
						$rel_subcategory = Subcategory::find_by_id($rel_classified->subcategory_id);
						
						echo "<a href='".$c_relink->replaceLink('?caturl='.$rel_category->url.'&subcaturl='.$rel_subcategory->url.'&stateurl='.$rel_state->url.'&ad_type='.$rel_classified->ad_type)."'> <span class='green'> $rel_state->title </span>";
						echo "<span class='blue'>| $rel_category->title > $rel_subcategory->title </span></a>";
					echo "</label>";
					
					echo "<a href='".$c_relink->replaceLink('?tablename='.$listing_tablename.'&adtitle='.$rel_classified->url.'&adid='.$rel_classified->id)."'><img src='".SITE_ROOT_URL."images/reply1.jpg' width='54' height='19' border='0' /></a>";
					echo "<div class='clear'></div>";
				 echo "</div><!--related-ads-->";
			}
	  ?>      
    <p> &raquo; View more ads in
     	<?php echo "<a href='".$c_relink->replaceLink('?caturl='.$category->url)."'> $category->title </a> - 
		<a href='".$c_relink->replaceLink('?caturl='.$category->url.'&subcaturl='.$subcategory->url)."'> $subcategory->title </a>"; ?>
    </p>
    </div>
     
    </div><!--detail-container-->
    
    <div class="sidebar">
    
        <div class="sidebar-links">
            <h3> Advertiser Contact Details </h3>       
            <p style="margin-bottom:9px;"> <strong> Email</strong>:<br /> <?php echo $classified->email; ?></p>
            <?php
            	if($classified->mobile != "")
				{
					echo "<p><strong> Mobile</strong>:<br /> $classified->mobile</p>";
				}
			?>
        </div><!--sidebar-links-->
        
        <script language="javascript" type="text/javascript" src="<?php echo SITE_ROOT_URL; ?>js/common.js"></script>
	    <script language="javascript" type="text/javascript" src="<?php echo SITE_ROOT_URL; ?>js/prototype.js"></script>
        <script type="text/javascript">
	  	  function select_innerHTML(objeto,innerHTML)
		  {
 
    objeto.innerHTML = ""
    var selTemp = document.createElement("micoxselect")
    var opt;
    selTemp.id="micoxselect1"
    document.body.appendChild(selTemp)
    selTemp = document.getElementById("micoxselect1")
    selTemp.style.display="none"
    if(innerHTML.indexOf("<option")<0){//se não é option eu converto
        innerHTML = "<option>" + innerHTML + "</option>"
    }
    innerHTML = innerHTML.replace(/<option/g,"<span").replace(/<\/option/g,"</span")
    selTemp.innerHTML = innerHTML
 
    for(var i=0;i<selTemp.childNodes.length;i++){
  var spantemp = selTemp.childNodes[i];
 
        if(spantemp.tagName){
            opt = document.createElement("OPTION")
 
   if(document.all){ //IE
    objeto.add(opt)
   }else{
    objeto.appendChild(opt)
   }
 
   //getting attributes
   for(var j=0; j<spantemp.attributes.length ; j++){
    var attrName = spantemp.attributes[j].nodeName;
    var attrVal = spantemp.attributes[j].nodeValue;
    if(attrVal){
     try{
      opt.setAttribute(attrName,attrVal);
      opt.setAttributeNode(spantemp.attributes[j].cloneNode(true));
     }catch(e){}
    }
   }
   //getting styles
   if(spantemp.style){
    for(var y in spantemp.style){
     try{opt.style[y] = spantemp.style[y];}catch(e){}
    }
   }
   //value and text
   opt.value = spantemp.getAttribute("value")
   opt.text = spantemp.innerHTML
   //IE
   opt.selected = spantemp.getAttribute('selected');
   opt.className = spantemp.className;
  }
 }
 document.body.removeChild(selTemp)
 selTemp = null}
	  	  function checkEmail(email)
		  {    
		// a very simple email validation checking. 
		// you can add more complex email checking if it helps 
			if(email.length <= 0)
			{
			  return true;
			}
			var splitted = email.match("^(.+)@(.+)$");
			if(splitted == null) return false;
			if(splitted[1] != null )
			{
			  var regexp_user=/^\"?[\w-_\.]*\"?$/;
			  if(splitted[1].match(regexp_user) == null) return false;
			}
			if(splitted[2] != null)
			{
			  var regexp_domain=/^[\w-\.]*\.[A-Za-z]{2,4}$/;
			  if(splitted[2].match(regexp_domain) == null) 
			  {
				var regexp_ip =/^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
				if(splitted[2].match(regexp_ip) == null) return false;
			  }// if
			  return true;
			}
		return false;
		}
		  function isInteger(s)
		  {
			var i;
			for (i = 0; i < s.length; i++)
			{
				var c = s.charAt(i);
				if (((c < "0") || (c > "9"))) return false;
			}
			// All characters are numbers.
			return true;
		  }
		  function validateFrm()
		  {	
			$('emailErr').innerHTML='' ; 
			$('mobileErr').innerHTML='' ;
			$('messageErr').innerHTML='' ;
			
			var email = $('email').value ;  
			var mobile = $('mobile').value ;
			var message = $('message').value ;     
			  
			if(trim(email)  == '' )
			{        
				$('emailErr').innerHTML='Enter your email';
				$('email').value="";
				$('email').focus();
				return false;
			}     
			else if(trim(email)  != '' && checkEmail(email)==false)
			{           
				$('emailErr').innerHTML='Enter your valid email address';            
				$('email').focus();
				return false;
			}
			else if(trim(mobile)  == '' )
			{        
				$('mobileErr').innerHTML='Enter your mobile no';
				$('mobile').value="";
				$('mobile').focus();
				return false;
			}
			else if(trim(mobile)  != '' && isInteger(mobile)==false)
			{           
				$('mobileErr').innerHTML='Mobile no should be numeric only';            
				$('mobile').focus();
				return false;
			}
			else if(trim(message)  == '' )
			{        
				$('messageErr').innerHTML='Enter your message';
				$('message').value="";
				$('message').focus();
				return false;
			}
			return true;
		}
	    </script>
        
        <form action="<?php echo getUrlAddress(); ?>" method="post" onSubmit="javascript:return validateFrm();">
        <div class="sidebar-links" style="margin-top:30px; margin-bottom:25px;">
            <h3> Reply to this ad </h3>        
             <div class="reply-form" style="padding-top:8px;">
               <label>Email:</label>
               <input name="email" id="email" type="text" class="textfield" />
               <div class="clear"></div>
               <div class="error" id="emailErr"></div>
             </div><!--reply-form-->         
             
              <div class="reply-form">
               <label>Mobile:</label>
               <input name="mobile" id="mobile" type="text" class="textfield" />
               <div class="clear"></div>
               <div class="error" id="mobileErr"></div>
             </div><!--reply-form-->         
             
              <div class="reply-form">
               <label>Message:</label>
               <textarea name="message" id="message" cols="" rows="" class="textarea"></textarea>
               <div class="clear"></div>
               <div class="error" id="messageErr"></div>
             </div><!--reply-form-->
             
            <div class="reply-form">
               <label>&nbsp;</label>
               <input name="response" type="submit" class="submit" value="Send Response" />
               <div class="clear"></div>
             </div><!--reply-form-->
        </div><!--sidebar-links-->
        </form>
    
        <div class="sidebar-links">
            <h3> Ads in Other Categories </h3>
            <ul>
              <?php
              	  $sidebar_categories = Category::find_limited_visible();
				  foreach($sidebar_categories as $sidebar_category)
				  {
				  	 echo "<li> <a href='".$c_relink->replaceLink('?caturl='.$sidebar_category->url)."'> &raquo; $sidebar_category->title </a> </li>"; 
				  }
			  ?>
            </ul>
        </div><!--sidebar-links-->     
  	</div><!--sidebar-->    
  	<div class="clear"></div>
  </div><!--container-->
  
  <div class="featured-ads">
    <div class="ads-left">
      <img src="<?php echo SITE_ROOT_URL; ?>images/ad1.jpg" />
    </div><!--ads-left-->
    
    <div class="ads-right">
      <img src="<?php echo SITE_ROOT_URL; ?>images/ad2.jpg" />
    </div><!--ads-right-->
    
    <div class="clear"></div>
  </div><!--featured-ads-->
  <?php include("footer.php"); ?>
 </div><!--wrapper-->
</body>
</html>