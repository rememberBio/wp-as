<?php
/**
 * Remember Pages Category Page
 */

//functions for array filter
function is_candle($value)
{
    return $value->type == 'candle';
}

function is_flower($value)
{
    return $value->type == 'flower';
}
get_header();
?>
<div class="wrap-search-results">
    <?php
    // Check if there are any posts to display
    
    if ( have_posts() ) {
            
        $post_count = 1;
        $use_panel = 1; ?>

        <h1 class="archive-title">Category: <?php single_cat_title(); ?></h1>
        <?php
        // Display optional category description
        if ( category_description() ) { ?>
            <div class="archive-meta"><?php echo category_description(); ?></div>
        <?php } ?>

        <div class="serch-results-container">
            <?php
                while ( have_posts() ) {
                    the_post();
                    $post_id = get_the_ID();
                    $name = get_field('full_name_of_the_deceased');
                    $img = get_field('main_image_of_the_deceased');
                    $date_of_death = get_field('about_death_day');
                    
                    if($date_of_death && $date_of_death !== "") {
                        $date_of_death = date_create($date_of_death);
                        $date_of_death = date_format($date_of_death,"m.d.Y");
                    }
                    $url = get_the_permalink();
                    //get candles and flowers to this post and other translated;
                    $posts_ids = get_all_translated_post_ids($post_id); 
                    $candles_flowers = db_get_remember_pages_payments($posts_ids);

                    $num_of_candles = 0;
                    $num_of_flowers = 0;

                    if($candles_flowers && is_array($candles_flowers)) {
                        $candles = array_filter($candles_flowers,"is_candle");
                        if(is_array($candles)) $num_of_candles = count($candles);
                        $flowers = array_filter($candles_flowers,"is_flower");
                        if(is_array($flowers)) $num_of_flowers = count($flowers);
                    } ?>
                    <a href="<?php echo $url; ?>" class="wrap-result-item">
                        <span class="image" style="background-image:url(<?php echo $img; ?>);"></span>
                        <span class="name"><?php echo $name; ?></span>
                        <span class="date"><?php echo $date_of_death; ?></span>
                        <span class="wrap-cf">
                            <span class="candle"><?php echo $num_of_candles; ?></span>
                            <span class="flower"><?php echo $num_of_flowers; ?></span>
                        </span>
                        <span class="link">
                            <?php _e('visit site','search'); ?>
                        </span>
                    </a>
                    <?php if ($post_count%3 == 0) { 
                        $class = 'blue';
                        if($use_panel == 2) $class = "gray";
                        if($use_panel == 3) { 
                            $class = "light-blue";
                            $use_panel = 0;
                        }
                        $use_panel = $use_panel + 1;
                        play_panel($class); 
                    } 
                    $post_count = $post_count + 1; ?>
                <?php }
                //if there are less from 3 posts
                if ($post_count <= 3) play_panel();
            ?>
        </div>
    <?php } ?>
</div>
<?php
get_footer();

function play_panel($class="blue") { ?>
    <a href="" class="wrap-result-item play-panel <?php echo $class; ?>">
        <p>
            <?php _e('Would you like to remember your loved ones','search'); ?>
        </p>
        <span class="link-start">
            <?php _e('start a new page','search') ?>
        </span>
    </a>
<?php }