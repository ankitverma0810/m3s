<?php require_once("siteadmin/includes/initialize.php"); ?>
<select name="locality" class="dropdown">
	<option value=""> -- Select Locality -- </option>
	<?php
		$id=$_GET["main_cat"];//IMPORTANT VARIABLE
		$areas = Areas::find_all_visible_states($id);
		foreach($areas as $area)
		{
			echo "<option value='$area->id'> $area->title </option>";
		}
    ?>
</select>