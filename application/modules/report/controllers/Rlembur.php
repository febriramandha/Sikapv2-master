<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Rlembur extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Laporan Lembur', 'report/rlembur');
		$this->data['title'] = "Laporan Umum";
		$this->load->model('m_instansi');
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->css('public/themes/plugin/clock/dist/bootstrap-clockpicker.min.css');
		$this->load->js('public/themes/plugin/clock/dist/bootstrap-clockpicker.min.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Laporan Lembur";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('rlembur/v_index', $this->data);
	}

}

/* End of file Rlembur.php */
/* Location: ./application/modules/report/controllers/Rlembur.php */