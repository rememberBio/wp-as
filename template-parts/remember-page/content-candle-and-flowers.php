<?php
    $post_id = get_the_ID();
    $url = get_permalink();

    $hero_img = get_field("main_image_of_the_deceased",$post_id);
    $hero_name = get_field("full_name_of_the_deceased",$post_id);

    $dynamic_products = get_field("settings_dynamic_profucts",$post_id);
   
    //get candles and flowers to this post and other translated;
    $pages_ids = get_all_translated_post_ids($post_id); 
    $candles_flowers = db_get_remember_pages_payments($pages_ids);
    $num_of_candles = 0;
    $num_of_flowers = 0;

    if($candles_flowers && is_array($candles_flowers)) {
        //functions for array filter
        function is_candle($value)
        {
            return $value->type == 'candle';
        }

        function is_flower($value)
        {
            return $value->type == 'flower';
        }
    
        $candles = array_filter($candles_flowers,"is_candle");
        if(is_array($candles)) $num_of_candles = count($candles);
        $flowers = array_filter($candles_flowers,"is_flower");
        if(is_array($flowers)) $num_of_flowers = count($flowers);
    }
    $candle_product_price_id = 'price_1Kgja1AZSRIVhKs4tbSNJeFs';
    $flower_product_price_id = 'price_1KgjaZAZSRIVhKs4UrIUq2gp';
    $stripe_dynamic_product_id = 'prod_LcBSGpkS6oqHtU';
  
    $candle_product_price = '$1';
    $flower_product_price = '$1';

    $default_donation_price = '$1';

    $payment_error = "";
    $success_payment = false;
    if(isset($_GET["payment"])) $success_payment = $_GET["payment"];
    if(isset($_GET["cf_post"]) && isset($_POST["cf_post_ID"])) {
        $cf = $_POST["candles_flowers"];
        $name = $_POST['senderName'];
        $message = $_POST['senderMessage'];
        $email = $_POST['senderEmail'];
        $phone = $_POST['senderPhone'];
        $card_number = $_POST['card_number'];
        $card_date = $_POST['card_date'];
        $card_cvc = $_POST['card_cvv'];
        $save_card_info = $_POST['credit_info_card'];
        $get_emails = $_POST['get_emails'];

        //get product price id
        $price_id = "";
        $dynamic_price_data = null;
        $dynamic_product_name = "";
        $is_dynamic_product = false;

        if($cf == 'flower') { 
            $price_id = $flower_product_price_id;
        } else if($cf == 'candle') {
            $price_id = $candle_product_price_id;
        } else { // dynamic price
            $is_dynamic_product = true;

            $dp_id = $cf;
            $current_post_field = get_dynamic_product_by_id($dp_id, $dynamic_products);
            $dp_name = get_field('dp_name',$dp_id);
            //price
            $price = 1;
            $dp_price = get_field('dp_price',$dp_id);
            $new_price = $current_post_field['price'];
            if($new_price) {
                $price = $new_price;
            } else if($dp_price) {
                $price = $dp_price;
            }  
            $dynamic_price_data = array(
                'unit_amount' => ($price * 100), 
                'currency' => 'usd',
                'product' => $stripe_dynamic_product_id
            );

            $dynamic_product_name = $dp_name;

        }
        //create payment method
        $card_year_ex = date("Y",strtotime($card_date));
        $card_month_ex = date("m",strtotime($card_date));
        $result = stripe_create_payment_method($card_number,$card_month_ex,$card_year_ex,$card_cvc,$phone,$email);
        
        if($result['result'] == 'error') {
            $payment_error = $result['error'];
        } else {
            $payment_method_id = $result['id'];
            //create or update stripe customer
            $customer_id = stripe_create_or_update_customer($phone,$email);

            if($customer_id) {
                //create payment intent
                //$payment_intend_result = stripe_create_payment_intend($payment_method_id,$customer_id,100,$url,'USD',"create from remember page");
                $result_payment = stripe_create_payment_and_invoice($customer_id,$price_id,$payment_method_id,$is_dynamic_product,$dynamic_price_data,$dynamic_product_name);
                if($result_payment['result'] == 'success') {
                    //create sendinblue contact
                    register_email_to_spec_remmember_page($email,$post_id);

                    //save customer to our db
                    db_create_customer($email,$phone,$name);

                    //save remember page and customer to db
                    db_add_customer_remember_page($email,$post_id);

                    //save payment to customer to db
                    db_add_payment_to_customer($email,$result_payment['id'],$name,$message,$cf,$post_id);

                    //redirect to current page
                    wp_safe_redirect( $url . "?tab=candle-and-flowers&payment=" . $cf);
                    exit();
                } else {
                    if($result_payment['result'] == 'error') {
                        $payment_error = $result_payment['error'];
                    }
                }
            } else {
                $payment_error = "Error in create payment, please try later";
            }
        }
    }
    //dynamic products functions
    function get_dp_donations_per_dp_id($dp_id,$candles_flowers) {
        $dp_ids = get_all_translated_post_ids($dp_id,'dynamic_products');
        $array = array_filter($candles_flowers,function($value) use($dp_ids){
            return in_array($value->type,$dp_ids);
        });
        return $array;
    }
    function get_dynamic_product_by_id($dp_id,$dynamic_products) {
       $product = array();
        for ($i=0; $i < count($dynamic_products); $i++) { 
            $element = $dynamic_products[$i];
            if($element['product']->ID == $dp_id) {
                $product = $element;
                $i = count($dynamic_products);
            }
        }
        return $product;
    }
