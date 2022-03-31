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

 $url = get_permalink();
 $name = get_the_title();

$current_tab = "";
if(isset($_GET["tab"])) { 
    $current_tab = $_GET["tab"];
}


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

	<header id="masthead" class="site-header remmember-item">
		<div class="wrap-header-content">
			<a href="<?php echo get_site_url(); ?>" class="site-branding">
				<img src="<?php the_field("remmember_item_header_logo","option"); ?>" alt="">
				
			</a>
			<nav id="site-navigation" class="main-navigation">
				<div class="menu-menu-1-container">
					<ul id="primary-menu" class="menu nav-menu">
						<li id="menu-item-1" class="menu-item <?php if(!$current_tab || $current_tab == '') echo("current"); ?>"><a href="<?= $url ?>"><?php  _e('Main', 'remmember'); ?></a></li>
						<li id="menu-item-2" class="menu-item <?php if($current_tab == 'about') echo("current"); ?>"><a href="<?= $url ?>?tab=about"><?php  _e('About', 'remmember'); ?></a></li>
						<li id="menu-item-3" class="menu-item <?php if($current_tab == 'stories') echo("current"); ?>"><a href="<?= $url ?>?tab=stories"><?php  _e('Stories', 'remmember'); ?></a></li>
						<li id="menu-item-4" class="menu-item <?php if($current_tab == 'gallery') echo("current"); ?>"><a href="<?= $url ?>?tab=gallery"><?php  _e('Gallery', 'remmember'); ?></a></li>
						<li id="menu-item-5" class="menu-item <?php if($current_tab == 'comments') echo("current"); ?>"><a href="<?= $url ?>?tab=comments"><?php  _e('Comments', 'remmember'); ?></a></li>
						<li id="menu-item-6" class="menu-item <?php if($current_tab == 'places-of-commemoration') echo("current"); ?>"><a href="<?= $url ?>?tab=places-of-commemoration"><?php  _e('Places Of Commemoration', 'remmember'); ?></a></li>
						<li id="menu-item-7" class="menu-item <?php if($current_tab == 'candle-and-flowers') echo("current"); ?>"><a href="<?= $url ?>?tab=candle-and-flowers"><?php  _e('Candle And Flowers', 'remmember'); ?></a></li>
						<li id="menu-item-8" class="menu-item <?php if($current_tab == 'the-grave') echo("current"); ?>"><a href="<?= $url ?>?tab=the-grave"><?php  _e('The Grave', 'remmember'); ?></a></li>
					<?php 
					$count_row = 1;
					$header_links = get_field("header-links","option");
					foreach ( $header_links as $header_link) { ?>
						<li id="menu-item-<?= $count_row ?>" class="menu-item <?php if($current_tab == $header_link['tab-name']) echo("current"); ?>"><a href="<?= $url ?>"><?= $header_link['text'] ?></a></li>
					<?php $count_row  = $count_row  + 1; } ?>
					</ul>
				</div>
				
			</nav><!-- #site-navigation -->

			<div class="share-btn">
				<a href="<?= 'mailto:?subject=' . $url ?>" target="_blank" class="email">
					<img src="/wp-content/uploads/2022/02/Group-133.svg" alt="">
				</a>
				<a href="<?= 'https://www.facebook.com/sharer/sharer.php?u=' . $url ?>" target="_blank" class="facebook">
					<img src="/wp-content/uploads/2022/02/Group-134.svg" alt="">
				</a>
				<a href="https://www.instagram.com/?url=<?= $url ?>" target="_blank" class="instegram">
					<img src="/wp-content/uploads/2022/02/Group-135.svg" alt="">
				</a>
				<a href="<?= 'https://twitter.com/share?url=' . $url ?>" target="_blank" class="twitter">
					<img src="/wp-content/uploads/2022/02/Group-129.svg" alt="">
				</a>
				<a href="" onClick="downloadQrCode(event)" id="qr-code-download">
					<img src="/wp-content/uploads/2022/02/Group-955.svg" alt="">
				</a>
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><img src="/wp-content/uploads/2022/02/Group-543.svg" alt=""></button>
			</div>
			<div id="qr-code-container" style="display:none;"></div>
		</div>
	</header><!-- #masthead -->
<script type="text/javascript">

    const qrCode = new QRCodeStyling({
        width: 300,
        height: 300,
        type: "png",
        data: "<?= $url ?>",
        image: "<?= url() . '/wp-content/uploads/2022/03/Group-961-1.svg' ?>",
        dotsOptions: {
            color: "#022855",
            type: "rounded"
        },
        backgroundOptions: {
            color: "#ffffff",
        },
        imageOptions: {
            crossOrigin: "anonymous",
            margin: 20
        }
    });
    //place it on the screen
    qrCode.append(document.getElementById("qr-code-container"));
	function downloadQrCode(event) {
		event.preventDefault();
    	//download the generate image of the QR code
    	qrCode.download({ name: "qr", extension: "png" });
	}
	jQuery(document).ready(($) => {
		$(".menu-toggle").click(function(){
			$("#site-navigation").toggleClass("toggled");
		});
	});
</script>

<div class="wrap-remmember-page">