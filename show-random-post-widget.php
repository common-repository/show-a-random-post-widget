<?php
/*
Plugin Name: Show a Random Post Widget
Plugin URI: http://www.dionrodrigues.com
Description: A simple widget that displays a random post from your blogroll, or from any custom post type in your blog, rather then the standard functionality of the built in recent post widget.
Version: 2.0
Author: Dion Rodrigues
Author URI: https://profiles.wordpress.org/dionrodrigues/
License: GPL2
*/

class dionrodrigues_srptw extends WP_Widget {
	// WP plugin constructor
	function dionrodrigues_srptw() {
        parent::WP_Widget(false, $name = __('Show a Random Post Widget', 'dionrodrigues_srptw') );
    }

	// widget form
	function form($instance) {
		if( $instance) {
			$title = esc_attr($instance['title']);
			$number_of_posts = esc_attr($instance['number_of_posts']);
			$taxonomy_id = esc_attr($instance['taxonomy_id']);
		} else {
			$title = '';
			$number_of_posts = '';
			$taxonomy_id = '';
		}
?>

<!-- FORM VALUES -->
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Give Your Widget a Title:', 'dionrodrigues_srptw'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

<p><label for="<?php echo $this->get_field_id('number_of_posts'); ?>"><?php _e('Enter the Number of Randomized Posts To Show:', 'dionrodrigues_srptw'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('number_of_posts'); ?>" name="<?php echo $this->get_field_name('number_of_posts'); ?>" type="text" value="<?php echo $number_of_posts; ?>" /></p>

<p><label for="<?php echo $this->get_field_id('taxonomy_id'); ?>"><?php _e('Enter the Slug of the Custom Post Type to Show Posts From:<br /><span style="font-size: smaller;">Note: to show posts from your standard WordPress blog roll, just leave this field blank</span>', 'dionrodrigues_srptw'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('taxonomy_id'); ?>" name="<?php echo $this->get_field_name('taxonomy_id'); ?>" type="text" value="<?php echo $taxonomy_id; ?>" /></p>

<?php
}

// update on form save
function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['number_of_posts'] = strip_tags($new_instance['number_of_posts']);
	$instance['taxonomy_id'] = strip_tags($new_instance['taxonomy_id']);
	return $instance;
}

// display widget
function widget($args, $instance) {
	extract( $args );

	// assign widget vars based on input
	$title = apply_filters('widget_title', $instance['title']);
	$number_of_posts = $instance['number_of_posts'];
	$taxonomy_id = $instance['taxonomy_id'];
	echo $before_widget;
	
	// initialize widget
	echo '<div class="srpw_content">';
	// if title is set
	if ( $title ) {
		echo $before_title . $title . $after_title;
	}
   
	// Check if plugin is configured
	if( $number_of_posts ) {

		// Set number of posts to display and taxonomy to display; use blogroll if no custom tax. isset
		if (!empty ($taxonomy_id)) {
			$args = array( 'post_type' => $taxonomy_id, 'posts_per_page' => $number_of_posts, 'orderby' => 'rand', 'order' => 'DESC', 'post_status' => 'publish' );
		} else {
			$args = array( 'post_type' => 'post', 'posts_per_page' => $number_of_posts, 'orderby' => 'rand', 'order' => 'DESC', 'post_status' => 'publish' );
		}
		$recents = wp_get_recent_posts( $args );
		shuffle($recents);
		foreach( $recents as $recent ){
			echo '<div class="random-post-content">' . $recent["post_content"] .'</div>';
			echo '<div class="title">' . $recent["post_title"] . '</div>';
		}
	}
	echo '</div>';
	echo $after_widget;
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("dionrodrigues_srptw");'));
?>