<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(empty($_GET['adid']) || empty($_GET['tablename']))
	{
		redirect_to("index.php");
	}
	else
	{
		$tablename = str_replace('-', '_', $_GET['tablename']);
		$classified = Classifieds::find_by_uniqueid($_GET['adid'], $tablename);
		$rate = Rates::find_by_id(1);
	}
	if(isset($_POST['submit']))
	{
		if(empty($_POST['type']))
		{
			$session->message("Please Select the type of Premium Ad");
			redirect_to('featured-spotlight-ads.php?tablename='.$_GET['tablename'].'&adid='.$_GET['adid']);
		}
		if(empty($_POST['payment']))
		{
			$session->message("Please select a payment option for the Premium Ad");
			redirect_to('featured-spotlight-ads.php?tablename='.$_GET['tablename'].'&adid='.$_GET['adid']);
		}
		$check_order = Orders::check_order($_GET['adid']);
		if($check_order)
		{
			if($check_order->order_status == 1 || $check_order->order_status == 2 || $check_order->order_status == 4)
			{
				$session->message("Sorry, the payment for this Ad is currently being processed");
				redirect_to('featured-spotlight-ads.php?tablename='.$_GET['tablename'].'&adid='.$_GET['adid']);
			}
		}
		else
		{
			$order = new Orders();
			$order->order_id = generateUniqueId(8);
			$order->ad_unique_id = $classified->unique_id;
			$order->tablename = $tablename;
			$order->user_id = $classified->user_id;
			$order->ad_type = $_POST['type'];
			if($_POST['type'] == 'featured')
			{
				$order->ad_price = $rate->featured_price;
			}
			else
			{
				$order->ad_price = $rate->spotlight_price;
			}
			$order->mode_of_payment = $_POST['payment'];
			$order->order_status = 1; //not paid
			$order->order_added_date = strftime('%Y-%m-%d %H:%M:%S', time());
			$order->order_modified_date = strftime('%Y-%m-%d %H:%M:%S', time());
			if($order->save())
			{
				if($order->mode_of_payment == 'Online')
				{
					redirect_to('');
				}
				else
				{
					redirect_to('payment-complete.php?tablename='.$_GET['tablename'].'&adid='.$_GET['adid'].'&orderid='.$order->order_id);
				}
			}
			else
			{
				$session->message("Some error occurs please try again later");
				redirect_to('featured-spotlight-ads.php?tablename='.$_GET['tablename'].'&adid='.$_GET['adid']);
			}
		}
	}
?>
<?php include("site-header.php"); ?>
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
      <h2 style="font-size:16px;"> Convert your Ad to a Premium Ad now!</h2>
      
      <div class="clear"></div>      
      <div class="border"> </div>
      
      <form action="featured-spotlight-ads.php?tablename=<?php echo $_GET['tablename']."&adid=".$_GET['adid']?>" method="post">
      <div class="payment-method">
      	<h4> Get 10 Times more Response to your Ad </h4>
        
        <div class="featured-ad">
          <input name="type" type="radio" value="featured" class="radio" />
          <p> <strong>Featured Ad</strong> - Rs <?php echo $rate->featured_price; ?> for <?php echo $rate->featured_days; ?> days </p>
          <div class="clear"></div>
        </div><!--featured-ad-->
        
        <div class="featured-ad">
          <input name="type" type="radio" value="spotlight" class="radio" />
          <p> <strong>Spotlight Ad</strong> - Rs <?php echo $rate->spotlight_price; ?> for <?php echo $rate->featured_days; ?> days </p>
          <div class="clear"></div>
        </div><!--featured-ad-->     
        
        <div class="clear"></div>
        
        <div class="featured-border"> </div> 
        
        <h5>Please select a payment option for the Premium Ad chosen</h5>
        
        <div class="payment-option">
            <input name="payment" type="radio" value="Online" class="radio" />
            <p> Online - Credit / Debit Card / Net Banking / Cash Card </p>
            <div class="clear"></div>
        </div><!--payment-option-->
        
        <div class="payment-option">
            <input name="payment" type="radio" value="Cheque" class="radio" />
            <p> Cheque </p>
            <div class="clear"></div>
        </div><!--payment-option-->
      </div><!--payment-method-->
      
      <table width="100%" cellpadding="10" cellspacing="1" class="table2" style="margin-top:10px; border:solid 1px #CCCCCC">
      	<tr bgcolor="#F2F2F2">
        	<td colspan="2"><strong>Premium Ad Details</strong></td>
        </tr>
        
        <tr>
        	<td width="17%"><strong>Ad ID:</strong></td>
            <td><?php echo $classified->unique_id; ?></td>
        </tr>
        
        <tr>
        	<td width="17%"><strong>Category:</strong></td>
            <td>
            	<?php 
					$category = Category::find_by_id($classified->category_id);
					echo $category->title;
				?>
            </td>
        </tr>
        
        <tr>
        	<td width="17%"><strong>Sub-Category:</strong></td>
            <td>
            	<?php 
					$subcategory = Subcategory::find_by_id($classified->subcategory_id);
					echo $subcategory->title;
				?>
            </td>
        </tr>
        
        <tr>
        	<td width="17%"><strong>Ad Title:</strong></td>
            <td><?php echo str_replace("\\","",$classified->title); ?></td>
        </tr>
        
        <tr valign="top">
        	<td width="17%"><strong>Ad Description:</strong></td>
            <td>
				<?php 
					$text = strip_tags($classified->description, "<span><p><h4><h3><h2><strong><a><img><table><tr><td><ul><li><br>");
					$content = str_replace('\\', '', $text);
					$content = str_replace('&nbsp;', '', $content);
					echo $content;
				?>
            </td>
        </tr>
        
        <tr>
        	<td width="17%"><strong>Location:</strong></td>
            <td>
            	<?php 
					$location = Areas::find_by_id($classified->location_id);
					echo $location->title;
				?>
            </td>
        </tr>
      </table>
            
      <input name="submit" type="submit" class="featured-submit" value="Post Premium Ad" />
      </form>
    </div><!--register-left-->

    <div class="clear"></div>
  </div><!--cms-container-->
  
  <?php include("footer.php"); ?>
</div><!--wrapper-->
</body>
</html>