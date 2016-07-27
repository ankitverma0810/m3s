<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(!$usersession->is_userlogged_in())
	{
		redirect_to("index.php");
	}
?>
<?php include("site-header.php"); ?>
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
            <h1>Premium Ads</h1>
            
            <?php
				//-------------------------for pagination variables starts here----------------------------------//
				$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
				$per_page = 10;			  
				$total_count = count(Orders::find_by_userid($_SESSION['reguser_id']));
				$pagination = new pagination($page, $per_page, $total_count);
				
				//------------------------searching Premium ads posted by this user----------------------------------//
				$sql = "SELECT * FROM mh_orders";
				$sql .= " WHERE user_id =".$_SESSION['reguser_id'];
				$sql .= " AND order_status IN(2,4)";
				$sql .= " ORDER BY order_modified_date DESC";
				$sql .= " LIMIT {$per_page}";
				$sql .= " OFFSET {$pagination->offset()}";
				$premium_ads = Orders::find_by_sql($sql);
				if($premium_ads)
				{
					foreach($premium_ads as $premium_ad)
					{
						$classified = Classifieds::find_by_uniqueid($premium_ad->ad_unique_id, $premium_ad->tablename);
												
						echo "<table width='100%' cellpadding='8' cellspacing='0' class='account-detail'>";
							echo "<tr bgcolor='#F0F0F0'>";
								echo "<td width='60%'> <strong>Ad Title:</strong> ".cropText($classified->title, 40)." </td>";
								if($premium_ad->ad_type == 'featured')
								{
									$ad_type = 'Featured';
								}
								if($premium_ad->ad_type == 'spotlight')
								{
									$ad_type = 'Spotlight';
								}
								echo "<td width='40%'> <strong>Ad Type:</strong> <span style='color:#FF0000'> $ad_type </span>";								
								echo "</td>";
							echo "</tr>";
							
							echo "<tr>";
								echo "<td> <strong>Start Date:</strong> " .datetime_to_text($premium_ad->ad_start_date). "</td>";
								echo "<td> <strong>End Date:</strong> " .datetime_to_text($premium_ad->ad_expiry_date). "</td>";
							echo "</tr>";
							
							echo "<tr>";
								$category = Category::find_by_id($classified->category_id);								
								echo "<td> <strong>Category:</strong> $category->title </td>";
								
								$subcategory = Subcategory::find_by_id($classified->subcategory_id);								
								echo "<td> <strong>Subcategory:</strong> $subcategory->title </td>";
								
							echo "</tr>";
							
							echo "<tr>";
								echo "<td>";
								$category_tablename = str_replace('_', '-', $category->tablename);
															 
				echo "<a href='".$c_relink->replaceLink('?tablename='.$category_tablename.'&adid='.$classified->unique_id)."'> View your Ad </a> |";
				$count_response = count(Response::find_by_adid($classified->unique_id));
				echo "<a href='response.php'> Response ($count_response) </a>";		
								echo "</td>";
								echo "<td>&nbsp;  </td>";
							echo "</tr>";
						echo "</table>";
				
					}
				}
			?>
            
            <div class="pagination" style="float:right; padding:10px 0px 10px 0px;">
                <ul>
                <?php
                  if($pagination->total_pages() > 1)
                  {		
                    if($pagination->has_previous_ten_page())
                    {
                        echo "<li><a href=\"my-premium-ads.php?page=";
                        echo $pagination->previous_ten_page();
                        echo "\">&laquo; Previous </a></li>";
                    }
                    
                    for($i = max(1, $page - 9); $i <= min($page + 9, $pagination->total_pages()); $i++)
                    {
                        if($i == $page)
                        {
                            echo "<li class='current'> {$i} </li>";
                        }
                        else
                        {
                            echo "<li> <a href=\"my-premium-ads.php?&page={$i}\">{$i}</a> </li>";
                        }
                    }
                    
                    if($pagination->has_next_ten_page())
                    {
                        echo "<li><a href=\"my-premium-ads.php?page=";
                        echo $pagination->next_ten_page();
                        echo "\">Next &raquo; </a> </li>";
                    }
                }
                ?>
                </ul>
                <div class="clear"></div>
            </div><!--pagination-->
            
        </div><!--ad-left-->       
       <div class="clear"></div>
    </div><!--inner-container-->  
  <?php include("footer.php"); ?>
</div><!--wrapper-->
</body>
</html>