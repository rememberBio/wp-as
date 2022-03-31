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