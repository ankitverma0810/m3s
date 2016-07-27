<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(!$usersession->is_userlogged_in())
	{
		redirect_to("index.php");
	}
	//=======================if user is deleting his ad===========================//
	if(isset($_GET['deleteid']) && isset($_GET['tablename']))
	{
		$delete_classified = Classifieds::find_by_uniqueid($_GET['deleteid'], $_GET['tablename']);
		if($delete_classified)
		{
			$delete_classified->ad_status = 5;
			if($delete_classified->update($_GET['tablename']))
			{
				$session->message('Ad has been deleted successfully','success');
				redirect_to('my-account.php');
			}
			else
			{
				$session->message('Some error occurs please try again later!!','error');
				redirect_to('my-account.php');
			}
		}
		else
		{
			$session->message('Some error occurs please try again later!!','error');
			redirect_to('my-account.php');
		}
	}
?>
<?php include("site-header.php"); ?>
<body>
<div class="wrapper">
    <?php include("header.php"); ?>
    
    <?php
	//===============code for checking whether any error or success message exists================//
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
            <h1>My  M3h</h1>
            
            <script type="text/javascript">
				function queryAction(id) {
					
					var confirmmessage = "Are you sure you want to continue?";
					var goifokay = "my-account.php?deleteid="+id;
					var cancelmessage = "Action Cancelled";
					
					if (confirm(confirmmessage)) {
					
					window.location = goifokay;
					
					} else {
					
					//alert(cancelmessage);
					return false;
					
					}
					
					}
			</script>
            
            <?php
				//-------------------------for pagination variables starts here----------------------------------//
				$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
				$per_page = 10;
				
				$count = "";
				$categories = Category::find_all_categries();
				foreach($categories as $category)
				{
					$count += count(Classifieds::find_by_user($category->tablename, $_SESSION['reguser_id']));
				}			  
				$total_count = $count;
				$pagination = new pagination($page, $per_page, $total_count);
				
				//------------------------searching classified ads posted by this user in all tables----------------------------------//	
            	$categories = Category::find_all_categries();
				$count = count($categories);
				$num = 0;
				$sql = "";
				foreach($categories as $category)
				{
					$num++;
					$sql .= " SELECT * FROM ". $category->tablename;
					$sql .= " WHERE user_id = {$_SESSION['reguser_id']}";
					$sql .= " AND featured_status = 0 AND spotlight_status = 0";
					if($num != $count)
					{
						$sql .= " UNION ALL";
					}
				}
				$sql .= " ORDER BY YEAR(expiry_date) DESC, MONTH(expiry_date) DESC, DAY(expiry_date) DESC, Hour(expiry_date) DESC, 
					  	  Minute(expiry_date) DESC, Second(expiry_date) DESC";		
				$sql .= " LIMIT {$per_page}";
				$sql .= " OFFSET {$pagination->offset()}";
				$classifieds = Classifieds::find_by_sql($sql);
    			
				foreach($classifieds as $classified)
				{
					echo "<table width='100%' cellpadding='8' cellspacing='0' class='account-detail'>";
						echo "<tr bgcolor='#F0F0F0'>";
							echo "<td width='60%'> <strong>Ad Title:</strong> ".cropText($classified->title, 40)." </td>";
							echo "<td width='40%'> <strong>Ad Status:</strong> ";
							if($classified->ad_status == 1)
							{
								echo "<span style='color:#FF0000'>Under Review</span>";
							}
							if($classified->ad_status == 2)
							{
								echo "<span style='color:#FF0000'>Approved</span>";
							}
							if($classified->ad_status == 3)
							{
								echo "<span style='color:#FF0000'>Rejected</span>";
							}
							if($classified->ad_status == 4)
							{
								echo "<span style='color:#FF0000'>Expired</span>";
							}
							if($classified->ad_status == 5)
							{
								echo "<span style='color:#FF0000'>Deleted</span>";
							}
							echo "</td>";
						echo "</tr>";
						
						echo "<tr>";
							echo "<td> <strong>Ad ID:</strong> $classified->unique_id </td>";
							echo "<td> <strong>Date of Posting:</strong> " .datetime_to_text($classified->added_date). "</td>";
						echo "</tr>";
						
						echo "<tr>";
							$category = Category::find_by_id($classified->category_id);
							$category_tablename = str_replace('_', '-', $category->tablename);
							
							echo "<td> <strong>Category:</strong> $category->title </td>";
							echo "<td> <strong>Date of Expiry:</strong> " .datetime_to_text($classified->expiry_date). "</td>";
						echo "</tr>";
						
						echo "<tr>";
							echo "<td>";
							 
			echo "<a href='".$c_relink->replaceLink('?tablename='.$category_tablename.'&adid='.$classified->unique_id)."'> View your Ad </a> ";
			if($classified->ad_status != 5)
			{
				echo " | <a href='".$c_relink->replaceLink('?tablename='.$category_tablename.'&edit_adid='.$classified->unique_id)."'> Edit </a> | ";					
				$count_response = count(Response::find_by_adid($classified->unique_id));
				echo "<a href='response.php'> Response ($count_response) </a> | ";
				echo "<a href='#' onClick=queryAction('".$classified->unique_id."&tablename=$category->tablename')> Delete </a> ";
			}			
							echo "</td>";
							echo "<td>&nbsp;  </td>";
						echo "</tr>";
					echo "</table>";
				}
			?>
            
            <span style="color:#FF0000"> </span>
            <div class="pagination" style="float:right; padding:10px 0px 10px 0px;">
                <ul>
                <?php
                  if($pagination->total_pages() > 1)
                  {		
                    if($pagination->has_previous_ten_page())
                    {
                        echo "<li><a href=\"my-account.php?page=";
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
                            echo "<li> <a href=\"my-account.php?&page={$i}\">{$i}</a> </li>";
                        }
                    }
                    
                    if($pagination->has_next_ten_page())
                    {
                        echo "<li><a href=\"my-account.php?page=";
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