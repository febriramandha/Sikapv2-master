<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Handika Putra
* 2022
*/

class Class_position extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Kelas Jabatan', 'master/class_position');
		$this->data['title'] = "Master";
		$this->load->model(['m_instansi','m_simpeg']);
	}

	private function _init()
	{
		$this->output->set_template('app');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');

	}

	public function index()
	{
		$this->data['sub_title']  = "Kelas Jabatan";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();

		$this->load->view('class_position/v_index', $this->data);
	}

	public function AjaxGet()
	{
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$level_tpp = $this->db->select('a.level,b.allowances_id ')->from('v_tree_tpp a')->join('_opd_allowances b','b.allowances_id = a.id')->where('a.deleted',1)->where('a.start_date <=', date("Y-m-d"))->where('a.end_date >=', date("Y-m-d"))->where(''.$instansi.'=any(b.dept_id)')->get()->row();

		$this->output->unset_template();
		$this->load->library('datatables');
		$this->datatables->select('a.id,a.name,a.tpp,a.parent,a.kelas_jabatan,a.level,a.path_info,a.path_id,a.position,a.sub,a.deleted,a.start_date,a.end_date,a.status_tpp,b.allowances_id')
		->from('v_tree_tpp a')
		->join('_opd_allowances b','b.allowances_id = a.id','left')
		->where('a.deleted',1)
		->where('a.sub',0)
		->where('a.start_date <=', date("Y-m-d"))
		->where('a.end_date >=', date("Y-m-d"))
		->order_by('a.path_id','asc')
		// ->add_column('tpp','$1','rupiah(tpp)')
		->add_column('action', '<a href="'.base_url('master/class_position/add').'">
			<i class="icon-pencil5 text-info-400"></i>
			</a>');
			if(!empty($level_tpp)){
		     $this->datatables->where("path_id['".$level_tpp->level."']='".$level_tpp->allowances_id."'");
			}
		return $this->output->set_output($this->datatables->generate());

	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('nama', 'uraian', 'required')
		->set_rules('eselon', 'eselon', 'required|numeric')
		->set_rules('golongan', 'golongan', 'required|numeric')
		->set_rules('tpp', 'tpp', 'required|numeric')
		->set_rules('order', 'nomor urut', 'required|numeric');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');			
			if ($this->input->post('status')) {
				$status = 1;
			}else {
				$status = 0;
			}
			if ($this->mod == "add") {
				
				$data = array(
					'name' 		    => $this->input->post('nama'),
					'eselon_id' 		=> $this->input->post('eselon'),
					'golongan_id' 	=> $this->input->post('golongan'),
					'tpp' 	 		=> $this->input->post('tpp'),
					'position' 	 	=> $this->input->post('order'),
					'status' 	 		=> $status,
					'created_at'		=> date('Y-m-d H:i:s'),
					'created_by'		=> $this->session->userdata('tpp_user_id')
				);
				$this->return = $this->db->insert('_allowances',$data);
				if ($this->return) {
					$this->result = array('status' => true,
						'message' => 'Data berhasil disimpan');
				}else{
					$this->result = array('status' => false,
						'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				
				$data = array(
					'name' 		    => $this->input->post('nama'),
					'eselon_id' 		=> $this->input->post('eselon'),
					'golongan_id' 	=> $this->input->post('golongan'),
					'tpp' 	 		=> $this->input->post('tpp'),
					'position' 	 	=> $this->input->post('order'),
					'status' 	 		=> $status,
					'updated_at'		=> date('Y-m-d H:i:s'),
					'updated_by'		=> $this->session->userdata('tpp_user_id')
				);
				$this->return = $this->db->update('_allowances', $data, ['id' => decrypt_url($this->input->post('id'),'allowance_id')]);
				if ($this->return) {
					$this->result = array('status' => true,
						'message' => 'Data berhasil disimpan');
				}else{
					$this->result = array('status' => false,
						'message' => 'Data gagal disimpan');
				}
			}

		}else {
			$this->result = array('status' => false,
				'message' => validation_errors(),);
		}

		if ($this->result) {
			$this->output->set_output(json_encode($this->result));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Tunjangan";
		$this->breadcrumbs->push('Edit Tunjangan', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['tunjangan']	= $this->db->get_where('_allowances',['id' => decrypt_url($id,'allowance_opd_id')])->row();
		$this->load->view('class_position/v_edit', $this->data);
	}
	
	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Jabatan";
		$this->breadcrumbs->push('Tambah Tunjangan', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		// $this->data['tunjangan']	= $this->db->get_where('_allowances',['id' => decrypt_url($id,'allowance_opd_id')])->row();
		$this->load->view('class_position/v_add', $this->data);
	}

	public function AjaxDel()
	{
		$this->output->unset_template();
		$del = $this->db->update('_allowances', ['deleted' => 0], ['id' => decrypt_url($this->input->get('id'),'allowance_id')]);
		
		if ($del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}

	public function AjaxJabatan($id)
	{
		$this->output->unset_template();
		$id = decrypt_url($id,'instansi');
		$getJabatan = $this->m_simpeg->getJabatan($id);
		$data = array();
		foreach($getJabatan as $jabatan){
			$data[] = [
				'id_jabatan' => encrypt_url($jabatan->id_jabatan,'id_jabatan'),
				'nama_jabatan' => $jabatan->nama_jabatan,
				'id_kelas_jabatan' => encrypt_url($jabatan->id_kelas_jabatan,'id_kelas_jabatan'),
				'kelas_jabatan' => $jabatan->kelas_jabatan
			];
		}
		if ($getJabatan) {
			$this->result = array('status' => true,
			'message' => 'Berhasil mengambil data',
									'data' => $data);
		}
		
		if ($this->result) {
			$this->output->set_output(json_encode($this->result));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}	
	
	public function AjaxKelasJabatan($id)
	{
		$this->output->unset_template();
		$id = decrypt_url($id,'id_jabatan');
		$getKelasJabatan = $this->m_simpeg->getKelasJabatan($id);
		
		if ($getKelasJabatan) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'data' => $getKelasJabatan, 'message' => 'Berhasil mengambil data']));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}

}

/* End of file Class Position.php */
/* Location: ./application/modules/Master/controllers/Class Position.php */