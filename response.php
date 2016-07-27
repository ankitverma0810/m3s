<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	if(!$usersession->is_userlogged_in())
	{
		redirect_to("index.php");
	}
	//for pagination
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	$per_page = 3;
	$total_count = Response::count_all($_SESSION['reguser_id']);
	$pagination = new pagination($page, $per_page, $total_count);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>M3H</title>
<link href="css/stylesheet.css" rel="stylesheet" type="text/css" />
</head>

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
          <h1>My  Responses</h1>
          <?php	
			  $sql = "SELECT * FROM mh_responses WHERE reg_userid = {$_SESSION['reguser_id']}";
			  $sql .= " LIMIT {$per_page}";
			  $sql .= " OFFSET {$pagination->offset()}";		
			  $responses = Response::find_by_sql($sql);		
          	  foreach($responses as $response)
			  {
			  	  echo "<table width='100%' cellpadding='8' cellspacing='0' class='account-detail'>";
					echo "<tr bgcolor='#F0F0F0'>";
						echo "<td colspan='2'>";
							echo "<img src='images/email.png' width='17' height='17' />";
							echo "<img src='images/phone.png' width='17' height='17' />";
							echo "<strong>$response->classified_id - $response->classified_title </strong> </td>";
					echo "</tr>";
					
					echo "<tr>";
						echo "<td width='122'> <strong>Response Date: </strong></td>";
						echo "<td width='534'>". datetime_to_text($response->added_date) ."</td>";
					echo "</tr>";
					
					echo "<tr>";
						echo "<td> <strong>Email Id: </strong></td>";
					    echo "<td> $response->email </td>";
					echo "</tr>";
					
					echo "<tr>";
						echo "<td> <strong>Mobile No: </strong></td>";
					    echo "<td> $response->mobile </td>";
					echo "</tr>";
					
					echo "<tr valign='top'>";
						echo "<td> <strong>Sender Message:</strong> </td>";
					    echo "<td> $response->message </td>";
					echo "</tr>";	
				  echo "</table>";
			  }
		  ?>                      
        </div><!--ad-left-->
        <div class="clear"></div>
        
        <div class="pagination" style="float:right; margin-top:15px;">
            <ul>
              <?php
                if($pagination->total_pages() > 1)
                {		
                    if($pagination->has_previous_ten_page())
                    {
                        echo "<li><a href=\"response.php?page=";
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
                            echo "<li> <a href=\"response.php?page={$i}\">{$i}</a> </li>";
                        }
                    }
                    
                    if($pagination->has_next_ten_page())
                    {
                        echo "<li><a href=\"response.php?page=";
                        echo $pagination->next_ten_page();
                        echo "\">Next &raquo; </a> </li>";
                    }
                }
              ?>
            </ul>
            <div class="clear"></div>
       </div><!--pagination-->
       
  	   <div class="clear"></div>
    </div><!--inner-container-->
      
  <?php include("footer.php"); ?>
</div><!--wrapper-->
</body>
</html>
