<?php
    $post_id = get_the_ID();

    //get custom fields
    $stories = get_field("stories_repeater",$post_id);

    $want_hebrew_dates =  get_field("settings_want_hebrew_dates",$post_id);
?>

<section class="stories">
    <h1><?php _e('Stories', 'remmember'); ?></h1>
    <div class="wrap-content">
        <?php foreach ($stories as $story) { 
            //take excerpt of story text
            $text = $story['text'];
            $count = count(explode(" ",$text));
            $pres_count = 100;
            $date = $story['date'];
            $img = $story['telling_image'];
            $img_url = $story['telling_image_url'];
            if($img_url) $img = $img_url;
            if($want_hebrew_dates && $date) $date = '<span class="ge-date">' . convert_acf_date_to_he_str_date($date) . '</span><span class="date-space"> | </span><sapn class="he-date">' . $date . '</span>';
            if($count > 1) {
                $pres_count =  ( $count / 4 );
                if( $pres_count < 100 || $pres_count > 100 ) $pres_count = 100;
                if( $count < 60 ) $pres_count = ($count - 5);
            }
        ?>
            <div class="wrap-story">
                <p class="short-text"><?php echo(force_balance_tags( html_entity_decode(wp_trim_words(htmlentities($story['text']), $pres_count, '...')))); ?></p>
                <p class="text" style="display:none;"><?php echo($story['text']); ?></p>
                <div class="wrap-bottom-story">
                    <div class="wrap-desc-story">
                        <div class="wrap-telling-image" style="background-image:url(<?php echo($img); ?>)"></div>
                        <div class="wrap-desc-date-story">
                            <span class="date"><?php echo($date); ?></span>
                            <span class="name"><?php echo($story['telling_name']); ?></span>
                        </div>
                    </div>
                    <a href="" onclick="showMoreStoryText(event)" class="read-more-btn"><?php _e('Read', 'remmember'); ?></a>
                    <a href="" onclick="hideMoreStoryText(event)" class="read-less-btn" style="display:none;"><?php _e('Less', 'remmember'); ?></a>
                </div>
            </div>
        <?php } ?>
    </div>
</section>