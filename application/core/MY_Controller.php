<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class MY_Controller extends CI_Controller {

	public $data = array();
		function __construct() {
			parent::__construct();
			$this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
			// $this->cache->redis->save('key', 'sikap', 10);
			$this->data['errors'] 		= array();
			$this->data['messages'] 	= array();
			$this->data['site_name'] 	= "SIKAP";
			$this->data['keywords'] 	= "sikap agam, aplikasi absensi agam, laporan kerja harian, LKH, SIKAP, aplikasi TPP, Sistem Informasi Kinerja Aparatur, Sistem Informasi Kinerja Aparatur Pemerintah";
			$this->data['description'] 	= "Aplikasi TPP Kabupaten Agam";
			$this->data['regency'] 		= "Kabupaten Agam";
			$this->data['regency_logo'] = base_url('public/images/agam.png');
			$this->data['favicon'] 		= base_url('public/images/favicon.ico');
			$this->data['author'] 		= 'Rian Reski';
			$this->data['development']  = 'Handika Putra';
			$this->load->section('analyticstracking', 'analyticstracking');

			if (base_url() == "https://sikap.agamkab.go.id" || base_url() == "http://sikap.agamkab.go.id") {
				$this->data['db_connect'] = "simpeg";
			} else {
				$this->data['db_connect'] = "simpeg";
			}
		}
}