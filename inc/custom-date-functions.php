<?php

if(!function_exists('curl_get_call')){
    function curl_get_call($url, $query_params){
        foreach ($query_params as $key => $value) {
            $url = $url . $key.'='.rawurlencode($value).'&';
        }
        $url = substr($url, 0, -1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        return $data;
    }
}

//get hebrew date from Gregorian, default today
if(!function_exists('gregorian_to_hebrew')){
    function gregorian_to_hebrew($year, $month, $day){
        $url = 'https://www.hebcal.com/converter?';
        $query_params = array(
            'cfg' => 'json', //return format
            'gy' => $year, //Gregorian year
            'gm' => $month, //Gregorian month
            'gd' => $day, //Gregorian day
            'g2h' => '1' //from gregorian to hebrew
        );
        $data = curl_get_call($url, $query_params);
        return $data;
    }
}

if(!function_exists('hebrew_to_gregorian')){
    function hebrew_to_gregorian($he_year, $he_month, $he_day){
        $url = 'https://www.hebcal.com/converter?';
        $query_params = array(
            'cfg' => 'json', //return format
            'hy' => $he_year, //hebrew year
            'hm' => $he_month, //hebrew month
            'hd' => $he_day, //hebrew day
            'h2g' => '1' //from hebrew to gregorian
        );
        $data = curl_get_call($url, $query_params);
        return $data;
    }
}

//converted functions
$he_months = array(
    "Tishrei"=>"תשרי",
    "Cheshvan"=>"חשון",
    "Kislev"=>"כסליו",
    "Tevet"=>"טבת",
    "Sh'vat"=>"שבט",
    "Adar"=>"אדר",
    "Adar I"=>"אדר א",
    "Adar II"=>"אדר ב",
    "Nisan"=>"ניסן",
    "Iyyar"=>"אייר",
    "Sivan"=>"סיון",
    "Tamuz"=>"תמוז",
    "Av"=>"אב",
    "Elul"=>"אלול",
);

$gematriya_val = array(
    1=>'א',
    2=>'ב',
    3=>'ג',
    4=>'ד',
    5=>'ה',
    6=>'ו',
    7=>'ז',
    8=>'ח',
    9=>'ט',
    10=>'י',
    20=>'כ',
    30=>'ל',
    40=>'מ',
    50=>'נ',
    60=>'ס',
    70=>'ע',
    80=>'פ',
    90=>'צ',
    100=>'ק',
    200=>'ר',
    300=>'ש',
    400=>'ת'
);

function get_gimatria_digits($num)
{
    $digits = array();
    while($num > 0)
    {
    if($num === 15 || $num === 16)
    {
        array_push($digits, 9);
        array_push($digits, $num-9);
        break;
    }
    $incr = 100;
    for ($i = 400; $i > $num; $i -= $incr) {
        if ($i === $incr) {
        $incr = $incr/10;
        }
    }
    array_push($digits, $i);
    $num -= $i;
    }
    return $digits;
}

function gematriya($num)
{
    global $gematriya_val;
    $str = '';
    $thousands = floor($num / 1000);
    if ($thousands > 0 && $thousands < 5) {
        $tdigits = get_gimatria_digits($thousands);
        for($i = 0 ; $i < count($tdigits) ; $i++) {
            $str = $str.$gematriya_val[$tdigits[$i]];
        }
        $str = $str."'";
    }
    $digits = get_gimatria_digits($num - $thousands*1000);
    if (count($digits) == 1) {
        return $str.$gematriya_val[$digits[0]]."'";
    }
    for($i = 0; $i < count($digits); $i++) {
        if ($i + 1 === count($digits)) {
            $str = $str.'"';
        }
        $str = $str.$gematriya_val[$digits[$i]];
    }
    return $str;
}

function get_str_converted_hebrew_date($he_date) {
    global $he_months;
    return gematriya($he_date['hd']) . ' ' . $he_months[$he_date['hm']] . ' ' . gematriya($he_date['hy']);
}

function get_year_str_converted_hebrew_date($he_date) {
    global $he_months;
    return gematriya($he_date['hy']);
}

function convert_acf_date_to_he_str_date($acf_date,$get_only_year = false,$delimiter = '/') {

    $date_format = 'd' . $delimiter . 'm' . $delimiter . 'Y';
    $converted_date = '';

    $date_time = date_create_from_format($date_format, $acf_date);
    if ( is_object($date_time) ) {
        $day = $date_time->format('d');
        $month = $date_time->format('m');
        $year = $date_time->format('Y');
        $result = gregorian_to_hebrew($year, $month, $day);
        if($result['hebrew']) {
            if($get_only_year) {
                $converted_date = get_year_str_converted_hebrew_date($result);
            } else {
                $converted_date = get_str_converted_hebrew_date($result);
            }
        }
    }
    return $converted_date;
}

