<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package remmember
 */

$url = get_site_url();
$name = get_bloginfo( 'name' );
$search_link = "/search";

$create_remember_page_link = "https://app.remember.bio/rp/create";
$login_remember_page_link = "https://app.remember.bio/auth/login";

$current_lang = apply_filters( 'wpml_current_language', NULL );
if($current_lang !== 'en') {
	$search_link = apply_filters( 'wpml_permalink', get_site_url() . $search_link, $current_lang,true ); 
}

?>

	<footer id="generalFooter" class="site-footer">
		<div class="site-info">
			<div class="part-1">
				<div class="wrap-search">
					<a href="<?php echo $search_link; ?>" class="wrap-serach-img">
						<img src="/wp-content/uploads/2022/04/Group-185.png" alt="">
					</a>
					<span><?php _e('Find Remember page', 'remmember'); ?></span>
				</div>
				<a class="create-link mobile-only" href="<?php echo $create_remember_page_link; ?>"><?php  _e('Create Remember page', 'remmember'); ?></a>

			</div>
			<div class="wrap-menus mobile-only">
				<div class="part-2">
					<?php wp_nav_menu( array(
							'menu' => 34
					)); ?>
				</div>
				<div class="part-3">
					<?php wp_nav_menu( array(
							'menu' => 21
					)); ?>
				</div>
			</div>
			<div class="part-2 desktop-only">
					<?php wp_nav_menu( array(
							'menu' => 34
					)); ?>
				</div>
				<div class="part-3 desktop-only">
					<?php wp_nav_menu( array(
							'menu' => 21
					)); ?>
				</div>
			<div class="part-4">
				<div class="wrap-col-content">
					<a class="create-link desktop-only" href="<?php echo $create_remember_page_link; ?>"><?php  _e('Create remember page', 'remmember'); ?></a>
					<div class="share-btns">
						<a href="<?= 'mailto:?subject=' . $url ?>" target="_blank" class="email">
							<img src="/wp-content/uploads/2022/03/Group-182.svg" alt="">
						</a>
						<a href="<?= 'https://www.facebook.com/sharer/sharer.php?u=' . $url ?>" target="_blank" class="facebook">
							<img src="/wp-content/uploads/2022/03/Group-180.svg" alt="">
						</a>
						<a href="https://www.instagram.com/?url=<?= $url ?>" target="_blank" class="instegram">
							<img src="/wp-content/uploads/2022/03/Group-181.svg" alt="">
						</a>
						<a href="<?= 'https://twitter.com/share?url=' . $url ?>" target="_blank" class="twitter">
							<img src="/wp-content/uploads/2022/03/Group-179.svg" alt="">
						</a>
					</div>
				</div>

			</div>
		
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
