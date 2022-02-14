<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Verifikator extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Data Verifikator', 'kepegawaian/verifikator');
		$this->data['title'] = "Kepegawaian";
		$this->load->model(['m_instansi','m_user','m_verifikator']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/plugin/autocomplete/dist/latest/bootstrap-autocomplete.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Data Verifikator";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('verifikator/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		if ($instansi) {
			$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $instansi])->row()->level;
		}
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.nama, a.gelar_dpn,a.gelar_blk, d.nama as nama_verifikator, d.nip as nip_verifikator, d.gelar_dpn as ver_gelar_dpn, d.gelar_blk as ver_gelar_blk')
        	->from('v_users_all a')
        	->join('verifikator c','a.id=c.user_id','left')
        	->join('v_users_all d','c.user_id_ver=d.id','left')
        	->where('a.key > 0')
        	->where('a.att_status',1)
        	->order_by('a.no_urut')
        	->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip,"kepegawaian/verifikator/edit",id)')
        	->add_column('verifikator','$1','nama_icon_nip(nama_verifikator,ver_gelar_dpn,ver_gelar_blk,nip_verifikator)');
        	 if ($instansi) {
		        $this->datatables->where("a.path_id['".$level."']='".$instansi."'");
		     }
        	 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(a.nama)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(a.nip)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
	       
        return $this->output->set_output($this->datatables->generate());
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Data Verifikator";
		$this->breadcrumbs->push('Edit Verifikator', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['user']			= $this->m_user->GetDetailBiodata(decrypt_url($id, 'user_id'))->row();
		$cek_verifikator = $this->db->select('user_id_ver')->get_where('verifikator',['user_id'=> decrypt_url($id, 'user_id')])->row();
		if ($cek_verifikator) {
				$cek_verifikator = $this->m_user->GetDetailBiodata($cek_verifikator->user_id_ver)->row();
		}
		$this->data['verifikator'] 	= $cek_verifikator;
		$this->load->view('verifikator/v_edit', $this->data);
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('modul');
		if ($this->mod == "listverifikator") {
			$user = decrypt_url($this->input->get('id'), 'user_id');
			$list_pegawai = $this->m_verifikator->GetVerifikatorQry($user)->result();
			$data_user = array();
			foreach ($list_pegawai as $row ) {
				$sub_array['id'] 		= encrypt_url($row->id,'user_id_ver');
				$sub_array['nama'] 		= $row->nama;
				$sub_array['nip'] 		= $row->nip;
				$sub_array['jabatan'] 	= $row->jabatan;
				$sub_array['icon'] 		= base_url('uploads/avatar/thumb/'.$row->avatar);
				$data_user[] 			= $sub_array; 
			}
			if ($list_pegawai) {
				$this->output->set_output(json_encode(['results'=> $data_user ]));
			}else {
				$this->output->set_output(json_encode(['status'=>false, 'message'=> 'Gagal mengambil data.', 'results'=>[]]));
			}
		}
			
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('user', 'verifikator', 'required')
							  ->set_rules('id', 'cek', 'required');		
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {

				$user_cek = $this->db->get_where('verifikator',['user_id' => decrypt_url($this->input->post('id'),'user_id')])->row();
				if ($user_cek) {
					$data = array(
							  'user_id_ver'	=> decrypt_url($this->input->post('user'),'user_id_ver'),
							  'updated_at' 	=> date('Y-m-d H:i:s'),
							  'updated_by'  => $this->session->userdata('tpp_user_id'), );
					$this->return = $this->db->update('verifikator', $data, ['user_id' => decrypt_url($this->input->post('id'),'user_id')]);
				}else {
					$data = array(
							  'user_id'		=> decrypt_url($this->input->post('id'),'user_id'),
							  'user_id_ver'	=> decrypt_url($this->input->post('user'),'user_id_ver'),
							  'updated_at' 	=> date('Y-m-d H:i:s'),
							  'updated_by'  => $this->session->userdata('tpp_user_id'), );
					$this->return = $this->db->insert('verifikator', $data);
				}
					
				
			
				if ($this->return) {
					$this->result = array('status'   => true,
		    	     		  		'message' => 'Data berhasil disimpan',);
				}else{
					$this->result = array('status'   => false,
		    	     		  		'message' => 'Data gagal disimpan',);
				}	
				

		}else {
			$this->result = array('status'   => false,
				    	     'message' => validation_errors(),);
		}
		if ($this->result) {
			$this->output->set_output(json_encode($this->result));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}

	public function cetak($dept_id)
	{
		$this->output->unset_template();
		$this->load->library('Tpdf');
		$this->data['instansi'] = $this->m_instansi->GetInstansi(decrypt_url($dept_id, 'instansi'))->row();
		$this->data['user']		= $this->m_verifikator->GetAllVerifikatorDept(decrypt_url($dept_id, 'instansi'))->result();
		$this->load->view('verifikator/v_cetak', $this->data);
	}

	public function AjaxDel()
	{
		$this->output->unset_template();
		$this->del = $this->db->delete('verifikator',['user_id' => decrypt_url($this->input->get('id'),"user_id")]);

		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}


}

/* End of file Verifikator.php */
/* Location: ./application/modules/Kepegawaian/controllers/Verifikator.php */