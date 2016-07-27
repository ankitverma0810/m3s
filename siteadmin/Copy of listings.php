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
	$per_page = 8;
	
	$count = "";
	$categories = Category::find_all_categries();
	foreach($categories as $category)
	{
		$count += count(Classifieds::find_by_user($category->tablename, $_GET['user_id']));
	}			  
	$total_count = $count;
	$pagination = new pagination($page, $per_page, $total_count);
	//------------------------for pagination variables ends here----------------------------------//
	
	if(isset($_POST['activate']))
	{		
		$activate = new Classifieds();
		$activate->checkbox = $_POST['checkbox'];
		if (!$activate->checkbox)
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("listings.php?user_id=".$_GET['user_id']);
		}
		$activate->update_tablename = $_POST['update_tablename'];		
		$activate->combine_array[] = array_combine($activate->checkbox, $activate->update_tablename);	
		print_r($activate->combine_array)."<br />";
		die;
		if($activate->update_multiple(1))
		{
			$session->message("Ad Activated", "success");
			redirect_to("listings.php?user_id=".$_GET['user_id']);
		}
	}	
	if(isset($_POST['deactivate']))
	{		
		$deactivate = new Classifieds();
		$deactivate->checkbox = $_POST['checkbox'];
		if (!$deactivate->checkbox)
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("listings.php?user_id=".$_GET['user_id']);
		}
		$deactivate->update_tablename = $_POST['update_tablename'];		
		$deactivate->combine_array = array_combine($deactivate->checkbox, $deactivate->update_tablename);		
		if($deactivate->update_multiple(0))
		{
			$session->message("Ad De-activated", "success");
			redirect_to("listings.php?user_id=".$_GET['user_id']);
		}
	}
	if(isset($_POST['notapprove']))
	{		
		$notapprove = new Classifieds();
		$notapprove->checkbox = $_POST['checkbox'];
		if (!$notapprove->checkbox)
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("listings.php?user_id=".$_GET['user_id']);
		}
		$notapprove->update_tablename = $_POST['update_tablename'];		
		$notapprove->combine_array = array_combine($notapprove->checkbox, $notapprove->update_tablename);		
		if($notapprove->update_multiple(2))
		{
			$session->message("Ad Not Approved", "success");
			redirect_to("listings.php?user_id=".$_GET['user_id']);
		}
	}
	if(isset($_POST['delete']))
	{
		$delete_ad = new Classifieds();
		$delete_ad->checkbox = $_POST['checkbox'];
		if (!$delete_ad->checkbox)
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("listings.php?user_id=".$_GET['user_id']);
		}
		$delete_ad->update_tablename = $_POST['update_tablename'];		
		$delete_ad->combine_array = array_combine($delete_ad->checkbox, $delete_ad->update_tablename);		
		if($delete_ad->delete_multiple())
		{
			$session->message("Ad deleted Successfully", "success");
			redirect_to("listings.php?user_id=".$_GET['user_id']);
		}
	}
	if(isset($_POST['search']))
	{
		$classifieds = Classifieds::find_by_search($_GET['user_id'], $_POST['code'], $_POST['users'], $_POST['status'], $_POST['state'], 
												   $_POST['category'], $_POST['type']);
	}
	else
	{
		$categories = Category::find_all_categries();
		$count = count($categories);
		$num = 0;
		foreach($categories as $category)
		{
			$num++;
			$sql .= " SELECT * FROM ". $category->tablename;
			$sql .= " WHERE user_id = {$_GET['user_id']}";
			if($num != $count)
			{
				$sql .= " UNION ALL";
			}
		}		
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
      <p> Users: </p>
      <select name="users" class="dropdown">
        <option value=""> -- Select -- </option>
        <?php
        	$users = Register::find_all_users();
			foreach($users as $user)
			{
				echo "<option value='$user->id'> $user->email </option>";
			}
		?>
      </select>
    </div><!--search-box-->
    
    <div class="search-box">
      <p> Status: </p>
      <select name="status" class="dropdown">
        <option value="0, 1, 2, 3"> -- Select -- </option>
        <option value="0"> Not Active </option>
         <option value="1"> Active </option>
        <option value="2"> Not Approved </option>
        <option value="3"> Expired </option>
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
    
    <div class="search-box" style="margin:10px 0 0 280px;">
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
			$i = $_GET['page'] * 8 - 8;
		}
		foreach($classifieds as $classified)
		{
				  $i++;			  	
				  echo "<tr bgcolor='#F5F5F5'>";
				  echo "<td><input name='checkbox[]' type='checkbox' value='$classified->id' class='case' id='group".$i."' onclick='toggleGroups(this);' /></td>";
				  echo "<td> $i </td>";
				  echo "<td>";
					echo "<strong>Title:</strong> $classified->title <br />";
					echo "(<strong>Ad Ref. Code:</strong> $classified->unique_id)";
				  echo "</td>";
				  
				  echo "<td>";
				  	  $category = Category::find_by_id($classified->category_id);
					  echo "$category->title";
					  echo "<input name='update_tablename[]' type='checkbox' value='$category->tablename' class='case' id='name".$i."' style='display:none;' />";
				  echo "</td>";
				  
				  echo "<td>";
					  $subcategory = Subcategory::find_by_id($classified->subcategory_id);
					  echo "$subcategory->title";
				  echo "</td>";				  
				  
				  echo "<td>";
				  if($classified->ad_type == 1)
				  {
					echo "offer";
				  }
				  else
				  {
					echo "wanted";
				  }
				  echo "</td>";
				  
				  
				  echo "<td>".datetime_to_text($classified->added_date)."</td>";
				  
				  
				  echo "<td>";
				  if($classified->ad_status == 0)
				  {
					echo "Not Active";
				  }
				  if($classified->ad_status == 1)
				  {
					echo "Active";
				  }
				  if($classified->ad_status == 2)
				  {
					echo "Not Approved";
				  }
				  if($classified->ad_status == 3)
				  {
					echo "Expired";
				  }
				  echo "</td>";				  
				  
				  echo "<td><a href='view-ad.php?ad_id=$classified->id&tablename=$category->tablename&user_id={$_GET['user_id']}'> View </a> </td>";
				echo "</tr>";
			}
		$count = $i; //for group and name used in javascript checkbox below
	?>
    <!---------------------Finding ads from multiple tables by this user ends here-----------------------> 
     
    
    <!---------------------Used to checked checkbox automatically, hidden in the same loop starts here-----------------------> 
    <script type='text/javascript'>
		window.groups = {		
		<?php
		for ($a = 1; $a <= $count; $a++)
		{
			echo "group".$a.": ['name".$a."'],";
		}
		?>
		group: ['name']
		};	
		window.toggleGroups = function(checkBox) {
		var members = groups[checkBox.id];
		for (var index = 0; index < members.length; index++) {
		var memberId = members[index];
		document.getElementById(memberId).checked = checkBox.checked;
		}
		};
	</script>
    <!---------------------Used to checked checkbox automatically, hidden in the same loop ends here-----------------------> 
    
    <tr align="center">
      <td colspan="10">
      	  <input name="activate" type="submit" class="submit" value="Activate" />
          <input name="deactivate" type="submit" class="submit" value="De-Activate" />
          <input name="notapprove" type="submit" class="submit" value="Not Approve" />
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