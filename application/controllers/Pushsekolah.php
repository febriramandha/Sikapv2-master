<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pushsekolah extends MY_Controller {
    public function __construct() {
		parent::__construct ();
	}
    public function index(){
        $select = $this->db->select('*')
                ->from('sch_run')
                ->where('id','161')->get()->result();

        
    }
}