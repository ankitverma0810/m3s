<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if($usersession->is_userlogged_in())
	{
		redirect_to("index.php");
	}
?>
<?php
	if(isset($_POST['submit']))
	{			
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);
		
		$found_user = Register::authenticate($email, $password);		
		if($found_user)
		{
			if(isset($_POST['rememberme']))
			{
				//means 10 days change value of 10 to how many days as you want
				setcookie ('useremail', $_POST['email'], time() + (60*60*24*100)); 
				setcookie ('userpassword', $_POST['password'], time() + (60*60*24*100));
			}
			else
			{
				//removing cookies is very simple, give the nagitive values in time
				setcookie ('useremail', "", time() - (60*60*24*100)); 
				setcookie ('userpassword', "", time() - (60*60*24*100));
			}
			$usersession->login($found_user);
			redirect_to("index.php");
		}
		else
		{			
			$session->message('The email/Password Combination is incorrect','error');
			redirect_to('login.php');
		}
	}
	else 
	{
		//checking whether cookie useremail is set or not
		if(isset($_COOKIE['useremail']))
		{
			$email = $_COOKIE['useremail'];
		}
		else
		{
			$email = "";
		}
		
		//checking whether cookie userpassword is set or not
		if(isset($_COOKIE['userpassword']))
		{
			$password = $_COOKIE['userpassword'];
		}
		else
		{
			$password = "";
		}
	}
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
	}
  ?>
  
  <div class="cms-container">
    <div class="register-left">
      <h2> Login Details </h2>
      <h3> Not a Member Yet? <a href="register.php"> Register Now! </a> </h3>
      
      <div class="clear"></div>
      
      <div class="border"> </div>
      
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
		function validateFrm()
		{	
			$('emailErr').innerHTML='' ; 
			$('passwordErr').innerHTML='' ;
			
			var email = $('email').value ;  
			var password = $('password').value ;     
			  
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
			return true;
		}
	  </script>
      
      <form action="login.php" method="post" onSubmit="javascript:return validateFrm();">
      <div class="resgiter-form" style="margin-bottom:15px;">
      	<label> Email: </label>
        <input name="email" id="email" type="text" class="textfield" value="<?php echo htmlentities($email); ?>" />
        <div class="error" id="emailErr"></div>
        <div class="clear"></div>
      </div><!--resgiter-form-->
      
      <div class="resgiter-form">
      	<label> Password: </label>
        <input name="password" id="password" type="password" class="textfield" value="<?php echo htmlentities($password); ?>" />
        <div class="error" id="passwordErr"></div>
        <div class="clear"></div>
      </div><!--resgiter-form-->
      
      <div class="resgiter-form">
      	<label> &nbsp; </label>        
        <input name="rememberme" type="checkbox" value="1" style="float:left;" <?php if(isset($_COOKIE['useremail']) && isset($_COOKIE['userpassword'])){echo "checked='checked'";} ?> />
        <p> Remember me </p>
        <div class="clear"></div>
      </div><!--resgiter-form-->

      <div class="resgiter-form">
      	<label> &nbsp; </label>
      	<input name="submit" type="submit" value="Login" class="submit" style="float:left;" />
        <p style="padding-top:15px; padding-left:10px;"> <a href="forgot-password.php" style="color:#D90000;"> Forgot Your Password? </a> </p>
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