<?php
function get_all_translated_post_ids($post_id) {

    $id = $post_id;
    $current_lang = apply_filters( 'wpml_current_language', NULL );
    $posts_ids = array($post_id);

    switch ($current_lang) {
        case 'he':
            $id_en = apply_filters( 'wpml_object_id', $id, 'post', false, 'en' );
            if($id_en);
                $posts_ids[] = $id_en;
            break;
        
        default: //en
            $id_he = apply_filters( 'wpml_object_id', $id, 'post', false, 'he' );
            if($id_he)
                $posts_ids[] = $id_he;
            break;
    }
    return $posts_ids;
}

function custom_switcher() {
    // uncomment this to find your theme's menu location
    //echo "args:<pre>"; print_r($args); echo "</pre>";

    // get languages
    $languages = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=1' );

    $items = "<div class='custom-switchers'>";
    $first_item = "";
    $current_lang_item = "";
    $other_lang_items = "";

    if ( $languages) {
        
        if(!empty($languages)){

            foreach($languages as $l){
                //var_dump($l); echo 👍;
                $name = $l['native_name'] . " ("  . $l['language_code'] .") ";
                if(!$l['active']){
                    // flag with native name
                    $other_lang_items = $other_lang_items . '
                    <span class="lang-menu-item"><a href="' . $l['url'] . '">' . $name . '</a></span>
                    ';
                } else {
                    $current_lang_item = $current_lang_item . '
                    <span class="lang-menu-item current">' . $name . '</span>

                    ';
                    //only flag
                    $first_item = '<span id="toggle-menu-item" class="absolute-lang menu-item-language"><img src="' . get_field($l['language_code'].'_image_lang','option') . '" height="28" width="28" alt="' . $l['language_code'] . '" /></span>';

                }
            }
            $other_lang_items = $current_lang_item . $other_lang_items; 
            $other_lang_items = "<div class='wrap-drop-down'>" . $other_lang_items . '</div>';
            $items =  $items . $first_item . $other_lang_items;
        }
    }
    $items = $items . "</div>";
    return $items;
}