<?php
$secret_key = 'sk_test_51KfKMZAZSRIVhKs4wMjF3OKwtGPWEh1AoWERCUOMUwtsVIP08SXKfDeodLvVlO7kFtMfhnYgoekabQVFEAQ9UEMG00XsvhTKe7';
$publish_key = 'pk_test_51KfKMZAZSRIVhKs426i3khXtrCypbfOmUuSwbWoB3nlxaWv6Gl3sMypS0ppZYrSDxSoT87o3Zn4IMmrEAb1UZi4F00nwx25qN8';

//stripe customer functions
function stripe_create_or_update_customer($phone,$email) {
    $customer = stripe_get_customer_by_email($email);
    if(!$customer) { //if there are customer with same email
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.stripe.com/v1/customers",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "email=$email&description=create by remember page&phone=$phone",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer sk_test_51KfKMZAZSRIVhKs4wMjF3OKwtGPWEh1AoWERCUOMUwtsVIP08SXKfDeodLvVlO7kFtMfhnYgoekabQVFEAQ9UEMG00XsvhTKe7"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            if(isset($response -> id) ) return $response -> id;
            return false;
        }
    } else return stripe_update_customer($customer -> id,$phone); //else update the customer
}

function stripe_update_customer($customer_id,$phone) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.stripe.com/v1/customers/$customer_id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "phone=$phone&description=create and update by remember page",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer sk_test_51KfKMZAZSRIVhKs4wMjF3OKwtGPWEh1AoWERCUOMUwtsVIP08SXKfDeodLvVlO7kFtMfhnYgoekabQVFEAQ9UEMG00XsvhTKe7"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $response = json_decode($response);
        //var_dump($response);
        if(isset($response -> id) ) return $response -> id;
        return false;
    }
}

function stripe_get_customer_by_email($email) {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.stripe.com/v1/customers",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "email=$email",
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Authorization: Bearer sk_test_51KfKMZAZSRIVhKs4wMjF3OKwtGPWEh1AoWERCUOMUwtsVIP08SXKfDeodLvVlO7kFtMfhnYgoekabQVFEAQ9UEMG00XsvhTKe7"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $response = json_decode($response);
        if(isset($response -> data) && count($response -> data) > 0) return $response -> data[0];
        return false;
    }
}

//stripe payment method functions\
function stripe_create_payment_method($card_number,$card_ex_mo,$card_ex_year,$card_cvc,$phone,$email) {
    if($card_number && $card_ex_mo && $card_ex_year && $card_cvc) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.stripe.com/v1/payment_methods",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "type=card&card[number]=$card_number&card[exp_month]=$card_ex_mo&card[exp_year]=$card_ex_year&card[cvc]=$card_cvc&billing_details[phone]=$phone&billing_details[email]=$email",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer sk_test_51KfKMZAZSRIVhKs4wMjF3OKwtGPWEh1AoWERCUOMUwtsVIP08SXKfDeodLvVlO7kFtMfhnYgoekabQVFEAQ9UEMG00XsvhTKe7"
            ],
        ]);
    
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            if(isset($response -> error) ) return ["result" => 'error' , 'error' => $response -> error -> message ];
            if(isset($response -> id) ) return ["result" => 'success' , 'id' => $response -> id ];
            return["result" => 'error' , 'error' => ['message' => "unknown error in create payment method"] ];
        }
    } else return ["result" => 'error' , 'error' => ['message' => "Missing credit information"] ];
}

function stripe_attach_payment_method_to_customer($customer_id,$payment_method_id) {
    if($customer_id && $payment_method_id) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.stripe.com/v1/payment_methods/$payment_method_id/attach",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "customer=$customer_id",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer sk_test_51KfKMZAZSRIVhKs4wMjF3OKwtGPWEh1AoWERCUOMUwtsVIP08SXKfDeodLvVlO7kFtMfhnYgoekabQVFEAQ9UEMG00XsvhTKe7"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            //var_dump($response);
            if(isset($response -> error) ) return ["result" => 'error' , 'error' => $response -> error -> message ];
            if(isset($response -> id) ) return ["result" => 'success' , 'id' => $response -> id ];
            return["result" => 'error' , 'error' => ['message' => "unknown error in attach invoice item"] ];
        }
    } else return["result" => 'error' , 'error' => ['message' => "missing information in attach invoice item"] ];
}
function stripe_create_payment_intend($payment_method_id,$customer_id,$amount,$remember_page_url,$currency = 'USD',$description="") {
    if( $payment_method_id && $customer_id) {
        $post_fields = "currency=$currency&confirm=true&customer=$customer_id&payment_method=$payment_method_id&amount=$amount&metadata[remember_page_url]=$remember_page_url&payment_method_types[]=card&description=$description";
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.stripe.com/v1/payment_intents",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post_fields,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer sk_test_51KfKMZAZSRIVhKs4wMjF3OKwtGPWEh1AoWERCUOMUwtsVIP08SXKfDeodLvVlO7kFtMfhnYgoekabQVFEAQ9UEMG00XsvhTKe7"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            //var_dump($response);
            if(isset($response -> error) ) return ["result" => 'error' , 'error' => $response -> error -> message ];
            if(isset($response -> id) ) return ["result" => 'success' , 'id' => $response -> id ];
            return["result" => 'error' , 'error' => ['message' => "unknown error in create payment intend"] ];
        }
    } else return ["result" => 'error' , 'error' => ['message' => "Missing payment information"] ];
}

