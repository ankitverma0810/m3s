<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if($usersession->is_userlogged_in())
	{
		redirect_to("index.php");
	}
	//code for captcha on registration page STARTS here...
	if(isset($_SESSION['sp_registration_pin']))
	{
		unset($_SESSION['sp_registration_pin']);    
	}
	$pin=substr(md5(rand(2,99999999)),0,6);
	$_SESSION['sp_registration_pin']=$pin;    
	//code for captcha on registration page ENDS here...
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>M3H</title>
<link href="css/stylesheet.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="wrapper">
  <?php include("header.php"); ?>
    
  <div class="welcome-text">
    <span style="color:#1b387d; font-size:15px;">Welcome to M3S.in</span>, a free classified ads portal of India for all categories as Education, Automobiles, Travels, Real Estate etc.
  </div><!--welcome-text-->
  
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
  
  <div class="cms-container">
    <div class="register-left">
      <h2> Registration Details </h2>
      <h3> Already a member? <a href="login.php"> Login </a> </h3>
      
      <div class="clear"></div>
      
      <div class="border"> </div>
      
      <!------------For Terms of use light box------------>
      <link type="text/css" rel="stylesheet" href="css/lightbox-form.css">
	  <script src="js/lightbox-form.js" type="text/javascript"></script>

	  <!------------For javascript validation------------>
      <script language="javascript" type="text/javascript" src="js/common.js"></script>
	  <script language="javascript" type="text/javascript" src="js/prototype.js"></script>
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
			$('passwordErr').innerHTML='' ;
			$('conf_passwordErr').innerHTML='' ;
			$('mobileErr').innerHTML='' ;
			$('confirm_pinErr').innerHTML ="";
			$('termsErr').innerHTML='' ;
			
			var email = $('email').value ;  
			var password = $('password').value ;
			var conf_password = $('conf_password').value ;
			var mobile = $('mobile').value ; 
			var pin = $('pin').value ; 
			var confirm_pin = $('confirm_pin').value ;
			var terms = $('terms').checked ;      
			  
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
			else if(trim(password)  == '' )
			{        
				$('passwordErr').innerHTML='Enter password';
				$('password').value="";
				$('password').focus();
				return false;
			}
			else if( trim(password.length) < 6)
			{
				$('passwordErr').innerHTML='Enter password more than 6 characters!';        
				$('password').focus();
				return false;
			}
			else if(trim(conf_password)  == '' )
			{        
				$('conf_passwordErr').innerHTML='Enter confirm password';
				$('conf_password').value="";
				$('conf_password').focus();
				return false;
			}
			else if(trim(conf_password) != trim(password)  )
			{        
				$('conf_passwordErr').innerHTML='Confirm Password not matched with password';
				$('conf_password').value="";
				$('conf_password').focus();
				return false;
			}
			else if(trim(mobile)  == '' )
            {        
                $('mobileErr').innerHTML='Enter your Mobile no';
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
			else if(trim(mobile.length) < 10)
			{
				$('mobileErr').innerHTML='Mobile no should not be less than 10 digits';
				return false;
			}
			else if(trim(mobile.length) > 10)
			{
				$('mobileErr').innerHTML='Mobile no should not be more than 10 digits';
				return false;
			}
			else if(mobile.charAt(0)!="9" && mobile.charAt(0)!="8" && mobile.charAt(0)!="7")
			{
				$('mobileErr').innerHTML='Mobile no should start with 9,8,7';
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
			else if(terms==false)
			{
				$('terms').value=0;
				$('termsErr').innerHTML = '(Please read and accept the terms and conditions)';        
				$('terms').focus();
				return false;
			}
			return true;
		}
	  </script>
      
      <form name="registrationFrm" id="registrationFrm" method="post" action="registeration-complete.php" onSubmit="javascript:return validateFrm();">
      <div class="resgiter-form">
      	<label> <span class="red">*</span>Email Address: </label>
        <input name="email" id="email" type="text" class="textfield" />
        <p style="margin-left:7px;"> Your login id </p>
        <div class="error" id="emailErr"></div>
        <div class="clear"></div>
      </div><!--resgiter-form-->
      
      <div class="resgiter-form">
      	<label> <span class="red">*</span>Password: </label>
        <input name="password" id="password" type="password" class="textfield" />
        <p style="margin-left:7px;"> Minimum 6 character </p>
        <div class="error" id="passwordErr"></div>
        <div class="clear"></div>
      </div><!--resgiter-form-->
      
      <div class="resgiter-form">
      	<label> <span class="red">*</span>Confirm Password: </label>
        <input name="conf_password" id="conf_password" type="password" class="textfield" />
        <div class="error" id="conf_passwordErr"></div>
        <div class="clear"></div>
      </div><!--resgiter-form-->
      
      <div class="resgiter-form">
      	<label> <span class="red">*</span>Mobile Number: </label>
        <input name="mobile" id="mobile" type="text" class="textfield" />
        <div class="error" id="mobileErr"></div>
        <div class="clear"></div>
      </div><!--resgiter-form-->
      
      <div class="resgiter-form">
      	<label> Landline Number: </label>
        <input name="landline" id="landline" type="text" class="textfield" />
        <div class="clear"></div>
      </div><!--resgiter-form-->
      
      <div class="resgiter-form">
      	<label> <span class="red">*</span>Word Verification: </label>
        <p> Type the characters you see in the picture below. </p>
        <div class="clear"></div>
      </div><!--resgiter-form-->

      <div class="resgiter-form"> 
      	<label> &nbsp; </label>     	
        <img src="CaptchaSecurityImages.php" style="float:left; margin-right:8px;" />
        <input type="hidden" name="pin" id="pin" value="<?php echo $_SESSION['sp_registration_pin']; ?>">
        <input name="confirm_pin" id="confirm_pin" type="text" style="width:100px; margin-top:10px;" />
        <div class="error" id="confirm_pinErr"></div>
        <div class="clear"></div>
      </div><!--resgiter-form-->
      
      <div class="resgiter-form" style="margin-bottom:4px;">
      	<label> &nbsp; </label>
        <p> <em> Letters are case-sensitive </em> </p> <br />
        <div class="clear"></div>
      </div><!--resgiter-form-->
      
      <div class="resgiter-form">
      	<label> &nbsp; </label>
        <input name="terms" id="terms" type="checkbox" value="" class="checkbox" />
        <p> <span class="red">*</span>I agree the <a href="javascript:void(0);" onClick="openbox('Terms of use', 1)"> Terms & Conditions </a> of m3s.in </p>
        <div class="error" id="termsErr"></div>
        <div class="clear"></div>
      </div><!--resgiter-form-->
      
      <div style="display: none;" id="shadowing"></div>
          <div style="opacity: 0.99; display: none;" id="box">
          <span id="boxtitle">Terms of use</span>
          <p class="cross"> 
              <a href="javascript:void(0);" onClick="closebox()" style="color:#ffffff; line-height:30px;"> Close Window </a> 
          </p>            
          <div class="term-of-use">        
              <p>  <strong>Service Agreement</strong><br>
            1.	There is a Membership fee Rs. 1000/- only, valid for one year from the date of registration. The membership fee is neither refundable nor transferable under any circumstances.<br />
            2.	Required Documents: Residence/address proof. Identity proof (must) and Two color pp size photographs<br />
            3.	There is a renewal of Rs. 500/- only every year.<br />
            4.	<strong> Our Commission</strong> : <br />
            &nbsp; &nbsp; &nbsp; a) In case Of Monthly Tuitions 50 % of Two  month Fee.<br />
            &nbsp; &nbsp; &nbsp;  b) In Case Of Hourly Assignment: First 10 Classes Payments.<br />
              &nbsp; &nbsp; &nbsp; c) In Case Of Short assignment: 35 % commission will be charged.<br />
            5.	If for any reason or means you work with our client directly, legal suit can be file against you.<br />
            6.	If you get new assignment from the reference of old assignment than you have to follow rule 4 and all.<br />
            7.	A tutor will be given at most two tuitions to prove his/her capabilities. If one can not finalise (satisfy) any of these two tuitions, then he/she may not get the third chance.<br />
            8.	When a tutor is given any tuition, then it is the duty of the tutor to inform the Bureau about the developments within 24 hours.<br />
            9.	The tutors will have to be punctual. If any tutor is not regular / punctual, then his / her registration may be cancelled.<br />
            10.	If a tutor want to discontinue any assignment/Tuition, He/She need to inform 1 month prior notice. If any tutor does so, the payment of the last month will not be done to the tutor as penalty.<br />
            11.	THE FEE IS COLLECTED FROM THE PARTY IN ADVANCE AND THE TUTORS ARE PAID ONLY AFTER THE COMPLETION OF THE MONTH / CLASSESS for First two Months only.<br />
            12.	If any tuition continues for the next year / years, the tutor has to pay the commission at the rate of 50% for one Month Only. In case of Hourly: only for 7 classes.<br />
            13.	Registration can be cancelled without notification on: - <br />
            &nbsp; &nbsp; &nbsp; A. Any misconduct to the Party or Bureau.<br />
            &nbsp; &nbsp; &nbsp; B. Failure of consecutive assignment by any reason. This term can be reconsider by Bureau.<br />
            &nbsp; &nbsp; &nbsp;  C. Absentee for a long duration without information.<br />
             &nbsp; &nbsp; &nbsp; D. Diverting from the above rule.<br />
            14.	Talent Tutorials is not responsible for any mishap/accident while handling assignment from Talent Tutorials.<br /></p>
              <div class="clear"></div>
          </div><!--term-of-use-->
      </div><!--box-->
      
      <div class="resgiter-form">
      	<label> &nbsp; </label>
      	<input name="submit" type="submit" value="Submit" class="submit" />
        <div class="clear"></div>
      </div><!--resgiter-form-->
      </form>
    </div><!--register-left-->
    
    <div class="register-right">
    	<h2> Registering allows you to </h2>
        <ul>
            <li> Post Free Ads </li>
            <li> Reserve your own nickname </li>
            <li> Manage your Ads & Replies </li>
        </ul>
    </div><!--register-right-->
    
    <div class="clear"></div>
  </div><!--cms-container-->
  
  <div class="featured-ads">
    <div class="ads-left">
      <img src="images/ad1.jpg" />
    </div><!--ads-left-->
    
    <div class="ads-right">
      <img src="images/ad2.jpg" />
    </div><!--ads-right-->
    
    <div class="clear"></div>
  </div><!--featured-ads-->
  
  <?php include("footer.php"); ?>
</div><!--wrapper-->
</body>
</html>