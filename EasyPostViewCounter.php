<?php
/*
Plugin Name: Easy Post View Counter
Plugin URI: http://wordpress.org/extend/plugins/easy-post-views-counter/
Description: With this plugin you easily can see how many views each post has. Just see the post list page
Author: Michael Ringhus Gertz
Version: 1.2.2
Author URI: http://ringhus.dk/
*/

session_start();


function myinit () {
	// Get PostID
	$postid = url_to_postid("http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);

	// Cookie Name,
	$cookiename = "EasyPostViewCounter_".$postid;

  	// Chech if the post has been loaded before, after the browser was opened.
	// 1 = first load of post, 2 = second load of post
	// If session is empty, its because it hasnt been loaded before.
	if( $_SESSION[$cookiename] == "" ) {
		$_SESSION[$cookiename] = "1";
	} else {
		$_SESSION[$cookiename] = "2";
	}
}
add_action('init','myinit');



/* This functions is used for tracking when a post is viewed. */
function EPVC_Content($content) {

	$postid = get_the_id();
	$key = "EasyPostViewCounter";
	$cookiename = $key."_".$postid;
	$count = get_post_meta($postid,$key,true);

    // Check if its a single, and the users isnt an admin
	if( is_single() AND !current_user_can('manage_options') ) {

     	// Check if the session is set to 1. if so the post has to be counted.
		if( $_SESSION[$cookiename] == "1" ) {
			 if( empty($count) ) {
    			add_post_meta($postid,$key,'1');
    		} else {
    			$count++;
    			update_post_meta($postid,$key,$count);
    		}
		}
	}

	return $content;
}
add_filter('the_content','EPVC_content');


// add column to post list
function EPVC_column_head($defaults) {
	$defaults['EPVC'] = 'Views';
	return $defaults;
}
add_filter('manage_posts_columns','EPVC_column_head');



// add content to the column
function EPVC_colums_content($column_name,$postid) {
	if( $column_name == 'EPVC' ) {
		$count = get_post_meta($postid,'EasyPostViewCounter',true);
		if( empty($count) ) {
			echo "0";
		} else {
			echo $count;
		}
	}
}
add_action('manage_posts_custom_column','EPVC_colums_content',10,2);
?>