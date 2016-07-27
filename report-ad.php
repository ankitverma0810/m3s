<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(empty($_GET['tablename']) || empty($_GET['repadid']))
	{
		redirect_to(SITE_ROOT_URL.'index.php');
	}
	else
	{
		$classified = Classifieds::find_by_uniqueid($_GET['repadid'], $_GET['tablename']);
	}
	
	//for seo friendly link//
	$htaccessFile	= 'htaccess';
	$c_relink		= new RELINK($htaccessFile);
	
	
	//code for captcha on registration page STARTS here...
	if(isset($_SESSION['sp_registration_pin']))
	{
		unset($_SESSION['sp_registration_pin']);    
	}
	$pin=substr(md5(rand(2,99999999)),0,6);
	$_SESSION['sp_registration_pin']=$pin;
	
	
	//if user clicks on submit button//
	if(isset($_POST['submit']))
	{
		include_once('mail_files/htmlMimeMail.php');
					
		//----------------------reading the email template starts from here-----------------------------//
		$file = SITE_ROOT_URL."email-templates/report-ad.html";
		$content = "";
		if($handle = fopen($file, 'r'))
		{
			while(!feof($handle)) //feof means until file end of read everything
			{
				$content .= fgets($handle); // get everything from this file and add into $content.
			}
			fclose($handle);
		}			
		$replaceFrom		= array("{AD_TITLE}","{UNIQUE_ID}","{SPAM}");
		$replaceTo 			= array(str_replace("\\","",$classified->title),$classified->unique_id,str_replace("\\","",$_POST['spam']));
		$emailContent		= str_replace($replaceFrom,$replaceTo,$content);
		//----------------------reading the email template ends from here-----------------------------//
										
		$mail = new htmlMimeMail();	
		$mail->setSMTPParams(SMTP_HOST,SMTP_PORT,SMTP_HELO,SMTP_AUTH,SMTP_USER,SMTP_PASS);			
		$emailTo=EMAIL_FROM;			
		$mail->setFrom($_POST['email']);  
		$mail->setSubject("Report Ad Mail");		 
		$mail->setHtml($emailContent);		
		if($mail->send(array($emailTo),'smtp'))
		{
			$session->message("Your Information has been successfully sent. Thank you!!", "success");
			redirect_to($c_relink->replaceLink('?tablename='.$_GET['tablename'].'&repadid='.$_GET['repadid']));
		}
		else
		{
			$session->message("Some error occurs, please try again", "error");
			redirect_to($c_relink->replaceLink('?tablename='.$_GET['tablename'].'&repadid='.$_GET['repadid']));
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>M3H</title>
<link href="<?php echo SITE_ROOT_URL; ?>css/stylesheet.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="wrapper">
  <?php include("header.php"); ?>
  
  <div class="clear"></div>
  
  <?php
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
		//echo $message;
	}
  ?>
  
   <div class="cms-wrapper">
   		<div class="cms-left">
        	<div class="report-ad-container">
            	<h2> Ad Title: <?php echo str_replace("\\","",$classified->title); ?> </h2>
                
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
                    function validateFrm()
                    {	
                        $('nameErr').innerHTML='' ; 
                        $('emailErr').innerHTML='' ;
                        $('messageErr').innerHTML='' ;
                        $('confirm_pinErr').innerHTML ="";
                        
                        var name = $('name').value ;  
                        var email = $('email').value ;
                        var message = $('message').value ; 
                        var pin = $('pin').value ; 
                        var confirm_pin = $('confirm_pin').value ;     
                          
                        if(trim(name)  == '' )
                        {        
                            $('nameErr').innerHTML='Enter your name';
                            $('name').value="";
                            $('name').focus();
                            return false;
                        }
						else if(trim(email)  == '' )
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
						else if(trim(message)  == '' )
                        {        
                            $('messageErr').innerHTML='Enter your message';
                            $('message').value="";
                            $('message').focus();
                            return false;
                        }                        
                        else if(trim(confirm_pin) == "" )
                        {   
                            $('confirm_pinErr').innerHTML = '(Enter the pin number as shown above)';        
                            $('confirm_pin').focus();
                            return false;
                        }
                        else if(confirm_pin != pin )
                        {           
                            $('confirm_pinErr').innerHTML = '(Enter the correct pin number as shown above)';        
                            $('confirm_pin').focus();
                            return false;
                        }
                        return true;
                    }
                </script>
                
                <form action="<?php echo $c_relink->replaceLink('?tablename='.$_GET['tablename'].'&repadid='.$_GET['repadid']); ?>" method="post" onSubmit="javascript:return validateFrm();">
                <div class="report-ad">
                	<label>Name: </label>
                    <input name="name" id="name" type="text" class="textfield" />
                    <div class="clear"></div>
                    <div class="error" id="nameErr"></div>
                </div><!--report-ad-->
                
                <div class="report-ad">
                	<label>Email: </label>
                    <input name="email" id="email" type="text" class="textfield" />
                    <div class="clear"></div>
                    <div class="error" id="emailErr"></div>
                </div><!--report-ad-->
                
                <div class="report-ad">
                	<p style="float:none; margin-top:19px;"> <strong>1.) What's wrong with this ad?</strong> </p>
                	<p>Spam - It's promoting another website </p>
                    <input name="spam" type="radio" value="Spam - It's promoting another website" class="radio" checked="checked" />
                    <div class="clear"></div>
                    
                    <p>Duplicate - It's identical to another ad in this category </p>
                    <input name="spam" type="radio" value="Duplicate - It's identical to another ad in this category" class="radio" />
                    <div class="clear"></div>
                    
                    <p> Wrong category - It doesn't belong in this category </p>
                    <input name="spam" type="radio" value="Wrong category - It doesn't belong in this category" class="radio" />
                    <div class="clear"></div>
                    
                    <p> Illegal / fraudulent - You suspect this to be a scam </p>
                    <input name="spam" type="radio" value="Illegal / fraudulent - You suspect this to be a scam" class="radio" />
                    <div class="clear"></div>
                    
                    <p> Against Travelswale policy </p>
                    <input name="spam" type="radio" value="Against Travelswale policy" class="radio" />
                    <div class="clear"></div>
                </div><!--report-ad-->
                
                <div class="report-ad">
                	<p style="float:none; margin-bottom:7px; margin-top:19px;"> <strong>Anything else you'd like to add?</strong> </p>
                    <textarea name="message" id="message" cols="" rows="" class="textarea"></textarea>
                    <div class="clear"></div>
                    <div class="error" id="messageErr" style="margin-left:0px;"></div>
                </div><!--report-ad-->
                
                <div class="report-ad">
                    <label> Word Verification: </label>
                    <p> Type the characters you see in the picture below. </p>
                    <div class="clear"></div>
                </div><!--report-ad-->
                
                <div class="report-ad"> 
                    <label> &nbsp; </label>     	
                    <img src="<?php echo SITE_ROOT_URL; ?>CaptchaSecurityImages.php" style="float:left; margin-right:8px;" />
                    <input type="hidden" name="pin" id="pin" value="<?php echo $_SESSION['sp_registration_pin']; ?>">
                    <input name="confirm_pin" id="confirm_pin" type="text" style="width:100px; margin-top:10px;" />
                    <div class="error" id="confirm_pinErr"></div>
                    <div class="clear"></div>
                </div><!--report-ad-->
                
                <div class="report-ad" style="margin-bottom:4px;">
                    <label> &nbsp; </label>
                    <p> <em> Letters are case-sensitive </em> </p> <br />
                    <div class="clear"></div>
                </div><!--report-ad-->
                
                <div class="report-ad">
                	<label> </label>
                    <input name="submit" type="submit" class="submit" value="SUBMIT" />
                    <input name="cancel" type="reset" class="submit" value="CANCEL" />
                    <div class="clear"></div>
                </div><!--report-ad-->
                </form>
            </div><!--report-ad-container-->
        </div><!--cms-left-->
        
        <?php include("sidebar.php"); ?>
        
        <div class="clear"></div>
   </div><!--cms-wrapper-->
    
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