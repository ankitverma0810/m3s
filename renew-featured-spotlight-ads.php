<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(empty($_GET['adid']) || empty($_GET['tablename']) || empty($_GET['type']))
	{
		redirect_to("index.php");
	}
	else
	{
		$tablename = str_replace('-', '_', $_GET['tablename']);
		$classified = Classifieds::find_by_uniqueid($_GET['adid'], $tablename);
		$rate = Rates::find_by_id(1);
		
		if($_GET['type'] == 'featured')
		{
			$ad_type = "Featured Ad";
			$ad_rate = $rate->featured_price;
			$ad_days = $rate->featured_days;
		}
		else
		{
			$ad_type = "Spotlight Ad";
			$ad_rate = $rate->spotlight_price;
			$ad_days = $rate->spotlight_days;
		}
	}
	if(isset($_POST['submit']))
	{
		if(empty($_POST['payment']))
		{
			$session->message("Please select a payment option for the Premium Ad");
			redirect_to('renew-featured-spotlight-ads.php?tablename='.$_GET['tablename'].'&adid='.$_GET['adid'].'&type='.$_GET['type']);
		}
		else
		{
			$check_order = Orders::check_renew_order($_GET['adid']);
			if($check_order->order_status == 2 && $check_order->alert_status == 1)
			{
				$order = Orders::find_by_adid($_GET['adid']);
				$order->ad_price = $ad_rate;
				$order->mode_of_payment = $_POST['payment'];
				$order->order_status = 4; //renew
				$order->alert_status = 2; //confirm sent alert or already maid the payment
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
					redirect_to('renew-featured-spotlight-ads.php?tablename='.$_GET['tablename'].'&adid='.$_GET['adid'].'&type='.$_GET['type']);
				}
			}
			if($check_order->order_status == 3)
			{
				$session->message("Sorry, Your Ad is Expired. <a href='".SITE_ROOT_URL."featured-spotlight-ads.php?adid=".$_GET['adid']."&tablename=".$_GET['tablename']."'> Make Your Ad Premium Again </a> ");
				redirect_to('renew-featured-spotlight-ads.php?tablename='.$_GET['tablename'].'&adid='.$_GET['adid'].'&type='.$_GET['type']);
			}
			if($check_order->order_status == 4)
			{
				$session->message("Sorry, Payment for this Ad is already being Processed.");
				redirect_to('renew-featured-spotlight-ads.php?tablename='.$_GET['tablename'].'&adid='.$_GET['adid'].'&type='.$_GET['type']);
			}
			else
			{
				$session->message("Some error occurs please try again later");
				redirect_to('renew-featured-spotlight-ads.php?tablename='.$_GET['tablename'].'&adid='.$_GET['adid'].'&type='.$_GET['type']);
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
      <h2 style="font-size:16px;"> Renew your Premium Ad now!</h2>
      
      <div class="clear"></div>      
      <div class="border"> </div>
      
      <form action="#" method="post">
      <div class="payment-method">
      	<h4> Get 10 Times more Response to your Ad </h4>
        
        <div class="featured-ad">
          <?php echo "<p> <strong>$ad_type</strong> - Rs $ad_rate for $ad_days days </p>"; ?>
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
            
      <input name="submit" type="submit" class="featured-submit" value="Renew Premium Ad" />
      </form>
    </div><!--register-left-->

    <div class="clear"></div>
  </div><!--cms-container-->
  
  <?php include("footer.php"); ?>
</div><!--wrapper-->
</body>
</html>