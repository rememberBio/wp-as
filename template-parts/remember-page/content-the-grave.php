<?php
    $post_id = get_the_ID();

    //get custom fields
    $images = get_field("the_grave_images_gallery",$post_id);
    //the_grave_images_gallery
    $google_maps_details = get_field("the_grave_in_google_maps",$post_id);
    if($google_maps_details) {
        $country = $google_maps_details['country'];
        $city = $google_maps_details['city'];
        $street =  $google_maps_details['street_name'] .' '. $google_maps_details['street_number'];
        $address_name = $google_maps_details['address'];
    }
    $images_count = 0;
?>

<section class="the-grave">
    <h1>The grave</h1>
    <div class="wrap-content">
        <div class="top">
            <div class="wrap-gallery">
                <?php foreach ($images as $img) { ?>
                    <?php if(!$images_count) { ?>
                        <img src="<?= $img ?>" class="current-gallery-image" alt="">
                    <?php }?>
                        <img src="<?= $img ?>" class="  <?php if(!$images_count) { echo "hovered-current-img";  $images_count = 1; } ?>" alt="">
                <?php } ?>
            </div>
            <div class="wrap-address">
                <span class="name"><?php echo(get_field("the_name_of_a_cemetery",$post_id)); ?></span>
                <span class="street"><?php echo $street; ?></span>
                <span class="city"><?php echo $city; ?></span>
                <span class="country"><?php echo $country; ?></span>
                <?php if($google_maps_details) { ?>
                <div class="wrap-links">
                    <a target="blank" href="https://www.waze.com/ul?q=<?php echo urlencode($address_name); ?>&navigate=yes"><img src="/wp-content/uploads/2022/03/Group-293.svg" alt="wase link"></a>
                    <a href="http://maps.google.com/?q=<?php echo urlencode($address_name); ?>" target="blank"><img src="/wp-content/uploads/2022/03/Group-291.svg" alt="google maps link"></a>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="bottom">
            <?php if($google_maps_details) { ?>
                <div class="acf-map" data-zoom="16">
                    <div class="marker" data-lat="<?php echo esc_attr($google_maps_details['lat']); ?>" data-lng="<?php echo esc_attr($google_maps_details['lng']); ?>"></div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>