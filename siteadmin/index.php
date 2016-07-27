<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
	if(isset($_POST['approve']))
	{		
		$activate = new Classifieds();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("index.php");
		}
		else
		{
			$activate->checkbox = $_POST['checkbox'];
			if($activate->update_multiple(2))
			{
				$session->message("Ads Approved", "success");
				redirect_to("index.php");
			}
		}
	}
	if(isset($_POST['reject']))
	{		
		$notapprove = new Classifieds();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("index.php");
		}
		else
		{
			$notapprove->checkbox = $_POST['checkbox'];
			if($notapprove->update_multiple(3))
			{
				$session->message("Ads Rejected", "success");
				redirect_to("index.php");
			}
		}
	}
	if(isset($_POST['under-review']))
	{		
		$deactivate = new Classifieds();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("index.php");
		}
		else
		{
			$deactivate->checkbox = $_POST['checkbox'];
			if($deactivate->update_multiple(1))
			{
				$session->message("Ads Under Reviewed", "success");
				redirect_to("index.php");
			}
		}
	}
	if(isset($_POST['delete']))
	{		
		$delete = new Classifieds();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("index.php");
		}
		else
		{
			$delete->checkbox = $_POST['checkbox'];
			if($delete->update_multiple(5))
			{
				$session->message("Ads Deleted", "success");
				redirect_to("index.php");
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
  
  <h2> Recently Posted Ads </h2>
  
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
  
  <form action="index.php" method="post">
  <table cellpadding="0" cellspacing="1" class="table" width="100%">
  	  <tr bgcolor="#F8F8F8">
          <td width="5%"><input name="" type="checkbox" value="" id="selectall" /></td>
          <td width="18%"><strong>Ad.Details</strong></td>
          <td width="20%"><strong>User Details</strong></td>
          <td width="15%"><strong>Levels</strong></td>
          <td width="16%"><strong>Posted On</strong></td>
          <td width="13%"><strong>Status</strong></td>
          <td width="11%"><strong>View Ad</strong></td>
    </tr>
      
      <!----------------Recently posted ads on m3s.in----------------------->
      <?php
		$categories = Category::find_all_categries();
		$count = count($categories);
		$num = 0;
		$sql = "";
		foreach($categories as $category)
		{
			$num++;
			$sql .= " SELECT * FROM ". $category->tablename;
			$sql .= " WHERE featured_status = 0 AND spotlight_status = 0";
			if($num != $count)
			{
				$sql .= " UNION ALL";
			}
		}
		$sql .= " ORDER BY YEAR(expiry_date) ASC, MONTH(expiry_date) ASC, DAY(expiry_date) DESC, Hour(expiry_date) DESC, 
			      Minute(expiry_date) DESC, Second(expiry_date) DESC";		
		$sql .= " LIMIT 30";
		$classifieds = Classifieds::find_by_sql($sql);
		
		foreach($classifieds as $classified)
		{
			//===============for table background color================//
			$color = classified_color($classified->ad_status);
		
			$newcategory = Category::find_by_id($classified->category_id); //for tablename to be shown in checkbox value
			echo "<tr bgcolor='$color'>";
			  echo "<td><input name='checkbox[]' type='checkbox' value='$classified->id%$newcategory->tablename' class='case' /></td>";
			  echo "<td>";
				echo "<strong>Title:</strong> $classified->title <br />";
				echo "(<strong>Ad Ref. Code:</strong> $classified->unique_id)";
			  echo "</td>";
			  
			  echo "<td>";
				echo "<strong>Type: </strong>";
					if($classified->user_id == 1)
					{
						echo "Not Registered";
					}
					else
					{
						echo "Registered";
					}
				echo "<br />";
				echo "<strong>Email: </strong> $classified->email";
			  echo "</td>";
			  
			  echo "<td>";
			  	$category = Category::find_by_id($classified->category_id);
				echo "<strong>Category:</strong> ".$category->title ."<br />";
				
				$subcategory = Subcategory::find_by_id($classified->subcategory_id);
				echo "<strong>Subcategory:</strong> ".$subcategory->title;
			  echo "</td>";
			  
			  echo "<td>";
			  	echo datetime_to_text($classified->added_date);
			  echo "</td>";
			  
			  echo "<td>";			  
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
			  echo "</td>";
			  
			  echo "<td><a href='view-ad.php?ad_id=$classified->id&tablename=$category->tablename&user_id=$classified->user_id&page=1&type=home'> View </a> </td>";
		    echo "</tr>";
		}
	  ?>
      
      <tr align="center">
          <td colspan="7">
              <input name="approve" type="submit" class="submit" value="Approve" />
              <input name="reject" type="submit" class="submit" value="Reject" />
              <input name="under-review" type="submit" class="submit" value="Under Review" />
              <input name="delete" type="submit" class="submit" value="Delete" onclick="return confirm('Are you sure?');" />
          </td>
     </tr>
  </table>
  </form>
  
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
