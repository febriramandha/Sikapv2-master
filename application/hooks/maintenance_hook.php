<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Check whether the site is offline or not.
 *
 */
class Maintenance_hook
{
    public function __construct(){
        log_message('debug','Accessing maintenance hook!');
    }
    
    public function offline_check(){
        if(file_exists(APPPATH.'config/config.php')){
            include(APPPATH.'config/config.php');
            
            if(!(in_array($_SERVER['REMOTE_ADDR'], $config['maintenance_ips']) || in_array($_SERVER['HTTP_X_FORWARDED_FOR'], $config['maintenance_ips'])) && $config['maintenance_mode'] === TRUE){
                include(APPPATH.'views/maintenance.php');
                exit;
            }
        }
    }
}