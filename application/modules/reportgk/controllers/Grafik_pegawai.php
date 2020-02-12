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
		$this->load->model(['m_instansi','m_grafik']);
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

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('mod');
		if ($this->mod == "Grafik") {
				$instansi = decrypt_url($this->input->get('instansi'),'instansi');
				$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $instansi])->row()->level;
				$this->data['data_grafik'] = $this->m_grafik->GetJUmlah_Pegawai($level,$instansi);
				$this->load->view('grafik_pegawai/v_grafik', $this->data);
		}
		
	}



}

/* End of file Grafik_pegawai.php */
/* Location: ./application/modules/reportgk/controllers/Grafik_pegawai.php */