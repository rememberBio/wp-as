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
// get the current taxonomy term
$term = get_queried_object();
$image = get_field("category_image_category_page",$term);

//dynamic products functions
function get_dp_donations_per_dp_id($dp_id,$candles_flowers) {
    $dp_ids = get_all_translated_post_ids($dp_id,'dynamic_products');
    $array = array_filter($candles_flowers,function($value) use($dp_ids){
        return in_array($value->type,$dp_ids);
    });
    return $array;
}

?>
<div class="wrap-archive-page category-page">
    <?php if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
        } ?>
       <h1 class="archive-title"><?php single_cat_title(); ?></h1>
    <?php
    // Check if there are any posts to display
    
    if ( have_posts() ) {
            
        $post_count = 1;
        $use_panel = 1; ?>
        
        <div class="category-image" style="background-image:url(<?php echo $image; ?>);">
            <?php
            // Display optional category description
            if ( category_description() ) { ?>
                <div class="archive-meta"><?php echo category_description(); ?></div>
            <?php } ?>
        </div>

        <div class="archive-page-container">
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
                   
                    $dynamic_products = get_post_meta($post_id,'settings_dynamic_profucts');
                    
                    $num_of_candles = 0;
                    $num_of_flowers = 0;
                    if(!$dynamic_products || !count($dynamic_products)) { 
                        if($candles_flowers && is_array($candles_flowers)) {
                            $candles = array_filter($candles_flowers,"is_candle");
                            if(is_array($candles)) $num_of_candles = count($candles);
                            $flowers = array_filter($candles_flowers,"is_flower");
                            if(is_array($flowers)) $num_of_flowers = count($flowers);
                        } 
                    }?>
                    
                    <a href="<?php echo $url; ?>" class="wrap-archive-item">
                        <div class="top-part">
                            <span class="image" style="background-image:url(<?php echo $img; ?>);"></span>
                            <span class="name"><?php echo $name; ?></span>
                        </div>
                        <span class="date"><?php echo $date_of_death; ?></span>
                       <div class="bottom-part">
                        <span class="wrap-cf">
                            <?php if($dynamic_products && count($dynamic_products)) { ?>
                                <?php for ($i=0; $i < $dynamic_products[0]; $i++) { 
                                    $dp_id = get_post_meta($post_id,'settings_dynamic_profucts_' . $i .'_product');
                                    if($dp_id && count($dp_id)) $dp_id = $dp_id[0];
                                    $num_of_dynamic_products = count(get_dp_donations_per_dp_id($dp_id,$candles_flowers));
                                    $image = get_field('dp_image',$dp_id);
                                    
                                ?>
                                    <span class="dp-<?php echo $dp_id; ?>"><?php echo $num_of_dynamic_products; ?></span>
                                    <style>
                                        <?php echo "a.wrap-archive-item .wrap-cf>span.dp-$dp_id::before {
                                            background-image: url($image);
                                        }"; ?>
                                    </style>
                                <?php } ?>
                            <?php } else { ?>
                                <span class="candle"><?php echo $num_of_candles; ?></span>
                                <span class="flower"><?php echo $num_of_flowers; ?></span>
                            <?php } ?>
                        </span>
                        <span class="link">
                            <?php _e('visit site','search'); ?>
                        </span>
                    </div>
                    </a>
                <?php }
            ?>
        </div>
    <?php } ?>
</div>
<?php
get_footer();