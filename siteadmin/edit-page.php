<?php require_once("includes/initialize.php"); ?>
<?php
	if(!$session->is_logged_in())
	{
		redirect_to("login.php");
	}
?>
<?php
	if(empty($_GET['id']))
	{
		$session->message("No Page id was provided", "error");
		redirect_to('view-pages.php');
	}
	else
	{
		$page = Cms::find_by_id($_GET['id']);
	}
?>
<?php
	if(isset($_POST['submit']))
	{
		$url = seo_url($_POST['title']);		
		$page->add_page($_POST['title'], $_POST['description'], $_POST['status'], $url);
		if($page->save())
		{
			$session->message("Page updated Successfully", "success");
			redirect_to('view-pages.php');
		}
		else
		{
			$message = join("<br />", $page->errors);
		}
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
  
  <form action="edit-page.php?id=<?php echo $page->id; ?>" method="post">
  <table width="100%" cellpadding="7" cellspacing="1" class="table">
  	<tr>
      <td width="8%"> Page Title: </td>
      <td width="60%"> <input name="title" type="text" class="textfield" value="<?php echo $page->title; ?>" /> </td>
    </tr>
    
    <tr>
      <td width="8%" valign="top"> Content: </td>
      <td width="60%">
       <textarea cols="30" id="editor1" name="description">
       		<?php 
				$text = strip_tags($page->description, "<strong><p><a><img><table><tr><td><h3><h2><ul><li><br>");
				$content = str_replace('\\', '', $text);
				echo $content;
		    ?>
       </textarea>
	   <script type="text/javascript">
        CKEDITOR.replace( 'editor1',
        {
        /*filebrowserBrowseUrl :'http://www.rameshwarherba.com/filemanager_in_ckeditor/js/ckeditor/filemanager/browser/default/browser.html?Connector=http://www.rameshwarherba.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
        filebrowserImageBrowseUrl : 'http://www.rameshwarherba.com/filemanager_in_ckeditor/js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=http://www.rameshwarherba.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
        filebrowserFlashBrowseUrl :'http://www.rameshwarherba.com/filemanager_in_ckeditor/js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=http://www.rameshwarherba.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php'}*/
		
		filebrowserBrowseUrl :'http://localhost/Parkovic/filemanager_in_ckeditor/js/ckeditor/filemanager/browser/default/browser.html?Connector=http://localhost/Parkovic/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
        filebrowserImageBrowseUrl : 'http://localhost/Parkovic/filemanager_in_ckeditor/js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=http://localhost/Parkovic/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
        filebrowserFlashBrowseUrl :'http://localhost/Parkovic/filemanager_in_ckeditor/js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=http://localhost/Parkovic/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php'}
        
        );
        </script>
      </td>
    </tr>
    
    <tr>
      <td width="8%"> Status: </td>
      <td width="60%"> 
      	<input name="status" type="radio" value="1" class="radio" <?php if($page->status==1) {echo "checked";} ?>/> <p> Visible </p>
        <input name="status" type="radio" value="0" class="radio" <?php if($page->status==0) {echo "checked";} ?> /> <p> Not Visible </p>
      </td>
    </tr>
    
    <tr>
      <td width="8%">&nbsp;  </td>
      <td width="60%"> 
      	<input name="submit" type="submit" class="submit" value="UPDATE" />
        <input name="cancel" type="reset" class="submit" value="CANCEL" />
      </td>
    </tr>
  </table>
  </form>
</div><!--content-->
<?php include_layout_template('admin-footer.php'); ?>
