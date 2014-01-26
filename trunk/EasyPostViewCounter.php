<?php
/*
Plugin Name: Easy Post View Counter
Plugin URI: http://wordpress.org/extend/plugins/easy-post-views-counter/
Description: With this plugin you easiely can see how maby views each post has. Just see the post list page
Author: Michael Ringhus Gertz
Version: 1.1
Author URI: http://ringhus.dk/
*/

add_filter('the_content','EPVC_content');

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



// add column to post list
function EPVC_column_head($defaults) {
	$defaults['EPVC'] = 'EPVC';
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



// Make header column clickable
function EPVC_sort( $columns ) {
	$columns["EPVC"] = "EPVC";
	return $columns;
}
add_filter('manage_edit-post_sortable_columns','EPVC_sort');


// sort content by EPVC info
function EPVC_sort_by($vars ){
	if ( isset( $vars["orderby"] ) && "EPVC" == $vars["orderby"] ) {
		$vars = array_merge( $vars, array(
			"orderby" => "EPVC"
		) );
	}
	return $vars;
}
add_filter( "request", "EPVC_sort_by" );




?>