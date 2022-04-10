<?php
/**
 * Template Name: Search Results
 */
$search_results = array();
$search_text = "";
//var_dump($_POST);
if(isset($_POST)) {
    //search by name
    if(isset($_POST['byName'])) { 
        $search_results = search_remember_page_by_name($_POST['byName']);
        $search_text = __('Name','search') . ": '" . $_POST['byName'] . "'";
    }
    //search by location
    if(isset($_POST['pac-input']) && isset($_POST['lng']) && isset($_POST['lat'])) { 
        $search_results = search_remember_page_by_location($_POST['pac-input'],$_POST['lat'],$_POST['lng']);
        $search_text = __('Tomb location','search') . ": " . $_POST['pac-input'];
    }
    //search by date
    if(isset($_POST['byDaetY']) && isset($_POST['byDaetMo'])) {
        $search_results = search_remember_page_by_death_date($_POST['byDaetY'],$_POST['byDaetMo']);
        $search_text = __('Death date','search') . ": " . $_POST['byDaetMo'] . '/' . $_POST['byDaetY'];
    }
}
if($search_text !== "") $search_text = __("Search results for ",'search') . $search_text;
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
    <?php if ( function_exists('yoast_breadcrumb') ) {
        yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
    } ?>
    <h1><?php _e('search results','search'); ?></h1>
    <?php if($search_text !== "") echo '<span class="search-by-text">' . $search_text . '</span>'; ?>
    <div class="serch-results-container">
        <?php
        if(count($search_results) > 0) {
            $post_count = 1;
            $use_panel = 1;
            foreach ($search_results as $res) {
                $post_id = $res -> ID;
                $name = get_field('full_name_of_the_deceased',$post_id);
                $img = get_field('main_image_of_the_deceased',$post_id);
                $date_of_death = get_field('about_death_day',$post_id);
                
                if($date_of_death && $date_of_death !== "") {
                    $date_of_death = date_create($date_of_death);
                    $date_of_death = date_format($date_of_death,"m.d.Y");
                }
                $url = get_permalink($post_id);
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
        } else {
            _e("results not found",'search');
        }
        ?>
    </div>
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