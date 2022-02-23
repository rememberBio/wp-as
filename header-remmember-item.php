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
			<div class="site-branding">
				<img src="<?php the_field("remmember_item_header_logo","option"); ?>" alt="">
				
			</div>
			<nav id="site-navigation" class="main-navigation">
				<div class="menu-menu-1-container">
					<ul id="primary-menu" class="menu nav-menu">
						<li id="menu-item-1" class="menu-item <?php if(!$current_tab || $current_tab == '') echo("current"); ?>"><a href="<?= $url ?>">Main</a></li>
						<li id="menu-item-2" class="menu-item <?php if($current_tab == 'about') echo("current"); ?>"><a href="<?= $url ?>?tab=about">About</a></li>
						<li id="menu-item-3" class="menu-item <?php if($current_tab == 'stories') echo("current"); ?>"><a href="<?= $url ?>?tab=stories">Stories</a></li>
						<li id="menu-item-4" class="menu-item <?php if($current_tab == 'gallery') echo("current"); ?>"><a href="<?= $url ?>?tab=gallery">Gallery</a></li>
						<li id="menu-item-5" class="menu-item <?php if($current_tab == 'comments') echo("current"); ?>"><a href="<?= $url ?>?tab=comments">Comments</a></li>
						<li id="menu-item-6" class="menu-item <?php if($current_tab == 'places-of-commemoration') echo("current"); ?>"><a href="<?= $url ?>?tab=places-of-commemoration">Places Of Commemoration</a></li>
						<li id="menu-item-7" class="menu-item <?php if($current_tab == 'candle-and-flowers') echo("current"); ?>"><a href="<?= $url ?>?tab=candle-and-flowers">Candle And Flowers</a></li>
						<li id="menu-item-8" class="menu-item <?php if($current_tab == 'the-grave') echo("current"); ?>"><a href="<?= $url ?>?tab=the-grave">The Grave</a></li>
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
        image: "<?= url() . '/wp-content/uploads/2022/02/Group-955.svg' ?>",
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