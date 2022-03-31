<?php 

$post_id = get_the_ID();
$url = get_permalink();

/* get custom fileds and other section variables */

//hero
$hero_img = get_field("main_image_of_the_deceased",$post_id);
$hero_name = get_field("full_name_of_the_deceased",$post_id);
$hero_desc = get_field("a_few_words_about_the_deceased",$post_id);
$remember_too_text = get_field("want_to_remmember_text_for_home","option");

//candles and flowers
//get candles and flowers to this post and other translated;
$pages_ids = get_all_translated_post_ids($post_id); 
$candles_flowers = db_get_remember_pages_payments($pages_ids);
$num_of_candles = 0;
$num_of_flowers = 0;

if($candles_flowers && is_array($candles_flowers)) {
    //functions for array filter
    function is_candle($value)
    {
        return $value->type == 'candle';
    }

    function is_flower($value)
    {
        return $value->type == 'flower';
    }

    $candles = array_filter($candles_flowers,"is_candle");
    if(is_array($candles)) $num_of_candles = count($candles);
    $flowers = array_filter($candles_flowers,"is_flower");
    if(is_array($flowers)) $num_of_flowers = count($flowers);
}

//about
$about_desc = get_field("about_description",$post_id);
$about_country = get_field("about_country",$post_id);
$about_parents = get_field("about_parents",$post_id);
$about_children = get_field("about_children",$post_id);
$about_birthday = get_field("about_birth_day",$post_id);
$about_day_of_death = get_field("about_death_day",$post_id);
$about_spouse = get_field("about__-_husband__wife",$post_id);

//stories
$main_stories = get_field("stories_repeater",$post_id);

//gallery
$gallery_items = get_field("gallery_items",$post_id);
//comments
$comments = get_remember_post_comments($post_id);

//places
$places = get_field("places_list",$post_id);

//the tomb
$google_maps_details = get_field("the_grave_in_google_maps",$post_id);
if($google_maps_details) {
    $tomb_country = $google_maps_details['country'];
    $tomb_city = trim($google_maps_details['city']);
    $tomb_street =  trim($google_maps_details['street_name'] .' '. $google_maps_details['street_number']);
    $tomb_address_name = trim($google_maps_details['address']);
}

?>
<section class="hero main-hero">
    <div class="wrap-left-hero">
        <div class="text desktop-only">
            <h1 class="name"><?= $hero_name ?></h1>
            <span class="desc"><?= $hero_desc ?></span>
            <a href="<?= $url . '/?tab=about' ?>"><?php _e('Read More >>','remmember') ?></a>
        </div>
        <div class="form">
            <div class="wrap-form-footer">
                <span class="text"><?=  $remember_too_text ?></span>
                <form method="post" name="registerForm" id="registerForm" action="">
                    <input type="email" name="email" value="" id="email" placeholder="<?php  _e('enter your email', 'remmember'); ?>" >
                    <button type="submit"><img src="/wp-content/uploads/2022/03/remember-1.svg" alt=""></button>
                </form>
            </div>
        </div>
    </div>
    <div class="mobile-only">
        <div class="text mobile-only">
            <h1 class="name"><?= $hero_name ?></h1>
            <span class="desc"><?= $hero_desc ?></span>
            <a href="<?= $url . '/?tab=about' ?>"><?php _e('Read More >>') ?></a>
        </div>
        <img class="main-hero-img" src="<?php echo $hero_img; ?>" alt="">
    </div>
    <img class="main-hero-img desktop-only" src="<?php echo $hero_img; ?>" alt="">

</section>
<section class="main-candles-and-flowers">
    <a href="<?php echo $url . '/?tab=candle-and-flowers' ?>" class="write-candles-flowers-btn">
        <img src="/wp-content/uploads/2022/03/Group-963.svg" alt="">
        <div class="right-btn">
            <span class="num"><?php echo $num_of_candles; ?></span>
            <span class="desc"><?php _e('Candles', 'remmember'); ?></span>
            <span class="text"><?php _e('have been lit until now', 'remmember'); ?></span>
            <div class="main-cf-btn pointer"><?php _e('light a candle', 'remmember'); ?></div>
        </div>
    </a>
    <a href="<?php echo $url . '/?tab=candle-and-flowers' ?>" class="write-candles-flowers-btn">
        <img src="/wp-content/uploads/2022/03/Group-965.svg" alt="">
        <div class="right-btn">
            <span class="num"><?php echo $num_of_flowers; ?></span>
            <span class="desc"><?php _e('Flowers', 'remmember'); ?></span>
            <span class="text"><?php _e('have been Sent until now', 'remmember'); ?></span>
            <div class="main-cf-btn"><?php _e('send a flower', 'remmember'); ?></div>
        </div>
    </a>
