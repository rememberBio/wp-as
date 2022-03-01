<?php
    $post_id = get_the_ID();

    //get custom fields
    $places = get_field("places_list",$post_id);
    //img
    //address
    //desc
    //text
?>

<section class="places">
    <h1>Places Of Commemoration</h1>
    <div class="wrap-content">
        <?php foreach ($places as $place) { 
            //teake all place fields
            $text = $place['text'];
            $img = $place['img'];
            $desc = $place['desc'];
            $name = $place['name'];
            $address = $place['address'];
        ?>
            <div class="wrap-place">
                <img src="<?= $img ?>" alt="">
                <div class="wrap-place-bottom">
                    <span class="name"><?= $name ?></span>
                    <span class="address"><?= $address ?></span>
                    <span class="desc"><?= $desc ?></span>
                    <span class="text"><?= $text ?></span>
                </div>
            </div>
        <?php } ?>
    </div>
</section>