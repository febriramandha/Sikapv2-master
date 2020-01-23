<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Worship extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Data Ibadah', 'datalkh/lkh');
		$this->data['title'] = "Data Ibadah";
		$this->load->model(['m_schrun_user','m_sch_lkh','m_ibadah','m_verifikator','m_instansi','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
		$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
		$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
		$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Data Ibadah";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('ibadah/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$tglshow = $this->m_schrun_user->CekTanggalLkh($this->session->userdata('tpp_user_id'), date('Y-m-d'))->row();
		$jumlkh  = $this->m_sch_lkh->Getsch_lkh($this->session->userdata('tpp_dept_id'),date('Y-m-d'))->row();

		if ($tglshow) {
		      if ($tglshow->shiftuserrun_id) {
		          $data_tgl_lkh = array();
		          for ($i=0; $i < $jumlkh->count_inday; $i++) { 
		                $data_tgl_lkh[] = tgl_minus(date('Y-m-d'), $i);
		          }
		      }else {
		         $data_tgl_lkh = tgl_minus_lkh(date('Y-m-d'), $jumlkh->count_inday, $tglshow->hari_kerja);
		      }

		 }
		 $data_tgl ='';
		 if ($data_tgl) {
		 	$data_tgl = str_replace(['[', ']', '"',','],['', '','','+'],json_encode($data_tgl_lkh));
		 }
		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));
		$this->load->library('datatables');
        $this->datatables->select('id, tgl_ibadah, ibadah_id, t_zuhur, t_ashar')
        	->from('ibadah_muslim')
        	->where('user_id', $this->session->userdata('tpp_user_id'))
        	->add_column('tgl_ibadah_tabel','$1','tglInd_hrtabel(tgl_ibadah)')
        	->add_column('action','<div style="white-space: nowrap;">$1</div>', "aksi_status_ibadah(id, $data_tgl, tgl_ibadah)");
        	if ($rank1 && $rank2) {
        			$this->datatables->where("tgl_ibadah::date BETWEEN '$rank1' and '$rank2'", NULL, FALSE );
        	}
        return $this->output->set_output($this->datatables->generate());
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Ibadah";
		$this->breadcrumbs->push('Tambah Ibadah', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['tglshow'] 		= $this->m_schrun_user->CekTanggalLkh($this->session->userdata('tpp_user_id'), date('Y-m-d'))->row();
		$this->data['jumlkh']		= $this->m_sch_lkh->Getsch_lkh($this->session->userdata('tpp_dept_id'),date('Y-m-d'))->row();
		$this->data['verifikator']	= $this->m_verifikator->GetVerifikator($this->session->userdata('tpp_user_id'))->row();
		$this->data['user']			= $this->m_user->GetDetailBiodata($this->session->userdata('tpp_user_id'))->row();
		$this->load->view('ibadah/v_add', $this->data);
	}

}

/* End of file Worship.php */
/* Location: ./application/modules/Datalkh/controllers/Worship.php */