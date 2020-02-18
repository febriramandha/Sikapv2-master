<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Mon_pegawai extends  App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Monitoring Pegawai', 'monitorinbg/mon-pegawai');
		$this->data['title'] = "Monitoring";
		$this->load->model('m_instansi');
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['sub_title']  = "Pegawai";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('mon_pegawai/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$dept_id = $this->m_instansi->GetAdminDept($this->session->userdata('tpp_dept_id'));
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.nama, a.dept_alias, gelar_dpn,gelar_blk,jabatan')
        	->from('v_users_all a')
        	->where("a.id not in (995)")
        	->order_by('no_urut')
        	->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip,"",id)')
        	->add_column('instansi','$1','dept_alias')
        	->add_column('jabatan','$1','jabatan');
		     $this->datatables->where("path_id['".$level."']='".$dept_id."'");
        	 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(nama)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(nip)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(a.dept_alias)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
	       
        return $this->output->set_output($this->datatables->generate());
	}

}

/* End of file Mon_pegawai.php */
/* Location: ./application/modules/monitoring/controllers/Mon_pegawai.php */