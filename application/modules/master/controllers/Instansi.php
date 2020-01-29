<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Instansi extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Instansi', 'master/instansi');
		$this->data['title'] = "Master";
		$this->load->model('m_instansi');
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		// $this->data['sub_title'] = "Instansi";
		// $this->data['breadcrumb'] = $this->breadcrumbs->show();
		// $this->load->view('instansi/v_index', $this->data);
		$this->tree();
	}

	public function tree()
	{
		$this->load->css('public/themes/plugin/jquery_treetable/css/jquery.treetable.css');
		$this->load->css('public/themes/plugin/jquery_treetable/css/jquery.treetable.theme.default.css');
		$this->load->js('public/themes/plugin/jquery_treetable/jquery.treetable.js');
		$this->data['sub_title'] = "Instansi";
		$this->db->select('a.id, dept_name, dept_alias, a.parent_id, level, path_info, position_order, status_instansi, b.nama as kecamatan, jum_user, jum_sub')
		->from('v_instansi_all_master a')
		->join('(SELECT count(*) as jum_user, dept_id FROM "mf_users" GROUP BY dept_id) as tot_user','a.id=tot_user.dept_id','left')
		->join('_kecamatan b','a.kecamatan_id=b.id','left')
		->join('(select count(*) as jum_sub, parent_id from mf_departments GROUP BY parent_id) as c','a.id=c.parent_id','left');
		$this->data['instansi']	 = $this->db->get()->result();

		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('instansi/v_tree', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		//header('Content-Type: application/json');
		$this->load->library('datatables');
		$this->datatables->select('a.id, dept_name, dept_alias, parent_id, level, path_info, position_order, status_instansi, b.nama as kecamatan, jum_user')
		->from('v_instansi_all_master a')
		->join('(SELECT count(*) as jum_user, dept_id FROM "mf_users" GROUP BY dept_id) as tot_user','a.id=tot_user.dept_id','left')
		->join('_kecamatan b','a.kecamatan_id=b.id','left')
		->add_column('dept_alias', '$1', 'level_instansi_tabel(dept_alias,dept_name,level, path_info)')
		->add_column('instansi_status', '$1', 'status_user(status_instansi)')
		->add_column('action', '<a href="'.base_url('master/instansi/add/').'$1">
			<i class="icon-file-plus2 text-orange-300 mr-1"></i>
			</a>
			<a href="'.base_url('master/instansi/edit/').'$1">
			<i class="icon-pencil5 text-info-400 mr-1"></i>
			</a>
			<span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus akun" style="cursor:pointer;" id="$1">
			<i class="icon-bin"></i>
			</span>', 'encrypt_url(id,"instansi")');
		if ($this->input->post('search[value]')) {
			$this->datatables->like('lower(dept_name)', strtolower($this->input->post('search[value]')));
			$this->datatables->or_like('lower(dept_alias)', strtolower($this->input->post('search[value]')));
			$this->datatables->or_like('(path_info)::text', $this->input->post('search[value]'));

		}
		return $this->output->set_output($this->datatables->generate());
	}

	public function add($id)
	{
		$instansi_induk = $this->m_instansi->GetInstansi(decrypt_url($id,'instansi'))->row();
		$position = $this->db->select('max(position_order)')->get_where('mf_departments', ['parent_id' => decrypt_url($id,'instansi')])->row();
		if ($position) {
			$position_ = $position->max+1;
		}else {
			$position_ =1;
		}
		$this->data['sub_title'] 		= "Tambah Instansi";
		$this->breadcrumbs->push('Tambah Instansi', '/');
		$this->data['breadcrumb'] 		= $this->breadcrumbs->show();
		$this->data['instansi_induk'] 	= $this->m_instansi->GetInstansi(decrypt_url($id,'instansi'))->row();
		$this->data['kecamatan']		= $this->db->order_by('id')->get('_kecamatan')->result();
		$this->data['position']			= $position_;
		$this->load->view('instansi/v_add', $this->data);
	}

	public function AjaxSave()
	{
		$this->load->model('m_server_att');
		$this->mod = $this->input->post('mod');
		$this->output->unset_template();
		$this->form_validation->set_rules('nama', 'nama instansi', 'required')
		->set_rules('alias', 'nama singkatan', 'required')
		->set_rules('kecamatan', 'kecamatan', 'required')
		->set_rules('order', 'urutan', 'required|numeric');
		if ($this->mod == "add") {
			$this->form_validation->set_rules('parent', 'instansi induk', 'required');
		}
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
			
			if ($this->mod == "add") {
				if ($this->input->post('status')) {
					$status = 1;
					$supdeptid = decrypt_url($this->input->post('parent'),'instansi');
				}else {
					$status = 0;
					$supdeptid = -1;
				}
				$data = array('dept_name' 	 	=> $this->input->post('nama'),
					'dept_alias' 		=> $this->input->post('alias'),
					'kecamatan_id' 	=> decrypt_url($this->input->post('kecamatan'),'kecamatan_id'),
					'position_order' 	=> $this->input->post('order'),
					'parent_id' 	 	=> decrypt_url($this->input->post('parent'),'instansi'),
					'created_at' 		=> date('Y-m-d H:i:s'),
					'status'			=> $status,
					'created_by' 	 	=> $this->session->userdata('tpp_user_id'),
				);
				$res_ = $this->db->insert('mf_departments',$data);
				$id_new = $this->db->insert_id();

				if ($res_) {
					$path = $this->db->select('path_info')->get_where('v_instansi_all_master', ['id' => $id_new])->row();
					$new_path = attConverPathNumber($path->path_info);
					$data_att_dept = array( 'deptid' 	=> $id_new,
						'deptname'  => $new_path.'_'.$this->input->post('alias'),
						'supdeptid' => $supdeptid,
					);
					$this->return = $this->m_server_att->Newdepartments($data_att_dept);
				}

				if ($this->return) {
					$this->result = array('status' => true,
						'message' => 'Data berhasil disimpan');
				}else{
					$this->result = array('status' => false,
						'message' => 'Data gagal disimpan');
					$this->db->delete('mf_departments', ['id' => $id_new]);
				}
			}elseif ($this->mod == "edit") {
				if ($this->input->post('status')) {
					$status = 1;
					$supdeptid = decrypt_url($this->input->post('parent'),'instansi');
				}else {
					$status = 0;
					$supdeptid = -1;
				}
				$data = array('dept_name' 	 	=> $this->input->post('nama'),
					'dept_alias' 		=> $this->input->post('alias'),
					'kecamatan_id' 		=> decrypt_url($this->input->post('kecamatan'),'kecamatan_id'),
					'position_order' 	=> $this->input->post('order'),
					'updated_at' 		=> date('Y-m-d H:i:s'),
					'status'			=> $status,
					'updated_by' 	 	=> $this->session->userdata('tpp_user_id'),
				);
				$this->return = $this->db->update('mf_departments', $data, ['id' => decrypt_url($this->input->post('id'),'instansi')]);

				$path = $this->db->select('path_info')->get_where('v_instansi_all_master', ['id' => decrypt_url($this->input->post('id'),'instansi')])->row();

				$new_path = attConverPathNumber($path->path_info);

				$data_att_dept = array('deptname' => $new_path.'_'.$this->input->post('alias'),
					'supdeptid' => $supdeptid, );
				$this->return = $this->m_server_att->Updatedepartments($data_att_dept, ['deptid' => decrypt_url($this->input->post('id'),'instansi')]);

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
		$this->data['sub_title'] 	= "Edit Instansi";
		$this->breadcrumbs->push('Edit Instansi', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi'] 	= $this->m_instansi->GetInstansi(decrypt_url($id,'instansi'))->row();
		$this->data['kecamatan']	= $this->db->order_by('id')->get('_kecamatan')->result();
		$this->load->view('instansi/v_edit', $this->data);
	}

	public function read($id)
	{
		$this->data['sub_title'] 	= "Instansi";
		$this->breadcrumbs->push('Read', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstansi($id)->row();
		$this->load->view('instansi/v_read', $this->data);
	}

	public function AjaxDel()
	{
		$this->load->model('m_server_att');
		$this->output->unset_template();
		$cek = $this->db->select('id')->get_where('mf_departments', ['parent_id' => decrypt_url($this->input->get('id'),'instansi')])->row();
		if (!$cek) {
			$this->del = $this->db->delete('mf_departments',['id' => decrypt_url($this->input->get('id'),'instansi')]);
			if ($this->del) {
				$this->m_server_att->Deldepartments(['deptid' => decrypt_url($this->input->get('id'),'instansi')]);
			}
		}
		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}

	public function cetak()
	{
		$this->output->unset_template();
		$this->load->library('Tpdf');
		$this->data['instansi'] = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('instansi/v_cetak', $this->data);
	}

	public function dump_path()
	{
		$this->db2 = $this->load->database('sqlsrv',TRUE);
		$this->output->unset_template();

		$data_dept = $this->db->select('id, path_info, dept_alias')->get_where('v_instansi_all')->result();

		foreach ($data_dept as $row) {
			$new_path = attConverPathNumber($row->path_info);

			$data_att_dept = array('deptname' => $new_path.'_'.$row->dept_alias, );
			$res_ = $this->db2->update('departments', $data_att_dept, ['deptid' => $row->id]);
		}

		
	}


}

/* End of file Instansi.php */
/* Location: ./application/modules/Master/controllers/Instansi.php */