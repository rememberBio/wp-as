<?php

function register_email_to_spec_remmember_page($email,$remmember_post_id) {
    $registerd_list = get_post_meta( $remmember_post_id, '_register_by', true );
    $registerd_list = is_array( $registerd_list ) ? $registerd_list : [];

    if ( ! isset( $registerd_list[ $email ] ) ) {
        $registerd_list[ $email ] = true;
        update_post_meta( $remmember_post_id, '_register_by', $registerd_list );
    }
}

function get_register_email_to_spec_remmember_page($remmember_post_id){
    $registerd_list = get_post_meta( $remmember_post_id, '_register_by', true );
    $registerd_list = is_array( $registerd_list ) ? $registerd_list : [];

    return count($registerd_list);
}