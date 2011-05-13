<?php
	/*
	Plugin Name: Local Navigation Widget
	Plugin URI: http://northeastwebdesign.com
	Description: Local Navigation using wp_list_pages.  CMS type widget useful for sites with a large amount of pages.
	Author: Northeast Web Design
	Version: 1.0
	Author URI: http://northeastwebdesign.com
	*/
	
	/**
	 * Output our widget
	 */
	function newd_lnw() {
		global $post;
		$options = newd_lnw_get_options();
		
		/**
		 * This variable tells us if
		 * this page has children or not
		 */
		 
		$currentPageHasChildren = wp_list_pages('child_of='.$post->ID.'&echo=0&depth=1&title_li='); ;
		
		
		
		/**
		 * Check to see if the currently selected page
		 * has a parent and has children
		 */
		 
		if (($post->post_parent != 0) && ($currentPageHasChildren)){
			/**
			 * Display the current menu name in the title
			 * and its children as options
			 */
			$titleID = $post->ID;
			$childrenID = $post->ID;
		}
		
		
		
		/**
		 * Check to see if the currently selected page
		 * has a parent and does NOT have children
		 */
		 
		elseif (($post->post_parent != 0) && !($currentPageHasChildren)) {
			/**
			 * Display the parent menu name in the title
			 * and its children as options
			 */
			
			$titleID = $childrenID = $post->post_parent;
		}
		
		
		
		/**
		 * Check to see if the currently selected page
		 * does not have a parent and has children
		 */
		 
		elseif (($post->post_parent == 0) && ($currentPageHasChildren) ) {
			/**
			 * Display the current menu name in the title
			 * and its children as options
			 */
			
			$titleID = $childrenID = $post->ID;
		}
		
		
		
		/**
		 * Create our display title and get our children
		 */
		if ($options['linkTitle'])
			$displayTitle = '<a href="' . get_permalink($titleID) . '" />' . get_the_title($titleID) . '</a>';
		else
			$displayTitle = get_the_title($titleID);
		$displayChildren = wp_list_pages('child_of=' . $childrenID . '&echo=0&depth=1&title_li=');
		
		
		
		/**
		 * If we have both, then display them
		 */
		 
		if ($displayTitle && $displayChildren) {
			echo '<div class="widget">';
			
			if ($options['displayTitle'])
				echo '<h2>' . $displayTitle . '</h2>' . "\n";
			
			echo '<ul id="localnavigationwidget">' . $displayChildren . '</ul>' . "\n";
			echo '</div>';
		}
	}
	
	function newd_lnw_get_options() {
		$options = get_option("newd_lnw_options");
		
		
		/**
		 * These are the default options
		 */
		if (!is_array( $options )) {
			$options = array(
		    	'displayTitle' => 0,
		    	'linkTitle' => 0
			);
		}
		
		return $options;
	}
	
	function newd_lnw_control () {
		/**
		 * Get our widget options
		 */
		$options = newd_lnw_get_options();
 
		if ($_POST['newd_lnw_submit']) {
			$options['displayTitle'] = $_POST['newd_lnw_display_title'];
			$options['linkTitle'] = $_POST['newd_lnw_link_title'];
			update_option("newd_lnw_options", $options);
		}
 
		?>
		<p>
			Use the options below to configure how the menu will display.
		<p>
		<h4>
			Parent Menu options
		</h4>
		<p>
			<input type="checkbox" <?php if ($options['displayTitle']) echo ' checked="checked" '; ?> id="newd_lnw_display_title" name="newd_lnw_display_title" value="1" />
			<label for="newd_lnw_display_title">Display parent menu title</label><br />
			
			<input type="checkbox" <?php if ($options['linkTitle']) echo ' checked="checked" '; ?> id="newd_lnw_link_title" name="newd_lnw_link_title" value="1" />
			<label for="newd_lnw_link_title">Link parent menu title</label>
			
			<input type="hidden" id="newd_lnw_submit" name="newd_lnw_submit" value="1" />
		</p>
		<?php
	}
	
	function init_newd_lnw() {
		register_sidebar_widget("Local Navigation Widget", "newd_lnw");
		register_widget_control("Local Navigation Widget", "newd_lnw_control", 200, 200 );  
	}
	
	add_action("plugins_loaded", "init_newd_lnw");
?>