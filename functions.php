<?php
/**
 * remmember functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package remmember
 */

define('WP_THEME_URI', get_template_directory_uri());
 
function url(){
	return sprintf(
		"%s://%s",
		isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : ""
	);
}

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function remmember_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on remmember, use a find and replace
		* to change 'remmember' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'remmember', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'remmember' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'remmember_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'remmember_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function remmember_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'remmember_content_width', 640 );
}
add_action( 'after_setup_theme', 'remmember_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function remmember_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'remmember' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'remmember' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'remmember_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function remmember_scripts() {

	$template_name  = basename(get_page_template());

	wp_enqueue_style( 'remmember-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'remmember-style', 'rtl', 'replace' );

	wp_enqueue_script( 'remmember-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'remmember-general', get_template_directory_uri() . '/assets/js/general.js', array(), _S_VERSION, true );

	if ($template_name == 'search-template.php') {
		//google_maps_scripts();
		wp_enqueue_style ( 'search-css', WP_THEME_URI . '/assets/css/search.css' );

	}
	else if ($template_name == 'search-results-template.php' ||  is_category()) {
		wp_enqueue_style ( 'search-results-css', WP_THEME_URI . '/assets/css/search-results.css' );
	}
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	if(is_front_page() || is_home()) {
		wp_enqueue_style ( 'home-css', WP_THEME_URI . '/assets/css/home.css' );
		wp_enqueue_script( 'remember-item-js', WP_THEME_URI . '/assets/js/home.js' );
		slick_slider_scripts();
	}
	if(is_front_page() || is_home() || is_single() && 'remmember_page' == get_post_type()) {
		wp_enqueue_script( 'custom-lazy-loading', WP_THEME_URI . '/assets/js/lazy-loading.js' );
	}
	if(is_single() && 'remmember_page' == get_post_type()) { 
		wp_enqueue_script( 'qr_js', 'https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js');

		wp_enqueue_script( 'remember-item-js', WP_THEME_URI . '/assets/js/remember-page.js' );
	    wp_enqueue_style ( 'remember-item', WP_THEME_URI . '/assets/css/single-remember/general.css' );
		
		google_maps_scripts();

		$current_tab = "";
		if(isset($_GET["tab"])) { 
			$current_tab = $_GET["tab"];
		}
		if($current_tab != "")  {
			wp_enqueue_style ( 'remember-item-' . $current_tab , WP_THEME_URI . '/assets/css/single-remember/' . $current_tab .'.css' );
		} 
		else { //Main tab
			slick_slider_scripts();
			wp_enqueue_style ( 'remember-item-main', WP_THEME_URI . '/assets/css/single-remember/main.css' );
		} 
	}

}
add_action( 'wp_enqueue_scripts', 'remmember_scripts' );


function google_maps_scripts() {
	wp_enqueue_script( 'google-maps-js', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAk5oOe33Nmwh6X_PbL13W50atji4wcUfo&libraries=places');
	wp_enqueue_script( 'custom-google-maps-js', WP_THEME_URI . '/assets/js/google-maps.js' ); 
}
function slick_slider_scripts() {
	wp_enqueue_style('slick-slider-css-1', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
	wp_enqueue_style('slick-slider-css-2', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css');
	wp_enqueue_script('slick-slider-js', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js');
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

require  get_template_directory() . '/inc/remmember-item-functions.php';

require  get_template_directory() . '/inc/custom-woocommerce.php';

require  get_template_directory() . '/inc/stripe-payments.php';

require  get_template_directory() . '/inc/sendinblue-functions.php';

require  get_template_directory() . '/inc/data-base-functions.php';

require  get_template_directory() . '/inc/wpml-functions.php';

require  get_template_directory() . '/inc/search-functions.php';

require  get_template_directory() . '/inc/custom-date-functions.php';

//Add custom type
function create_posttypes() {
	//post type
	///remmeber items
	register_post_type( 'remmember_page',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'Remember Pages' ),
				'singular_name' => __( 'Remember Page' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'remember_pages'),
			'show_in_rest' => true,
			'rest_base'             => 'remember_page',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'taxonomies'          => array('category' ),
			'supports' => array(
				'title',
				'comments'
			)
		)
	);

	///remember dynamic product
	register_post_type( 'dynamic_products',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'Dynamic Products' ),
				'singular_name' => __( 'dynamic product' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'dynamic_products'),
			'show_in_rest' => true,
			'rest_base'             => 'dynamic_products',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			//'taxonomies'          => array('category' ),
			'supports' => array(
				'title',
				'comments'
			)
		)
	);
  
  }
  // Hooking up our function to theme setup
  add_action( 'init', 'create_posttypes' );

//Add custom post type to category php
function namespace_add_custom_types( $query ) {
	if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
		$query->set( 'post_type', array(
			'post', 'nav_menu_item', 'remmember_page'
		));
		return $query;
	}
}
  add_filter( 'pre_get_posts', 'namespace_add_custom_types' );
//Add option page

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}

// register api key for google map
function my_acf_google_map_api( $api ){
	$api['key'] = 'AIzaSyAk5oOe33Nmwh6X_PbL13W50atji4wcUfo';
	return $api;
}

//filter breadcrumbs arrow
function filter_wpseo_breadcrumb_separator($this_options_breadcrumbs_sep) {
    return '<span class="breadcrumb_arrow">></span>';
};

// add the filter
add_filter('wpseo_breadcrumb_separator', 'filter_wpseo_breadcrumb_separator', 10, 1);
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');
//Allow SVG
function cc_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
  }
  add_filter('upload_mimes', 'cc_mime_types');
  /**
   * uncommented only when upload svg to media, because is security issue 
   * !!!!
   */
  define(ALLOW_UNFILTERED_UPLOADS, true);