?>

<section class="candles-flowers">
    <?php if($dynamic_products && count($dynamic_products)) { ?>
        <h1><?php _e('Donations', 'remmember'); ?></h1>
    <?php } else { ?>
        <h1><?php _e('Candle And Flower', 'remmember'); ?></h1>
    <?php } ?>
    <div class="write-candles-flowers <?php if($success_payment || $payment_error != "") echo "opened"; ?>">
    <?php if(!$dynamic_products || !count($dynamic_products)) { ?>

        <div class="write-candles-flowers-btn">
            <img src="/wp-content/uploads/2022/03/Group-425-1.svg" alt="">
           
            <div class="right-btn">
                <span class="num"><?php echo $num_of_candles; ?></span>
                <span class="desc"><?php _e('Candles', 'remmember'); ?></span>
                <span class="text"><?php _e('have been lit until now', 'remmember'); ?></span>
                <a data-product="candle" href=""><?php _e('light a candle', 'remmember'); ?></a>
            </div>
        </div>
        <div class="write-candles-flowers-btn">
            <img src="/wp-content/uploads/2022/03/Group-424.svg" alt="">
            <div class="right-btn">
                <span class="num"><?php echo $num_of_flowers; ?></span>
                <span class="desc"><?php _e('Flowers', 'remmember'); ?></span>
                <span class="text"><?php _e('have been Sent until now', 'remmember'); ?></span>
                <a data-product="flower"  href=""><?php _e('send a flower', 'remmember'); ?></a>
            </div>
        </div>
    <?php } else { ?>
        <?php foreach ($dynamic_products as $dp) { 
            $dp_id = $dp['product']->ID;
            $name = get_field('dp_name',$dp_id);
            $image = get_field('dp_image',$dp_id);
        ?>
            <div class="write-candles-flowers-btn">
                <img src="<?php echo $image ?>" alt="">
                <div class="right-btn">
                    <span class="num"><?php echo count(get_dp_donations_per_dp_id($dp_id,$candles_flowers)); ?></span>
                    <span class="desc"><?php echo $name ?></span>
                    <span class="text"><?php _e('have been donated until now', 'remmember'); ?></span>
                    <a data-product="<?php echo $dp_id ?>" href=""><?php _e('donate a ', 'remmember');  echo $name; ?></a>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
        <div class="wrap-write-candles-flowers-form">
            <form action="<?php echo $url ?>?tab=candle-and-flowers&cf_post=true" id="cfform" method="POST" <?php if($success_payment) echo "style='display:none;'"; ?> >
                <a href="" class="close" role="button"><img src="/wp-content/uploads/2022/02/Group-956.svg" alt="close candle and flower popup"></a>
                <div class="step-1" <?php if($payment_error != "") echo "style='display:none;'"; ?>>
                    <div class="wrap-top-form">
                        <?php if($dynamic_products && count($dynamic_products)) { ?>
                            <span class="head"><?php _e('What would you like to donate?', 'remmember'); ?></span>
                            <?php foreach ($dynamic_products as $dp) {
                                $dp_id = $dp['product']->ID;
                                $name = get_field('dp_name',$dp_id);
                                $image = get_field('dp_image',$dp_id); 
                                //price of image
                                $price = $default_donation_price;
                                $dp_price = get_field('dp_price',$dp_id);
                                $new_price = $dp['price'];
                                if($new_price) {
                                    $price =  '$' . $new_price;
                                } else if($dp_price) {
                                    $price =  '$' . $dp_price;
                                } 
                            ?>
                                <div class="wrap-radio <?php if( $payment_error != "" && isset($_POST["candles_flowers"]) && $_POST["candles_flowers"] == $name ) { echo ""; } else echo 'current'; ?>">
                                    <span class="price"><?= $price ?></span>
                                    <label for="<?php echo $dp_id; ?>"><?php echo $name;  ?></label>
                                    <input type="radio" <?php if( $payment_error != "" && isset($_POST["candles_flowers"]) && $_POST["candles_flowers"] == $name ) { echo ""; } else echo 'checked="checked"'; ?> name="candles_flowers" id="<?php echo $dp_id; ?>" value="<?php echo $dp_id; ?>">
                                </div>
                                <style>
                                    label[for='<?php echo $dp_id; ?>']:before {
                                        background-image:url(<?php echo $image; ?>) !important;
                                    }
                                    .wrap-top-form .wrap-radio.current label:before {
                                        border: 3.5px solid var(--custom-light-blue);
                                    }
                                </style>
                            <?php } ?>

                        <?php } else { ?>
                            <span class="head"><?php _e('What would you like to send?', 'remmember'); ?></span>
                            <div class="wrap-radio <?php if( $payment_error != "" && isset($_POST["candles_flowers"]) && $_POST["candles_flowers"] == "flower" ) { echo ""; } else echo 'current'; ?>">
                                <span class="price"><?= $candle_product_price ?></span>
                                <label for="candle"><?php _e('candle', 'remmember'); ?></label>
                                <input type="radio" <?php if( $payment_error != "" && isset($_POST["candles_flowers"]) && $_POST["candles_flowers"] == "flower" ) { echo ""; } else echo 'checked="checked"'; ?> name="candles_flowers" id="candle" value="candle">
                            </div>
                            <div class="wrap-radio <?php if( $payment_error != "" && isset($_POST["candles_flowers"]) && $_POST["candles_flowers"] == "flower" ) { echo "current"; } ?>">
                                <span class="price"><?= $flower_product_price ?></span>
                                <label for="flower"><?php _e('flower', 'remmember'); ?></label>
                                <input type="radio" name="candles_flowers" <?php if( $payment_error != "" && isset($_POST["candles_flowers"]) && $_POST["candles_flowers"] == "flower" ) { echo 'checked="checked"'; } ?>  id="flower" value="flower">
                            </div>
                        <?php } ?>
                    </div>
                    <div class="wrap-error" style="display:none;">
                        <span class="error"><?php _e('There is empty field or field with invalid value', 'remmember'); ?></span>
                    </div>
                    <input required type="text" <?php if( $payment_error != "" && isset($_POST["senderName"])) { echo 'value="' . $_POST["senderName"] . '"'; } ?> maxlength="12" placeholder="<?php _e('Name', 'remmember'); ?>" id="senderName" name="senderName" size="30" maxlength="245">
                    <div class="wrap-form-fields">
                        <input <?php if( $payment_error != "" && isset($_POST["senderEmail"])) { echo 'value="' . $_POST["senderEmail"] . '"'; } ?> required type="email" placeholder="<?php _e('Email', 'remmember'); ?>" id="senderEmail" name="senderEmail" >
                        <input <?php if( $payment_error != "" && isset($_POST["senderPhone"])) { echo 'value="' . $_POST["senderPhone"] . '"'; } ?> required type="tel"placeholder="<?php _e('Phone', 'remmember'); ?>" id="senderPhone" name="senderPhone">
                    </div>
                    <textarea  maxlength="30" name="senderMessage" id="senderMessage" cols="30" rows="10" aria-required="true" placeholder="<?php _e('Message To Family Or Dead', 'remmember'); ?>"><?php if( $payment_error != "" && isset($_POST["senderMessage"])) { echo $_POST["senderMessage"]; } ?></textarea>
                    
                  
                    <input type="button" id="next-button" value="<?php _e('Send', 'remmember'); ?>">
                    <input type="hidden" name="cf_post_ID" value="<?php echo $post_id; ?>" id="comment_post_ID">
                </div>
                <div class="step-2" <?php if($payment_error == "") echo "style='display:none;'"; ?>>
                    <div class="wrap-top-form">
                        <?php if(!$dynamic_products || !count($dynamic_products)) { ?>
                            <?php if( $payment_error != "" && isset($_POST["candles_flowers"]) && $_POST["candles_flowers"] == "candle" ) { ?>
                                <img class="circle candle-image" src="/wp-content/uploads/2022/03/Group-963.svg" alt="">
                                <span class="price-candle"><?= $candle_product_price ?></span>
                            <?php } else if( $payment_error != "" && isset($_POST["candles_flowers"]) && $_POST["candles_flowers"] == "flower" ) { ?>
                                <img class="circle flower-image" src="/wp-content/uploads/2022/03/Group-965.svg" alt="">
                                <span class="price-flower"><?= $flower_product_price ?></span>
                            <?php } else { ?>
                                <img class="circle candle-image" src="/wp-content/uploads/2022/03/Group-963.svg" alt="">
                                <img class="circle flower-image" src="/wp-content/uploads/2022/03/Group-965.svg" alt="">
                                <span class="price-candle"><?= $candle_product_price ?></span>
                                <span class="price-flower"><?= $flower_product_price ?></span>
                            <?php } ?>
                        <?php } else { ?>
                                <?php if( $payment_error != "" && isset($_POST["candles_flowers"])) { 
                                    $dp_id = $_POST["candles_flowers"];
                                    $current_post_field = get_dynamic_product_by_id($dp_id,$dynamic_products); 
                                    $name = get_field('dp_name',$dp_id);
                                    $image = get_field('dp_image',$dp_id);
                                    
                                    //price
                                    $price = $default_donation_price;
                                    $dp_price = get_field('dp_price',$dp_id);
                                    $new_price = $current_post_field['price'];
                                    if($new_price) {
                                        $price =  '$' . $new_price;
                                    } else if($dp_price) {
                                        $price =  '$' . $dp_price;
                                    }  
                                ?>
                                    <img class="circle" src="<?php echo $image; ?>" alt="">
                                    <span class="price"><?= $price ?></span>
                                <?php } else { ?>
                                    <?php foreach ($dynamic_products as $dp) {
                                        $dp_id = $dp['product']->ID;
                                        $name = get_field('dp_name',$dp_id);
                                        $image = get_field('dp_image',$dp_id);
                                        //price of image
                                        $price = $default_donation_price;
                                        $dp_price = get_field('dp_price',$dp_id);
                                        $new_price = $dp['price'];
                                        if($new_price) {
                                            $price =  '$' . $new_price;
                                        } else if($dp_price) {
                                            $price =  '$' . $dp_price;
                                        }  
                                    ?>
                                        <img class="circle <?php echo $dp_id; ?>-image" src="<?php echo $image; ?>" alt="">
                                        <span class="price-<?php echo $dp_id; ?>"><?php echo $price; ?></span>
                                    <?php } ?>

                                <?php } ?>
                        <?php } ?>
                        <span class="text"><?php _e('Enter credit information', 'remmember'); ?></span>
                        <?php if($payment_error != "") { ?>
                            <div class="wrap-error">
                                <span class="error"><?php echo $payment_error; ?></span>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="wrap-field">
                        <label for="card_number"><?php _e('Card Number', 'remmember'); ?></label>
                        <input <?php if( $payment_error != "" && isset($_POST["card_number"])) { echo 'value="' . $_POST["card_number"] . '"'; } ?> type="number" required placeholder="0000 0000 0000 000" id="card_number" name="card_number">
                    </div>
                    <div class="wrap-form-fields">
                        <div class="wrap-field">
                            <label for="card_date"><?php _e('Expire Date', 'remmember'); ?></label>
                            <input <?php if( $payment_error != "" && isset($_POST["card_date"])) { echo 'value="' . $_POST["card_date"] . '" type="month"'; }  else { echo 'type="text" onfocus="(this.type=`month`)"'; }?> required placeholder="MM/YY" id="card_date" name="card_date">
                        </div>
                        <div class="wrap-field">
                            <label for="card_cvv"><?php _e('CVV', 'remmember'); ?></label>
                            <input <?php if( $payment_error != "" && isset($_POST["card_cvv"])) { echo 'value="' . $_POST["card_cvv"] . '"'; } ?> type="number" required placeholder="..." id="card_cvv" name="card_cvv">
                        </div>
                    </div>
                    <div class="wrap-field checkbox">
                        <input <?php if( $payment_error != "" && isset($_POST["credit_info_card"]) && $_POST["credit_info_card"] == 'on') { echo 'checked="checked"'; } ?> type="checkbox" required id="credit_info_card" name="credit_info_card">
                        <label for="credit_info_card"><?php _e('I Confirm Saving Credit Information', 'remmember'); ?></label>
                    </div>
                    <div class="wrap-field checkbox">
                        <input <?php if( $payment_error != "" && isset($_POST["get_emails"]) && $_POST["get_emails"] == 'on') { echo 'checked="checked"'; } ?> type="checkbox" required id="get_emails" name="get_emails">
                        <label for="get_emails"><?php _e('I Confirm Receipt Of Reminder Updates On This Remembrance Day', 'remmember'); ?></label>
                    </div>
                    <input type="submit" id="submit" value="<?php _e('Complete', 'remmember'); ?>">
                </div>
                <input type="reset" value="" style="display:none;">
            </form>
            <div class="wrap-thank-section" <?php if(!$success_payment) echo "style='display:none;'"; ?>>
                <a href="" class="close" role="button"><img src="/wp-content/uploads/2022/02/Group-956.svg" alt="close candle and flower popup"></a>
                <div class="wrap-top-form">
                    <?php if($dynamic_products && count($dynamic_products)) {
                        $dp_id = $success_payment;
                        $current_post_field = get_dynamic_product_by_id($dp_id,$dynamic_products); 
                        $name = get_field('dp_name',$dp_id);
                        $image = get_field('dp_image',$dp_id);
                        
                    ?>
                        <img class="circle candle-<?php echo $dp_id ?>" src="<?php echo $image; ?>" alt="">
                        <span class="thanks"><?php _e('Thanks', 'remmember'); ?></span>
                        <img src="<?php echo $hero_img; ?>" alt="" class="main-image">
                        <span class="text text_<?php echo $dp_id ?>"><?php _e("You're donate a ", 'remmember');echo $name; ?></span>

                    <?php } else { ?>
                        <?php if( $success_payment == "candle" ) { ?>
                        <img class="circle candle-image" src="/wp-content/uploads/2022/03/Group-963.svg" alt="">
                        <?php } else if( $success_payment == "flower" ) { ?>
                        <img class="circle flower-image" src="/wp-content/uploads/2022/03/Group-965.svg" alt="">
                        <?php } ?>
                        <span class="thanks"><?php _e('Thanks', 'remmember'); ?></span>
                        <img src="<?php echo $hero_img; ?>" alt="" class="main-image">
                        <?php if( $success_payment == "candle" ) { ?>
                        <span class="text text_candle"><?php _e("You're lighting a candle", 'remmember'); ?></span>
                        <?php } else if( $success_payment == "flower" ) { ?>
                        <span class="text text_flower"><?php _e("You're sending a flower", 'remmember'); ?></span>
                        <?php } ?>
                    <?php } ?>
                    <span class="text"><?php _e("You commemorated the memory of the dead", 'remmember'); ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="wrap-content">
        <?php foreach ($candles_flowers as $candles_flower) { 
            $type = $candles_flower->type;
            $name = $candles_flower->name;
            $message = $candles_flower->message;
        ?>
            <div class="wrap-candles_flower">
                <div class="wrap-left-candles_flower">
                   <?php if($type == 'flower')  { ?>
                        <img src="/wp-content/uploads/2022/03/Group-965.svg" alt="">
                   <?php } else if($type == 'candle') { ?>
                        <img src="/wp-content/uploads/2022/03/Group-963.svg" alt="">
                   <?php } else { $image = get_field('dp_image',$type); ?>
                        <img src="<?php echo $image; ?>" alt="">
                    <?php } ?>
                </div>
                <div class="wrap-right-candles_flower">
                    <span class="name"><?php echo($name); ?></span>
                    <span class="message"><?php echo($message); ?></span>              
                </div> 
            </div>
        <?php } ?>
    </div>
</section>