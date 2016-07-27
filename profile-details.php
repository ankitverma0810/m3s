<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(!$usersession->is_userlogged_in())
	{
		redirect_to("index.php");
	}
	else
	{
		$user = Register::find_by_id($_SESSION['reguser_id']);
	}
	if(isset($_POST['submit']))
	{
		$user->email = $_POST['email'];
		$user->mobile = $_POST['mobile'];
		if($user->save())
		{
			$session->message('Profile updated successfully', 'success');
			redirect_to('profile-details.php');
		}
		else
		{
			$session->message('Some error occurs, please try again!', 'error');
			redirect_to('profile-details.php');
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
  
  <div class="welcome-text">
    <span style="color:#1b387d; font-size:15px;">Welcome to M3S.in</span>, a free classified ads portal of India for all categories as Education, Automobiles, Travels, Real Estate etc.
  </div><!--welcome-text-->
  
  <div class="clear"></div>
  
    <div class="inner-container">
       <?php include("inner-sidebar.php"); ?>
       <div class="ad-left">
          <h1>Profile Details</h1>
          
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
                $('mobileErr').innerHTML='' ;
                
                var email = $('email').value ;  
                var mobile = $('mobile').value ;     
                  
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
                return true;
            }
          </script>
          
          <form action="profile-details.php" method="post" onSubmit="javascript:return validateFrm();">
          <div class="ad-form-contaimer">
             
              <div class="ad-form">
               <label><span class="red"> *</span>Email Address:</label>
               <input name="email" id="email" type="text"  class="textfield" value="<?php echo $user->email; ?>"/>
               <div class="error" id="emailErr"></div>
               <div class="clear"></div>
             </div><!--ad-form-->
             
               <div class="ad-form">
               <label><span class="red"> *</span>Mobile Number:</label>
               <input name="mobile" id="mobile" type="text"  class="textfield" value="<?php echo $user->mobile; ?>"/>
               <div class="error" id="mobileErr"></div>
               <div class="clear"></div>
             </div><!--ad-form-->
             
             <div class="ad-form" style="margin-top:-10px;">
               <label>&nbsp;</label>
               <input name="submit" type="submit" value="Update Profile" class="submit"/>
               <div class="clear"></div>
             </div><!--ad-form-->            
          </div><!--ad-form-contaimer-->
          </form>
       </div><!--ad-left-->
       
       <div class="clear"></div>
    </div><!--inner-container-->    
  
  <?php include("footer.php"); ?>
</div><!--wrapper-->
</body>
</html>