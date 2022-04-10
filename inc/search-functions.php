<?php

function search_remember_page_by_name($name) {

    $args = array(
        'post_type'		=> 'remmember_page',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        'meta_query' => array(
            array(
                'key' => 'full_name_of_the_deceased',
                'value' => $name,
                'compare' => 'LIKE',
            )
        )
    );
    $wp_query = new WP_Query($args);
    return $wp_query -> posts;
}
function search_remember_page_by_death_date($year,$month) {
    $date = $year . '-' . $month . '-1';
    $date = date("Y-m-d H:i", strtotime($date));

    $month_after = $month + 1;

    if($month_after > 12) {
        $month_after = 1;
        $year = $year + 1;
    }

    $date_after = $year . '-' . $month_after . '-1';
    $date_after = date("Y-m-d H:i", strtotime($date_after));

    $args = array(
        'post_type'		=> 'remmember_page',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        'meta_query' => array(
            array(
               'key'		=> 'about_death_day',
               'value' => array($date, $date_after),
                'compare' => 'BETWEEN',
                'type' => 'DATE'
           )
       ),
    );
    $wp_query = new WP_Query($args);
    return $wp_query -> posts;
}
function search_remember_page_by_location($location_name,$lat,$lng) {
    $args = array(
        'post_type'		=> 'remmember_page',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key'       => 'the_grave_in_google_maps',
                'value'     => $lng,
                'compare'   => 'LIKE',
            ),
            array(
                'key'       => 'the_grave_in_google_maps',
                'value'     => $lat,
                'compare'   => 'LIKE',
            )
        )
        
    );
    $wp_query = new WP_Query($args);
    return $wp_query -> posts;
}
