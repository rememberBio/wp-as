<?php
    $post_id = get_the_ID();

    //get custom fields
    $stories = get_field("stories_repeater",$post_id);
    //telling_image
    //text
    //telling_name
    //date
?>

<section class="stories">
    <h1>Stories</h1>
    <div class="wrap-content">
        <?php foreach ($stories as $story) { 
            //take excerpt of story text
            $text = $story['text'];
            $count = count(explode(" ",$text));
            $pres_count = 100;
            if($count > 1) {
                $pres_count =  ( $count / 3 );
            }
        ?>
            <div class="wrap-story">
                <p class="short-text"><?php echo(wp_trim_words($story['text'], $pres_count, '...')); ?></p>
                <p class="text" style="display:none;"><?php echo($story['text']); ?></p>
                <div class="wrap-bottom-story">
                    <div class="wrap-desc-story">
                        <img src="<?php echo($story['telling_image']); ?>" alt="">
                        <div class="wrap-desc-date-story">
                            <span class="date"><?php echo($story['date']); ?></span>
                            <span class="name"><?php echo($story['telling_name']); ?></span>
                        </div>
                    </div>
                    <a href="" onclick="showMoreStoryText(event)" class="read-more-btn">Read</a>
                    <a href="" onclick="hideMoreStoryText(event)" class="read-less-btn" style="display:none;">Less</a>
                </div>
                
            </div>
        <?php } ?>
    </div>
</section>