<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
function encrypt_url($string,$key) {
    $CI=& get_instance();
    $output = false;
    /*
    * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
    */    
    // $date = date('Ymd');
    $user_id = $CI->session->userdata('tpp_login_id');
    $level = $CI->session->userdata('tpp_level');
    // $string = $string.','.$date; 
    // $string = $string.','.$user_id;
    $string = $string;    
    $security       = parse_ini_file("security.ini");
    $secret_key     = $security["encryption_key"].$key.$user_id.$level;
    $secret_iv      = $security["iv"];
    $encrypt_method = $security["encryption_mechanism"];
    // hash
    $key    = hash("sha256", $secret_key);
    // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
    $iv     = substr(hash("sha256", $secret_iv), 0, 16);
    //do the encryption given text/string/number
    $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($result);
    return $output;
}
function decrypt_url($string,$key) {
    $CI=& get_instance();
    $output = false;
    /*
    * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
    */
    $user_id = $CI->session->userdata('tpp_login_id');
    $level = $CI->session->userdata('tpp_level');

    $security       = parse_ini_file("security.ini");
    $secret_key     = $security["encryption_key"].$key.$user_id.$level; 
    $secret_iv      = $security["iv"];
    $encrypt_method = $security["encryption_mechanism"];
    // hash
    $key    = hash("sha256", $secret_key);
    // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
    $iv = substr(hash("sha256", $secret_iv), 0, 16);
    //do the decryption given text/string/number
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

    // $pecah = explode(",", $output);
    // $dateme = '';
    // if(isset($pecah[1])) {
    //     $dateme = $pecah[1];
    // }
    
    
    // if($dateme != date('Ymd')) {
    //     $output = false;
    // }else {
    //     $output = $pecah[0];
    // }
    //  if($dateme != $user_id) {
    //     $output = false;
    // }else {
    //     $output = $pecah[0];
    // }


    return $output;
}

function url_expl_date($string='')
{
     $pecah = explode(",", $teks);
     //mencari element array 0
     $hasil = $pecah[0];
     //tampilkan hasil pemecahan
     return $hasil;
}