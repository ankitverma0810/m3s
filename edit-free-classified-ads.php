<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(empty($_GET['edit_adid']) || empty($_GET['tablename']))
	{
		$session->message('Some error occurs, please try again!','error');
		redirect_to(SITE_ROOT_URL.'index.php');
	}
	else
	{
		$category_tablename = str_replace('-', '_', $_GET['tablename']);
		$classified = Classifieds::find_by_uniqueid($_GET['edit_adid'], $category_tablename);
		if(!$classified || $classified->ad_status == 3 || $classified->ad_status == 4 || $classified->ad_status == 5)
		{
			$session->message('We are not able to find the ad which you want, please try again later!','error');
			redirect_to(SITE_ROOT_URL.'index.php');
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>M3H</title>
<link href="<?php echo SITE_ROOT_URL; ?>css/stylesheet.css" rel="stylesheet" type="text/css" />
<!--rich text editor files-->
<script type="text/javascript" src="<?php echo SITE_ROOT_URL; ?>siteadmin/filemanager_in_ckeditor/js/ckeditor/ckeditor.js"></script>
<script src="<?php echo SITE_ROOT_URL; ?>siteadmin/filemanager_in_ckeditor/sample.js" type="text/javascript"></script>
<link href="<?php echo SITE_ROOT_URL; ?>siteadmin/filemanager_in_ckeditor/sample.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE_ROOT_URL; ?>siteadmin/filemanager_in_ckeditor/js/ckeditor/skins/kama/editor.css" rel="stylesheet" type="text/css" />
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
        xmlhttp.open("GET","<?php echo SITE_ROOT_URL; ?>getlocality.php?main_cat="+str,true);
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
        xmlhttpq.open("GET","<?php echo SITE_ROOT_URL; ?>getsubcat.php?main_cat="+str,true);
        xmlhttpq.send();
    }
	function show_type(val)
	{
		if(val<1)
		{
			document.getElementById('ad_type').style.display='none';
		}
		else
		{
			document.getElementById('ad_type').style.display='';
		}
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
            
            var states = $('states').value ;  
            var category = $('category').value ;
            var subcategory = $('subcategory').value ;
			var title = $('title').value ;
			//var editor1 = $('editor1').value ;
			var editor1 = CKEDITOR.instances['editor1'].getData().replace(/<[^>]*>/gi, '');
			var email = $('email').value ;
			var mobile = $('mobile').value ;   
              
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
			/*else if(trim(filename)  == '' )
            {        
                $('filenameErr').innerHTML='Please Upload Image';
                $('filename').value="";
                $('filename').focus();
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
            }*/
            return true;
        }
      </script>
    <!-- Scripts for validation of form ends here-->
      
      <form method="post" action="<?php echo SITE_ROOT_URL."post-ad.php?edit_adid=".$_GET['edit_adid']."&tablename=$category_tablename"; ?>" onSubmit="javascript:return validateFrm();" enctype="multipart/form-data">
      <div class="post-form" style="margin-top:20px;">
      	<label> <span class="red">*</span>State: </label>
        <select name="states" id="states" class="dropdown" onchange="showarea(this.value)">
        	<option value=""> -- Select State -- </option>
            <?php
            	$states = States::find_all_visible();
				foreach($states as $state)
				{
					echo "<option value='$state->id'";
					if($classified->state_id == $state->id)
					{
						echo "selected='selected'";
					}
					echo "> $state->title </option>";
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
            <?php
				$areas = Areas::find_all_visible_states($classified->state_id);
				foreach($areas as $area)
				{
					echo "<option value='$area->id'";
					if($classified->location_id == $area->id)
					{
						echo "selected='selected'";
					}
					echo "> $area->title </option>";
				}
			?>
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
						echo "<option value='$category->id'";
						if($classified->category_id == $category->id)
						{
							echo "selected='selected'";
						}
						echo "> $category->title </option>";
					}
				?>
			</select>
            </div>
            
            <div class="post-form-headings"> 
            <h4> Please select a relevant Subcategory </h4>            
            <div id="subcat_select">
              <select size="10" name="subcategory" id="subcategory" style="border:none 0px; padding:2px; width:275px;">
              		<?php	
						$subcategories = Subcategory::find_by_categoryid($classified->category_id);
						foreach($subcategories as $subcategory)
						{
							echo "<option value='$subcategory->id'";
							if($classified->subcategory_id == $subcategory->id)
							{
								echo "selected='selected'";
							}
							echo "> $subcategory->title </option>";
						}
					?>
              </select> 
            </div><!--subcat_select-->
            </div><!--post-form-headings-->
            <div class="error" id="categoryErr"></div>
            <div class="error" id="subcategoryErr"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form" id="ad_type">
      	<label> Ad Type: </label>
        <input name="type" id="type" type="radio" value="offer-ad" class="radio" <?php if($classified->ad_type == 'offer-ad') {echo "checked = 'checked'";}?> />
        <p style="margin-right:10px;"> Offer Ad </p>
        
        <input name="type" id="type" type="radio" value="wanted-ad" class="radio" <?php if($classified->ad_type == 'wanted-ad') {echo "checked = 'checked'";}?> />
        <p> Wanted Ad</p>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <span class="red">*</span>Ad Title: </label>
        <input name="title" id="title" type="text" class="textfield" value="<?php echo $classified->title; ?>" />
        <div class="error" id="titleErr"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <span class="red">*</span>Ad Description: </label>
        <div style="float:left; width:650px;">
        	<textarea id="editor1" name="editor1" class="textarea">
				<?php 
					$text = strip_tags($classified->description, "<strong><p><a><img><table><tr><td><h3><h2><ul><li><br>");
					$content = str_replace('\\', '', $text);
					echo $content;
				?>
            </textarea>
        </div>
	    <script type="text/javascript">
        CKEDITOR.replace( 'editor1',
			{
				fontSize_sizes : "30/30%;50/50%;100/100%;120/120%;150/150%;200/200%;300/300%",
				toolbar :
				[
					['-','Undo','Redo'],
					['Find','Replace','-','SelectAll','RemoveFormat'],
					['Link', 'Unlink'],
					['Bold', 'Italic','Underline'],
					['FontSize'],
					['TextColor'],
					['NumberedList','BulletedList','-','Blockquote']
				],		
			}        
        );
        </script>
        <div class="error" id="editor1Err"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <span class="red">*</span>Email Id: </label>
        <input name="email" id="email" type="text" class="textfield" value="<?php echo $classified->email; ?>" />
        <div class="error" id="emailErr"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label>Mobile Number: </label>
        <input name="mobile" id="mobile" type="text" class="textfield" value="<?php echo $classified->mobile; ?>" />
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
        <input name="company" type="text" class="textfield" value="<?php echo $classified->company_name; ?>" />
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> Website/Blog URL: </label>
        <input name="website" type="text" class="textfield" value="<?php echo $classified->website_url; ?>" />
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> Keywords: </label>
        <textarea name="keywords" cols="" rows="" class="textarea" style="width:400px; height:70px;"><?php echo $classified->keywords; ?></textarea>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> &nbsp; </label>
      	<input name="edit" type="submit" value="Post Now" class="submit" style="float:left; margin:0px 5px 0 0px;" />
        <input name="cancel" type="reset" value="Cancel" class="submit" style="float:left; margin-top:0px;" />
        <div class="clear"></div>
      </div><!--post-form-->
      </form>
    </div><!--register-left-->

    <div class="clear"></div>
  </div><!--cms-container-->
  
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