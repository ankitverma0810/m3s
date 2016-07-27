<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
	if(empty($_GET['ad_id']) && empty($_GET['tablename']))
	{
		$session->message("No ad id was provided", "error");
		redirect_to('users.php');
	}
	else
	{
		$classified = Classifieds::find_by_adid($_GET['tablename'], $_GET['ad_id']);
	}
?>
<?php include_layout_template('admin-header.php'); ?>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

<div class="sidebar">
<?php include_layout_template('admin-sidebar.php'); ?>
</div><!--sidebar-->

<div class="content">
  <h2> Ad Manager </h2>
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
	}
  ?>
  <!--code for checking whether any error or success msg exists END here-->
  
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
  	<tr bgcolor="#F4F4F4">
      <td width="21%"> <strong> City:</strong></td>
      <td width="79%">
      	<?php
        	$state = States::find_by_id($classified->state_id);
			echo $state->title;
		?>      </td>
    </tr>
    
    <tr bgcolor="#F7F7F7">
      <td> <strong> Area: </strong> </td>
      <td>
      	<?php
        	$area = Areas::find_by_id($classified->location_id);
			echo $area->title;
		?>
      </td>
    </tr>
    
    <tr bgcolor="#F4F4F4">
      <td> <strong> Category: </strong> </td>
      <td>
      	<?php
        	$category = Category::find_by_id($classified->category_id);
			echo $category->title;
		?>
      </td>
    </tr>
    
    <tr bgcolor="#F7F7F7">
      <td> <strong> SubCategory: </strong> </td>
      <td>
      	<?php
        	$subcategory = Subcategory::find_by_id($classified->subcategory_id);
			echo $subcategory->title;
		?>
      </td>
    </tr>
    
    <tr bgcolor="#F4F4F4">
      <td> <strong> Ad Title: </strong> </td>
      <td><?php echo $classified->title; ?> (<strong>Ad Ref. Code</strong>: <?php echo $classified->unique_id; ?>)</td>
    </tr>
    
    <tr bgcolor="#F7F7F7">
      <td> <strong> Ad Type: </strong> </td>
      <td>
      	<?php echo $classified->ad_type; ?>
      </td>
    </tr>
    
    <tr bgcolor="#F4F4F4" valign="top">
      <td> <strong> Ad Description: </strong> </td>
      <td>
      	<?php 
			$text = strip_tags($classified->description, "<strong><p><a><img><table><tr><td><h3><h2><ul><li><br>");
			$content = str_replace('\\', '', $text);
			echo $content;
		?>
      </td>
    </tr>
    
    <tr bgcolor="#F7F7F7">
      <td> <strong> Email Id: </strong> </td>
      <td> <?php echo $classified->email; ?> </td>
    </tr>
    
    <tr bgcolor="#F4F4F4">
      <td> <strong> Mobile: </strong> </td>
      <td> <?php echo $classified->mobile; ?> </td>
    </tr>
    
    <tr bgcolor="#F7F7F7">
      <td valign="top"> <strong> Image: </strong> </td>
      <td>
      	<?php
      	if(!empty($classified->filename))
        {
        	echo "<img src='../classified-ads/$classified->filename' width='150' height='150' border='1' />";
        }
		else
		{
			echo "&nbsp;";
		} 
	  	?>
      </td>
    </tr>
    
    <tr bgcolor="#F4F4F4">
      <td> <strong> Company Name: </strong> </td>
      <td> <?php echo $classified->company_name; ?> </td>
    </tr>
    
    <tr bgcolor="#F7F7F7">
      <td> <strong> Website/Blog URL: </strong> </td>
      <td> <?php echo $classified->website_url; ?> </td>
    </tr>
    
    <tr bgcolor="#F4F4F4">
      <td> <strong> Keywords: </strong> </td>
      <td> <?php echo $classified->keywords; ?> </td>
    </tr>
    
    <tr bgcolor="#F7F7F7">
      <td> <strong> Added Date: </strong> </td>
      <td> <?php echo datetime_to_text($classified->added_date); ?> </td>
    </tr>
    
    <tr bgcolor="#F4F4F4">
      <td> <strong> Expiry Date: </strong> </td>
      <td> <?php echo datetime_to_text($classified->expiry_date); ?> </td>
    </tr>
    
    <tr bgcolor="#F7F7F7">
      <td> <strong> Ad Status: </strong> </td>
      <td> 
      	<?php
			if($classified->ad_status == 1)
			{
			  echo "Under Review";
			}
			if($classified->ad_status == 2)
			{
			  echo "Approved";
			}
			if($classified->ad_status == 3)
			{
			  echo "Rejected";
			}
			if($classified->ad_status == 4)
			{
			  echo "Expired";
			}
			if($classified->ad_status == 5)
			{
			  echo "Deleted";
			}
		?>
      </td>
    </tr>
    
    <?php
    	if($_GET['type'] == 'featured' || $_GET['type'] == 'spotlight')
		{
			$premium_ad = Orders::find_by_order_id($_GET['orderid']);
			if($premium_ad->order_status == 2)
			{
				echo "<tr bgcolor='#F7F7F7'>";
				  echo "<td> <strong> Premium AD Start Date: </strong> </td>";
				  echo "<td> ".datetime_to_text($premium_ad->ad_start_date)." </td>";
				echo "</tr>";
				
				echo "<tr bgcolor='#F4F4F4'>";
				  echo "<td> <strong> Premium AD End Date: </strong> </td>";
				  echo "<td> ".datetime_to_text($premium_ad->ad_expiry_date)." </td>";
				echo "</tr>";
			}
		}
		if($_GET['type'] == 'featured')
		{
			$premium_ad = Orders::find_by_order_id($_GET['orderid']);
			echo "<tr bgcolor='#F7F7F7'>";
			  echo "<td> <strong> Premium AD Status: </strong> </td>";
			  echo "<td>";
			  if($premium_ad->order_status == 1)
			  {
			  	  echo "Not Featured";
			  }
			  if($premium_ad->order_status == 2)
			  {
			  	  echo "Featured";
			  }
			  if($premium_ad->order_status == 3)
			  {
			  	  echo "Cancelled";
			  }
			  echo "</td>";
			echo "</tr>";
		}
		if($_GET['type'] == 'spotlight')
		{
			$premium_ad = Orders::find_by_order_id($_GET['orderid']);
			echo "<tr bgcolor='#F7F7F7'>";
			  echo "<td> <strong> Premium AD Status: </strong> </td>";
			  echo "<td>";
			  if($premium_ad->order_status == 1)
			  {
			  	  echo "Not Spotlight";
			  }
			  if($premium_ad->order_status == 2)
			  {
			  	  echo "Spotlight";
			  }
			  if($premium_ad->order_status == 3)
			  {
			  	  echo "Cancelled";
			  }
			  echo "</td>";
			echo "</tr>";
		}
	?>
    
    <tr>
      <td colspan="2">
      	<?php
			if($_GET['type'] == 'normal')
			{
				$goback_url = 'listings.php?user_id='.$_GET['user_id'].'&page='.$_GET['page'];
			}
			if($_GET['type'] == 'home')
			{
				$goback_url = 'index.php';
			}
			if($_GET['type'] == 'featured')
			{
				$goback_url = 'featured-ads.php?page='.$_GET['page'];
			}
			if($_GET['type'] == 'spotlight')
			{
				$goback_url = 'spotlight-ads.php?page='.$_GET['page'];
			}
		?>
      	<input name="submit" type="button" class="submit" value="GO BACK" onclick="window.location.href='<?php echo $goback_url; ?>'" />
      </td>
    </tr>
  </table>
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
