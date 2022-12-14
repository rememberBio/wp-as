<?php

// Save the comment meta data when saving the comment.
add_action( 'comment_post', 'add_comment_title_to_comment' );
function add_comment_title_to_comment( $comment_id ) {
    
    error_log(print_r($_FILES,true));
    if ( ( isset( $_POST['name'] ) ) && ( $_POST['name'] != '') ) {
        $name = wp_filter_nohtml_kses( $_POST['name'] );
        update_field('name_of_the_author_of_the_comment',$name, 'comment_' . $comment_id);
    }
    if ( ( isset( $_POST['rel'] ) ) && ( $_POST['rel'] != '') ) {
        $rel = wp_filter_nohtml_kses( $_POST['rel'] );
        update_field('relationship_of_the_author_of_the_comment',$rel, 'comment_' . $comment_id);
    }
}

  //redirect user after submit comment
  function dome_comment_redirect( $location ) {
    if ( isset( $_POST['dome_redirect_to'] ) ) // Don't use "redirect_to", internal WP var
        $location = $_POST['dome_redirect_to'];

    return $location;
}

add_filter( 'comment_post_redirect', 'dome_comment_redirect' );

//return all post comments
function get_remember_post_comments($post_id) {

    $args = array(
        'post_id' => $post_id,   // Use post_id, not post_ID
        'status'       => 'approve',
        'lang' => $my_current_lang
    );
    
    $comments = get_comments( $args );
    return $comments;
}

//return all comments from all posts
function get_remember_translated_post_comments($post_id) {
    $comments = array();
    $translated_posts = get_all_translated_post_ids($post_id);
    if($translated_posts && is_array($translated_posts)) {
        foreach ($translated_posts as $translate_post_id) {
            $translated_comments = get_remember_post_comments($translate_post_id);
            if($translated_comments) $comments = array_merge($comments,$translated_comments);
        }
        

    } else {
        $comments = get_remember_post_comments($post_id);
    }
   
    return $comments;
}

//set candle or flower by order id
function set_remember_post_candles_flowers($remmember_post_id,$type,$name,$message,$order_id) {
    $cf_list = get_post_meta( $remmember_post_id, '_cf_by', true );
    $cf_list = is_array( $cf_list ) ? $cf_list : [];

    if ( ! isset( $cf_list[ $order_id ] ) ) {
        $cf_list[ $order_id ] = array(
            'type' => $type,
            'name' => $name,
            'message' => $message
        );
        update_post_meta( $remmember_post_id, '_cf_by', $cf_list );
    }
}

//get cndle or flower of remember post type by post id
function get_remember_post_candles_flowers($remmember_post_id){
    $cf_list = get_post_meta( $remmember_post_id, '_cf_by', true );
    $cf_list = is_array( $cf_list ) ? $cf_list : [];
    return $cf_list;
}

function app_output_buffer() {
    ob_start();
} // soi_output_buffer
add_action('init', 'app_output_buffer');