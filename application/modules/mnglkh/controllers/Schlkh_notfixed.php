<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Schlkh_notfixed extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jadwal LKH', 'mnglkh/schlkh-notfixed');
		$this->data['title'] = "Manajemen LKH";
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->js('public/themes/plugin/ckeditor/ckeditor.js');
    	
	}

	public function index()
	{
		$this->data['sub_title']  = "Jadwal LKH Tidak Tetap";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('schlkh_notfixed/v_index', $this->data);
	}

}

/* End of file Schlkh_notfixed.php */
/* Location: ./application/modules/Mnglkh/controllers/Schlkh_notfixed.php */