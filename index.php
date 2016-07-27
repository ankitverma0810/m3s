<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php include("site-header.php"); ?>
<body>
<div class="wrapper">
  <?php include("header.php"); ?> 
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

  <div class="welcome-text">
    <span style="color:#1b387d; font-size:15px;">Welcome to M3S.in</span>, a free classified ads portal of India for all categories as Education, Automobiles, Travels, Real Estate etc.
  </div><!--welcome-text-->
  
  <div class="container">
    <div class="content">
    	
        <?php
        	$categories = Category::find_all_visible_limited();
			foreach($categories as $category)
			{
				echo "<div class='category-container'>";
					echo "<div class='category-top'>";
						echo "<img src='category/$category->filename' width='26' height='22' />";
						echo "<h2> <a href='".$c_relink->replaceLink('?caturl='.$category->url)."'> $category->title </a> </h2>";							
						echo "<p> (".count(Classifieds::find_by_tablename($category->tablename)).") </p>";
						echo "<div class='clear'></div>";
					echo "</div><!--category-top-->";
		
					echo "<div class='category-links'>";
						echo "<ul>";
							$subcategories = Subcategory::find_by_categoryid($category->id);
							foreach($subcategories as $subcategory)
							{
								echo "<li> <a href='".$c_relink->replaceLink('?caturl='.$category->url.'&subcaturl='.$subcategory->url)."'> $subcategory->title </a> </li>";
							}			
						echo "</ul>";
					echo "</div><!--category-links-->";
				echo "</div><!--category-container-->";				
			}
		?>
        <div class="clear"></div>
    </div><!--content-->
    
    <?php include("sidebar.php"); ?>
    
    <div class="clear"></div>
  </div><!--container-->
  
  <div class="featured-ads">
    <div class="ads-left">
      <img src="images/ad1.jpg" />
    </div><!--ads-left-->
    
    <div class="ads-right">
      <img src="images/ad2.jpg" />
    </div><!--ads-right-->
    
    <div class="clear"></div>
  </div><!--featured-ads-->
  
  <!----------------Featured ads on m3s.in----------------------->
  <?php
	$featured_ads = Orders::find_all_featured();
	if($featured_ads)
	{
		echo "<div class='featured-listing-container'>";
			echo "<h2> Featured Ads: </h2>";
			foreach($featured_ads as $featured_ad)
			{
				$classified = Classifieds::find_by_uniqueid($featured_ad->ad_unique_id, $featured_ad->tablename);
				$state = States::find_by_id($classified->state_id);
				$category = Category::find_by_id($classified->category_id);
				$subcategory = Subcategory::find_by_id($classified->subcategory_id);
				$tablename = str_replace('_', '-', $featured_ad->tablename);
				
				echo "<div class='featured-listing'>";
				  echo "<div class='featured-listing-left'>";
					echo "<h3> <a href='".$c_relink->replaceLink('?tablename='.$tablename.'&adtitle='.$classified->url.'&adid='.$classified->id)."'> ".str_replace("\\","",$classified->title)." </a> <span style='color:#666666'> - $state->title </span> </h3>";
					echo "<p style='color:#999999;'> $category->title >> $subcategory->title </p>";
					echo "<p>";
						echo htmlentities(cropText($classified->description, 200));
					echo "</p>";
				  echo "</div><!--featured-listing-left-->";
				  
				  echo "<div class='featured-listing-right'>";
					echo "<a href='".$c_relink->replaceLink('?tablename='.$tablename.'&adtitle='.$classified->url.'&adid='.$classified->id)."'><img src='images/reply.jpg' border='0' /></a>";
				  echo "</div><!--featured-listing-right-->";
				  
				  echo "<div class='clear'></div>";
				echo "</div><!--featured-listing-->";
		  }
		  echo "</div><!--featured-listing-container-->";
	}
  ?>
  
  <!----------------Recently posted ads on m3s.in----------------------->
  <div class='featured-listing-container'>
  	  <h2> Recent Ads: </h2>
      <?php
		$categories = Category::find_all_categries();
		$count = count($categories);
		$num = 0;
		$sql = "";
		foreach($categories as $category)
		{
			$num++;
			$sql .= " SELECT * FROM ". $category->tablename;
			$sql .= " WHERE ad_status = 2";
			$sql .= " AND featured_status = 0 AND spotlight_status = 0";
			if($num != $count)
			{
				$sql .= " UNION ALL";
			}
		}
		$sql .= " ORDER BY YEAR(expiry_date) ASC, MONTH(expiry_date) ASC, DAY(expiry_date) DESC, Hour(expiry_date) DESC, 
			      Minute(expiry_date) DESC, Second(expiry_date) DESC";		
		$sql .= " LIMIT 5";
		$classifieds = Classifieds::find_by_sql($sql);
		
		foreach($classifieds as $classified)
		{
			$category = Category::find_by_id($classified->category_id);
			$subcategory = Subcategory::find_by_id($classified->subcategory_id);
			$state = States::find_by_id($classified->state_id);
			$tablename = $category->tablename;
			
			echo "<div class='featured-listing'>";
			  echo "<div class='featured-listing-left'>";
				echo "<h3> <a href='".$c_relink->replaceLink('?tablename='.$tablename.'&adtitle='.$classified->url.'&adid='.$classified->id)."'> ".str_replace("\\","",$classified->title)." </a>";
				 
				echo "<span style='color:#666666'> - $state->title </span> </h3>";
				
				echo "<p style='color:#999999;'> $category->title >> $subcategory->title </p>";
				echo "<p> ".htmlentities(cropText($classified->description, 200))." </p>";
			  echo "</div><!--featured-listing-left-->";
			  
			  echo "<div class='featured-listing-right'>";
				echo "<a href='".$c_relink->replaceLink('?tablename='.$tablename.'&adtitle='.$classified->url.'&adid='.$classified->id)."'><img src='images/reply.jpg' border='0' /></a>";
			  echo "</div><!--featured-listing-right-->";
			  
			  echo "<div class='clear'></div>";
			echo "</div><!--featured-listing-->";
		}
	  ?>
  </div><!--featured-listing-container-->
  
  <?php include("footer.php"); ?>
</div><!--wrapper-->
</body>
</html>
