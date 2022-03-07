<?php

//allow only one product in Woocommerce cart
function filter_woocommerce_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = null, $variations = null ) {
    // When NOT empty
    if ( ! WC()->cart->is_empty() ) {
        // Empties the cart and optionally the persistent cart too.
        WC()->cart->empty_cart();
    }

    return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'filter_woocommerce_add_to_cart_validation', 10, 5 );

// Early enable customer WC_Session
add_action( 'init', 'wc_session_enabler' );
function wc_session_enabler() {
    if ( is_user_logged_in() || is_admin() )
        return;

    if ( isset(WC()->session) && ! WC()->session->has_session() ) {
        WC()->session->set_customer_session_cookie( true );
    }
}

// Set posted data in a WC session variable
add_action( 'template_redirect', 'set_custom_posted_data_to_wc_session' );
function set_custom_posted_data_to_wc_session() {
    if ( is_checkout() ) {
        if ( isset($_POST['cf_post_ID']) ) {
            $values = array(); // Initializing

            if ( isset($_POST['candles_flowers']) && ! empty($_POST['candles_flowers']) ) {
                $values['candles_flowers'] = $_POST['candles_flowers'];
            }

            if ( isset($_POST['senderName']) && ! empty($_POST['senderName']) ) {
                $values['senderName'] = sanitize_text_field($_POST['senderName']);
            }

            if ( isset($_POST['senderMessage']) && ! empty($_POST['senderMessage']) ) {
                $values['senderMessage'] = sanitize_text_field($_POST['senderMessage']);
            }

            if ( isset($_POST['cf_post_ID']) && ! empty($_POST['cf_post_ID']) ) {
                $values['cf_post_ID'] = sanitize_text_field($_POST['cf_post_ID']);
            }

            // Set data to a WC_Session variable
            if ( ! empty($values) ) {
                WC()->session->set('custom_data', $values);
            }
        }
    }
}

// Save WC session data as custom order meta data
add_action( 'woocommerce_checkout_create_order', 'action_checkout_add_custom_order_meta', 10, 2 );
function action_checkout_add_custom_order_meta( $order, $data ) {
    $values = WC()->session->get('custom_data'); // Get data from WC Session variable

    if( ! empty($values) ) {
        if ( isset($values['candles_flowers']) ) {
            $order->update_meta_data( '_type', $values['candles_flowers'] ); 
        }
        if ( isset($values['senderName']) ) {
            $order->update_meta_data( '_sender_name', $values['senderName'] ); 
        }
        if ( isset($values['senderMessage']) ) {
            $order->update_meta_data( '_sender_message', $values['senderMessage'] ); 
        }
        if ( isset($values['cf_post_ID']) ) {
            $order->update_meta_data( '_remember_post_id', $values['cf_post_ID'] ); 
        }
        // Remove the WC_Session variable (as we don't need it anymore)
        WC()->session->__unset('custom_data');
    }
}

//add fileds to admin order page
function candle_and_flower_admin_fields_shown($order){
    echo '<h3>'.__('Candle And Flowers Item Details: ').'</h3>';
    if(get_post_meta( $order->get_id(), '_type', true )){
        $href = get_permalink(get_post_meta( $order->get_id(), '_remember_post_id', true ));
        echo '<p><strong>'.__('Type').':</strong> <br/>' . get_post_meta( $order->get_id(), '_type', true ) . '</p>';
        echo '<p><strong>'.__('Name').':</strong> <br/>' . get_post_meta( $order->get_id(), '_sender_name', true ) . '</p>';
        echo '<p><strong>'.__('Message').':</strong> <br/>' . get_post_meta( $order->get_id(), '_sender_message', true ) . '</p>';
        echo '<p><span>Remember Page: </span><a href="' . $href  .'">' . $href .'</a><p>'; 

    }
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'candle_and_flower_admin_fields_shown', 10, 1 );

//add to thank you page
add_action( 'woocommerce_thankyou', 'thankyou_display_data' ); 
function thankyou_display_data( $order_id ) { 
    
    $order = wc_get_order( $order_id ); // Get an instance of the WC_Order object
    
    $type = $order->get_meta('_type');
    $sender_name  = $order->get_meta('_sender_name');
    $sender_message = $order->get_meta('_sender_message');
    $post_id = $order->get_meta('_remember_post_id');
    $href = get_permalink($post_id );

    echo ! empty($type) ? '<h3>Candles And Flowers Details</h3><p><span>Type: </span>' . $type .'<p>' : ''; 
    echo ! empty($sender_name) ? '<p><span>Sender Name: </span>' . $sender_name .'<p>' : ''; 
    echo ! empty($sender_message) ? '<p><span>Sender Message: </span>' . $sender_message .'<p>' : ''; 
    echo ! empty($post_id) ? '<p><span>Remember Page: </span><a href="' .  $href .'">' . $href .'</a><p>' : ''; 

} 
function dome_woocommerce_order_status_completed( $order_id ) {
    $order = wc_get_order( $order_id ); // Get an instance of the WC_Order object
    
    $type = $order->get_meta('_type');
    $sender_name  = $order->get_meta('_sender_name');
    $sender_message = $order->get_meta('_sender_message');
    $post_id = $order->get_meta('_remember_post_id');

    set_remember_post_candles_flowers($post_id,$type,$sender_name,$sender_message,$order_id);
}
add_action( 'woocommerce_order_status_completed', 'dome_woocommerce_order_status_completed', 10, 1 );