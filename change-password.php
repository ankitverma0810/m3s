<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if($usersession->is_userlogged_in())
	{
		redirect_to("index.php");
	}
	if(empty($_GET['reguserid']))
	{
		$session->message('Wrong Confirmation code!', 'error');
		redirect_to('forgot-password.php');
	}
	else
	{
		$user = Register::find_by_id($_GET['reguserid']);
	}
?>
<?php
	if(isset($_POST['submit']))
	{
		$user->password = $_POST['conf_password'];
		if($user->save())
		{
			$session->message('Password Change Successfully', 'success');
			redirect_to('login.php');
		}
		else
		{
			$session->message('Some error occurs, please try again!', 'error');
			redirect_to('change-password.php?reguserid='.$user->id);
		}
	}
	else
	{
		$password = "";
		$confirm_password = "";
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
  
  <div class="cms-container">
    <div class="register-left" style="width:950px;">
      <h2>Change Password</h2>
      
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
		function validateFrm()
		{	
			$('passwordErr').innerHTML='' ;
			$('conf_passwordErr').innerHTML='' ;
			
			var password = $('password').value ;
			var conf_password = $('conf_password').value ;     

			if(trim(password)  == '' )
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
			return true;
		}
	  </script>
      
      <form method="post" action="change-password.php?reguserid=<?php echo $user->id; ?>" onSubmit="javascript:return validateFrm();">
      <div class="resgiter-form" style="margin-top:20px;">
      	<label style="width:200px;">Login Name:</label>
        <p style="font-size:12px;"> <?php echo $user->email; ?></p>
        <div class="clear"></div>
      </div><!--resgiter-form-->
      
      <div class="resgiter-form" style="margin-top:20px;">
      	<label style="width:200px;"> <span class="red">*</span> New Password:</label>
        <input name="password" id="password" type="password" class="textfield" value="<?php echo $password; ?>" />
        <div class="error" id="passwordErr" style="margin-left:200px;"></div>
        <div class="clear"></div>
      </div><!--resgiter-form-->
      
      <div class="resgiter-form" style="margin-top:20px;">
      	<label style="width:200px;"> <span class="red">*</span>Confirm Password:</label>
        <input name="conf_password" id="conf_password" type="password" class="textfield" value="<?php echo $confirm_password; ?>" />
        <div class="error" id="conf_passwordErr" style="margin-left:200px;"></div>
        <div class="clear"></div>
      </div><!--resgiter-form-->
      
      <div class="resgiter-form">
      	<label style="width:200px;"> &nbsp; </label>
      	<input name="submit" type="submit" value="Change Password" class="submit" style="float:left; margin-top:0px;" />
        <div class="clear"></div>
      </div><!--resgiter-form-->
      </form>
    </div><!--register-left-->

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
