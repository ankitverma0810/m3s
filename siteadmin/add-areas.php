<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
?>
<?php
	if(isset($_POST['submit']))
	{
		$area = new Areas();
		$url = seo_url($_POST['title']);
		$area->add_area($_POST['state_id'], $_POST['title'], $_POST['status'], $url);
		if($area->save())
		{
			$session->message("Area Added Successfully", "success");
			redirect_to('view-areas.php');
		}
		else
		{
			$session->message(join("<br />", $area->errors), "error");
			redirect_to('add-areas.php');
		}
	}
?>
<?php include_layout_template('admin-header.php'); ?>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

<div class="sidebar">
<?php include_layout_template('admin-sidebar.php'); ?>
</div><!--sidebar-->

<div class="content">
  <h2> Area Manager  </h2>
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
  
  <form action="add-areas.php" method="post">
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
  	<tr>
      <td width="24%"> State: </td>
      <td width="76%"> 
      	<select name="state_id" class="dropdown">
        	<option value=""> -- Select -- </option>
            <?php
            	$states = States::find_all();
				foreach($states as $state)
				{
					echo "<option value='$state->id'> $state->title </option>";
				}
			?>
        </select>
      </td>
    </tr>
    
    <tr>
      <td width="24%"> Area Title: </td>
      <td width="76%"> <input name="title" type="text" class="textfield" /> </td>
    </tr>

    <tr>
      <td width="24%"> Status: </td>
      <td width="76%"> 
      	<input name="status" type="radio" value="1" class="radio" checked="checked" /> <p> Visible </p>
        <input name="status" type="radio" value="0" class="radio" /> <p> Not Visible </p>
      </td>
    </tr>
    
    <tr>
      <td width="24%">&nbsp;  </td>
      <td width="76%"> 
      	<input name="submit" type="submit" class="submit" value="ADD AREA" />
        <input name="cancel" type="reset" class="submit" value="CANCEL" />
      </td>
    </tr>
  </table>
  </form>
  
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
