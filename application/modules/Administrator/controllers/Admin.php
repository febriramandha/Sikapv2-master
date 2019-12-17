<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Admin  extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Admin', 'administrator/admin');
		$this->data['title'] = "Administrator";
		$this->load->model(['m_instansi','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['sub_title'] = "Admin";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('admin/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		if ($instansi) {
			$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $instansi])->row()->level;
		}
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.key, a.nama, a.dept_alias, b.level, status, status_pegawai, b.username,gelar_dpn,gelar_blk')
        	->from('v_users_all a')
        	->where('key > 0')
        	->join('users_login b','a.id=b.user_id','left')
        	->order_by('no_urut')
        	->where('b.level in(1,2)')
        	->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)')
        	->add_column('status_user','$1','status_user(status)')
        	->add_column('level','$1','level_alias(level)')
        	->add_column('action','<a href="'.base_url('administrator/user/edit/').'$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>', 'encrypt_url(id,"user_id")');
        	 if ($instansi) {
		        $this->datatables->where("path_id['".$level."']='".$instansi."'");
		     }
        	 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(nama)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(nip)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
	       
        return $this->output->set_output($this->datatables->generate());
	}

	public function cetak($dept_id)
	{
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 300); //300 seconds = 5 minutes
		$this->output->unset_template();
		$this->load->library('Tpdf');
		$this->data['instansi'] = $this->m_instansi->GetInstansi(decrypt_url($dept_id, 'instansi'))->row();
		$this->data['user']		= $this->m_user->GetUserAdmin(decrypt_url($dept_id, 'instansi'))->result();
		$this->load->view('admin/v_cetak', $this->data);
	}


}

/* End of file Admin.php */
/* Location: ./application/modules/Administrator/controllers/Admin.php */