<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
	else
	{
		$featured = Rates::find_by_id(1);
	}
	if(isset($_POST['submit']))
	{
		if(empty($_POST['price']) || empty($_POST['days']))
		{
			$session->message("Please fill all the mandatory fields", "error");
			redirect_to('edit-featured-ad.php');
		}
		else
		{
			$featured->featured_price = $_POST['price'];
			$featured->featured_days  = $_POST['days'];		
			if($featured->save())
			{
				$session->message("Details updated Successfully", "success");
				redirect_to('edit-featured-ad.php');
			}
			else
			{
				$message = join("<br />", $featured->errors);
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
  <h2> Featured Ads </h2>
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
  
  <form action="edit-featured-ad.php" method="post">  
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
  	<tr>
      <td width="19%"> Fetaured Ad Rate: (Rs)</td>
      <td width="81%"> 
      	<input name="price" type="text" class="textfield" style="float:left; margin-right:10px;" value="<?php echo number_format($featured->featured_price); ?>" />
        <p> [Should be Numeric like:- 250 or 350] </p>
      </td>
    </tr>
    
    <tr>
      <td> No of days:</td>
      <td> 
      	<input name="days" type="text" class="textfield" value="<?php echo $featured->featured_days; ?>" />  
      </td>
    </tr>  
    
    <tr>
    	<td></td>
    	<td>
        	<input name="submit" type="submit" class="submit" value="Submit" />
        </td>
    </tr>
  </table>
  </form>
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
