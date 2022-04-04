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
			<a href="<?php echo get_site_url(); ?>" class="site-branding">
				<img src="<?php the_field("remmember_item_header_logo","option"); ?>" alt="">
			</a>
			<div class="wrap-serach">
				<input type="text" placeholder="<?php  _e('Search remember page', 'remmember'); ?>">
			</div>
			<div class="wrap-btns">
				<a href="" class="login"><?php  _e('log in', 'remmember'); ?></a>
				<a href="<?php echo $create_remember_page_link;  ?>" class="create"><?php  _e('Create remeber page', 'remmember'); ?></a>
			</div>
			<?php echo custom_switcher(); ?>
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><img src="/wp-content/uploads/2022/02/Group-543.svg" alt=""></button>
		</div>
	</header><!-- #masthead -->