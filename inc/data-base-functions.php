<?php
function create_db_customers_table()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "tb_customers";
  $charset_collate = $wpdb->get_charset_collate();
  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    email tinytext NOT NULL,
    phone tinytext NOT NULL,
    name tinytext NOT NULL,
    PRIMARY KEY  (id)
  ) $charset_collate";
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}

function create_db_customers_and_payments_table()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "tb_customers_and_payments";
  $charset_collate = $wpdb->get_charset_collate();
  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    customer_email tinytext NOT NULL,
    invoice_id tinytext NOT NULL,
    remember_page_id tinytext NOT NULL,
    name tinytext NOT NULL,
    message tinytext NOT NULL,
    type tinytext NOT NULL,
    PRIMARY KEY  (id)
  ) $charset_collate";
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}

function create_db_customers_and_remember_page_table()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "tb_customers_and_remember_page";
  $charset_collate = $wpdb->get_charset_collate();
  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    customer_email tinytext NOT NULL,
    remember_page_id tinytext NOT NULL,
    PRIMARY KEY  (id)
  ) $charset_collate";
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}

function db_add_customer_remember_page($customer_email,$remember_page_id) {
    create_db_customers_and_remember_page_table();
    global $wpdb;
    $table = $wpdb->prefix . 'tb_customers_and_remember_page';
    $rows = $wpdb->get_results("SELECT *  FROM $table WHERE customer_email='$customer_email' AND remember_page_id='$remember_page_id'");
    if(count($rows) == 0) {
      $data = array('customer_email' => $customer_email, 'remember_page_id' => $remember_page_id);
      $format = array('%s', '%s');
      $wpdb->insert($table, $data, $format);
    }
}

function db_create_customer($email,$phone,$name) {
    create_db_customers_table();
    global $wpdb;
    $table = $wpdb->prefix . 'tb_customers';
    $rows = $wpdb->get_results("SELECT *  FROM $table WHERE email='$email'");
    if(count($rows) > 0) {
        if('From lead' !== $name) //don't clear customer's details that lead from rememebr form
          db_update_customer($email,$phone,$name);
    } else {
        $data = array('email' => $email, 'phone' => $phone , "name" =>  $name);
        $format = array('%s', '%d', '%s');
        $wpdb->insert($table, $data, $format);
    }
}

function db_update_customer($email,$phone,$name) {
    create_db_customers_table();
    global $wpdb;
    $table = $wpdb->prefix . 'tb_customers';
    $data = array('phone' => $phone , "name" =>  $name);
    $format = array('%s', '%d', '%s');
    $where = [ 'email' => $email ]; 
    $wpdb->update( $table, $data, $where ); // Also works in this case.
}

function db_add_payment_to_customer($customer_email,$invoice_id,$customer_name,$message,$type,$remember_page_id) {
    create_db_customers_and_payments_table();
    global $wpdb;
    $table = $wpdb->prefix . 'tb_customers_and_payments';
    $data = array('customer_email' => $customer_email, 'invoice_id' => $invoice_id , "name" =>  $customer_name, "type" =>  $type, "message" =>  $message, "remember_page_id" =>  $remember_page_id);
    $format = array('%s', '%s', '%s', '%s', '%s', '%s');
    $wpdb->insert($table, $data, $format);
    return true;
}

function db_get_remember_page_payments($remember_page_id) {
    create_db_customers_and_payments_table();
    global $wpdb;
    $table = $wpdb->prefix . 'tb_customers_and_payments';
    $rows = $wpdb->get_results("SELECT *  FROM $table WHERE remember_page_id='$remember_page_id'");
    return $rows;
}

function db_get_remember_pages_payments($remember_pages_arr) {
  $candles_flowers_array = array();
  if($remember_pages_arr && is_array($remember_pages_arr)) {
      foreach ($remember_pages_arr as $post_id) {
          $cf_arr = db_get_remember_page_payments($post_id);
          if($cf_arr && is_array($cf_arr) && count($cf_arr) > 0) 
            $candles_flowers_array = array_merge($candles_flowers_array,$cf_arr);
      }
      
  }
  return $candles_flowers_array;
}

function db_get_uniqe_remember_pages_register($remember_pages_arr){

  create_db_customers_and_remember_page_table();
  $emails = array();

  if($remember_pages_arr && is_array($remember_pages_arr) && count($remember_pages_arr) > 0) {
    global $wpdb;
    $table = $wpdb->prefix . 'tb_customers_and_remember_page';
    $sql_pages_arr = implode(',', $remember_pages_arr);
    $query = "SELECT DISTINCT customer_email FROM $table WHERE remember_page_id IN ($sql_pages_arr)";
    $emails = $wpdb->get_results($query);

  }
  return count($emails);
}