</section>
<?php if($about_country && $about_country !== "" || $about_day_of_death && $about_day_of_death !== "" || $about_birthday && $about_birthday !== "" ||  $about_children && count($about_children) > 0 || $about_parents && count($about_parents) > 0) { ?>
<section class="main-about">
    <h2 class="main-heading"><?php _e('About', 'remmember'); ?></h2>
    <div class="wrap-content">
        <p class="text"><?php echo($about_desc); ?></p>
        <div class="wrap-dates">
            <?php if($about_birthday && $about_birthday !== "") { ?>
            <a href="<?= $url . '/?tab=about' ?>" class="date">
                <span class="date-desc"><?php _e('Date of birth:', 'remmember'); ?></span>
                <span class="year"><?php echo($about_birthday); ?></span>
            </a>
            <?php } if($about_day_of_death && $about_day_of_death !== "") { ?>
            <a href="<?= $url . '/?tab=about' ?>" class="date">
                <span class="date-desc"><?php _e('Date of death:', 'remmember'); ?></span>
                <span class="year"><?php echo($about_day_of_death); ?></span>
            </a>
            <?php } ?>
        </div>
        <div class="second-part">
            <?php if($about_country && $about_country !== "") { ?>
            <div class="circle country">
                <img src="/wp-content/uploads/2022/02/Group-106.svg" alt="">
                <h3><?php _e('Country:', 'remmember'); ?></h3>
                <span><?php echo($about_country); ?></span>
            </div>
            <?php } if($about_spouse && $about_spouse['hasband_or_wife'] ) {  ?>
                <div class="circle spouse">
            <?php $has_link = false;
            if($about_spouse['link_to_the_spouses_remember_page']) { 
                $has_link = get_permalink( $about_spouse['link_to_the_spouses_remember_page'] );
            } ?>
            <?php if($spouse['hasband_or_wife'] == 'husband') { ?>
                    <img src="/wp-content/uploads/2022/03/husband.svg" alt="">
                    <h3><?php _e('Husband:', 'remmember'); ?></h3>
                    <?php if($has_link) {  echo '<a href="' . $has_link .'">'; }?>
                    <span><?php echo($about_spouse['husband_name']); ?></span>
                    <?php if($has_link) { echo("</a>"); } ?>
            <?php } else { ?>
                    <img src="/wp-content/uploads/2022/03/wife.svg" alt="">
                    <h3><?php _e('Wife:', 'remmember'); ?></h3>
                    <?php if($has_link) {  echo '<a href="' . $has_link .'">'; }?>
                        <span><?php echo($about_spouse['wifes_name']); ?></span>
                    <?php if($has_link) { echo("</a>"); } ?>
            <?php } ?>
            </div>

            <?php } if($about_parents && count($about_parents) > 0 ) {  ?>
            <div class="circle parents">
                <img src="/wp-content/uploads/2022/02/Group-109.svg" alt="">
                <h3><?php _e('Parents:', 'remmember'); ?></h3>
                <div class="wrap-parents flex">
                    <?php foreach ($about_parents as $parent) { 
                        if($parent['link']) {
                            $parent_link = get_permalink($parent['link']);
                        }
                    ?>
                    <?php if($parent_link) {  echo '<a href="' . $parent_link .'">'; }?>
                        <span><?php echo($parent['name_of_parent']); ?></span>
                    <?php if($parent_link) { echo("</a>");  $parent_link = null; } ?>
                    <?php } ?>
                </div>
            </div>
            <?php } if($about_children && count($about_children) > 0) { ?>
            <div class="circle children">
                <img src="/wp-content/uploads/2022/02/Group-110.svg" alt="">
                <h3><?php _e('Children:', 'remmember'); ?></h3>
                <div class="wrap-children flex">
                    <?php foreach ($about_children as $child) { 
                        if($child['link']) {
                            $child_link = get_permalink($child['link']);
                        }
                    ?>
                        <?php if($child_link) {  echo '<a href="' . $child_link .'">'; }?>
                        <span><?php echo($child['name_of_child']); ?></span>
                        <?php if($child_link) { echo("</a>");  $child_link = null; } ?>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="wrap-bottom-link-main">
        <a href="<?= $url . '/?tab=about' ?>" class="main-link-main"><?php _e('Read More >', 'remmember'); ?></a>
    </div>
</section>
<?php } ?>
<?php if($main_stories && count($main_stories) > 0) {  ?>
<section class="main-stories">
    <h2 class="main-heading"><?php _e('Stories', 'remmember'); ?></h2>
    <div class="wrap-content">
        <?php 
        $stories_count = 0;
        foreach ($main_stories as $story) { 
                //take excerpt of story text
                if( $stories_count > 3 ) break;
                $stories_count =  $stories_count + 1;

                $text = $story['text'];
                $count = count(explode(" ",$text));
                $pres_count = 100;
                if($count > 1) {
                    $pres_count =  ( $count / 3 );
                }
        ?>
            <a href="<?= $url . '/?tab=stories' ?>" class="wrap-story">
                <p class="short-text"><?php echo(wp_trim_words($story['text'], $pres_count, '...')); ?></p>
                <span class="read-more"><?php _e('Read >', 'remmember'); ?></span>
            </a>
        <?php } ?>
    </div>
    <div class="wrap-bottom-link-main">
        <a href="<?= $url . '/?tab=stories' ?>" class="main-link-main"><?php _e('More Stories >', 'remmember'); ?></a>
    </div>
</section>
<?php } ?>
<?php if($gallery_items && count($gallery_items) > 0) {  ?>
<section class="main-gallery">
    <h2 class="main-heading"><?php _e('Gallery', 'remmember'); ?></h2>
    <div class="wrap-content" id="galley-main-slider">
        <?php foreach ($gallery_items as $gallery_item) {  
            $albums = $gallery_item['albums'];
            foreach ($albums as $album) { 
                $album_photos = $album['photos'];
                $album_videos = $album['videos'];
                if($album_photos && is_array($album_photos)) {
                    foreach ($album_photos as $photo) { ?>
                    <a href="<?= $url . '/?tab=gallery' ?>"><img class="lazy" src="" data-srcset="<?= $photo['url'] ?>" alt=""></a>
            <?php   }
                }
                if($album_videos && is_array($album_videos)) {
                    foreach ($album_videos as $video) { ?>
                        <a class="gallery-video" href="<?= $url . '/?tab=gallery' ?>">
                            <video class="lazy">
                                <source src="" data-src="<?= $video['video'] ?>">
                            </video>
                        </a>
                <?php }
                }
            }
        } ?>
    </div>
    <div class="wrap-bottom-link-main">
        <a href="<?= $url . '/?tab=gallery' ?>" class="main-link-main"><?php _e('To The Gallery >', 'remmember'); ?></a>
    </div>
</section>
<?php } ?>

<section class="main-comments">
    <h2 class="main-heading"><?php _e('Comments', 'remmember'); ?></h2>
    <div class="wrap-content">
        <?php 
        $comments_count = 0;
        foreach ($comments as $comment) {
            if($comments_count > 2)  break;
            $comment_text = $comment->comment_content;
            $comment_owner_name = get_field("name_of_the_author_of_the_comment",$comment);
            $comment_owner_rel = get_field("relationship_of_the_author_of_the_comment",$comment);
            $comments_count = $comments_count + 1;
        ?>
            <a href="<?= $url . '/?tab=comments' ?>" class="wrap-comment">
                <div class="wrap-left-comment">
                    <span class="name"><?php echo($comment_owner_name); ?></span>
                    <span class="rel"><?php echo($comment_owner_rel); ?></span>
                </div>
                <div class="wrap-right-comment">
                    <p class="text" ><?php echo($comment_text); ?></p>
                </div>
            </a>
        <?php } ?>
    </div>
    <div class="wrap-bottom-link-main">
        <a href="<?= $url . '/?tab=comments' ?>" class="main-link-main"><?php _e('Write A Comment >', 'remmember'); ?></a>
    </div>
</section>

<?php if($places && count($places) > 0) {  ?>
<section class="main-places">
    <h2 class="main-heading"><?php _e('Places Of Commemoration', 'remmember'); ?></h2>
    <div class="wrap-content">
        <?php 
        $places_count = 0;
        foreach ($places as $place) { 
            //take all place fields
            if( $places_count > 3 ) break;
            $places_count = $places_count + 1;
            $img = $place['img'];
            $address = $place['address'];
            $name = $place['name'];
        ?>
            <a <?= $url . '/?tab=places-of-commemoration' ?> class="wrap-place">
                <img class="lazy" src="" data-srcset="<?= $img ?>" alt="">
                <div class="wrap-place-bottom">
                    <span class="name"><?= $name ?></span>
                    <span class="desc"><?= $address ?></span>
                </div>
            </a>
        <?php } ?>
    </div>
    <div class="wrap-bottom-link-main">
        <a href="<?= $url . '/?tab=places-of-commemoration' ?>" class="main-link-main"><?php _e('View More >', 'remmember'); ?></a>
    </div>
</section>
<?php } ?>
<?php if($google_maps_details && $google_maps_details !== "") {  ?>
<section class="main-tomb">
    <h2 class="main-heading"><?php _e('The Tomb', 'remmember'); ?></h2>
    <div class="wrap-content">
            <?php if($google_maps_details) { ?>
                <div class="acf-map" data-zoom="16">
                    <div class="marker" data-lat="<?php echo esc_attr($google_maps_details['lat']); ?>" data-lng="<?php echo esc_attr($google_maps_details['lng']); ?>"></div>
                </div>
            <?php } ?>
            <a href="<?= $url . '/?tab=the-grave' ?>" class="wrap-address">
                <span class="name"><?php echo(get_field("the_name_of_a_cemetery",$post_id)); ?></span>
                <span class="street"><?php echo $tomb_street . ","; ?></span>
                <span class="city"><?php echo $tomb_city. ","; ?></span>
                <span class="country"><?php echo $tomb_country; ?></span>
                <div class="pointer"><?php _e('View >', 'remmember'); ?></div>
            </a>
    </div>
</section>
<?php } ?>