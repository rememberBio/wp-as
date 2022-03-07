<?php
    $post_id = get_the_ID();
    $url = get_permalink();
    //get comments
    $candles_flowers = get_remember_post_candles_flowers($post_id);
    $num_of_candles = 0;
    $num_of_flowers = 0;

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
                <a href="" role="button"><img src="/wp-content/uploads/2022/02/Group-956.svg" alt="close candle and flower popup"></a>
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
                <input type="submit" id="submit" value="Send">
                <input type="hidden" name="cf_post_ID" value="<?php echo $post_id; ?>" id="comment_post_ID">
            </form>
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
