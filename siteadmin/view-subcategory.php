<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
	//for pagination
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	$per_page = 15;
	$total_count = Subcategory::count_all();
	$pagination = new pagination($page, $per_page, $total_count);
?>
<?php include_layout_template('admin-header.php'); ?>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

<div class="sidebar">
<?php include_layout_template('admin-sidebar.php'); ?>
</div><!--sidebar-->

<div class="content">
  <h2> SubCategory Manager </h2>
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
      <td width="30%"> <strong> Title </strong> </td>
      <td width="36%"> <strong> Category </strong> </td>
      <td width="12%"> <strong> Status </strong> </td>
      <td width="14%"> <strong> Actions </strong> </td>
    </tr>
    
    <script type="text/javascript">
		function queryAction(id) {
		
		var confirmmessage = "Are you sure you want to continue?";
		var goifokay = "delete-subcategory.php?subid="+id;
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
			$i = $_GET['page'] * 15 - 15;
		}		
		$sql = "SELECT * FROM mh_subcategory WHERE status IN(0, 1)";
		$sql .= " LIMIT {$per_page}";
		$sql .= " OFFSET {$pagination->offset()}";		
		$subcategories = Subcategory::find_by_sql($sql);
		foreach($subcategories as $subcategory)
		{
			$i++;
			echo "<tr bgcolor='#F5F5F5'>";
			  echo "<td> $i </td>";
			  
			  echo "<td>";
			  $category = Category::find_by_id($subcategory->category_id);
			  echo "$category->title";
			  echo "</td>";
			  
			  echo "<td> $subcategory->title </td>";
			  
			  echo "<td>";
			  if($subcategory->status == 1)
			  {
				echo "Visible";
			  }
			  else
			  {
				echo "Not Visible";
			  }
			  echo "</td>";
			  
			  echo "<td> <a href='edit-subcategory.php?subid=$subcategory->id'> Edit </a> | 
			  <a href='#' onClick=queryAction('".$subcategory->id."')> Delete </a> </td>";
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
                echo "<li><a href=\"view-subcategory.php?page=";
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
                    echo "<li> <a href=\"view-subcategory.php?page={$i}\">{$i}</a> </li>";
                }
            }
            
            if($pagination->has_next_ten_page())
            {
                echo "<li><a href=\"view-subcategory.php?page=";
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
