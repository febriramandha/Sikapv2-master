<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $data = array();
		function __construct() {
			parent::__construct();
			$this->data['errors'] 		= array();
			$this->data['messages'] 	= array();
			$this->data['site_name'] 	= "SIKAP";
			$this->data['keywords'] 	= "sikap agam, aplikasi absensi agam, laporan kerja harian, LKH, SIKAP, aplikasi TPP, rianreski, Rian Reski";
			$this->data['description'] 	= "Aplikasi TPP Agam";
			$this->data['regency'] 		= "Kabupaten Agam";
			$this->data['regency_logo'] = base_url('public/images/agam.png');
			$this->data['favicon'] 		= base_url('public/images/favicon.ico');
			$this->data['author'] 		= 'Rian Reski A';
		}
}



