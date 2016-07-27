<div class="header">
    <div class="logo">
      <a href="<?php echo SITE_ROOT_URL; ?>index.php"><img src="<?php echo SITE_ROOT_URL; ?>/images/logo.jpg" border="0" /></a>
    </div><!--logo-->
    
    <div class="header-right">
      <div class="header-links">
        <ul>
          <?php
          	if(isset($_SESSION['reguser_id']))
			{
				echo "<li> <a href='".SITE_ROOT_URL."my-account.php'> My M3S </a> </li>";
			    echo "<li> | </li>";
			    echo "<li> <a href='".SITE_ROOT_URL."logout.php'> Logout </a> </li>";
			}
			else
			{
				echo "<li> <a href='".SITE_ROOT_URL."register.php'> Register </a> </li>";
			    echo "<li> | </li>";
			    echo "<li> <a href='".SITE_ROOT_URL."login.php'> Login </a> </li>";
			}
		  ?>
          <li> | </li>
          <li> <a href="#"> Help </a> </li>
        </ul>
        <div class="clear"></div>
      </div><!--header-links-->
      
      <div class="clear"></div>
      
      <form action="<?php echo SITE_ROOT_URL; ?>search-listing.php" method="post">
      <div class="search-filter">
        <div class="search-filter-left">
          <input name="name" type="text" value="Keyword" onclick="if(this.value=='Keyword'){this.value=''}" onblur="if(this.value==''){this.value='Keyword'}" class="textfield">
          
          <select name="categories" class="dropdown">
            <option value="all-categories" selected="selected"> All Categories </option>
            <?php
            	$search_categories = Category::find_all_visible();
				foreach($search_categories as $search_category)
				{
					echo "<option value='$search_category->url'";
					if(isset($_POST['search']))
					{
						if($search_category->url == $_POST['categories'])
						{
							echo "selected='selected'";
						}
					}
					else
					{
						$get_categories = !empty($_GET['caturl']) ? $_GET['caturl'] : "";
						if($search_category->url == $get_categories)
						{
							echo "selected='selected'";
						}
					}
					echo "> $search_category->title </option>";
				}
			?>
          </select>
          
          <select name="states" class="dropdown"> 
            <option value="all-india"> All India </option>
            <?php
            	$search_states = States::find_all_visible();
				foreach($search_states as $search_state)
				{
					echo "<option value='$search_state->url'";
					if(isset($_POST['search']))
					{
						if($search_state->url == $_POST['states'])
						{
							echo "selected='selected'";
						}
					}
					else
					{
						$get_states = !empty($_GET['stateurl']) ? $_GET['stateurl'] : "";
						if($search_state->url == $get_states)
						{
							echo "selected='selected'";
						}
					}
					echo "> $search_state->title </option>";
				}
			?>
          </select>
          <input name="search" type="submit" class="search" value="" />
          <div class="clear"></div>
          <p> eg: used iPhone, lg mobile, hero honda bikes, clerical job </p>
        </div><!--search-filter-left-->
        
        <div class="search-filter-right">
          <a href="<?php echo SITE_ROOT_URL; ?>post-free-classified-ads.php"><img src="<?php echo SITE_ROOT_URL; ?>images/post-free-ads.jpg" border="0" /></a>
        </div><!--search-filter-right-->  
        
        <div class="clear"></div>
      </div><!--search-filter-->
      </form>
    </div><!--header-right-->
    
    <div class="clear"></div>
  </div><!--header-->