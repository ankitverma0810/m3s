<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
	//for pagination
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	$per_page = 20;
	$total_count = Areas::count_all();
	$pagination = new pagination($page, $per_page, $total_count);
?>
<?php include_layout_template('admin-header.php'); ?>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

<div class="sidebar">
<?php include_layout_template('admin-sidebar.php'); ?>
</div><!--sidebar-->

<div class="content">
  <h2> Area Manager </h2>
  
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
  
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
  	<tr bgcolor="#F5F5F5">
    	<td width="8%"> <strong> S.No. </strong> </td>
      <td width="30%"> <strong> State </strong> </td>
      <td width="34%"> <strong> Area </strong> </td>
      <td width="14%"> <strong> Status </strong> </td>
      <td width="14%"> <strong> Actions </strong> </td>
    </tr>
    
    <script type="text/javascript">
		function queryAction(id) {
		
		var confirmmessage = "Are you sure you want to continue?";
		var goifokay = "delete-area.php?areaid="+id;
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
		if(empty($_GET['page']) || $_GET['page'] == 1)
		{
			$i = 0;
		}
		else
		{
			$i = $_GET['page'] * 20 - 20;
		}
		$sql = "SELECT * FROM mh_locations WHERE status IN(0, 1)";
		$sql .= " LIMIT {$per_page}";
		$sql .= " OFFSET {$pagination->offset()}";
		$areas = Areas::find_by_sql($sql);
		foreach($areas as $area)
		{
			$i++;
			echo "<tr bgcolor='#F5F5F5'>";
			  echo "<td> $i </td>";
			  
			  echo "<td>";
			  $state = States::find_by_id($area->state_id);
			  echo $state->title;
			  echo "</td>";
			  
			  echo "<td> $area->title </td>";
			  
			  echo "<td>";
			  if($area->status == 1)
			  {
			  	echo "Visible";
			  }
			  else
			  {
			  	echo "Not Visible";
			  }
			  echo "</td>";
			  
			  echo "<td> <a href='edit-area.php?areaid=$area->id'> Edit </a> | 
			  <a href='#' onClick=queryAction('".$area->id."')> Delete </a> </td>";
			echo "</tr>";
		}
	?>
  </table>
  
  <div class="pagination">
  	<ul>
	  <?php
        if($pagination->total_pages() > 1)
        {		
            if($pagination->has_previous_ten_page())
            {
                echo "<li><a href=\"view-areas.php?page=";
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
                    echo "<li> <a href=\"view-areas.php?page={$i}\">{$i}</a> </li>";
                }
            }
            
            if($pagination->has_next_ten_page())
            {
                echo "<li><a href=\"view-areas.php?page=";
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
