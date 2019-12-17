<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Dl_manual extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Dinas Luar Manual', 'kepegawaian/dl-manual');
		$this->data['title'] = "Kepegawaian";
		$this->load->model(['m_instansi','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/plugin/autocomplete/dist/latest/bootstrap-autocomplete.js');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Data Dinas Luar Manual";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('dl_manual/v_index', $this->data);
	}

	public function view($id)
	{
		$this->data['sub_title'] 	= "Dinas Luar";
		$this->breadcrumbs->push('Dinas Luar', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->load->view('dl_manual/v_view', $this->data);
	}

}

/* End of file Dl_manual.php */
/* Location: ./application/modules/Kepegawaian/controllers/Dl_manual.php */