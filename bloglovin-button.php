<?php
/*
Plugin Name: Bloglovin Button
Plugin URI: http://wordpress.org/extend/plugins/bloglovin-button/
Version: 1.0.0
Author: pipdig
Description: Easily add the Bloglovin Button to your WordPress blog.
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
	
	switch ($style_select) {
		case '1':
			$counter = 'true';
			$button = 'button';
		break;
		case '2':
			$counter = 'false';
			$button = 'button';
		break;
		case '3':
			$counter = 'false';
			$button = '';
		break;
	}

    if (!empty($bloglovin_url)) {
		echo '<div style="text-align:center;width:98%;margin:0 auto"><a class="blsdk-follow" href="'.$bloglovin_url.'" target="_blank" rel="nofollow" data-blsdk-type="'.$button.'" data-blsdk-counter="'.$counter.'">Follow on Bloglovin</a><script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s);js.id = id;js.src = "https://widget.bloglovin.com/assets/widget/loader.js";fjs.parentNode.insertBefore(js, fjs);}(document, "script", "bloglovin-sdk"))</script></div>';
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
<br /><br />
		<input type="radio" id="<?php echo ($this->get_field_id( 'style_select' ) . '-3') ?>" name="<?php echo ($this->get_field_name( 'style_select' )) ?>" value="3" <?php checked( $style_select == 3, true) ?>>
		<label for="<?php echo ($this->get_field_id( 'style_select' ) . '-3' ) ?>"><img src="<?php echo plugins_url( 'img/bloglovin-button-full.png', __FILE__ ) ?>" style="position:relative;top:5px;" /></label>
	</p>
	
     <?php
   
  }
 
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
    $instance['bloglovin_url'] = strip_tags($new_instance['bloglovin_url']);
	$instance['style_select'] = ( isset( $new_instance['style_select'] ) && $new_instance['style_select'] > 0 && $new_instance['style_select'] < 4 ) ? (int) $new_instance['style_select'] : 0; // 4 is total radio +1
	update_option('pipdig_bloglovin_btn_url', $instance['bloglovin_url']);
    return $instance;
  }
  
}
add_action( 'widgets_init', create_function('', 'return register_widget("bloglovin_button_widget");') );


/*
function pipdig_bloglovin_button_event_setup_schedule() {
	if ( ! wp_next_scheduled( 'pipdig_bloglovin_button_daily_event' ) ) {
		wp_schedule_event( time(), 'daily', 'pipdig_bloglovin_button_daily_event'); //hourly, twicedaily or daily
	}
}
add_action( 'wp', 'pipdig_bloglovin_button_event_setup_schedule' );

function pipdig_bloglovin_button_do_this_daily() {

		$bloglovin_url = get_option('pipdig_bloglovin_btn_url');
		if($bloglovin_url) {
			$bloglovin = wp_remote_fopen($bloglovin_url); //get the html returned from the following url (was file_get_contents)
			$bloglovin_doc = new DOMDocument();
				libxml_use_internal_errors(TRUE); //disable libxml errors
				if(!empty($bloglovin)){ //if any html is actually returned
					$bloglovin_doc->loadHTML($bloglovin);
					libxml_clear_errors(); //remove errors for yucky html
					$bloglovin_xpath = new DOMXPath($bloglovin_doc);
					$bloglovin_row = $bloglovin_xpath->query('//div[@class="num"]');
					if($bloglovin_row->length > 0){
					foreach($bloglovin_row as $row){
						$followers = $row->nodeValue;
						$followers = str_replace(' ', '', $followers);
						$followers_int = intval( $followers );
						$date = date('d');
						update_option('pipdig_bloglovin_btn_count'.$date, $followers_int);
					}
				}
			}
		}

}
add_action( 'pipdig_bloglovin_button_daily_event', 'pipdig_bloglovin_button_do_this_daily' );






function pipdig_bloglovin_button_dashboard_widgets() {
	add_meta_box( 
		'pipdig_bloglovin_button_dashboard_stats',
		__("Bloglovin' Follower History", 'bloglovin-button'),
		'pipdig_bloglovin_button_dashboard_stats_function',
			'dashboard',
			'side',
			'high'
		);
}
add_action( 'wp_dashboard_setup', 'pipdig_bloglovin_button_dashboard_widgets' );


function pipdig_bloglovin_button_dashboard_stats_function() {

	$bloglovin = get_option('pipdig_bloglovin_follower_count');
	
	?>
	<?php if ($bloglovin) {	?>
		<script src="https://www.google.com/jsapi"></script>
		<script>
			google.load('visualization', '1', {packages: ['corechart', 'line']});
			google.setOnLoadCallback(socialMediaFollowers);

			function socialMediaFollowers() {
				var data = google.visualization.arrayToDataTable([
				['Channel', 'Followers', { role: 'style' }],
				<?php if ($bloglovin) { ?> ['1st', <?php echo $bloglovin; ?>, 'color: #37aeed' ], <?php } ?>
				<?php if ($bloglovin) { ?> ['2nd', <?php echo $bloglovin; ?>, 'color: #37aeed' ], <?php } ?>
				<?php if ($bloglovin) { ?> ['3rd', <?php echo $bloglovin; ?>, 'color: #37aeed' ], <?php } ?>
				<?php if ($bloglovin) { ?> ['4th', <?php echo $bloglovin; ?>, 'color: #37aeed' ], <?php } ?>
				]);

				  var options = {
					title: '',
					chartArea: {width: '64%'},
					hAxis: {
					  title: '',
					  minValue: 0,
					},
					vAxis: {
					  title: '',
					},
					legend: 'none',
				  };

				  var chart = new google.visualization.BarChart(document.getElementById('pipdig_bloglovin_button_stats_graph'));
				  chart.draw(data, options);
			}
			
		</script>
			<div id="pipdig_bloglovin_button_stats_graph" style="width: 400px; height: 120px;"></div>
			<?php if ($bloglovin) { ?><p><strong>Bloglovin:</strong> <?php echo $bloglovin; ?></p><?php } ?>
		<?php
	} //end if ($total)
}

*/