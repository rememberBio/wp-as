<?php
    $post_id = get_the_ID();
    $url = get_permalink();
    //get comments
    $candles_flowers = get_remember_post_candles_flowers($post_id);
    $num_of_candles = 0;
    $num_of_flowers = 0;
    $hero_img = get_field("main_image_of_the_deceased",$post_id);
    $hero_name = get_field("full_name_of_the_deceased",$post_id);

    if($candles_flowers && is_array($candles_flowers)) {
        //functions for array filter
        function is_candle($value)
        {
            return $value['type'] == 'candle';
        }

        function is_flower($value)
        {
            return $value['type'] == 'flower';
        }
    
        $candles = array_filter($candles_flowers,"is_candle");
        if(is_array($candles)) $num_of_candles = count($candles);
        $flowers = array_filter($candles_flowers,"is_flower");
        if(is_array($flowers)) $num_of_flowers = count($flowers);
    }

    $candle_product_id = 210;
    $flower_candle_id = 212;

    $candle_product = wc_get_product( $candle_product_id );
    $flower_product = wc_get_product( $flower_candle_id );
    $candle_product_price = get_woocommerce_currency_symbol(). $candle_product -> get_price() ;
    $flower_product_price = get_woocommerce_currency_symbol() . $flower_product -> get_price();

    $payment_error = "";
    $success_payment = false;
    if(isset($_GET["payment"])) $success_payment = $_GET["payment"];
    if(isset($_GET["cf_post"]) && isset($_POST["cf_post_ID"])) {
        $cf = $_POST["candles_flowers"];
        $name = $_POST['senderName'];
        $message = $_POST['senderMessage'];
        $email = $_POST['senderEmail'];
        $phone = $_POST['senderPhone'];
        //$card_name = $_POST['card_name'];
        //$card_id = $_POST['card_id'];
        $card_number = $_POST['card_number'];
        $card_date = $_POST['card_date'];
        $card_cvc = $_POST['card_cvv'];
        $save_card_info = $_POST['credit_info_card'];
        $get_emails = $_POST['get_emails'];

        //create sendinblue contact
        register_email_to_spec_remmember_page($email,$post_id);
        //create stripe customer
        $customer_id = stripe_create_customer($phone,$email);
        if($customer_id) {
            //create payment method
            $card_year_ex = date("Y",strtotime($card_date));
            $card_month_ex = date("m",strtotime($card_date));
            $result = stripe_create_payment_method($card_number,$card_month_ex,$card_year_ex,$card_cvc);
            if($result['result'] == 'error') {
                $payment_error = $result['error'];
            } else {
                $payment_method_id = $result['id'];
                //create payment intent
                $payment_intend_result = stripe_create_payment_intend($payment_method_id,$customer_id,100,'USD',"create from remember page");
                if($payment_intend_result['result'] == 'error') {
                    $payment_error = $payment_intend_result['error'];
                } else {
                    $payment_intend_id = $payment_intend_result['id'];
                    //redirect to current page
                    wp_safe_redirect( $url . "?tab=candle-and-flowers&payment=" . $cf);
                    exit();
                }
            }

        }
       
    }
?>

