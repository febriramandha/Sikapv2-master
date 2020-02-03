<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/


class Rtlkh extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Laporan LKH', 'report/rtlkh');
		$this->data['title'] = "Laporan Umum";
		$this->load->model(['m_instansi','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
    	$this->load->js('public/themes/plugin/datatables/dataTables.rowsGroup.js');
    	$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Laporan LKH";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('rtlkh/v_index', $this->data);
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('mod');
		if ($this->mod == "DataPegawai") {
			    $dept_id 		= decrypt_url($this->input->get('instansi'),'instansi');
        		$data_pegawai 	= $this->m_user->GetUserAllAktifDept($dept_id)->result();
        		$res='';
        		foreach ($data_pegawai as $row ){
		          		$res.= "<option value='".encrypt_url($row->id,'user_id_lkh')."'>$row->nama ($row->nip)</option>";
		          }
		        $this->output->set_output($res);
		}
		
	}

	public function lkhJson()
	{
		$this->output->unset_template();
		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));
		$user_id = decrypt_url($this->input->post('pegawai'),'user_id_lkh');
		$this->load->library('datatables');
        $this->datatables->select('a.id, tgl_lkh, jam_mulai, jam_selesai, kegiatan, hasil, jenis, a.status, verifikasi_by, b.nama as ver_nama, b.gelar_dpn as ver_gelar_dpn, b.gelar_blk as ver_gelar_blk, comment, a.jenis')
        	->from('data_lkh a')
        	->join('v_users_all b','a.verifikasi_by=b.id','left')
        	->join('lkh_rejected c','a.id=c.lkh_id','left')
        	->order_by('tgl_lkh,jam_mulai, id','desc')
        	->add_column('tgl_lkh_tabel','$1','tglInd_hrtabel(tgl_lkh)')
        	->add_column('jam_mulai','$1 <i class="icon-arrow-right16"></i> $2 $3','jm(jam_mulai), jm(jam_selesai), cek_dltabel(jenis)')
        	->add_column('status_lkh','$1','status_lkh_tabel(status, comment)')
        	->add_column('ver','$1','pejabat_ptabel(verifikasi_by, ver_nama, ver_gelar_dpn, ver_gelar_blk, status)');
        	if ($rank1 && $rank2) {
        			$this->datatables->where("tgl_lkh::date BETWEEN '$rank1' and '$rank2'", NULL, FALSE );
        	}
        	if ($user_id) {
        		    $this->datatables->where('user_id', $user_id);
        	}else {
        			$this->datatables->where('user_id', '0');
        	}
        return $this->output->set_output($this->datatables->generate());
	}


}

/* End of file Rtlkh.php */
/* Location: ./application/modules/report/controllers/Rtlkh.php */