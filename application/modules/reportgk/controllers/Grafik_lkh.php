<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Grafik_lkh extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Grafik Kehadiran', 'mnglkh/schlkh');
		$this->data['title'] = "Laporan Khusus";
		$this->load->model(['m_instansi','m_sch_run']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/plugin/Highcharts-7.2.0/code/highcharts.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Grafik LKH";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->data['laporan_tahun'] = $this->m_sch_run->GetTahun()->result();
		$this->load->view('grafik_lkh/v_index', $this->data);
	}

}

/* End of file Grafik_lkh.php */
/* Location: ./application/modules/reportgk/controllers/Grafik_lkh.php */