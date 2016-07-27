<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
	if(empty($_GET['user_id']))
	{
		$session->message("No user id was provided", "error");
		redirect_to('users.php');
	}
	
	//-------------------------for pagination variables starts here----------------------------------//
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	$per_page = 30;
	
	$count = "";
	$categories = Category::find_all_categries();
	foreach($categories as $category)
	{
		$count += count(Classifieds::find_by_user($category->tablename, $_GET['user_id']));
	}			  
	$total_count = $count;
	$pagination = new pagination($page, $per_page, $total_count);
	//------------------------for pagination variables ends here----------------------------------//
	
	if(isset($_POST['approve']))
	{		
		$activate = new Classifieds();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("listings.php?user_id=".$_GET['user_id']);
		}
		else
		{
			$activate->checkbox = $_POST['checkbox'];
			if($activate->update_multiple(2))
			{
				$session->message("Ads Approved", "success");
				redirect_to("listings.php?user_id=".$_GET['user_id']);
			}
		}
	}	
	if(isset($_POST['reject']))
	{		
		$notapprove = new Classifieds();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("listings.php?user_id=".$_GET['user_id']);
		}
		else
		{
			$notapprove->checkbox = $_POST['checkbox'];
			if($notapprove->update_multiple(3))
			{
				$session->message("Ads Rejected", "success");
				redirect_to("listings.php?user_id=".$_GET['user_id']);
			}
		}
	}
	if(isset($_POST['under-review']))
	{		
		$deactivate = new Classifieds();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("listings.php?user_id=".$_GET['user_id']);
		}
		else
		{
			$deactivate->checkbox = $_POST['checkbox'];
			if($deactivate->update_multiple(1))
			{
				$session->message("Ads Under Reviewed", "success");
				redirect_to("listings.php?user_id=".$_GET['user_id']);
			}
		}
	}
	if(isset($_POST['delete']))
	{		
		$delete = new Classifieds();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("listings.php?user_id=".$_GET['user_id']);
		}
		else
		{
			$delete->checkbox = $_POST['checkbox'];
			if($delete->update_multiple(5))
			{
				$session->message("Ads Deleted", "success");
				redirect_to("listings.php?user_id=".$_GET['user_id']);
			}
		}
	}
	if(isset($_POST['search']))
	{
		$classifieds = Classifieds::find_by_search($_GET['user_id'], $_POST['code'], $_POST['status'], $_POST['state'], 
												   $_POST['category'], $_POST['type']);
	}
	else
	{
		$categories = Category::find_all_categries();
		$count = count($categories);
		$num = 0;
		$sql = "";
		foreach($categories as $category)
		{
			$num++;
			$sql .= " SELECT * FROM ". $category->tablename;
			$sql .= " WHERE user_id = {$_GET['user_id']}";
			$sql .= " AND featured_status = 0 AND spotlight_status = 0";
			if($num != $count)
			{
				$sql .= " UNION ALL";
			}
		}
		$sql .= " ORDER BY YEAR(expiry_date) ASC, MONTH(expiry_date) ASC, DAY(expiry_date) DESC, Hour(expiry_date) DESC, 
				  Minute(expiry_date) DESC, Second(expiry_date) DESC";		
		$sql .= " LIMIT {$per_page}";
		$sql .= " OFFSET {$pagination->offset()}";
		$classifieds = Classifieds::find_by_sql($sql);
	}
?>
<?php include_layout_template('admin-header.php'); ?>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<div class="sidebar">
<?php include_layout_template('admin-sidebar.php'); ?>
</div><!--sidebar-->

