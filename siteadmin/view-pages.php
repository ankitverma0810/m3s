<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
?>
<?php include_layout_template('admin-header.php'); ?>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

<div class="sidebar">
<?php include_layout_template('admin-sidebar.php'); ?>
</div><!--sidebar-->

<div class="content">
  <h2> Content Management System </h2>
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
      <td width="61%"> <strong> Title </strong> </td>
      <td width="16%"> <strong> Status </strong> </td>
      <td width="15%"> <strong> Actions </strong> </td>
    </tr>
    
    <script type="text/javascript">
		function queryAction(id) {
		
		var confirmmessage = "Are you sure you want to continue?";
		var goifokay = "delete-page.php?id="+id;
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
		$i = 0;
    	$pages = Cms::find_all();
		foreach($pages as $page)
		{
			$i++;
			echo "<tr bgcolor='#F5F5F5'>";
			  echo "<td> $i </td>";
			  echo "<td> $page->title </td>";
			  
			  echo "<td>";
			  if($page->status == 1)
			  {
			  	echo "Visible";
			  }
			  else
			  {
			  	echo "Not Visible";
			  }
			  echo "</td>";
			  
			  echo "<td> <a href='edit-page.php?id=$page->id'> Edit </a> | 
			  <a href='#' onClick=queryAction('".$page->id."')> Delete </a> </td>";
			echo "</tr>";
		}
	?>
  </table>
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
