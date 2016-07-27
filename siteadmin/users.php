<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
	//for pagination
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	$per_page = 30;
	if(isset($_POST['search']))
	{
		$getemail = !empty($_POST['email']) ? $_POST['email'] : "";
		$getstatus = $_POST['status'];
	}
	else
	{
		$getemail = !empty($_GET['email']) ? $_GET['email'] : "";
		$getstatus = !empty($_GET['status']) ? $_GET['status'] : "";
	}
	$total_count = count(Register::find_by_search($getemail, $getstatus));
	$pagination = new pagination($page, $per_page, $total_count);
	
	if(isset($_POST['activate']))
	{
		$activate = new Register();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("users.php");
		}
		else
		{
			$activate->checkbox = $_POST['checkbox'];
			if($activate->update_multiple(2))
			{
				$session->message("User Activated", "success");
				redirect_to("users.php");
			}
		}
	}
	if(isset($_POST['deactivate']))
	{
		$deactivate = new Register();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("users.php");
		}		
		else
		{
			$deactivate->checkbox = $_POST['checkbox'];
			if($deactivate->update_multiple(1))
			{
				$session->message("User De-activated", "success");
				redirect_to("users.php");
			}
		}
	}
	if(isset($_POST['delete']))
	{
		$delete = new Register();
		if(empty($_POST['checkbox']))
		{
			$session->message("ERROR - Nothing Selected", "error");
			redirect_to("users.php");
		}		
		else
		{
			$delete->checkbox = $_POST['checkbox'];
			if($delete->update_multiple(4))
			{
				$session->message("User Deleted", "success");
				redirect_to("users.php");
			}
		}
	}
	else
	{
		$sql = "SELECT * FROM mh_reg_users";
		if(!empty($getemail) && !empty($getstatus))
		{
			$sql .= " WHERE email LIKE '%".$getemail."%' AND status IN($getstatus)";
		}
		elseif(!empty($getemail))
		{
			$sql .= " WHERE email LIKE '%".$getemail."%'";
		}
		elseif(!empty($getstatus))
		{
			$sql .= " WHERE user_status IN(".$getstatus.")";
		}
		$sql .= " ORDER BY YEAR(added_date) DESC, MONTH(added_date) DESC, DAY(added_date) DESC, Hour(added_date) DESC, 
				  Minute(added_date) DESC, Second(added_date) DESC";
		$sql .= " LIMIT {$per_page}";
		$sql .= " OFFSET {$pagination->offset()}";		
		$users = Register::find_by_sql($sql);
	}
?>
<?php include_layout_template('admin-header.php'); ?>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<div class="sidebar">
<?php include_layout_template('admin-sidebar.php'); ?>
</div><!--sidebar-->

<div class="content">
  <h2> Users Manager </h2>
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
    <form action="users.php" method="post">    
    <div class="search-box">
      <p> Email: </p>
      <input name="email" type="text" class="textfield" style="width:230px;" />
    </div><!--search-box-->
    
    <div class="search-box">
      <p> Status: </p>
      <select name="status" class="dropdown">
        <option value=""> -- Select -- </option>
        <option value="1"> Not Active </option>
        <option value="2"> Active </option>
        <option value="3"> Not Confirmed </option>
        <option value="4"> Deleted </option>
      </select>
    </div><!--search-box-->
    
    <div class="search-box" style="margin:16px 0 0 0px;">
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
  
  <form action="users.php" method="post">   
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
  	<tr bgcolor="#F5F5F5">
      <td width="8%"> <input name="" type="checkbox" value="" id="selectall" /> </td>
      <td width="7%"> <strong> S.No. </strong> </td>
      <td width="34%"> <strong> User Details</strong> </td>
      <td width="18%"> <strong> Added Date </strong> </td>
      <td width="16%"> <strong> Status </strong> </td>
      <td width="17%"> <strong> Actions </strong> </td>
    </tr>   
    
    <!--------------------------listing for register users starts here----------------------------------->
    <?php
		if(empty($_GET['page']) || $_GET['page'] == 1)
		{
			$i = 0;
		}
		else
		{
			$i = $_GET['page'] * 30 - 30;
		}
		foreach($users as $user)
		{
			$i++;
			echo "<tr bgcolor='#F5F5F5'>";
			  echo "<td> <input name='checkbox[]' type='checkbox' value='$user->id' class='case' /> </td>";
			  echo "<td> $i </td>";
			  
			  echo "<td> <strong>email:</strong> $user->email <br /> <strong>password:</strong> $user->password </td>";			  
			  echo "<td>". datetime_to_text($user->added_date) ."</td>";
			  
			  echo "<td>";
			  if($user->user_status == 1)
			  {
			  	echo "Not active";
			  }
			  if($user->user_status == 2)
			  {
			  	echo "Active";
			  }
			  if($user->user_status == 3)
			  {
			  	echo "Not Confirmed";
			  }
			  if($user->user_status == 4)
			  {
			  	echo "Deleted";
			  }
			  echo "</td>";

			  $count = "";
			  $categories = Category::find_all_categries();
			  foreach($categories as $category)
			  {
			  	$count += count(Classifieds::find_by_user($category->tablename, $user->id));
			  }	  
			  
			  echo "<td> <a href='listings.php?user_id=$user->id'> View Listings ($count) </a> </td>";
			echo "</tr>";
		}
	?>
    <!--------------------------listing for register users ends here----------------------------------->
    
    <tr align="center">
      <td colspan="10">
      	  <input name="activate" type="submit" class="submit" value="Activate" />
          <input name="deactivate" type="submit" class="submit" value="De-Activate" />
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
                echo "<li><a href=\"users.php?email=$getemail&status=$getstatus&page=";
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
                    echo "<li> <a href=\"users.php?email=$getemail&status=$getstatus&page={$i}\">{$i}</a> </li>";
                }
            }
            
            if($pagination->has_next_ten_page())
            {
                echo "<li><a href=\"users.php?email=$getemail&status=$getstatus&page=";
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
