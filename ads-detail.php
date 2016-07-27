<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(!$usersession->is_userlogged_in())
	{
		redirect_to("index.php");
	}
	if(empty($_GET['adid']) || empty($_GET['tablename']))
	{
		$session->message('Some error occurs, please try again!','error');
		redirect_to('my-account.php');
	}
	else
	{
		$category_tablename = str_replace('-', '_', $_GET['tablename']);
		$classified = Classifieds::find_by_uniqueid($_GET['adid'], $category_tablename);
		if(!$classified)
		{
			$session->message('We are not able to find the ad which you want, please try again later!','error');
			redirect_to('my-account.php');
		}
	}
?>
<?php include("site-header.php"); ?>
<body>
<div class="wrapper">
  <?php include("header.php"); ?>
  
  <div class="welcome-text">
    <span style="color:#1b387d; font-size:15px;">Welcome to M3S.in</span>, a free classified ads portal of India for all categories as Education, Automobiles, Travels, Real Estate etc.
  </div><!--welcome-text--> 
  
  <div class="clear"></div>
  
    <div class="inner-container">
    	<?php include("inner-sidebar.php"); ?>
        <div class="ad-left">
          <h1>Ad Details</h1>          
          <table width="100%" cellpadding="0" cellspacing="0" class="ad-details">
          	  <tr>
              	  <td width="148">  <strong>Email Id:</strong> </td>
                  <td width="512"> <?php echo $classified->email; ?> </td>
              </tr>
              
              <tr bgcolor="#F3F3F3">
              	  <td> <strong>Category:</strong> </td>
                  <td> 
						<?php
							$category = Category::find_by_id($classified->category_id);
							echo "$category->title";
                        ?> 
                 </td>
              </tr>
              
              <tr>
              	  <td> <strong>Sub-Category:</strong> </td>
                  <td> 
						<?php
							$subcategory = Subcategory::find_by_id($classified->subcategory_id);
							echo "$subcategory->title";
                        ?>
                 </td>
              </tr>
              
              <tr bgcolor="#F3F3F3">
              	  <td> <strong>Ad Title:</strong> </td>
                  <td> <?php echo $classified->title; ?> </td>
              </tr>
              
              <tr valign="top">
              	  <td> <strong>Ad Description:</strong> </td>
                  <td> 
						<?php
							$text = strip_tags($classified->description, "<strong><p><a><img><table><tr><td><h3><h2><ul><li><br>");
							$content = str_replace('\\', '', $text);
							echo $content;
                        ?>
                  </td>
              </tr>
              
              <tr bgcolor="#F3F3F3">
              	  <td> <strong>Location:</strong> </td>
                  <td>
						<?php
							$area = Areas::find_by_id($classified->location_id);
							echo "$area->title";
                        ?>
                  </td>
              </tr>
              
              <tr>
              	  <td> <strong>Mobile Number:</strong> </td>
                  <td> <?php echo $classified->mobile; ?> </td>
              </tr>
              
              <tr bgcolor="#F3F3F3">
              	  <td> <strong>Company Name:</strong> </td>
                  <td> <?php echo $classified->company_name; ?> </td>
              </tr>
              
              <tr>
              	  <td> <strong>Website/Blog URL:</strong> </td>
                  <td> <?php echo $classified->website_url; ?> </td>
              </tr>
              
              <tr bgcolor="#F3F3F3">
              	  <td> <strong>Keywords:</strong> </td>
                  <td> <?php echo $classified->keywords; ?> </td>
              </tr>
          </table>
       </div><!--ad-left-->       
       <div class="clear"></div>
    </div><!--inner-container-->   
  
  <?php include("footer.php"); ?>
</div><!--wrapper-->
</body>
</html>