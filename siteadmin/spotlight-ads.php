<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}	
	//for pagination
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	$per_page = 8;
	$total_count = Orders::count_all_spotlight();
	$pagination = new pagination($page, $per_page, $total_count);
	if(isset($_POST['active']))
	{
		$activate = new Orders();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("spotlight-ads.php");
		}
		else
		{
			$activate->checkbox = $_POST['checkbox'];
			if($activate->update_multiple(2, 'spotlight_status'))
			{
				$session->message("Ads Activated", "success");
				redirect_to("spotlight-ads.php");
			}
		}
	}
	if(isset($_POST['not-active']))
	{
		$deactivate = new Orders();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("spotlight-ads.php");
		}
		else
		{
			$deactivate->checkbox = $_POST['checkbox'];
			if($deactivate->update_multiple(1, 'spotlight_status'))
			{
				$session->message("Ad Deactivated", "success");
				redirect_to("spotlight-ads.php");
			}
		}
	}
	if(isset($_POST['renew']))
	{
		$renew = new Orders();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("spotlight-ads.php");
		}
		else
		{			
			$renew->checkbox = $_POST['checkbox'];			
			if($renew->update_multiple(3, 'spotlight_status'))
			{
				$session->message("Ad Renewed", "success");
				redirect_to("spotlight-ads.php");
			}
		}
	}
?>
<?php include_layout_template('admin-header.php'); ?>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<div class="sidebar">
<?php include_layout_template('admin-sidebar.php'); ?>
</div><!--sidebar-->

<div class="content">
  <h2> Spotlight Ads </h2>
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
  
  <script type="text/javascript" src="js/jquery.min.js"></script>  
  <SCRIPT language="javascript">
    $(function(){
    
    // add multiple select / deselect functionality
    $("#selectall").click(function () {
          $('.case').attr('checked', this.checked);
    });
    
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    $(".case").click(function(){
    
        if($(".case").length == $(".case:checked").length) {
            $("#selectall").attr("checked", "checked");
        } else {
            $("#selectall").removeAttr("checked");
        }
    
    });
    });
  </SCRIPT>
  
  <form action="spotlight-ads.php" method="post">  
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
  	<tr bgcolor="#F5F5F5">
      <td width="6%"> <input name="" type="checkbox" value="" id="selectall" /> </td>
      <td width="7%"> <strong> S.No. </strong> </td>
      <td width="30%"> <strong> Ad Details </strong> </td>
      <td width="9%"> <strong> Price </strong> </td>
      <td width="16%"> <strong> Mode of Payment </strong> </td>
      <td width="11%"> <strong> Status </strong> </td>
      <td width="12%"> <strong> Order Date </strong> </td>
      <td width="9%"> <strong> View Ad </strong> </td>
    </tr>
    
    <?php
		if(empty($_GET['page']) || $_GET['page'] == 1)
		{
			$i = 0;
		}
		else
		{
			$i = $_GET['page'] * 8 - 8;
		}
		$sql = "SELECT * FROM mh_orders WHERE ad_type = 'spotlight'";
		$sql .= " ORDER BY order_added_date DESC";
		$sql .= " LIMIT {$per_page}";
		$sql .= " OFFSET {$pagination->offset()}";		
    	$orders = Orders::find_by_sql($sql);
		foreach($orders as $order)
		{
			$i++;
						
			//===============for table background color================//
			$color = classified_color($order->order_status);
			
			echo "<tr bgcolor='$color'>";
			  echo "<td> <input name='checkbox[]' type='checkbox' value='$order->id%$order->tablename%$order->ad_unique_id' class='case' /> </td>";
			  echo "<td> $i </td>";
			  echo "<td>"; 
				echo "<strong>Order Id: </strong> $order->order_id <br />";
				echo "<strong>Ad Ref.Code: </strong> $order->ad_unique_id  <br />";
				$user = Register::find_by_id($order->user_id);
				echo "<strong>User Email: </strong> $user->email";
			  echo "</td>";
			  echo "<td> Rs.$order->ad_price </td>";
			  echo "<td> $order->mode_of_payment </td>";
			  
			  echo "<td>";
			  	if($order->order_status == 1)
				{
					echo "Not Spotlight";
				}
				if($order->order_status == 2)
				{
					echo "Spotlight";
				}
			  echo "</td>";
			  
			  echo "<td> ".datetime_to_text($order->order_added_date)." </td>";
			  
			  $classified = Classifieds::find_by_uniqueid($order->ad_unique_id, $order->tablename); //finding ad_id for view link
			  echo "<td> <a href='view-ad.php?ad_id=$classified->id&tablename=$order->tablename&user_id=$order->user_id&orderid=$order->order_id&page=$page&type=spotlight'> view </a></td>";
			echo "</tr>";
		}
	?>
    
    <tr>
    	<td colspan="8">
        	<input name="active" type="submit" class="submit" value="Spotlight" />
            <input name="not-active" type="submit" class="submit" value="Not Spotlight" />
            <input name="renew" type="submit" class="submit" value="Renew" />
        </td>
    </tr>
  </table>
  </form>
  
  <div class="pagination">
  	<ul>
	  <?php
        if($pagination->total_pages() > 1)
        {		
            if($pagination->has_previous_ten_page())
            {
                echo "<li><a href=\"spotlight-ads.php?page=";
                echo $pagination->previous_ten_page();
                echo "\">&laquo; Previous </a></li>";
            }
            
            for($i = max(1, $page - 9); $i <= min($page + 9, $pagination->total_pages()); $i++)
            //for($i=1; $i <= $pagination->total_pages(); $i++)
            {
                if($i == $page)
                {
                    echo "<li class='current'> {$i} </li>";
                }
                else
                {
                    echo "<li> <a href=\"spotlight-ads.php?page={$i}\">{$i}</a> </li>";
                }
            }
            
            if($pagination->has_next_ten_page())
            {
                echo "<li><a href=\"spotlight-ads.php?page=";
                echo $pagination->next_ten_page();
                echo "\">Next &raquo; </a> </li>";
            }
        }
      ?>
  </ul>
  <div class="clear"></div>
  </div><!--pagination-->
  
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
