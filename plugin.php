<?php
/**
 * Plugin Name: WP REST API shortcodes
 * Description: This plugin renders all shortcodes in "content" and "excerpt" fields in WP REST API response.
 * Author: evilkov
 * Author URI: https://github.com/zzeneg
 * Version 0.1
 * License: GPL2+
 **/

add_action( 'rest_api_init', 'rest_api_map_shortcodes' );

/**
 * Add a hook for page.content field
 **/
function rest_api_map_shortcodes() {
  $types = array( 'page', 'post' );
  register_rest_field($types, 'content', array( 'get_callback' => 'add_mapped_shortcodes' ));
  register_rest_field($types, 'excerpt', array( 'get_callback' => 'add_mapped_shortcodes' ));
}

/**
 * Add mapped shortcodes and return field value
 **/
function add_mapped_shortcodes( $object, $field_name, $request )
{
   WPBMap::addAllMappedShortcodes(); // This does all the work

   global $post;
   $post = get_post ($object['id']);

   $output = [];
   if ( $field_name == 'content' ) {
     $output['rendered'] = apply_filters( 'the_content', $post->post_content );
   } else if ( $field_name == 'excerpt' ) {
     $output['rendered'] = apply_filters( 'the_excerpt', $post->post_excerpt );
   }

   return $output;
}
