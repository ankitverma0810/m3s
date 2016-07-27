<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(empty($_GET['tablename']) || empty($_GET['adid']) || empty($_GET['orderid']))
	{
		redirect_to("index.php");
	}
	else
	{
		$tablename = str_replace('-', '_', $_GET['tablename']);
		$classified = Classifieds::find_by_uniqueid($_GET['adid'], $tablename);
		$order = Orders::find_by_order_id($_GET['orderid']);
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
      <h2 style="font-size:14px;"> Thank you for Posting a Premium Ad and opting to pay by Cheque </h2>
      
      <div class="clear"></div>
      <div class="border"></div>
      
      <div class="payment-complete">
        <p> Please follow instructions below carefully (or in the mail sent to you) to complete payment successfully and get your Ad live on site.</p>
        <ul>
            <li> Please make a crossed account payee cheque in favour of M3S India Pvt. Ltd – a/c no 000405032195. </li>
            <li>Please mention your ORDER ID behind the cheque.</li>
            <li>You may either courier the cheque to our office at
            M3S India Pvt Ltd, 1st Floor, Raghuvanshi Mansion, Senapati Bapat Marg, Lower Parel, Mumbai - 400 013
            OR drop it at the nearest ICICI ATM.</li>
            <li>Please e-mail us to cheques@M3S.com with the following details : Your name, mobile number, cheque number, your name / your company’s name and amount as appearing on the cheque, your Order ID, bank name and branch name once you courier or drop the cheque. This is very important and required by us to identify your ad without which we may not be able to post your ad as a paid ad on the site.</li>
            <li>Please note that we do not accept "Post Dated Cheques".</li>
            <li>Please note that your ad will go live on the site only post clearance of the cheque which may take upto 4 working days. We will inform you as soon as your ad goes live on the site.</li>
        </ul>
      </div><!--payment-complete-->
      
      <table width="100%" cellpadding="10" cellspacing="1" class="table2" style="margin-top:10px; border:solid 1px #CCCCCC">
      	<tr bgcolor="#F2F2F2">
        	<td colspan="2"><strong>Order Details</strong></td>
        </tr>
        
        <tr>
        	<td width="17%"><strong>Order ID:</strong></td>
            <td><?php echo $order->order_id; ?></td>
        </tr>
        
        <tr>
        	<td width="17%"><strong>Payment Option:</strong></td>
            <td><?php echo $order->mode_of_payment; ?></td>
        </tr>
        
        <tr>
        	<td width="17%"><strong>Listing Fees:</strong></td>
            <td><?php echo "Rs. ".$order->ad_price;	?></td>
        </tr>
      </table>
      
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
            <td><?php echo $classified->title; ?></td>
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
    </div><!--register-left-->

    <div class="clear"></div>
  </div><!--cms-container-->
  
  <?php include("footer.php"); ?>
</div><!--wrapper-->
</body>
</html>