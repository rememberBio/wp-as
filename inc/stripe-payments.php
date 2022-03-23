<?php
$secret_key = 'sk_test_51KfKMZAZSRIVhKs4wMjF3OKwtGPWEh1AoWERCUOMUwtsVIP08SXKfDeodLvVlO7kFtMfhnYgoekabQVFEAQ9UEMG00XsvhTKe7';
$publish_key = 'pk_test_51KfKMZAZSRIVhKs426i3khXtrCypbfOmUuSwbWoB3nlxaWv6Gl3sMypS0ppZYrSDxSoT87o3Zn4IMmrEAb1UZi4F00nwx25qN8';


function stripe_create_customer($phone,$email) {
    $customer = stripe_get_customer_by_email($email);
    if(!$customer && $email && $email !== "") {
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
    } else return $customer -> id;
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

function stripe_create_payment_method($card_number,$card_ex_mo,$card_ex_year,$card_cvc) {
    if($card_number && $card_ex_mo && $card_ex_year && $card_cvc) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.stripe.com/v1/payment_methods",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "type=card&card[number]=$card_number&card[exp_month]=$card_ex_mo&card[exp_year]=$card_ex_year&card[cvc]=$card_cvc",
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

function stripe_create_payment_intend($payment_method_id,$customer_id,$amount,$currency = 'USD',$description="") {
    if( $payment_method_id && $customer_id) {
        $post_fields = "currency=$currency&confirm=true&customer=$customer_id&payment_method=$payment_method_id&amount=$amount&payment_method_types[]=card&description=$description";
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
            /*var_dump($response);*/
            if(isset($response -> error) ) return ["result" => 'error' , 'error' => $response -> error -> message ];
            if(isset($response -> id) ) return ["result" => 'success' , 'id' => $response -> id ];
            return["result" => 'error' , 'error' => ['message' => "unknown error in create payment intend"] ];
        }
    } else return ["result" => 'error' , 'error' => ['message' => "Missing payment information"] ];
}
//stripe_create_customer("0500000000",'test@test.com');
//stripe_get_customer_by_email("tova@shal3v.com");