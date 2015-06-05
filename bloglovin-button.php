<?php
/*
Plugin Name: Bloglovin Button
Plugin URI: http://wordpress.org/extend/plugins/bloglovin-button/
Version: 1.0.0
Author: pipdig
Description: Easily add the Bloglovin Button to your site.
Text Domain: bloglovin-button
Author URI: http://www.pipdig.co/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Copyright 2015 pipdig Ltd.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}


/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function bloglovin_button_textdomain() {
	$domain = 'bloglovin-button';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	// wp-content/languages/plugin-name/plugin-name-en_GB.mo
	load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	// wp-content/plugins/plugin-name/languages/plugin-name-en_GB.mo
	load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'bloglovin_button_textdomain' );



/**
 * The widget
 *
 * @since 1.0.0
 */
class bloglovin_button_widget extends WP_Widget {
 
  public function __construct() {
      $widget_ops = array('classname' => 'bloglovin_button_widget', 'description' => __("Display the official Bloglovin' button.", 'bloglovin-button') );
      $this->WP_Widget('bloglovin_button_widget', __("Bloglovin' Button", 'bloglovin-button'), $widget_ops);
  }
  
  function widget($args, $instance) {
    // PART 1: Extracting the arguments + getting the values
    extract($args, EXTR_SKIP);
    $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
    $bloglovin_url = empty($instance['bloglovin_url']) ? '' : $instance['bloglovin_url'];
	$style_select = empty($instance['style_select']) ? '' : $instance['style_select'];
	
    // Before widget code, if any
    echo (isset($before_widget)?$before_widget:'');
   
    // PART 2: The title and the text output
    if (!empty($title)) {
		echo $before_title . $title . $after_title;
	}
	
    switch ( $style_select ) {
		case '1':
			$hide_counter_marker = '';
			break;
		case '2':
			$hide_counter_marker = 'data-blsdk-counter="false"';
			break;
	}

    if (!empty($bloglovin_url)) {
		echo '<a data-blsdk-type="button" '.$hide_counter_marker.' target="_blank" href="'.$bloglovin_url.'" class="blsdk-follow">Follow</a><script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s);js.id = id;js.src = "https://widget.bloglovin.com/assets/widget/loader.js";fjs.parentNode.insertBefore(js, fjs);}(document, "script", "bloglovin-sdk"))</script>';
	} else {
		_e("Setup not complete. Please add your  Bloglovin' URL to the Bloglovin' Button in the dashboard.", 'bloglovin-button');
	}
    // After widget code, if any
    echo (isset($after_widget)?$after_widget:'');
  }
 
  public function form( $instance ) {
   
     // PART 1: Extract the data from the instance variable
     $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
     $title = $instance['title'];
	 if (isset($instance['bloglovin_url'])) {
		$bloglovin_url = $instance['bloglovin_url'];
	 }
	 if (isset($instance['bloglovin_url'])) {
		$bloglovin_url = $instance['bloglovin_url'];
	 } else {
		 $bloglovin_url = '';
	 }
	 $style_select = ( isset( $instance['style_select'] ) && is_numeric( $instance['style_select'] ) ) ? (int) $instance['style_select'] : 1;
 
   
     // PART 2-3: Display the fields
     ?>
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:', 'bloglovin-button'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
		name="<?php echo $this->get_field_name('title'); ?>" type="text" 
		value="<?php echo esc_attr($title); ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('bloglovin_url'); ?>"><?php _e("Bloglovin' Profile URL:", 'bloglovin-button'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('bloglovin_url'); ?>" 
		name="<?php echo $this->get_field_name('bloglovin_url'); ?>" type="text" 
		value="<?php echo esc_attr($bloglovin_url); ?>" />
	</p>
	
	<p><?php _e("You should enter your full Bloglovin' URL. For example:", 'bloglovin-widget'); ?><br /><em>https://www.bloglovin.com/blogs/inthefrow-4177899</em></p>
	
	<p>
		<legend><h4><?php _e('Button style:', 'bloglovin-widget'); ?></h4></legend>
		
		<input type="radio" id="<?php echo ($this->get_field_id( 'style_select' ) . '-1') ?>" name="<?php echo ($this->get_field_name( 'style_select' )) ?>" value="1" <?php checked( $style_select == 1, true) ?>>
		<label for="<?php echo ($this->get_field_id( 'style_select' ) . '-1' ) ?>"><img src="<?php echo plugins_url( 'img/button_with_count.png', __FILE__ ) ?>" style="position:relative;top:5px;" /></label>
<br /><br />
		<input type="radio" id="<?php echo ($this->get_field_id( 'style_select' ) . '-2') ?>" name="<?php echo ($this->get_field_name( 'style_select' )) ?>" value="2" <?php checked( $style_select == 2, true) ?>>
		<label for="<?php echo ($this->get_field_id( 'style_select' ) . '-2' ) ?>"><img src="<?php echo plugins_url( 'img/button_no_count.png', __FILE__ ) ?>" style="position:relative;top:5px;" /></label>

	</p>
	
     <?php
   
  }
 
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
    $instance['bloglovin_url'] = strip_tags($new_instance['bloglovin_url']);
	$instance['style_select'] = ( isset( $new_instance['style_select'] ) && $new_instance['style_select'] > 0 && $new_instance['style_select'] < 3 ) ? (int) $new_instance['style_select'] : 0; // 3 is number above total radio options

    return $instance;
  }
  
}

add_action( 'widgets_init', create_function('', 'return register_widget("bloglovin_button_widget");') );