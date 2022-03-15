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
$candles_flowers = get_remember_post_candles_flowers($post_id);
$num_of_candles = 0;
$num_of_flowers = 0;

if($candles_flowers && is_array($candles_flowers)) {
    //functions for array filter
    function is_candle($value)
    {
        return $value['type'] == 'candle';
    }

    function is_flower($value)
    {
        return $value['type'] == 'flower';
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
        </div>
        <div class="form">
            <div class="wrap-form-footer">
                <span class="text"><?=  $remember_too_text ?></span>
                <form method="post" name="registerForm" id="registerForm" action="">
                    <input type="email" name="email" value="" id="email" placeholder="<?= get_field("enter_email_text","option"); ?>" >
                    <button type="submit"><img src="/wp-content/uploads/2022/02/Group-884.png" alt=""></button>
                </form>
            </div>
        </div>
    </div>
    <div class="mobile-only">
        <div class="text mobile-only">
            <h1 class="name"><?= $hero_name ?></h1>
            <span class="desc"><?= $hero_desc ?></span>
        </div>
        <div class="main-hero-img" style="background-image:url(<?= $hero_img ?>)"></div>
    </div>
    <div class="main-hero-img desktop-only" style="background-image:url(<?= $hero_img ?>)"></div>

</section>
<section class="main-candles-and-flowers">
    <a href="<?php echo $url . '/?tab=candle-and-flowers' ?>" class="write-candles-flowers-btn">
        <img src="/wp-content/uploads/2022/03/Group-963.svg" alt="">
        <div class="right-btn">
            <span class="num"><?php echo $num_of_candles; ?></span>
            <span class="desc">Candles</span>
            <span class="text">have been lit until now</span>
            <div class="main-cf-btn pointer">light a candle</div>
        </div>
    </a>
    <a href="<?php echo $url . '/?tab=candle-and-flowers' ?>" class="write-candles-flowers-btn">
        <img src="/wp-content/uploads/2022/03/Group-965.svg" alt="">
        <div class="right-btn">
            <span class="num"><?php echo $num_of_flowers; ?></span>
            <span class="desc">Flowers</span>
            <span class="text">have been Sent until now</span>
            <div class="main-cf-btn">send a flower</div>
        </div>
    </a>
</section>
<?php if($about_country && $about_country !== "" || $about_day_of_death && $about_day_of_death !== "" || $about_birthday && $about_birthday !== "" ||  $about_children && count($about_children) > 0 || $about_parents && count($about_parents) > 0) { ?>
<section class="main-about">
    <h2 class="main-heading">About</h2>
    <div class="wrap-content">
        <p class="text"><?php echo($about_desc); ?></p>
        <div class="wrap-dates">
            <?php if($about_birthday && $about_birthday !== "") { ?>
            <a href="<?= $url . '/?tab=about' ?>" class="date">
                <span class="date-desc">Date of birth:</span>
                <span class="year"><?php echo($about_birthday); ?></span>
            </a>
            <?php } if($about_day_of_death && $about_day_of_death !== "") { ?>
            <a href="<?= $url . '/?tab=about' ?>" class="date">
                <span class="date-desc">Date of death:</span>
                <span class="year"><?php echo($about_day_of_death); ?></span>
            </a>
            <?php } ?>
        </div>
        <div class="second-part">
            <?php if($about_country && $about_country !== "") { ?>
            <div class="circle country">
                <img src="/wp-content/uploads/2022/02/Group-106.svg" alt="">
                <h3>Country:</h3>
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
                    <h3>Husband:</h3>
                    <?php if($has_link) {  echo '<a href="' . $has_link .'">'; }?>
                    <span><?php echo($about_spouse['husband_name']); ?></span>
                    <?php if($has_link) { echo("</a>"); } ?>
            <?php } else { ?>
                    <img src="/wp-content/uploads/2022/03/wife.svg" alt="">
                    <h3>Wife:</h3>
                    <?php if($has_link) {  echo '<a href="' . $has_link .'">'; }?>
                        <span><?php echo($about_spouse['wifes_name']); ?></span>
                    <?php if($has_link) { echo("</a>"); } ?>
            <?php } ?>
            </div>

            <?php } if($about_parents && count($about_parents) > 0 ) {  ?>
            <div class="circle parents">
                <img src="/wp-content/uploads/2022/02/Group-109.svg" alt="">
                <h3>Parents:</h3>
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
                <h3>Children:</h3>
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
        <a href="<?= $url . '/?tab=about' ?>" class="main-link-main">Read More ></a>
    </div>
</section>
<?php } ?>
<?php if($main_stories && count($main_stories) > 0) {  ?>
<section class="main-stories">
    <h2 class="main-heading">Stories</h2>
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
                <span class="read-more">Read ></span>
            </a>
        <?php } ?>
    </div>
    <div class="wrap-bottom-link-main">
        <a href="<?= $url . '/?tab=stories' ?>" class="main-link-main">More Stories ></a>
    </div>
</section>
<?php } ?>
<?php if($gallery_items && count($gallery_items) > 0) {  ?>
<section class="main-gallery">
    <h2 class="main-heading">Gallery</h2>
    <div class="wrap-content" id="galley-main-slider">
        <?php foreach ($gallery_items as $gallery_item) {  
            $albums = $gallery_item['albums'];
            foreach ($albums as $album) { 
                $album_photos = $album['photos'];
                $album_videos = $album['videos'];
                if($album_photos && is_array($album_photos)) {
                    foreach ($album_photos as $photo) { ?>
                    <a href="<?= $url . '/?tab=gallery' ?>"><img src="<?= $photo['url'] ?>" alt=""></a>
            <?php   }
                }
                if($album_videos && is_array($album_videos)) {
                    foreach ($album_videos as $video) { ?>
                        <a class="gallery-video" href="<?= $url . '/?tab=gallery' ?>"><video src="<?= $video['video'] ?>"></video></a>
                <?php }
                }
            }
        } ?>
    </div>
    <div class="wrap-bottom-link-main">
        <a href="<?= $url . '/?tab=gallery' ?>" class="main-link-main">To The Gallery ></a>
    </div>
</section>
<?php } ?>

<section class="main-comments">
    <h2 class="main-heading">Comments</h2>
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
        <a href="<?= $url . '/?tab=comments' ?>" class="main-link-main">Write A Comment ></a>
    </div>
</section>

<?php if($places && count($places) > 0) {  ?>
<section class="main-places">
    <h2 class="main-heading">Places Of Commemoration</h2>
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
                <img src="<?= $img ?>" alt="">
                <div class="wrap-place-bottom">
                    <span class="name"><?= $name ?></span>
                    <span class="desc"><?= $address ?></span>
                </div>
            </a>
        <?php } ?>
    </div>
    <div class="wrap-bottom-link-main">
        <a href="<?= $url . '/?tab=places-of-commemoration' ?>" class="main-link-main">View More ></a>
    </div>
</section>
<?php } ?>
<?php if($google_maps_details && $google_maps_details !== "") {  ?>
<section class="main-tomb">
    <h2 class="main-heading">The Tomb</h2>
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
                <div class="pointer">View ></div>
            </a>
    </div>
</section>
<?php } ?>