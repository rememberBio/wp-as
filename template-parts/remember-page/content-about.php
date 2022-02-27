<?php
    $post_id = get_the_ID();

    //get custom fields
    $hero_img = get_field("about_image",$post_id);
    $hero_desc = get_field("about_description",$post_id);
    $country = get_field("about_country",$post_id);
    $parents = get_field("about_parents",$post_id);
    $children = get_field("about_children",$post_id);
    $birthday = get_field("about_birth_day",$post_id);
    $day_of_death = get_field("about_death_day",$post_id);
    $about_timeline = get_field("about_timeline",$post_id);
?>

<section class="about">
    <div class="first-part">
        <h1>About</h1>
        <div class="wrap-content">
            <p class="text desktop-only"><?php echo($hero_desc); ?></p>
            <div class="wrap-desc">
                <img src="<?php echo($hero_img); ?>" alt="">
                <div class="wrap-dates">
                    <div class="date">
                        <span class="date-desc">Date of birth:</span>
                        <span class="year"><?php echo($birthday); ?></span>
                    </div>
                    <div class="date">
                        <span class="date-desc">Date of death:</span>
                        <span class="year"><?php echo($day_of_death); ?></span>
                    </div>
                </div>
            </div>
            <p class="text mobile-only"><?php echo($hero_desc); ?></p>
        </div>
    </div>
    <div class="second-part">
        <div class="circle country">
            <img src="/wp-content/uploads/2022/02/Group-106.svg" alt="">
            <h2>Country:</h2>
            <span><?php echo($country); ?></span>
        </div>
        <div class="circle parents">
            <img src="/wp-content/uploads/2022/02/Group-109.svg" alt="">
            <h2>Parents:</h2>
            <?php foreach ($parents as $parent) { ?>
            <span><?php echo($parent['name_of_parent']); ?></span>
            <?php } ?>
        </div>
        <div class="circle children">
            <img src="/wp-content/uploads/2022/02/Group-110.svg" alt="">
            <h2>Children:</h2>
            <?php foreach ($children as $child) { ?>
                <span><?php echo($child['name_of_child']); ?></span>
            <?php } ?>
        </div>
    </div>
    <div class="third-part">
        <div class="numbers-line">
            <?php
                //extract year from date field
                $year_birth = $birthday;
                if($year_birth) {
                    $year_birth = explode("/",$year_birth);
                    $year_birth = $year_birth[count($year_birth) - 1];
                }

                $year_dead = $day_of_death;
                if($year_dead) {
                    $year_dead = explode("/",$year_dead);
                    $year_dead = $year_dead[count($year_dead) - 1];
                }

                //get years range
                $years_range =  $year_dead - $year_birth; //83
                $year_diff = 100;
                $next_year = $year_dead;//2020

                if(count($about_timeline) > 0) {

                    $next_year = $about_timeline[0]['year'];
                    $next_year = explode("/",$next_year);
                    $next_year = $next_year[count($next_year) - 1];

                    $year_diff = $next_year - $year_birth; //5
                    if($year_diff == 0) $year_diff = 1;
                    $year_diff = ($year_diff / $years_range ) * 100;
                }
            ?>
            <div class="wrap-year mobile-only" style="height:<?php echo $year_diff ?>%">
                <span class="year"><?php echo $year_birth; ?></span>
                <span class="desc">born</span>
            </div>
            <div class="wrap-year desktop-only" style="width:<?php echo $year_diff ?>%">
                <span class="year"><?php echo $year_birth; ?></span>
                <span class="desc">born</span>
            </div>
            <?php //print_r($about_timeline); ?>
            <?php for ($i=0; $i < count($about_timeline) ; $i++) {

                $line = $about_timeline[$i];
                //teake current line year
                $year = $line['year'];
                if($year) {
                    $year = explode("/",$year);
                    $year = $year[count($year) - 1];
                }

                //get next year
                $next_year = $year_dead; 
                if(count($about_timeline) - 1 !== $i) {
                    $next_year = $about_timeline[$i + 1]['year'];
                }
               
                $next_year = explode("/",$next_year);
                $next_year = $next_year[count($next_year) - 1];

                $year_diff = $next_year - $year; //5
                $year_diff = ($year_diff / $years_range ) * 100;
                //echo(' $year ' . $year . ' $next_year ' .$next_year. ' $year_diff ' . $year_diff);

            ?>
             <div class="wrap-year mobile-only" style="height:<?php echo $year_diff ?>%;">
                <span class="year"><?php echo $year; ?></span>
                <span class="desc"><?php echo $line['short_description']; ?></span>
            </div>
            <div class="wrap-year desktop-only" style="width:<?php echo $year_diff ?>%;">
                <span class="year"><?php echo $year; ?></span>
                <span class="desc"><?php echo $line['short_description']; ?></span>
            </div>
            <?php } 
            ?>
            <div class="wrap-year">
                <span class="year"><?php echo $year_dead; ?></span>
                <span class="desc">dead</span>
            </div>
        </div>
    </div>
</section>