//invoice and payment function
function stripe_create_invoice_item($customer_id,$price_id,$is_dynamic_product = false,$dynamic_price_data=array()) {
    if($customer_id && ($price_id || $is_dynamic_product)) {
        $curl = curl_init();
        $CURLOPT_POSTFIELDS = "customer=$customer_id&price=$price_id";
        if($is_dynamic_product) {
            $currency = $dynamic_price_data['currency'];
            $unit_amount = $dynamic_price_data['unit_amount'];
            $product = $dynamic_price_data['product'];
            $CURLOPT_POSTFIELDS = "customer=$customer_id&price_data[currency]=$currency&price_data[unit_amount]=$unit_amount&price_data[product]=$product";
        }
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.stripe.com/v1/invoiceitems",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $CURLOPT_POSTFIELDS,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer sk_test_51KfKMZAZSRIVhKs4wMjF3OKwtGPWEh1AoWERCUOMUwtsVIP08SXKfDeodLvVlO7kFtMfhnYgoekabQVFEAQ9UEMG00XsvhTKe7"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        var_dump($response);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            //var_dump($response);
            if(isset($response -> error) ) return ["result" => 'error' , 'error' => $response -> error -> message ];
            if(isset($response -> id) ) return ["result" => 'success' , 'id' => $response -> id ];
            return["result" => 'error' , 'error' => ['message' => "unknown error in create invoice item"] ];
        }
    } else return["result" => 'error' , 'error' => ['message' => "missing information in create invoice item"] ];
}
function stripe_create_invoice($customer_id,$is_dynamic_product = false,$dynamic_product_name="") {
    if($customer_id) {
        $curl = curl_init();
        $CURLOPT_POSTFIELDS = "customer=$customer_id&metadata[more_details]=create invoice";
        if($is_dynamic_product) {
            $CURLOPT_POSTFIELDS = "customer=$customer_id&metadata[product_name]=" . $dynamic_product_name;
        }
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.stripe.com/v1/invoices",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $CURLOPT_POSTFIELDS,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer sk_test_51KfKMZAZSRIVhKs4wMjF3OKwtGPWEh1AoWERCUOMUwtsVIP08SXKfDeodLvVlO7kFtMfhnYgoekabQVFEAQ9UEMG00XsvhTKe7"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            //var_dump($response);
            if(isset($response -> error) ) return ["result" => 'error' , 'error' => $response -> error -> message ];
            if(isset($response -> id) ) return ["result" => 'success' , 'id' => $response -> id];
            return["result" => 'error' , 'error' => ['message' => "unknown error in create invoice"] ];
        }
    } else return["result" => 'error' , 'error' => ['message' => "missing information in create invoice"] ];
}
function stripe_pay_the_invoice($invoice_id,$payment_method_id) {
    if($invoice_id && $payment_method_id) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.stripe.com/v1/invoices/$invoice_id/pay",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "payment_method=$payment_method_id",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer sk_test_51KfKMZAZSRIVhKs4wMjF3OKwtGPWEh1AoWERCUOMUwtsVIP08SXKfDeodLvVlO7kFtMfhnYgoekabQVFEAQ9UEMG00XsvhTKe7"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            if(isset($response -> error) ) return ["result" => 'error' , 'error' => $response -> error -> message ];
            if(isset($response -> id) ) return ["result" => 'success' , 'id' => $response -> id ];
            return["result" => 'error' , 'error' => ['message' => "unknown error in pay invoice"] ];
        }
    } else return["result" => 'error' , 'error' => ['message' => "missing information in pay invoice"] ];
}
function stripe_send_invoice($invoice_id) {
    if($invoice_id) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.stripe.com/v1/invoices/$invoice_id/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer sk_test_51KfKMZAZSRIVhKs4wMjF3OKwtGPWEh1AoWERCUOMUwtsVIP08SXKfDeodLvVlO7kFtMfhnYgoekabQVFEAQ9UEMG00XsvhTKe7"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            //var_dump($response);
            if(isset($response -> error) ) return ["result" => 'error' , 'error' => $response -> error -> message ];
            if(isset($response -> id) ) return ["result" => 'success' , 'id' => $response -> id ];
            return["result" => 'error' , 'error' => ['message' => "unknown error in send invoice"] ];
        }
    } else return["result" => 'error' , 'error' => ['message' => "missing information in send invoice"] ];
}
function stripe_create_payment_and_invoice($customer_id,$price_id,$payment_method_id,$is_dynamic_product=false,$dynamic_price_data,$dynamic_product_name) {
    //attach payment method to customer
    $result_attach_payment_method = stripe_attach_payment_method_to_customer($customer_id,$payment_method_id);
    if($result_attach_payment_method['result'] == 'error') {
        //$result_attach_payment_method['error'] = 'Error in create payment, please try later';
        return $result_attach_payment_method;
    }
    if(!$is_dynamic_product) {
        //create invoice item
        $result_create_invoice_item = stripe_create_invoice_item($customer_id,$price_id);
    } else {
        $result_create_invoice_item = stripe_create_invoice_item($customer_id,$price_id,$is_dynamic_product,$dynamic_price_data);
    }
    if($result_create_invoice_item['result'] == 'error') {
        //$result_create_invoice_item['error'] = 'Error in create payment, please try later';
        return $result_create_invoice_item;
    } 
    //create invoice
    if(!$is_dynamic_product) {
        $result_create_invoice = stripe_create_invoice($customer_id);
    } else {
        $result_create_invoice = stripe_create_invoice($customer_id,$is_dynamic_product,$dynamic_product_name);
    }
    if($result_create_invoice['result'] == 'error') return $result_create_invoice;
    $invoice_id = $result_create_invoice['id'];

    //pay for invoice
    $result_pay_invoice = stripe_pay_the_invoice($invoice_id,$payment_method_id);
    if($result_pay_invoice['result'] == 'error') return $result_pay_invoice;

    return ["result" => 'success' , 'id' => $invoice_id ];
}