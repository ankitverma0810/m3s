      <div class="sidebar">
	  <?php
      	if(basename($_SERVER["REQUEST_URI"]) == 'index.php' || $_SERVER["REQUEST_URI"] == '/')
		{
			   echo "<div class='sidebar-links'>";
			     echo "<h3> Browse by city </h3>";
			     echo "<ul>";
				   $sidebar_states = States::find_all_featured();
				   foreach($sidebar_states as $sidebar_state)
				   {
					 echo "<li> <a href='".$c_relink->replaceLink('?stateurl='.$sidebar_state->url)."'> $sidebar_state->title </a> </li>";
				   }
			    echo "</ul>";
		    echo "</div><!--sidebar-links-->";
      
			echo "<div class='sidebar-links'>";
			  echo "<h3> Popular Searches </h3>";
			  echo "<ul>";
			  	$sidebar_popular_searches = Search::find_all();
				foreach($sidebar_popular_searches as $sidebar_popular_search)
				{
					$popular_search_word=str_replace("-"," ",$sidebar_popular_search->search_word);
					echo "<li> <a href='".$c_relink->replaceLink('?caturl=all-categories&stateurl=all-india&name='.$sidebar_popular_search->search_word)."'>$popular_search_word</a> </li>";
				}			
			  echo "</ul>";
		    echo "</div><!--sidebar-links-->";
	  
			  $spotlight = Orders::find_spotlight();
			  if($spotlight)
			  {
			  	  $spotlight_classified = Classifieds::find_by_uniqueid($spotlight->ad_unique_id, $spotlight->tablename);
				  $spotlight_state = States::find_by_id($spotlight_classified->state_id);
				  $spotlight_tablename = str_replace('_', '-', $spotlight->tablename);
				  
				  echo "<div class='sidebar-links'>";
				  echo "<h3> Spotlight Ads </h3>";
				  
				  echo "<img src=".SITE_ROOT_URL."classified-ads/$spotlight_classified->filename width='203' height='131' style='border:solid 2px #a8d2eb' />";
				  echo "<h4 style='line-height:17px; margin-bottom:5px;'> ".cropText(str_replace("\\","",$spotlight_classified->title), 35)." - $spotlight_state->title </h4>";
				  echo "<p> ".htmlentities(cropText($spotlight_classified->description, 78))."... <a href='".$c_relink->replaceLink('?tablename='.$spotlight_tablename.'&adtitle='.$spotlight_classified->url.'&adid='.$spotlight_classified->id)."'> read more </a></p>";
				echo "</div><!--sidebar-links-->";
			  }
		}
		else
		{
			echo '<div class="sidebar-links">
				<h3> Ads in Other Categories </h3>
				<ul>';
				  $sidebar_categories = Category::find_limited_visible();
				  foreach($sidebar_categories as $sidebar_category)
				  {
				  	 echo "<li> <a href='".$c_relink->replaceLink('?caturl='.$sidebar_category->url)."'> &raquo; ".$sidebar_category->title." </a> </li>"; 
				  }	
				echo '</ul>
				</div><!--sidebar-links-->';
				
		    echo '<div class="sidebar-links">
				<h3> Ads in Other Locations</h3>
				<ul>';
				  $sidebar_states = States::find_limited_visible();
				  foreach($sidebar_states as $sidebar_state)
				  {
				  	 echo "<li> <a href='".$c_relink->replaceLink('?stateurl='.$sidebar_state->url)."'> &raquo; $sidebar_state->title </a> </li>"; 
				  }
				echo '</ul>
			</div><!--sidebar-links-->';
		}
	  ?>
      </div><!--sidebar-->