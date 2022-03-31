<?php 

//create list when publish new post
function dome_publish_post( $new_status, $old_status, $post ) {
    if(get_post_type( $post->ID ) == "remmember_page") {
        if ( $new_status == 'publish' && $old_status != 'publish' ) {
            $post_slug = $post->post_name;
            $post_id = $post->ID;
            create_list_sendinblue_function($post_slug,$post_id);
        }
    }
}
add_action('transition_post_status', 'dome_publish_post', 10, 3 );

function create_list_sendinblue_function($name_slug,$post_id) {
    if(!get_remember_post_sendinblue_list_id($post_id)) {
        $name_slug = $name_slug . "_" . $post_id;
        $curl = curl_init();

        curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/lists",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"name\":\"$name_slug\",\"folderId\":41}",
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/json",
            "api-key: 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"
        ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            set_remember_post_sendinblue_list_id($post_id,$response -> id);
        }
    }
    
}

function create_contact_sendinblue($list_id,$post_id,$email) {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.sendinblue.com/v3/contacts",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"email\":\"$email\",\"emailBlacklisted\":false,\"smsBlacklisted\":false,\"listIds\":[$list_id],\"updateEnabled\":false }" ,
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/json",
            "api-key: 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"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
}

function get_count_list_contacts_sendinblue($list_id,$offset) {
    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/lists/$list_id/contacts?limit=1&offset=$offset&sort=desc",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Accept: application/json",
        "api-key: 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"
    ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        return json_decode($response);
    }
}
function is_exist_contact_by_email($email){
    
    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/$email",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Accept: application/json",
        "api-key: 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"
    ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $response = json_decode($response);
        // error_log("🤣🤣");
        // error_log(print_r($response,true));
        // error_log(isset($response->id));
        // error_log("🤣🤣");
        if(isset($response->id)) {
            return $response;
        } else {
            return false;
        }
    }
}
function update_contact_lists_array($contact_email,$list_ids) {

    $list_ids = json_encode($list_ids);
    //error_log($list_ids);
    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/$contact_email",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "PUT",
    CURLOPT_POSTFIELDS => "{\"listIds\":$list_ids}",
    CURLOPT_HTTPHEADER => [
        "Accept: application/json",
        "Content-Type: application/json",
        "api-key: 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"
    ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
        // error_log("😁");
        // error_log($response);
        // error_log("😁");
    }
}

//set list id to remember page
function set_remember_post_sendinblue_list_id($remember_post_id,$list_id) {
    $sendinblue_list_id = $list_id;
    update_post_meta( $remember_post_id, '_sendinblue_list_id', $sendinblue_list_id );
}

//get list id of remember page
function get_remember_post_sendinblue_list_id($remember_post_id){
    $sendinblue_list_id = get_post_meta( $remember_post_id, '_sendinblue_list_id', true );
    return $sendinblue_list_id;
}

//create sendinblue contact
function register_email_to_spec_remmember_page($email,$remmember_post_id) {
    $post_slug = get_post_field('post_name',$remmember_post_id);
    $list_id = get_remember_post_sendinblue_list_id($remmember_post_id);
    if(!$list_id) {
        create_list_sendinblue_function($post_slug,$remmember_post_id);
        $list_id = get_remember_post_sendinblue_list_id($remmember_post_id);
    }
    if($list_id) {
        //check if exist contact with this email
        $exist_contact = is_exist_contact_by_email($email);
        if(!$exist_contact) {
            create_contact_sendinblue($list_id,$remmember_post_id,$email); 
        } else {
            //add this contact to list conatcts
            $list_ids = $exist_contact -> listIds;
            //add list id to list_ids
            $list_ids[] = (int)$list_id;
            update_contact_lists_array($email,$list_ids);
        }
    }
}
//get count of page contacts
function get_register_email_to_spec_remmember_page($remember_post_id){
    $offset = 0;
    $list_id = get_remember_post_sendinblue_list_id($remember_post_id);
    $contacts_count = 0;
    if($list_id) {
        $result = get_count_list_contacts_sendinblue($list_id,$offset);
        /*while(isset($result->count) && $result->count <= 1 ) { //load all contacts*/
        if(isset($result->count)) {
            $count = $result -> count;
            $contacts_count = $contacts_count + $count;
        }
        //$offset = $offset + 1;
        //$result = get_list_contacts_sendinblue($list_id,$offset);
        /*}*/
    }
    return $contacts_count;
}

function get_register_emails_for_remember_pages($posts_ids) {
    $count = 0;
    if($posts_ids && is_array($posts_ids)) {
        foreach ($posts_ids as $post_id) {
            $emails_count = get_register_email_to_spec_remmember_page($post_id);
            $count = $count + $emails_count;
        }
        
    }
    return $count;
}


//create_list_sendinblue_function('maurice-wohl',271);
// echo ❤️❤️❤️;
// echo get_remember_post_sendinblue_list_id(271);
// echo ❤️❤️❤️;