<section class="candles-flowers">
    <h1>Candle Or Flower</h1>
    <div class="write-candles-flowers <?php if($success_payment || $payment_error != "") echo "opened"; ?>">
        <div class="write-candles-flowers-btn">
            <img src="/wp-content/uploads/2022/03/Group-425-1.svg" alt="">
           
            <div class="right-btn">
                <span class="num"><?php echo $num_of_candles; ?></span>
                <span class="desc">Candles</span>
                <span class="text">have been lit until now</span>
                <a data-product="candle" href="">light a candle</a>
            </div>
        </div>
        <div class="write-candles-flowers-btn">
            <img src="/wp-content/uploads/2022/03/Group-424.svg" alt="">
            <div class="right-btn">
                <span class="num"><?php echo $num_of_flowers; ?></span>
                <span class="desc">Flowers</span>
                <span class="text">have been Sent until now</span>
                <a data-product="flower"  href="">send a flower</a>
            </div>
        </div>
        <div class="wrap-write-candles-flowers-form">
            <form action="<?php echo $url ?>?tab=candle-and-flowers&cf_post=true" id="cfform" method="POST" <?php if($success_payment) echo "style='display:none;'"; ?> >
                <a href="" class="close" role="button"><img src="/wp-content/uploads/2022/02/Group-956.svg" alt="close candle and flower popup"></a>
                <div class="step-1">
                    <div class="wrap-top-form">
                        <?php if($payment_error != "") { ?>
                            <span class="error"><?php echo $payment_error; ?></span>
                        <?php } ?>
                        <span class="head">What would you like to send?</span>
                        <div class="wrap-radio <?php if( $payment_error != "" && isset($_POST["candles_flowers"]) && $_POST["candles_flowers"] == "flower" ) { echo ""; } else echo 'current'; ?>">
                            <span class="price"><?= $candle_product_price ?></span>
                            <label for="candle">candle</label>
                            <input type="radio" <?php if( $payment_error != "" && isset($_POST["candles_flowers"]) && $_POST["candles_flowers"] == "flower" ) { echo ""; } else echo 'checked="checked"'; ?> name="candles_flowers" id="candle" value="candle">
                        </div>
                        <div class="wrap-radio <?php if( $payment_error != "" && isset($_POST["candles_flowers"]) && $_POST["candles_flowers"] == "flower" ) { echo "current"; } ?>">
                            <span class="price"><?= $flower_product_price ?></span>
                            <label for="flower">flower</label>
                            <input type="radio" name="candles_flowers" <?php if( $payment_error != "" && isset($_POST["candles_flowers"]) && $_POST["candles_flowers"] == "flower" ) { echo 'checked="checked"'; } ?>  id="flower" value="flower">
                        </div>
                    </div>
                    <input required type="text" <?php if( $payment_error != "" && isset($_POST["senderName"])) { echo 'value="' . $_POST["senderName"] . '"'; } ?> maxlength="12" placeholder="Name" id="senderName" name="senderName" size="30" maxlength="245">
                    <textarea  maxlength="30" <?php if( $payment_error != "" && isset($_POST["senderMessage"])) { echo 'value="' . $_POST["senderMessage"] . '"'; } ?> name="senderMessage" id="senderMessage" cols="30" rows="10" aria-required="true" placeholder="Message To Family Or Dead"></textarea>
                    
                    <input <?php if( $payment_error != "" && isset($_POST["senderEmail"])) { echo 'value="' . $_POST["senderEmail"] . '"'; } ?> required type="email" placeholder="Your Email" id="senderEmail" name="senderEmail" >
                    <input <?php if( $payment_error != "" && isset($_POST["senderPhone"])) { echo 'value="' . $_POST["senderPhone"] . '"'; } ?> required type="tel"placeholder="Your Phone" id="senderPhone" name="senderPhone">

                    <input type="button" id="next-button" value="Send">
                    <input type="hidden" name="cf_post_ID" value="<?php echo $post_id; ?>" id="comment_post_ID">
                </div>
                <div class="step-2" style="display:none;">
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
                        <span class="text">Enter credit information</span>
                        
                    </div>
                    <!-- <div class="wrap-field">
                        <label for="card_name">Name On Card</label>
                        <input type="text"  required placeholder="Full Name" id="card_name" name="card_name"> 
                    </div> -->
                    <!-- <div class="wrap-field">
                        <label for="card_id">ID Number</label>
                        <input type="number" required placeholder="000000000" id="card_id" name="card_id">
                    </div> -->
                    <div class="wrap-field">
                        <label for="card_number">Card Number</label>
                        <input <?php if( $payment_error != "" && isset($_POST["card_number"])) { echo 'value="' . $_POST["card_number"] . '"'; } ?> type="number" required placeholder="0000 0000 0000 000" id="card_number" name="card_number">
                    </div>
                    <div class="wrap-form-fields">
                        <div class="wrap-field">
                            <label for="card_date">Expire Date</label>
                            <input <?php if( $payment_error != "" && isset($_POST["card_date"])) { echo 'value="' . $_POST["card_date"] . '"'; } ?> type="date" required placeholder="MM/YY" id="card_date" name="card_date">
                        </div>
                        <div class="wrap-field">
                            <label for="card_cvv">CVV</label>
                            <input <?php if( $payment_error != "" && isset($_POST["card_cvv"])) { echo 'value="' . $_POST["card_cvv"] . '"'; } ?> type="number" required placeholder="" id="c" name="card_cvv">
                        </div>
                    </div>
                    <div class="wrap-field">
                        <label for="credit_info_card">I want to keep my credit information</label>
                        <input <?php if( $payment_error != "" && isset($_POST["credit_info_card"]) && $_POST["credit_info_card"] == 'on') { echo 'checked="checked"'; } ?> type="checkbox" required id="credit_info_card" name="credit_info_card">
                    </div>
                    <div class="wrap-field">
                        <label for="get_emails">I want to get updates about <?php echo $hero_name; ?></label>
                        <input <?php if( $payment_error != "" && isset($_POST["get_emails"]) && $_POST["get_emails"] == 'on') { echo 'checked="checked"'; } ?> type="checkbox" required id="get_emails" name="get_emails">
                    </div>
                    <input type="submit" id="submit" value="Complete">
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
                    <img src="<?php echo $hero_img; ?>" alt="" class="main-image">
                    <?php if( $success_payment == "candle" ) { ?>
                    <span class="text text_candle">You're lighting a candle</span>
                    <?php } else if( $success_payment == "flower" ) { ?>
                    <span class="text text_flower">You're sending a flower</span>
                    <?php } ?>
                    <span class="text">You commemorated the memory of the dead</span>
                </div>
            </div>
        </div>
    </div>
    <div class="wrap-content">
        <?php foreach ($candles_flowers as $candles_flower) { 
            $type = $candles_flower['type'];
            $name = $candles_flower['name'];
            $message = $candles_flower['message'];
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

<script>
</script>