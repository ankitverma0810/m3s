	<div class="ad-right">
       <div class="ad-links">
        <h2> Quick Links </h2>
        <ul>
        	<?php
            	$total_response_count = Response::count_all($_SESSION['reguser_id']);
				$total_premium_ads = count(Orders::find_by_userid($_SESSION['reguser_id']));
			?>
            <li><a href="<?php echo SITE_ROOT_URL; ?>my-account.php">My M3h</a></li>
            <li><a href="<?php echo SITE_ROOT_URL; ?>profile-details.php"> Profile Details</a></li>
            <li><a href="<?php echo SITE_ROOT_URL; ?>password-change.php">Change Password</a></li>
            <li><a href="<?php echo SITE_ROOT_URL; ?>response.php">My Responses (<?php echo $total_response_count; ?>)</a></li>
            <li style="border-bottom:none;"> <a href="<?php echo SITE_ROOT_URL; ?>my-premium-ads.php"> Premium Ads (<?php echo $total_premium_ads; ?>) </a> </li>
        </ul>
        </div><!--ad-links-->
   </div><!--ad-right-->