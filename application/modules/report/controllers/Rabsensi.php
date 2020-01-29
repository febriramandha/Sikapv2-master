<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Rabsensi extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Laporan Kehadiran', 'mnglkh/schlkh');
		$this->data['title'] = "Laporan Umum";
		$this->load->model(['m_instansi','m_pejabat_instansi']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Laporan Kehadiran";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('rabsensi/v_index', $this->data);
	}

	public function cetak($rank1,$rank2)
	{
		$this->output->unset_template();
		$dept_id = decrypt_url($this->input->get('in'),'instansi');
		$rank1 = format_tgl_eng(str_replace('_', '-', $rank1));
		$rank2 = format_tgl_eng(str_replace('_', '-', $rank2));

		if (jumlah_hari_rank($rank1, $rank2) > 31) {
			echo 'maksimat tanggal yang diizinkan 31 hari';
		}else{
			$this->data['rank1'] 	= $rank1;
			$this->data['priode']		= tgl_ind_bulan($rank1).' s/d '.tgl_ind_bulan($rank2);
			$this->data['datainstansi'] = $this->m_pejabat_instansi->GetPajabatByInstansi($dept_id, 7)->row();
			$this->load->library('Tpdf');
			$this->load->view('rabsensi/v_cetak', $this->data);
		}
		
	}

}

/* End of file Rabsensi.php */
/* Location: ./application/modules/Report/controllers/Rabsensi.php */