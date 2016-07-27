<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(empty($_GET['delete_adid']) || empty($_GET['tablename']))
	{
		$session->message('Some error occurs, please try again!','error');
		redirect_to(SITE_ROOT_URL.'index.php');
	}
	else
	{
		$category_tablename = str_replace('-', '_', $_GET['tablename']);
		$classified = Classifieds::find_by_uniqueid($_GET['delete_adid'], $category_tablename);
		if(!$classified || $classified->ad_status != 1)
		{
			$session->message('We are not able to find the ad which you want, please try again later!','error');
			redirect_to(SITE_ROOT_URL.'index.php');
		}
	}
	if(isset($_POST['submit']))
	{
		$classified->ad_status = 5;
		if($classified->update($category_tablename))
		{
			$session->message('Your Ad has been deleted successfully','success');
			redirect_to(SITE_ROOT_URL.'index.php');
		}
		else
		{
			$session->message('Some error occurs, please try again!','error');
			redirect_to(SITE_ROOT_URL.'delete-free-classified-ads.php');
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
      <h2 style="font-size:14px;"> Basic Ad Details </h2>
      
      <div class="clear"></div>      
      <div class="border"> </div>
      
      <form method="post" action="<?php echo SITE_ROOT_URL; ?>delete-free-classified-ads.php?delete_adid=<?php echo $_GET['delete_adid']?>&tablename=<?php echo $_GET['tablename']?>">
      <div class="post-form" style="margin-top:20px;">
      	<label> <strong>Ad Id</strong> </label>
        <p style="font-size:12px;">
			<?php echo $classified->unique_id; ?>
        </p>
        <div class="error" id="statesErr"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form" style="margin-top:20px;">
      	<label> <strong>State</strong> </label>
        <p style="font-size:12px;">
			<?php 
				$state = States::find_by_id($classified->state_id);
				echo $state->title;
			?>
        </p>
        <div class="error" id="statesErr"></div>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <strong>Locality</strong> </label>
        <p style="font-size:12px;">
			<?php 
				$area = Areas::find_by_id($classified->location_id);
				echo $area->title;
			?>
        </p>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <strong>Category</strong> </label>
        <p style="font-size:12px;">
			<?php 
				$category = Category::find_by_id($classified->category_id);
				echo $category->title;
			?>
        </p>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <strong>Subcategory</strong> </label>
        <p style="font-size:12px;">
			<?php 
				$subcategory = Subcategory::find_by_id($classified->subcategory_id);
				echo $subcategory->title;
			?>
        </p>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form" id="ad_type" style="display:none;">
      	<label> <strong>Ad Type</strong> </label>
        <p style="font-size:12px;">
        <?php
        	echo $classified->ad_type;
		?>
        </p>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <strong>Ad Title</strong> </label>
        <p style="font-size:12px;">
			<?php echo $classified->title; ?>
        </p>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <strong>Ad Description</strong> </label>
        <p style="font-size:12px; width:750px; line-height:18px;">
        <?php 
			$text = strip_tags($classified->description, "<strong><a><img><table><tr><td><h3><h2><ul><li><br>");
			$content = str_replace('\\', '', $text);
			echo $content;
		?>
        </p>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <strong>Email Id</strong> </label>
        <p style="font-size:12px;">
			<?php echo $classified->email; ?>
        </p>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <strong>Mobile Number:</strong> </label>
        <p style="font-size:12px;">
			<?php echo $classified->mobile; ?>
        </p>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <strong>Company Name:</strong> </label>
        <p style="font-size:12px;">
			<?php echo $classified->company_name; ?>
        </p>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <strong>Website/Blog URL:</strong> </label>
        <p style="font-size:12px;">
			<?php echo $classified->website_url; ?>
        </p>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> <strong>Keywords:</strong> </label>
        <p style="font-size:12px; width:750px; line-height:18px;">
			<?php echo $classified->keywords; ?>
        </p>
        <div class="clear"></div>
      </div><!--post-form-->
      
      <div class="post-form">
      	<label> &nbsp; </label>
      	<input name="submit" type="submit" value="Delete Ad" class="submit" style="float:left; margin-top:0px;" onclick="return confirm('Are you sure?');" />
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
