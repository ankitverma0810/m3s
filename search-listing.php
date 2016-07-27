<?php require_once("siteadmin/includes/initialize.php"); ?>
<?php
	//saving searches for popular search option
	if(isset($_POST['search']))
	{
		if(!empty($_POST['name']) && $_POST['name'] != 'Keyword')
		{
			$search_word = seo_url($_POST['name']);
			$update_popular_search = Search::find_by_word($search_word);
			if($update_popular_search)
			{
				$update_popular_search->value++;
				$update_popular_search->update();
			}
			else
			{
				$new_popular_search = new Search();
				$new_popular_search->search_word = $search_word;
				$new_popular_search->value = 1;
				$new_popular_search->create();
			}
		}
	}
		
	//----------------------To check whether its an offer or wanted Ad starts from here------------------------//
	if(empty($_GET['ad_type']))
	{
		$_GET['ad_type'] = "offer-ad";
	}
	if($_GET['ad_type'] == "offer-ad")
	{
		$ad_type = "offer-ad";
	}
	if($_GET['ad_type'] == "wanted-ad")
	{
		$ad_type = "wanted-ad";
	}
	
	//-----------------values of all variables stored here whether we click on search or not------------------------//
	if(isset($_POST['search']))
	{
		if($_POST['categories'] == "all-categories")
		{
			$category = "";
		}
		else
		{
			$category = Category::find_by_url($_POST['categories']);
			$category = $category->id;
		}		
		if($_POST['states'] == "all-india")
		{
			$state = "";
		}
		else
		{
			$state = States::find_by_url($_POST['states']);
			$state = $state->id;
		}
		$subcategory = "";
		if($_POST['name'] == "Keyword")
		{
			$name = "";
		}
		else
		{
			$name = str_replace("-"," ",$database->escape_value($_POST['name']));
		}
	}
	else
	{
		$category = !empty($_GET['caturl']) ? Category::find_by_url($_GET['caturl']) : "";
		if(!empty($category))
		{
			$category = $category->id;
		}		
		$subcategory = !empty($_GET['subcaturl']) ? Subcategory::find_by_url($_GET['subcaturl']) : "";
		if(!empty($subcategory))
		{
			$subcategory = $subcategory->id;
		}		
		$state = !empty($_GET['stateurl']) ? States::find_by_url($_GET['stateurl']) : "";
		if(!empty($state))
		{
			$state = $state->id;
		}		
		$name = !empty($_GET['name']) ? str_replace("-"," ",$database->escape_value($_GET['name'])) : "";
	}
	
	//counting offer and wanted ads	
	$count_offer_ads = count(Classifieds::classified_by_ad_type($category, $subcategory, $state, $name, 'offer-ad'));
	$count_wanted_ads = count(Classifieds::classified_by_ad_type($category, $subcategory, $state, $name, 'wanted-ad'));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<?php
	//finding title for the page eg.(All Classified Ads in India)
	$heading = "All";
	$heading_state = "India";		
	if(!empty($name))
	{
		if(!empty($state))
		{
			$heading = $name;
			$heading_state = States::find_by_id($state);
			$heading_state = $heading_state->title;
		}
		else
		{
			$heading = $name;
		}
	}
	else if(!empty($subcategory))
	{
		if(!empty($state))
		{
			$heading = Subcategory::find_by_id($subcategory);
			$heading = $heading->title;
			
			$heading_state = States::find_by_id($state);
			$heading_state = $heading_state->title;
		}
		else
		{
			$heading = Subcategory::find_by_id($subcategory);
			$heading = $heading->title;
		}
	}
	else if(!empty($category))
	{
		if(!empty($state))
		{
			$heading = Category::find_by_id($category);
			$heading = $heading->title;
			
			$heading_state = States::find_by_id($state);
			$heading_state = $heading_state->title;
		}
		else
		{
			$heading = Category::find_by_id($category);
			$heading = $heading->title;
		}
	}
	else if(!empty($state))
	{
		$heading = States::find_by_id($state);
		$heading = $heading->title;
	}
	
	//finding meta keywords and description for the page
	$meta_keywords = "";
	$meta_description = "";	
	if(!empty($subcategory))
	{
		$meta_subcategory = Subcategory::find_by_id($subcategory);
		$meta_keywords = $meta_subcategory->meta_keywords;
		$meta_description = $meta_subcategory->meta_description;
	}
	else if(!empty($category))
	{		
		$meta_category = Category::find_by_id($category);
		$meta_keywords = $meta_category->meta_keywords;
		$meta_description = $meta_category->meta_description;	
	}
	else if(!empty($state))
	{		
		$meta_state = States::find_by_id($state);
		$meta_keywords = $meta_state->meta_keywords;
		$meta_description = $meta_state->meta_description;	
	}
	
