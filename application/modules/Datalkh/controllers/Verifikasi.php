<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Verifikasi extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Verifikasi LKH', 'datalkh/verifikasi');
		$this->data['title'] = "Verifiikasi";
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
		$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Verifikasi LKH";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('verifikasi/v_index', $this->data);
	}


}

/* End of file Verifikasi.php */
/* Location: ./application/modules/Datalkh/controllers/Verifikasi.php */