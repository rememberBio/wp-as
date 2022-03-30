<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package remmember
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function remmember_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'remmember_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function remmember_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'remmember_pingback_header' );

//change image's from acf url to bunny.net url
function dome_acf_format_value( $value, $post_id, $field ) {

    $domain = $_SERVER['SERVER_NAME'];
    if(is_array($value)){
		//replace webp
        //$value['url'] = str_replace('.png', '.webp', $value['url']);
        //$value['url'] = str_replace('.jpg', '.webp', $value['url']);
        if(isset($value['sizes']) && !empty($value['sizes'])){
            foreach($value['sizes'] as $key=>$size){
				//replace webp
                //$value['sizes'][$key] = str_replace('.png', '.webp', $value['sizes'][$key]);
                //$value['sizes'][$key] = str_replace('.jpg', '.webp', $value['sizes'][$key]);
            }
        }
    } else {
		//replace webp
        //$value = str_replace('.png', '.webp', $value);
        //$value = str_replace('.jpg', '.webp', $value);
    }
	return $value;
}
//add_filter('acf/format_value/type=image', 'dome_acf_format_value',20,3);