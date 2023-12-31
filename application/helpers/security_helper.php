<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
function encrypt_url($string,$key, $is_login = true) {
    $CI=& get_instance();
    $output = false;
    /*
    * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
    */    

    $user_id = "";
    $level = "";
    
    if($is_login == true) { 
        $user_id = $CI->session->userdata('tpp_login_id');
        $level = $CI->session->userdata('tpp_level');
    }
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
function decrypt_url($string,$key, $is_login = true) {
    $CI=& get_instance();
    $output = false;
    /*
    * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
    */

    $user_id = "";
    $level = "";
    
    if($is_login == true) { 
        $user_id = $CI->session->userdata('tpp_login_id');
        $level = $CI->session->userdata('tpp_level');
    }
    
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

// simpeg new 
function encrypt_url_public($string, $key) 
{
        $CI=& get_instance();
        $output = false;
        $user_id = '';
        $level = '';
        
        $string = $string;    
        
        $secret_key = 'INTEGRASI'.$key.$user_id.$level;
        $secret_iv = 2456358494765231;
        $encrypt_method = "aes-256-cbc";
        $key = hash("sha256", $secret_key);
        $iv = substr(hash("sha256", $secret_iv), 0, 16);
        $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($result);

        return $output;
}

function decrypt_url_public($string, $key) {
        $CI=& get_instance();
        $output = false;
        $user_id = '';
        $level = '';
        
        $secret_key = 'INTEGRASI'.$key.$user_id.$level;
        $secret_iv = 2456358494765231;
        $encrypt_method = "aes-256-cbc";
        $key = hash("sha256", $secret_key);
        $iv = substr(hash("sha256", $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    
        return $output;
    
}