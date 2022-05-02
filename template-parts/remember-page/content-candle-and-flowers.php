<?php
    $post_id = get_the_ID();
    $url = get_permalink();

    $hero_img = get_field("main_image_of_the_deceased",$post_id);
    $hero_name = get_field("full_name_of_the_deceased",$post_id);

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
  
    $candle_product_price = '$1';
    $flower_product_price = '$1';

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
        $price_id = $candle_product_price_id;
        if($cf == 'flower') $price_id = $flower_product_price_id;
       
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
                $result_payment = stripe_create_payment_and_invoice($customer_id,$price_id,$payment_method_id);
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
?>

<section class="candles-flowers">
    <h1><?php _e('Candle And Flower', 'remmember'); ?></h1>
    <div class="write-candles-flowers <?php if($success_payment || $payment_error != "") echo "opened"; ?>">
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
        <div class="wrap-write-candles-flowers-form">
            <form action="<?php echo $url ?>?tab=candle-and-flowers&cf_post=true" id="cfform" method="POST" <?php if($success_payment) echo "style='display:none;'"; ?> >
                <a href="" class="close" role="button"><img src="/wp-content/uploads/2022/02/Group-956.svg" alt="close candle and flower popup"></a>
                <div class="step-1" <?php if($payment_error != "") echo "style='display:none;'"; ?>>
                    <div class="wrap-top-form">
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
                   <?php }  else { ?>
                    <img src="/wp-content/uploads/2022/03/Group-963.svg" alt="">
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