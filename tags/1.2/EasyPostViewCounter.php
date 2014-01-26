<?php
/*
Plugin Name: Easy Post View Counter
Plugin URI: http://wordpress.org/plugins/easy-post-view-counter

Description: With this plugin you easiely can see how maby views each post has. Just see the post list page
Author: Michael Ringhus Gertz
Version: 1.2
Author URI: http://ringhus.dk/
*/

add_filter('the_content','EPVC_content');

function EPVC_Content($content) {
	$key = "EasyPostViewCounter";
	$postid = get_the_id();
	$count = get_post_meta($postid,$key,true);

	if( is_single() AND !current_user_can('manage_options') ) {
		$count++;
		update_post_meta($postid,$key,$count);
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
			echo "0 - No views yet";
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
function EPVC_sort_by( $vars ) {
    if ( isset( $vars['orderby'] ) && 'EPVC' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'EasyPostViewCounter',
            'orderby' => 'EasyPostViewCounter'
        ) );
    }
 
    return $vars;
}
add_filter( 'request', 'EPVC_sort_by' );




// This part is executed when plugin is being activated.
function EPVC_Activation() {
	$key = "EasyPostViewCounter";

	$posts = get_posts();
	foreach( $posts as $p) {
		$postID = $p->ID;
		$count = get_post_meta($postid,$key,true);

		if( empty($count) ) {
			add_post_meta($postID,$key,'0');
		}
	}
}

register_activation_hook(__FILE__,'EPVC_Activation');







?>