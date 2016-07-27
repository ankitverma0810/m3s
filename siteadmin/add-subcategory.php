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
		$subcategory = new Subcategory();
		$url = seo_url($_POST['title']);		
		$subcategory->add_subcategory($_POST['category_id'], $_POST['title'], $_POST['status'], $url, $_POST['keywords'], $_POST['description']);		
		if($subcategory->save())
		{
			$session->message("Subcategory Created Successfully", "success");
			redirect_to('view-subcategory.php');
		}
		else
		{
			$session->message(join("<br />", $subcategory->errors), "error");
			redirect_to('add-subcategory.php');
		}
	}
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
  
  <form action="add-subcategory.php" method="post">
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
  	<tr>
      <td width="24%"> Category: </td>
      <td width="76%"> 
      	<select name="category_id" class="dropdown">
        	<option value=""> -- Select -- </option>
            <?php
            	$categories = Category::find_all();
				foreach($categories as $category)
				{
					echo "<option value='$category->id'>$category->title</option>";
				}
			?>
        </select>
      </td>
    </tr>
    
    <tr>
      <td width="24%"> SubCategory Title: </td>
      <td width="76%"> <input name="title" type="text" class="textfield" /> </td>
    </tr>
    
    <tr valign="top">
      <td width="24%"> Meta Keywords: </td>
      <td width="76%"> <textarea name="keywords" cols="" rows="" class="textarea"></textarea> </td>
    </tr>
    
    <tr valign="top">
      <td width="24%"> Meta Description: </td>
      <td width="76%"> <textarea name="description" cols="" rows="" class="textarea"></textarea> </td>
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
      	<input name="submit" type="submit" class="submit" value="ADD SUBCATEGORY" />
        <input name="reset" type="reset" class="submit" value="CANCEL" />
      </td>
    </tr>
  </table>
  </form>
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