<div class="content">
  <h2> Listings Manager </h2>
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
  
  <div class="search-container">
    <form action="listings.php?user_id=<?php echo $_GET['user_id']; ?>" method="post">
    <div class="search-box">
      <p> Ad. Ref. Code: </p>
      <input name="code" type="text" style="width:235px;" />
    </div><!--search-box-->
    
    <div class="search-box">
      <p> Status: </p>
      <select name="status" class="dropdown">
        <option value=""> -- Select -- </option>
        <option value="1"> Under Review </option>
        <option value="2"> Approved </option>
        <option value="3"> Rejected </option>
        <option value="4"> Expired </option>
        <option value="5"> Deleted </option>
      </select>
    </div><!--search-box-->
    
    <div class="search-box">
      <p> State: </p>
      <select name="state" class="dropdown">
        <option value=""> -- Select -- </option>
        <?php
        	$states = States::find_all();
			foreach($states as $state)
			{
				echo "<option value='$state->id'> $state->title </option>";
			}
		?>
      </select>
    </div><!--search-box-->
    
    <div class="search-box">
      <p> Category: </p>
      <select name="category" class="dropdown">
        <option value=""> -- Select -- </option>
        <?php
        	$categories = Category::find_all();
			foreach($categories as $category)
			{
				echo "<option value='$category->id'> $category->title </option>";
			}
		?>
      </select>
    </div><!--search-box-->
    
    <div class="search-box">
      <p> Ad Type: </p>
      <select name="type" class="dropdown">
        <option value=""> -- Select -- </option>
        <option value="1"> Offer </option>
        <option value="2"> Wanted </option>
      </select>
    </div><!--search-box-->
    
    <div class="search-box" style="margin:17px 0 0 10px;">
      <input name="search" type="submit" value="SEARCH" class="submit" />
      <input name="reset" type="reset" value="CLEAR" class="submit" />
    </div><!--search-box-->
    
    <div class="clear"></div>
    </form>
  </div><!--search-container-->
  
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
  
  <form action="listings.php?user_id=<?php echo $_GET['user_id']; ?>" method="post">
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
    <tr bgcolor="#F5F5F5">
      <td width="5%"><input name="" type="checkbox" value="" id="selectall" /></td>
      <td width="7%"><strong> S.No. </strong> </td>
      <td width="20%"><strong> Ad Details </strong> </td>
      <td width="15%"><strong> LevelA </strong> </td>
      <td width="9%"><strong> LevelB </strong></td>
      <td width="9%"><strong> Ad Type </strong> </td>
      <td width="14%"><strong> Posted On </strong> </td>
      <td width="13%"><strong> Status </strong> </td>
      <td width="8%"><strong> Actions </strong> </td>
    </tr>
	
    <!---------------------Finding ads from multiple tables by this user starts here----------------------->
    <?php
		if(empty($_GET['page']) || $_GET['page'] == 1)
		{
			$i = 0;
		}
		else
		{
			$i = $_GET['page'] * 30 - 30;
		}
		foreach($classifieds as $classified)
		{			
			  //===============for table background color================//			
			  $color = classified_color($classified->ad_status);
			
			  $i++;
			  $newcategory = Category::find_by_id($classified->category_id); //for tablename to be shown in checkbox value			  	
			  echo "<tr bgcolor='$color'>";
			  echo "<td><input name='checkbox[]' type='checkbox' value='$classified->id%$newcategory->tablename' class='case' /></td>";
			  echo "<td> $i </td>";
			  echo "<td>";
				echo "<strong>Title:</strong> $classified->title <br />";
				echo "(<strong>Ad Ref. Code:</strong> $classified->unique_id)";
			  echo "</td>";
			  
			  echo "<td>";
				  $category = Category::find_by_id($classified->category_id);
				  echo "$category->title";
			  echo "</td>";
			  
			  echo "<td>";
				  $subcategory = Subcategory::find_by_id($classified->subcategory_id);
				  echo "$subcategory->title";
			  echo "</td>";				  
			  
			  echo "<td> $classified->ad_type </td>";			  
			  
			  echo "<td>".datetime_to_text($classified->added_date)."</td>";
			  
			  
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
			  
			  echo "<td><a href='view-ad.php?ad_id=$classified->id&tablename=$category->tablename&user_id={$_GET['user_id']}&page=$page&type=normal'> View </a> </td>";
			  echo "</tr>";
		}
	?>
    <!---------------------Finding ads from multiple tables by this user ends here----------------------->
    
    <tr align="center">
      <td colspan="10">
      	  <input name="approve" type="submit" class="submit" value="Approve" />
          <input name="reject" type="submit" class="submit" value="Reject" />
          <input name="under-review" type="submit" class="submit" value="Under Review" />
          <input name="delete" type="submit" class="submit" value="Delete" onclick="return confirm('Are you sure?');" />
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
			echo "<li><a href=\"listings.php?user_id={$_GET['user_id']}&page=";
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
				echo "<li> <a href=\"listings.php?user_id={$_GET['user_id']}&page={$i}\">{$i}</a> </li>";
			}
		}
		
		if($pagination->has_next_ten_page())
		{
			echo "<li><a href=\"listings.php?user_id={$_GET['user_id']}&page=";
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