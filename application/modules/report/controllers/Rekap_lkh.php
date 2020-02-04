<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Rekap_lkh extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Rekapitulasi LKH', 'report/rekap-lkh');
		$this->data['title'] = "Laporan Umum";
		$this->load->model(['m_instansi','m_pejabat_instansi','m_absen','m_sch_run']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
    	$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Rekapitulasi LKH";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->data['laporan_tahun'] = $this->m_sch_run->GetTahun()->result();
		$this->load->view('rekap_lkh/v_index', $this->data);
	}
}

/* End of file Rekap_lkh.php */
/* Location: ./application/modules/report/controllers/Rekap_lkh.php */