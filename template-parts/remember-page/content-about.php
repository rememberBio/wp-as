<?php
    $post_id = get_the_ID();
    $want_hebrew_dates =  get_field("settings_want_hebrew_dates",$post_id);
    //get custom fields
    $hero_img = get_field("main_image_of_the_deceased",$post_id);
    $hero_desc = get_field("about_description",$post_id);
    $country = get_field("about_country",$post_id);
    $spouse = get_field("about__-_husband__wife",$post_id);
    $parents = get_field("about_parents",$post_id);
    $children = get_field("about_children",$post_id);
    $birthday = get_field("about_birth_day",$post_id);
    $day_of_death = get_field("about_death_day",$post_id);
    $about_timeline = get_field("about_timeline",$post_id);

    //hebrew dates
    $about_birthday_he = "";
    $about_day_of_death_he = "";
    if($want_hebrew_dates) {
        if($day_of_death) {
            $about_day_of_death_he = convert_acf_date_to_he_str_date($day_of_death);
        }
        if($birthday) {
            $about_birthday_he = convert_acf_date_to_he_str_date($birthday);
        }
    }
    
?>

<section class="about">
    <div class="first-part">
        <h1><?php _e('About', 'remmember'); ?></h1>
        <div class="wrap-content">
            <p class="text desktop-only"><?php echo($hero_desc); ?></p>
            <div class="wrap-desc">
                <img src="<?php echo($hero_img); ?>" alt="">
                <div class="wrap-dates">
                    <div class="date">
                        <span class="date-desc"><?php _e('Date of birth:', 'remmember'); ?></span>
                        <?php if($want_hebrew_dates && $birthday) { ?>
                            <span class="year"><?php echo($about_birthday_he); ?></span>
                        <?php } ?>
                        <span class="year"><?php echo($birthday); ?></span>
                    </div>
                    <div class="date">
                        <span class="date-desc"><?php _e('Date of death:', 'remmember'); ?></span>
                        <?php if($want_hebrew_dates && $day_of_death) { ?>
                            <span class="year"><?php echo($about_day_of_death_he); ?></span>
                        <?php } ?>
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
            <h2><?php _e('Country:', 'remmember'); ?></h2>
            <span><?php echo($country); ?></span>
        </div>
        <?php if($spouse['hasband_or_wife'] && ( $spouse['husband_name'] ||  $spouse['wifes_name'] )) { ?>
        <div class="circle spouse">
            <?php $has_link = false;
            if($spouse['link_to_the_spouses_remember_page']) { 
                $has_link = get_permalink( $spouse['link_to_the_spouses_remember_page'] );
            } ?>
            <?php if($spouse['hasband_or_wife'] == 'husband') { ?>
                <img src="/wp-content/uploads/2022/03/husband.svg" alt="">
                <h2><?php _e('Husband:', 'remmember'); ?></h2>
                <?php if($has_link) { ?>
                <a href="<?= $has_link ?>">
                <?php  }?>
                <span><?php echo($spouse['husband_name']); ?></span>
                <?php if($has_link) { ?>
                </a>
                <?php  }?>
            <?php } else { ?>
                <img src="/wp-content/uploads/2022/03/wife.svg" alt="">
                <h2><?php _e('Wife:', 'remmember'); ?></h2>
                <?php if($has_link) { ?>
                <a href="<?= $has_link ?>">
                <?php  }?>
                <span><?php echo($spouse['wifes_name']); ?></span>
                <?php if($has_link) { ?>
                </a>
                <?php  }?>
            <?php } ?>
        </div>
        <?php } ?>
        <div class="circle parents">
            <img src="/wp-content/uploads/2022/02/Group-109.svg" alt="">
            <h2><?php _e('Parents:', 'remmember'); ?></h2>
            <?php foreach ($parents as $parent) { 
                if($parent['link']) {
                    $parent_link = get_permalink($parent['link']);
                }
            ?>
            <?php if($parent_link) {  echo '<a href="' . $parent_link .'">'; }?>
            <span><?php echo($parent['name_of_parent']); ?></span>
            <?php if($parent_link) { echo("</a>"); $parent_link = null; } ?>
            <?php } ?>
        </div>
        <?php if($children && count($children) > 0) { ?>
            <div class="circle children">
                <img src="/wp-content/uploads/2022/02/Group-110.svg" alt="">
                <h2><?php _e('Children:', 'remmember'); ?></h2>
                <?php foreach ($children as $child) { 
                    if($child['link']) {
                        $child_link = get_permalink($child['link']);
                    }
                ?>
                    <?php if($child_link) {  echo '<a href="' . $child_link .'">'; }?>
                    <span><?php echo($child['name_of_child']); ?></span>
                    <?php if($child_link) { echo("</a>");  $child_link = null; } ?>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    <div class="third-part">
        <div class="numbers-line <?php if(count($about_timeline) >= 9) echo 'mobile-more-9'; ?>" >
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
                if($about_timeline && count($about_timeline) > 0) {
                    $next_year = $about_timeline[0]['year'];
                    $next_year = explode("/",$next_year);
                    $next_year = $next_year[count($next_year) - 1];

                    $year_diff = $next_year - $year_birth; //5
                    if($year_diff == 0) $year_diff = 1;
                    if($years_range == 0) $years_range = 1;
                    $year_diff = ($year_diff / $years_range ) * 100;
                }

                if($want_hebrew_dates && $year_birth) $year_birth .= ' | ' .  convert_acf_date_to_he_str_date($birthday,true);
            ?>
           
            <div class="wrap-year mobile-only" style="height:<?php echo $year_diff ?>%">
                <span class="year"><?php echo $year_birth; ?></span>
                <span class="desc"><?php _e('born', 'remmember'); ?></span>
            </div>
            <div class="wrap-year desktop-only" style="width:<?php echo $year_diff ?>%">
                <span class="year"><?php echo $year_birth; ?></span>
                <span class="desc"><?php _e('born', 'remmember'); ?></span>
            </div>
            
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

                if($year_diff == 0) $year_diff = 1;
                if($years_range == 0) $years_range = 1;
                //echo(' $year ' . $year . ' $next_year ' .$next_year. ' $year_diff ' . $year_diff);

            ?>
             <?php if($year && $year != "") { 
                if($want_hebrew_dates && $year) $year.= ' | ' . convert_acf_date_to_he_str_date($line['year'],true);
            ?>
             <div class="wrap-year mobile-only" style="height:<?php echo $year_diff ?>%;">
                <span class="year"><?php echo $year; ?></span>
                <span class="desc"><?php echo $line['short_description']; ?></span>
            </div>
            <div class="wrap-year desktop-only" style="width:<?php echo $year_diff ?>%;">
                <span class="year"><?php echo $year; ?></span>
                <span class="desc"><?php echo $line['short_description']; ?></span>
            </div>
            <?php } ?>
            <?php } 
            if($want_hebrew_dates && $year_dead) $year_dead .= ' | ' . convert_acf_date_to_he_str_date($day_of_death,true);
            ?>
            <div class="wrap-year">
                <span class="year"><?php echo $year_dead; ?></span>
                <span class="desc"><?php _e('dead', 'remmember'); ?></span>
            </div>
        </div>
    </div>
</section>