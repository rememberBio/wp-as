<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package remmember
 */
$create_remember_page_link = "";
$search_link = "/search";
$site_link = get_site_url();

$current_lang = apply_filters( 'wpml_current_language', NULL );
if($current_lang !== 'en') {
    $site_link = $site_link . "/" . $current_lang;
	$search_link = apply_filters( 'wpml_permalink', get_site_url() . "/" . $search_link, $current_lang,true ); 
}

$is_home = false;
if(is_front_page() || is_home()) $is_home = true;

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'remmember' ); ?></a>

	<header id="generalHeader" class="site-header">
		<div class="wrap-header-content">
			<a href="<?php echo $site_link; ?>" class="site-branding">
				<img src="<?php the_field("remmember_item_header_logo","option"); ?>" alt="">
			</a>
			<div class="wrap-header-btns">
				<a href="<?php echo $search_link; ?>" class="wrap-serach-img">
					<img src="<?php if($is_home) { echo '/wp-content/uploads/2022/04/Group-185.png'; } else { echo '/wp-content/uploads/2022/04/search-3.svg'; } ?>" alt="">
				</a>
				<div class="wrap-btns desktop-only">
					<a href="" class="login"><?php  _e('log in', 'remmember'); ?></a>
					<a href="<?php echo $create_remember_page_link;  ?>" class="create"><?php  _e('Create remeber page', 'remmember'); ?></a>
				</div>
				<a href="" class="login mobile-only">
					<img src="<?php if($is_home) { echo '/wp-content/uploads/2022/04/user-1.svg'; } else { echo '/wp-content/uploads/2022/05/user-2.svg'; } ?>" alt="">
				</a>
				<?php echo custom_switcher(); ?>
				<button class="menu-toggle-header" aria-controls="primary-menu" aria-expanded="false">
					<img src="<?php if($is_home) { echo '/wp-content/uploads/2022/04/menu.svg'; } else { echo '/wp-content/uploads/2022/04/menu-3.svg'; } ?>" alt="">
				</button>
				<nav id="site-navigation" class="main-navigation">

					<div class="menu-menu-1-container">
						<ul id="primary-menu" class="menu nav-menu">
							<span class="close-menu mobile-only"><img src="/wp-content/uploads/2022/02/Group-956.svg" alt=""></span>
							<li id="menu-item-1" class="menu-item"><a href="<?php echo $search_link; ?>"><?php  _e('Find Remember Page', 'remmember'); ?></a></li>
							<li id="menu-item-2" class="menu-item"><a href="<?php echo $create_remember_page_link; ?>"><?php  _e('Creat A New Page', 'remmember'); ?></a></li>
							<li id="menu-item-3" class="menu-item"><a href="<?php echo $site_link; ?>"><?php  _e('Home', 'remmember'); ?></a></li>
							<li id="menu-item-4" class="menu-item"><a href=""><?php  _e('About Us', 'remmember'); ?></a></li>
							<li id="menu-item-5" class="menu-item"><a href=""><?php  _e('Help', 'remmember'); ?></a></li>
							<li id="menu-item-6" class="menu-item"><a href=""><?php  _e('Contact Us', 'remmember'); ?></a></li>
							<div class="wrap-btns mobile-only">
								<a href="" class="login"><?php  _e('log in', 'remmember'); ?></a>
								<a href="<?php echo $create_remember_page_link;  ?>" class="create"><?php  _e('Create remeber page', 'remmember'); ?></a>
							</div>
						</ul>
					</div>
					
				</nav><!-- #site-navigation -->
			</div>
		</div>
	</header><!-- #masthead -->

<script>
	jQuery(document).ready(($) => {
		$(".menu-toggle-header").click(function(){
			$("#site-navigation").toggleClass("toggled");
		});
		$(".close-menu").click(function(){
			$("#site-navigation").removeClass("toggled");
		});
	});
</script>