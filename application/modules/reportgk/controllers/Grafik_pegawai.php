<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Grafik_pegawai extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Grafik Pegawai', 'mnglkh/schlkh');
		$this->data['title'] = "Laporan Khusus";
		$this->load->model(['m_instansi']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/plugin/Highcharts-7.2.0/code/highcharts.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Grafik Pegawai";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('grafik_pegawai/v_index', $this->data);
	}


}

/* End of file Grafik_pegawai.php */
/* Location: ./application/modules/reportgk/controllers/Grafik_pegawai.php */