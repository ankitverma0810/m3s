<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	//code for captcha on registration page STARTS here...
	if(isset($_SESSION['sp_registration_pin'])) {
		unset($_SESSION['sp_registration_pin']);    
	}
	$pin=substr(md5(rand(2,99999999)),0,6);
	$_SESSION['sp_registration_pin']=$pin;  
	
	//code for checking whether user is logged in or not for email address and mobile no STARTS here......
	if(isset($_SESSION['reguser_id']))
	{
		$user = Register::find_by_id($_SESSION['reguser_id']);
		$useremail = $user->email;
		$usermobile = $user->mobile;
	}
	else
	{
		$useremail = "";
		$usermobile = "";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>M3H</title>
<link href="css/stylesheet.css" rel="stylesheet" type="text/css" />
<!--rich text editor files-->
<script type="text/javascript" src="siteadmin/filemanager_in_ckeditor/js/ckeditor/ckeditor.js"></script>
<script src="siteadmin/filemanager_in_ckeditor/sample.js" type="text/javascript"></script>
<link href="siteadmin/filemanager_in_ckeditor/sample.css" rel="stylesheet" type="text/css" />
<link href="siteadmin/filemanager_in_ckeditor/js/ckeditor/skins/kama/editor.css" rel="stylesheet" type="text/css" />
<!--rich text editor files-->
</head>

<body>
<div class="wrapper">
  <?php include("header.php"); ?>
  
  <!--code for checking whether any error or success msg exists STARTS here-->
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
  <!--code for checking whether any error or success msg exists END here-->
  
  <div class="cms-container">
    <div class="register-left" style="width:950px;">
      <h2 style="font-size:14px;"> Please DO NOT post multiple ad for same product / service. All Duplicate, Spam & Wrongly Categorized Ads will deleted. </h2>
      
      <div class="clear"></div>      
      <div class="border"> </div>
    
    <!------------For Terms of use light box------------>
    <link type="text/css" rel="stylesheet" href="css/lightbox-form.css">
	<script src="js/lightbox-form.js" type="text/javascript"></script>
        
    <!-- USE THIS AJAX SCRIPT -->
    <script type="text/javascript">
    function showarea(str)
    {
        if (str=="")
        {
            alert('You have to Select any service');
        }
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            var subcategory = xmlhttp.responseText;
            document.getElementById('sub_select').innerHTML = subcategory;
            }
        }
        xmlhttp.open("GET","getlocality.php?main_cat="+str,true);
        xmlhttp.send();
    }
	function showsubcat(str)
    {
        if (str=="")
        {
            alert('You have to Select any service');
        }
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpq=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttpq=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttpq.onreadystatechange=function()
        {
            if (xmlhttpq.readyState==4 && xmlhttpq.status==200)
            {
            var subcategory = xmlhttpq.responseText;
            document.getElementById('subcat_select').innerHTML = subcategory;
            }
        }
        xmlhttpq.open("GET","getsubcat.php?main_cat="+str,true);
        xmlhttpq.send();
    }
	function show_type(val)
	{
		if(val<1)
		{
			document.getElementById('ad_type').style.display='none';
		}
		else
			document.getElementById('ad_type').style.display='';
	}
    </script>
    <!-- USE THIS AJAX SCRIPT EOF-->
    
    <!-- Scripts for validation of form starts from here-->
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
            $('statesErr').innerHTML='' ; 
            $('categoryErr').innerHTML='' ;
            $('subcategoryErr').innerHTML='' ;
            $('titleErr').innerHTML='' ;
			$('editor1Err').innerHTML='' ;
			$('emailErr').innerHTML='' ;
			$('mobileErr').innerHTML='' ;
			$('filenameErr').innerHTML='' ;
			$('confirm_pinErr').innerHTML ="";
            
            var states = $('states').value ;  
            var category = $('category').value ;
            var subcategory = $('subcategory').value ;
			var title = $('title').value ;
			//var editor1 = $('editor1').value ;
			var editor1 = CKEDITOR.instances['editor1'].getData().replace(/<[^>]*>/gi, '');			
			var email = $('email').value ;
			var mobile = $('mobile').value ;
			var filename = $('filename').value ; 
            var pin = $('pin').value ; 
            var confirm_pin = $('confirm_pin').value ;    
              
            if(trim(states)  == '' )
            {        
                $('statesErr').innerHTML='Please Select State';
                $('states').value="";
                $('states').focus();
                return false;
            }
			else if(trim(category)  == '' )
            {        
                $('categoryErr').innerHTML='Please Select Category';
                $('category').value="";
                $('category').focus();
                return false;
            }
			else if(trim(subcategory)  == '' )
            {        
                $('subcategoryErr').innerHTML='Please select a relevant Subcategory ';
                $('subcategory').value="";
                $('subcategory').focus();
                return false;
            }
			else if(trim(title)  == '' )
            {        
                $('titleErr').innerHTML='Enter Ad Title';
                $('title').value="";
                $('title').focus();
                return false;
            }
			else if(trim(title.length) < 20)
            {        
                $('titleErr').innerHTML='Ad Title should not be less than 20 characters';
                $('title').value="";
                $('title').focus();
                return false;
            }
			else if(trim(title.length) > 70)
            {        
                $('titleErr').innerHTML='Ad Title should not be more than 70 characters';
                $('title').value="";
                $('title').focus();
                return false;
            }
			else if(trim(editor1.length) == '')
            {        
                $('editor1Err').innerHTML='Enter Ad Description';
				return false;
            }
			else if(trim(editor1.length) < 60)
            {        
                $('editor1Err').innerHTML='Ad Description should not be less than 60 characters';
				return false;
            }
			else if(trim(editor1.length) > 1000)
            {        
                $('editor1Err').innerHTML='Ad Description should not be more than 1000 characters';
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
                $('confirm_pinErr').innerHTML = '(Enter the pin number as shown)';        
                $('confirm_pin').focus();
                return false;
            }
            else if(confirm_pin != pin )
            {           
                $('confirm_pinErr').innerHTML = '(Enter the correct pin number as shown)';        
                $('confirm_pin').focus();
                return false;
            }
            return true;
        }
      </script>
    <!-- Scripts for validation of form ends here-->
      
      <form method="post" action="post-ad.php" onSubmit="javascript:return validateFrm();" enctype="multipart/form-data">
      <div class="post-form" style="margin-top:20px;">
      	<label> <span class="red">*</span>State: </label>
        <select name="states" id="states" class="dropdown" onchange="showarea(this.value)">
        	<option value=""> -- Select State -- </option>
            <?php
            	$states = States::find_all_visible();
				foreach($states as $state)
				{
					echo "<option value='$state->id'> $state->title </option>";
				}
			?>
        </select>
        <div class="error" id="statesErr"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> Locality: </label>
        <div id="sub_select">
        <select name="locality" id="locality" class="dropdown">
        	<option value=""> -- Select Locality -- </option>
        </select>
        </div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <span class="red">*</span>Select Category:</label>
        	<div class="post-form-headings"> 
            <h4> Select Category </h4>
        	<select size="10" name="category" id="category" style="border:none 0px; padding:2px; width:275px;" onchange="showsubcat(this.value)">
            	<?php
					$categories = Category::find_all_visible();
					foreach($categories as $category)
					{
						echo "<option value='$category->id'> $category->title </option>";
					}
				?>
			</select>
            </div>
            
            <div class="post-form-headings"> 
            <h4> Please select a relevant Subcategory </h4>            
            <div id="subcat_select">
              <select size="10" name="subcategory" id="subcategory" style="border:none 0px; padding:2px; width:275px;">
              </select> 
            </div><!--subcat_select-->
            </div><!--post-form-headings-->
            <div class="error" id="categoryErr"></div>
            <div class="error" id="subcategoryErr"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form" id="ad_type" style="display:none;">
      	<label> Ad Type: </label>
        <input name="type" id="type" type="radio" value="offer-ad" class="radio" checked="checked" />
        <p style="margin-right:10px;"> Offer Ad </p>
        
        <input name="type" id="type" type="radio" value="wanted-ad" class="radio" />
        <p> Wanted Ad</p>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <span class="red">*</span>Ad Title: </label>
        <input name="title" id="title" type="text" class="textfield" />
        <div class="error" id="titleErr"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <span class="red">*</span>Ad Description: </label>
        <div style="float:left; width:650px;">
        	<textarea id="editor1" name="editor1" class="textarea"></textarea>
        </div>
	    <script type="text/javascript">
        CKEDITOR.replace( 'editor1',
			{
				fontSize_sizes : "30/30%;50/50%;75/75%;100/100%;120/120%;150/150%;200/200%;300/300%",
				toolbar :
				[
					['Link', 'Unlink'],
					['Bold', 'Italic','Underline'],
					['FontSize'],
					['TextColor'],
					['NumberedList','BulletedList'],
					['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
				],		
			}      
        );
        </script>
        <div class="error" id="editor1Err"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <span class="red">*</span>Email Id: </label>        
        <input name="email" id="email" type="text" class="textfield" value="<?php echo $useremail; ?>" <?php if(isset($_SESSION['reguser_id'])){ echo "readonly='readonly' onfocus='this.blur();'";}?> />        
        <div class="error" id="emailErr"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <span class="red">*</span>Mobile Number: </label>
        <input name="mobile" id="mobile" type="text" class="textfield" value="<?php echo $usermobile; ?>" <?php if(isset($_SESSION['reguser_id'])){ echo "readonly='readonly' onfocus='this.blur();'";}?> />
        <div class="error" id="mobileErr"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label>Image Upload:<br />
      	 <p style="color:#999999"> (upto 2MB only) </p> </label>
        <input name="filename" id="filename" type="file" size="26" />
        <div class="error" id="filenameErr"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> Company Name: </label>
        <input name="company" type="text" class="textfield" />
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> Website/Blog URL: </label>
        <input name="website" type="text" class="textfield" />
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> Keywords: </label>
        <textarea name="keywords" cols="" rows="" class="textarea" style="width:400px; height:70px;"></textarea>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <span class="red">*</span>Word Verification: <br /> <p style="color:#999999"> (Reduce Spam posting) </p> </label>
        <img src="CaptchaSecurityImages.php" style="float:left; margin-right:8px; border:solid 1px #666666;" />
        <input type="hidden" name="pin" id="pin" value="<?php echo $_SESSION['sp_registration_pin']; ?>">
        <input name="confirm_pin" id="confirm_pin" type="text" style="width:100px; margin-top:10px;" />
        <div class="error" id="confirm_pinErr"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form" style="margin-top:20px;">
      	<label> &nbsp; </label>
        <p> By clicking 'Post Now' button, you agree to <a href="javascript:void(0);" onClick="openbox('Terms of use', 1)"> Terms & Conditions </a> of m3s.in</p>
        <div class="clear"></div>
      </div><!--post-form-->
      
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
      
      <div class="post-form">
      	<label> &nbsp; </label>
      	<input name="submit" type="submit" value="Post Now" class="submit" style="float:left; margin-top:0px;" />
        <div class="clear"></div>
      </div><!--post-form-->
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
