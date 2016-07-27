<div class="clear"></div>

  <div class="footer">
    &copy; Copyright <?php echo strftime("%Y", time()); ?> M3S | All Right Reserved
  </div><!--footer-->
</div><!--wrapper-->
</body>
</html>
<?php
	if(isset($database))
	{
		$database->close_connection();
	}
?>
