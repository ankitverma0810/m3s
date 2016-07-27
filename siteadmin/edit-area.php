<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
?>
<?php
	if(empty($_GET['areaid']))
	{
		$session->message("No Area id was provided", "error");
		redirect_to('view-areas.php');
	}
	else
	{
		$area = Areas::find_by_id($_GET['areaid']);
	}
?>
<?php
	if(isset($_POST['submit']))
	{
		$url = seo_url($_POST['title']);
		$area->add_area($_POST['state_id'], $_POST['title'], $_POST['status'], $url);
		if($area->save())
		{
			$session->message("Area updated Successfully", "success");
			redirect_to('view-areas.php');
		}
		else
		{
			$message = join("<br />", $area->errors);
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
  
  <form action="edit-area.php?areaid=<?php echo $area->id; ?>" method="post">
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
					echo "<option value='$state->id'";
					if($area->state_id == $state->id)
					{
						echo "selected='selected'";
					}
					echo "> $state->title </option>";
				}
			?>
        </select>
      </td>
    </tr>
    
    <tr>
      <td width="24%"> Area Title: </td>
      <td width="76%"> <input name="title" type="text" class="textfield" value="<?php echo $area->title; ?>" /> </td>
    </tr>

    <tr>
      <td width="24%"> Status: </td>
      <td width="76%"> 
      	<input name="status" type="radio" value="1" class="radio" <?php if($area->status==1) {echo "checked";} ?>/> <p> Visible </p>
        <input name="status" type="radio" value="0" class="radio" <?php if($area->status==0) {echo "checked";} ?> /> <p> Not Visible </p>
      </td>
    </tr>
    
    <tr>
      <td width="24%">&nbsp;  </td>
      <td width="76%"> 
      	<input name="submit" type="submit" class="submit" value="UPDATE AREA" />
        <input name="cancel" type="reset" class="submit" value="CANCEL" />
      </td>
    </tr>
  </table>
  </form>
  
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