?>
<title><?php echo "M3S | {$heading} Classifieds {$heading_state} | Free Classifieds Ads"; ?></title>
<meta name="description" content="<?php echo $meta_description; ?>" />
<meta name="keywords" content="<?php echo $meta_keywords; ?>" />

<link href="<?php echo SITE_ROOT_URL; ?>css/stylesheet.css" rel="stylesheet" type="text/css" />
<?php
	$htaccessFile	= 'htaccess';
	$c_relink		= new RELINK($htaccessFile);
?>
</head>

<body>
<div class="wrapper">
  <?php include("header.php"); ?>
  
  <div class="listing-welcome-text">
  	<h2><?php echo "{$heading} Classified Ads in {$heading_state}";	?></h2>
    <p> Shown below are ads available in m3h Classified: </p>
	<ul>
      <li> <a href="#"> All Categories </a> </li> 
      <li> &raquo; </li>
      <li> <?php echo "{$heading} Ads in {$heading_state}"; ?> </li>
    </ul>
    <div class="clear"></div>
  </div><!--listing-welcome-text-->
  
  <div class="listing-social-media">
    <a href="#"><img src="<?php echo SITE_ROOT_URL; ?>/images/facebook-share.jpg" /></a>
    <a href="#"><img src="<?php echo SITE_ROOT_URL; ?>/images/rss.jpg" /></a>
  </div><!--listing-social-media-->
  
  <div class="clear"></div>

  <div class="listing-ad-types">
  	<?php
		//link for offer and wanted ads if we click or not on seacrh
    	if(isset($_POST['search']))
		{
			//link for offer ad if we click on seach
			$offer_url = 'search-listing.php?';
			$offer_url .= 'caturl='.$_POST['categories'];
			$offer_url .= '&stateurl='.$_POST['states'];
			if($_POST['name'] != '')
			{
				$offer_url .= '&name='.$_POST['name'];
			}
			$offer_url .= '&ad_type=offer-ad';
			
			//link for wanted ad if we click on seach
			$wanted_url = 'search-listing.php?';
			$wanted_url .= 'caturl='.$_POST['categories'];
			$wanted_url .= '&stateurl='.$_POST['states'];
			if($_POST['name'] != '')
			{
				$wanted_url .= '&name='.$_POST['name'];
			}
			$wanted_url .= '&ad_type=wanted-ad';	
		}
		else
		{
			//link for offer ad if we dont click on seach
			$offer_url = 'search-listing.php?';
			if(!empty($_GET['caturl']))
			{
				$offer_url .= 'caturl='.$_GET['caturl'];
			}
			if(!empty($_GET['subcaturl']))
			{
				if(!empty($_GET['caturl']))
				{
					$offer_url .= '&subcaturl='.$_GET['subcaturl'];
				}
				else
				{
					$offer_url .= 'subcaturl='.$_GET['subcaturl'];
				}
			}
			if(!empty($_GET['stateurl']))
			{
				if(!empty($_GET['caturl']) || !empty($_GET['subcaturl']))
				{
					$offer_url .= '&stateurl='.$_GET['stateurl'];
				}
				else
				{
					$offer_url .= 'stateurl='.$_GET['stateurl'];
				}
			}
			if(!empty($_GET['name']))
			{
				if(!empty($_GET['caturl']) || !empty($_GET['subcaturl']) || !empty($_GET['stateurl']))
				{
					$offer_url .= '&name='.$_GET['name'];
				}
				else
				{
					$offer_url .= 'name='.$_GET['name'];
				}
			}
			$offer_url .= '&ad_type=offer-ad';			
			
			//link for wanted ad if we dont click on seach
			$wanted_url = 'search-listing.php?';
			if(!empty($_GET['caturl']))
			{
				$wanted_url .= 'caturl='.$_GET['caturl'];
			}
			if(!empty($_GET['subcaturl']))
			{
				if(!empty($_GET['caturl']))
				{
					$wanted_url .= '&subcaturl='.$_GET['subcaturl'];
				}
				else
				{
					$wanted_url .= 'subcaturl='.$_GET['subcaturl'];
				}
			}
			if(!empty($_GET['stateurl']))
			{				
				if(!empty($_GET['caturl']) || !empty($_GET['subcaturl']))
				{
					$wanted_url .= '&stateurl='.$_GET['stateurl'];
				}
				else
				{
					$wanted_url .= 'stateurl='.$_GET['stateurl'];
				}
			}
			if(!empty($_GET['name']))
			{
				if(!empty($_GET['caturl']) || !empty($_GET['subcaturl']) || !empty($_GET['stateurl']))
				{
					$wanted_url .= '&name='.$_GET['name'];
				}
				else
				{
					$wanted_url .= 'name='.$_GET['name'];
				}
			}
			$wanted_url .= '&ad_type=wanted-ad';			
		}
	?>
  	<ul>
        <?php
			//offer ads (link and count)
        	echo "<li";
			if($_GET['ad_type'] != 'wanted-ad')
			{
				echo " class='current'";
			}
			echo ">";
			echo "<a href='".$c_relink->replaceLink(str_after($offer_url, 'php'))."'>";
			echo "Offer Ads ($count_offer_ads) </a>";
			echo "</li>";
			
			//wanted ads (link and count)
			echo "<li";
			if($_GET['ad_type'] == 'wanted-ad')
			{
				echo " class='current'";
			}
			echo ">";
			echo "<a href='".$c_relink->replaceLink(str_after($wanted_url, 'php'))."'>";
			echo "Wanted Ads ($count_wanted_ads) </a>";
			echo "</li>";
		?>
    </ul>
    <div class="clear"></div>
  </div><!--listing-ad-types-->
  
    
  <div class="container" style="margin-top:0px; position:relative; z-index:1px; padding-bottom:25px;">
    <div class="listing-container">
    
    	<?php
			//------------for pagination variables starts here-----------------//
			$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
			$per_page = 15;
			$total_count = count(Classifieds::classified_by_ad_type($category, $subcategory, $state, $name, $ad_type));		  
			$pagination = new pagination($page, $per_page, $total_count);			
		  
			//----------------find all offer ads in this particular category starts here------------------//		  
			$classifieds = Classifieds::classified_by_ad_type($category, $subcategory, $state, $name, $ad_type, $page, $per_page, $total_count);
			if($classifieds)
			{
				foreach($classifieds as $classified)
				{
				  //finding state, category, subcategory and tablename for seo url//
				  $listing_state = States::find_by_id($classified->state_id); 
				  $listing_category = Category::find_by_id($classified->category_id);
				  $listing_subcategory = Subcategory::find_by_id($classified->subcategory_id);			  
				  $listing_tablename = str_replace('_', '-', $listing_category->tablename);
										  
				  echo "<div class='listing'>";
					echo "<h2> <a href='".$c_relink->replaceLink('?tablename='.$listing_tablename.'&adtitle='.$classified->url.'&adid='.$classified->id)."'> ".str_replace("\\","",$classified->title)." </a> </h2>";		
					echo "<p>";
						$text = strip_tags($classified->description, "<strong><p><a><img><table><tr><td><h3><h2><ul><li><br>");
						$content = htmlentities(str_replace('\\', '', $text));
						echo cropText($content, 90)."...<a href='".$c_relink->replaceLink('?tablename='.$listing_tablename.'&adtitle='.$classified->url.'&adid='.$classified->id)."'> view more </a> ";
					echo "</p>";
					
					echo "<p style='float:left; font-size:11px;'>";				
					echo "<a href='".$c_relink->replaceLink('?caturl='.$listing_category->url.'&subcaturl='.$listing_subcategory->url.'&stateurl='.$listing_state->url.'&ad_type='.$classified->ad_type)."'>";								
					echo "<span style='color:#009900;'> $listing_state->title </span> | $listing_category->title > $listing_subcategory->title </a> | ". datetime_to_text($classified->added_date);				
					echo "</p>";
					
					echo "<a href='".$c_relink->replaceLink('?tablename='.$listing_tablename.'&adtitle='.$classified->url.'&adid='.$classified->id)."'><img src='".SITE_ROOT_URL."images/reply1.jpg' /></a>";
					
					echo "<div class='clear'></div>";
				  echo "</div><!--listing-->";
			   }
			}
			else
			{
				echo "<div class='listing'>";
					echo "<h2 style='font-size:17px;'> No Matching result found. </h2>";
					echo "<p> Please broaden your search criteria or you can: </p>";
					
					echo "<ul>";
					  echo "<li>Review the Category, Keyword or Location chosen by you.</li>";
					  echo "<li> <a href='".SITE_ROOT_URL."post-free-classified-ads.php'> Didn't find what you are looking for? <strong>Post Wanted Ad in 30 secs</strong> to get quick response. It's FREE! </a> </li>";
					echo "</ul>";
				echo "</div><!--listing-->";
			}
		?>
        
        <div class="pagination" style="float:right;">
            <ul>
              <?php
			  	//find url for pagination starts here
				if($_GET['ad_type'] == 'offer-ad')
				{
					$pageurl = $offer_url;
				}
				else
				{
					$pageurl = $wanted_url;
				}
				
				//pagination starts
                if($pagination->total_pages() > 1)
                {		
                    if($pagination->has_previous_ten_page())
                    {
                        echo "<li><a href=".$c_relink->replaceLink(str_after($pageurl, 'php')."&page=");
                        echo $pagination->previous_ten_page();
                        echo ">&laquo; Previous </a></li>";
                    }
                    
                    for($i = max(1, $page - 9); $i <= min($page + 9, $pagination->total_pages()); $i++)
                    {
                        if($i == $page)
                        {
                            echo "<li class='current'> {$i} </li>";
                        }
                        else
                        {
                            echo "<li> <a href=".$c_relink->replaceLink(str_after($pageurl, 'php')."&page=".$i)."> {$i} </a> </li>";
                        }
                    }
                    
                    if($pagination->has_next_ten_page())
                    {
                        echo "<li><a href=".$c_relink->replaceLink(str_after($pageurl, 'php')."&page=");
                        echo $pagination->next_ten_page();
                        echo ">Next &raquo; </a> </li>";
                    }
                }
              ?>
            </ul>
            <div class="clear"></div>
        </div><!--pagination-->          
    </div><!--listing-container-->
    
    <?php include("sidebar.php"); ?>
    
    <div class="clear"></div>
  </div><!--container-->
  
  <div class="featured-ads">
    <div class="ads-left">
      <img src="<?php echo SITE_ROOT_URL; ?>/images/ad1.jpg" />
    </div><!--ads-left-->
    
    <div class="ads-right">
      <img src="<?php echo SITE_ROOT_URL; ?>/images/ad2.jpg" />
    </div><!--ads-right-->
    
    <div class="clear"></div>
  </div><!--featured-ads-->
  <?php include("footer.php"); ?>
</div><!--wrapper-->
</body>
</html>