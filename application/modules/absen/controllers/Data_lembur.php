<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Data_lembur extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Data Lembur', 'absen/data-lembur');
		$this->data['title'] = "Kehadiran";
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
		$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Data Lembur";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('data_lembur/v_index', $this->data);
	}

}

/* End of file Data_lembur.php */
/* Location: ./application/modules/Absen/controllers/Data_lembur.php */