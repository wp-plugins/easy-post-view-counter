<?php
/*
Plugin Name: Easy Post View Counter
Plugin URI: http://wordpress.org/extend/plugins/easy-post-views-counter/
Description: With this plugin you easily can see how many views each post has. Just see the post list page
Author: Michael Ringhus Gertz
Version: 1.2.1
Author URI: http://ringhus.dk/
*/


/* This functions is used for tracking when a post is viewed. */
function EPVC_Content($content) {
	$key = "EasyPostViewCounter";
	$postid = get_the_id();
	$count = get_post_meta($postid,$key,true);

	if( is_single() AND !current_user_can('manage_options') ) {
		if( empty($count) ) {
			add_post_meta($postid,$key,'1');
		} else {
			$count++;
			update_post_meta($postid,$key,$count);
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


/*
// THIS FUNCTION HAS BEEN REMOVE, MAYBE IT WILL GET BACK IN LATER
// Make header column clickable
function EPVC_sort( $columns ) {
	$columns["EPVC"] = "EPVC";
	return $columns;
}
add_filter('manage_edit-post_sortable_columns','EPVC_sort');
*/


/*
// THIS FUNCTION HAS BEEN REMOVE, MAYBE IT WILL GET BACK IN LATER
// sort content by EPVC info
function EPVC_sort_by( $vars ){

        echo "vars";
    	print_r($vars);



	if ( isset( $vars["orderby"] ) && $vars["orderby"] == "EPVC" ) {
        $vars = array_merge( $vars, array(
			"orderby" => "EPVC"
		) );
	}
    
    print_r($vars);
	return $vars;
}
add_filter( "request", "EPVC_sort_by" );
*/



?>