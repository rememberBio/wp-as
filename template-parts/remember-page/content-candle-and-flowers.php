<?php
    $post_id = get_the_ID();
    $url = get_permalink();
    //get comments
    $candles_flowers = get_remember_post_candles_flowers($post_id);
    $num_of_candles = 0;
    $num_of_flowers = 0;
    $hero_img = get_field("main_image_of_the_deceased",$post_id);

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
?>

<section class="candles-flowers">
    <h1>Candle Or Flower</h1>
    <div class="write-candles-flowers">
        <div class="write-candles-flowers-btn">
            <img src="/wp-content/uploads/2022/03/Group-425-1.svg" alt="">
           
            <div class="right-btn">
                <span class="num"><?php echo $num_of_candles; ?></span>
                <span class="desc">Candles</span>
                <span class="text">have been lit until now</span>
                <a href="">light a candle</a>
            </div>
        </div>
        <div class="write-candles-flowers-btn">
            <img src="/wp-content/uploads/2022/03/Group-424.svg" alt="">
            <div class="right-btn">
                <span class="num"><?php echo $num_of_flowers; ?></span>
                <span class="desc">Flowers</span>
                <span class="text">have been Sent until now</span>
                <a href="">send a flower</a>
            </div>
        </div>
        <div class="wrap-write-candles-flowers-form">
            <form action="<?php echo url() . '/checkout?add-to-cart=' . $candle_product_id;  ?>" id="cfform" method="POST">
                <a href="" class="close" role="button"><img src="/wp-content/uploads/2022/02/Group-956.svg" alt="close candle and flower popup"></a>
                <div class="step-1">
                    <div class="wrap-top-form">
                        <span class="head">What would you like to send?</span>
                        <div class="wrap-radio current">
                            <span class="price"><?= $candle_product_price ?></span>
                            <label for="candle">candle</label>
                            <input type="radio" checked name="candles_flowers" id="candle" value="candle">
                        </div>
                        <div class="wrap-radio">
                            <span class="price"><?= $flower_product_price ?></span>
                            <label for="flower">flower</label>
                            <input type="radio" name="candles_flowers" id="flower" value="flower">
                        </div>
                    </div>
                    <input required type="text" maxlength="12" placeholder="Name" id="senderName" name="senderName" size="30" maxlength="245">
                    <textarea  maxlength="30" name="senderMessage" id="senderMessage" cols="30" rows="10" aria-required="true" placeholder="Message To Family Or Dead"></textarea>
                    <input type="button" id="next-button" value="Send">
                    <input type="hidden" name="cf_post_ID" value="<?php echo $post_id; ?>" id="comment_post_ID">
                </div>
                <div class="step-2" style="display:none;">
                    <div class="wrap-top-form">
                        <img class="circle candle-image" src="/wp-content/uploads/2022/03/Group-963.svg" alt="">
                        <img class="circle flower-image" src="/wp-content/uploads/2022/03/Group-965.svg" alt="">
                        <span class="price-candle"><?= $candle_product_price ?></span>
                        <span class="price-flower"><?= $flower_product_price ?></span>
                        <span class="text">Enter credit information</span>
                        
                    </div>
                    <!-- <div class="rapyd">
                        
                        <div class="row justify-content-center">
                            <div class="col text-center my-4" style="display: none" id="feedback">
                                <img src="" id="image" alt="" height="120" class="mt-2">
                                <h3 id="title" class="my-4"></h3>
                                <p id="message"></p>
                                <a role="button" class="btn btn-custom mt-2" href="" id="action"></a>
                            </div>
                        </div>

                       
                        <div class="row justify-content-center">
                            <div class="col" style="max-width: 500px;" id="rapyd-checkout"></div>
                        </div>
                    </div> -->
                    <div class="wrap-field">
                        <label for="card_name">Name On Card</label>
                        <input type="text"  required placeholder="Full Name" id="card_name" name="card_name"> 
                    </div>
                    <div class="wrap-field">
                        <label for="card_id">ID Number</label>
                        <input type="number" required placeholder="000000000" id="card_id" name="card_id">
                    </div>
                    <div class="wrap-field">
                        <label for="card_number">Card Numbrer</label>
                        <input type="number" required placeholder="0000 0000 0000 000" id="card_number" name="card_number">
                    </div>
                    <div class="wrap-form-fields">
                        <div class="wrap-field">
                            <label for="card_date">Expire Date</label>
                            <input type="date" required placeholder="MM/YY" id="card_date" name="card_date">
                        </div>
                        <div class="wrap-field">
                            <label for="card_cvv">CVV</label>
                            <input type="number" required placeholder="" id="card_cvv" name="card_cvv">
                        </div>
                    </div>
                    <input type="submit" id="submit" value="Complete">
                </div>
                <input type="reset" value="" style="display:none;">
            </form>
            <div class="wrap-thank-section" style="display:none;">
                <a href="" class="close" role="button"><img src="/wp-content/uploads/2022/02/Group-956.svg" alt="close candle and flower popup"></a>
                <div class="wrap-top-form">
                    <img class="circle candle-image" src="/wp-content/uploads/2022/03/Group-963.svg" alt="">
                    <img class="circle flower-image" src="/wp-content/uploads/2022/03/Group-965.svg" alt="">
                    <img src="<?php echo $hero_img; ?>" alt="" class="main-image">
                    <span class="text text_candle">You're lighting a candle</span>
                    <span class="text text_flower">You're sending a flower</span>
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
     /*window.onload = function () {
            let checkout = new RapydCheckoutToolkit({
                pay_button_text: "Pay Now",
                pay_button_color: "#4BB4D2",
                id: "", // your checkout page id goes here
                style: {
                    submit: {
                        base: {
                            color: "white"
                        }
                    }
                }
            });
            checkout.displayCheckout();
        }
        window.addEventListener('onCheckoutPaymentSuccess', function (event) {
            console.log(event.detail);
            feedback(event);
        });
        window.addEventListener('onCheckoutFailure', function (event) {
            console.log(event.detail.error);
            feedback(event);
        });
        window.addEventListener('onCheckoutPaymentFailure', (event)=> {
            console.log(event.detail.error);
            feedback(event);
        })


        // display information to the user
        function feedback(event){
            if (event.detail.error){
                document.getElementById('title').textContent = "Whoops!";
                document.getElementById('message').innerHTML = "We cannot process your payment:<br/>" + 
                    event.detail.error;
                document.getElementById('image').src = "img/no-bike.svg";
                document.getElementById('action').textContent = "Try again";
            }
            else {
                document.getElementById('title').textContent = "Success!";
                document.getElementById('message').innerHTML = "Thank you! Your product is on its way!" + 
                    "<br>" +
                    "Order: " + event.detail.metadata.sales_order;
                document.getElementById('image').src = "img/logo.svg";
                document.getElementById('action').textContent = "Home";
            }

            document.getElementById('action').href = "bike.html";
            document.getElementById('feedback').style.display = "block";
        }*/
</script>