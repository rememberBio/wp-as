<?php 
get_header();

$create_remember_page_link = "";
//get custom fields from option page
//hero
$hero_img = get_field("home_hero_background_image","option");
$hero_img_mobile = get_field("home_hero_background_image_mobile","option");
if(!$hero_img_mobile || $hero_img_mobile == "") $hero_img_mobile = $hero_img;
$hero_head = get_field("home_hero_main_head","option");
$hero_text = get_field("home_hero_text","option");
$hero_link_text = get_field("home_hero_link_text","option");

//how it work
$how_repeater = get_field("home_how_it_works_items","option");
 // image,text,link_text

//about us
$about_video = get_field("home_about_us_video","option");

//examples 
$ex_items = get_field("home_example_of_remember_pages_items","option");
    //image, name,link

//our services
$services_items = get_field("home_our_services_items","option");

//say about us
$say_about_items = get_field("home_say_about_us__items","option");
?>

<style>
    @media screen and (max-width: 1100px) { 
        section#hero {
            background-image:url(<?php echo($hero_img_mobile); ?>) !important;
        }
    }
</style>

<div class="wrap-home-page">
    <section id="hero" class="hero" style="background-image:url(<?php echo($hero_img); ?>)">
        <div class="wrap-content">
            <h1><?php echo($hero_head); ?></h1>
            <p><?php echo($hero_text); ?></p>
            <a href="<?php echo $create_remember_page_link; ?>"><?php echo($hero_link_text); ?></a>
        </div>
    
    </section>
    <section id="howWork" class="how-it-works">
        <h2><?php  _e('How It Works', 'remmember'); ?></h2>
        <div class="wrap-content" id="howSlider">
            <?php foreach ($how_repeater as $item) { ?>
                <div class="wrap-how-item">
                    <div class="left">
                        <img class="lazy" src="" data-srcset="<?php echo($item['image']); ?>" alt="">
                    </div>
                    <div class="right">
                        <p><?php echo($item['text']); ?></p>
                        <a href="<?php echo $create_remember_page_link; ?>"><?php echo($item['link_text']); ?></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
    <section id="about" class="about-us">
        <h2><?php  _e('About Us', 'remmember'); ?></h2>
        <div class="wrap-content">
            <video controls class="lazy" >
                <source src="" data-src="<?php echo($about_video); ?>">
            </video>
        </div>
    </section>
    <section id="examples" class="example-of-remember-pages">
        <h2><?php _e('Example Of Remember Pages', 'remmember'); ?></h2>
        <div class="wrap-content">
            <?php foreach ($ex_items as $item) { 
                $item_post_id = $item['link'];
                $item_permalink = get_permalink($item_post_id);
            ?>
                <a href="<?php echo($item_permalink); ?>" class="wrap-ex-item">
                    <img class="lazy" src="" data-srcset="<?php echo($item['image']); ?>" alt="">
                    <p class="ex_name"><?php echo($item['name']); ?></p>
                </a>
            <?php } ?>
            <a href=""><?php  _e('View all', 'remmember'); ?></a>
        </div>
    </section>
    <section id="services" class="our-services">
        <h2><?php  _e('Our Services', 'remmember'); ?></h2>
        <div class="wrap-content">
            <?php foreach ($services_items as $item) { ?>
                <div class="wrap-service-item">
                    <div class="top lazy" style="background-image:url(<?php echo($item['image']); ?>)"></div>
                    <p class="text"><?php echo($item['text']); ?></p>
                </div>
            <?php } ?>
            <a href="<?php $create_remember_page_link ?>"><?php  _e('Create remember page', 'remmember'); ?></a>
        </div>
    </section>
    <section id="sayAbout" class="say-about-us">
        <h2><?php  _e('Say About Us', 'remmember'); ?></h2>
        <div class="wrap-content" id="sayAboutSlider">
            <?php foreach ($say_about_items as $item) { ?>
                <div class="wrap-say-about-item">
                    <div class="wrap-inner-say-about">
                        <div class="top">
                            <div class="image lazy" style="background-image:url(<?php echo($item['telling_image']); ?>)"></div>
                            <span class="name"><?php echo($item['telling_name']); ?></span>
                            <span class="desc"><?php echo($item['telling_desc']); ?></span>
                        </div>
                        <div class="bottom">
                            <p><?php echo($item['text']); ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
</div>
<?php get_footer(); ?>