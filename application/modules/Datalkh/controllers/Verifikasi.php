<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Verifikasi extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Verifikasi LKH', 'datalkh/verifikasi');
		$this->data['title'] = "Verifiikasi";
		$this->load->model(['m_schrun_user','m_sch_lkh','m_data_lkh','m_verifikator','m_instansi']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
		$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Verifikasi LKH";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('verifikasi/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$this->load->library('datatables');
        $this->datatables->select('a.id, b.nip, b.nama, b.gelar_dpn, b.gelar_blk, c.jabatan, d.jum_non_ver')
        	->from('verifikator a')
        	->join('v_users_all b','a.user_id=b.id','left')
        	->join('sp_pegawai c','a.user_id=c.user_id','left')
        	->join('v_jum_non_ver d','a.user_id=d.user_id','left')
        	->where('a.user_id_ver', $this->session->userdata('tpp_user_id'))
        	->where('key > 0')
        	->where('att_status',1)
        	->order_by('no_urut')
        	->add_column('nama_nip','$1','nama_icon_nip_key(nama,gelar_dpn,gelar_blk,nip,"datalkh/verifikasi/view",id, jabatan,"verifikator_id")')
        	->add_column('jumlah','$1','jum_non_ver(jum_non_ver)');
        return $this->output->set_output($this->datatables->generate());
	}



}

/* End of file Verifikasi.php */
/* Location: ./application/modules/Datalkh/controllers/Verifikasi.php */