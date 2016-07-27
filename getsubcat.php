<?php require_once("siteadmin/includes/initialize.php"); ?>
<select size="10" name="subcategory" id="subcategory" style="border:none 0px; padding:2px; width:275px;" onchange="show_type(this.value)" multiple="multiple">
	<?php
		$catid=$_GET["main_cat"];//IMPORTANT VARIABLE		
		$subcategories = Subcategory::find_by_categoryid($catid);
		foreach($subcategories as $subcategory)
		{
			echo "<option value='$subcategory->id'> $subcategory->title </option>";
		}
    ?>
</select>