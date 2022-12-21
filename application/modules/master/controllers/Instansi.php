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
		$this->load->model(['m_instansi','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
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
		$this->db->select('a.id, a.simpeg_dept_id, dept_name, dept_alias, a.parent_id, level, path_info, position_order, b.nama as kecamatan, status_instansi, jum_user, jum_sub')
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
		$this->datatables->select('a.id, dept_name,simpeg_dept_id,dept_alias, parent_id, level, path_info, position_order, status_instansi, b.nama as kecamatan, jum_user')
		->from('v_instansi_all_master a')
		->join('(SELECT count(*) as jum_user, dept_id FROM "mf_users" GROUP BY dept_id) as tot_user','a.id=tot_user.dept_id','left')
		->join('_kecamatan b','a.kecamatan_id=b.id','left')
		->add_column('dept_alias', '$1', 'level_instansi_tabel(dept_alias,dept_name,level, path_info)')
		->add_column('instansi_status', '$1', 'status_user(status_instansi)')
		->add_column('status_simpeg', '$1','status_opd_simpeg(simpeg_dept_id)')
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
		$this->data['instansi_all']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->data['instansi_simpeg']  = $this->m_instansi->getOPDSimpeg()->result();
		$this->load->view('instansi/v_add', $this->data);
	}

	public function AjaxSave()
	{
		$this->load->model('m_server_att');
		$this->mod = $this->input->post('mod');
		$this->output->unset_template();
		$this->form_validation->set_rules('nama', 'nama instansi', 'required')
		->set_rules('alias', 'nama singkatan', 'required')
		->set_rules('kategori', 'kategori', 'required')
		->set_rules('kecamatan', 'kecamatan', 'required')
		->set_rules('order', 'urutan', 'required|numeric');
		if ($this->mod == "add") {
			$this->form_validation->set_rules('parent', 'instansi induk', 'required');
		}
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
			if ($this->input->post('absen_online')) {
				$absen_online = 1;
			}else {
				$absen_online = 0;
			}
			
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
							'kecamatan_id' 		=> decrypt_url($this->input->post('kecamatan'),'kecamatan_id'),
							'position_order' 	=> $this->input->post('order'),
							'parent_id' 	 	=> decrypt_url($this->input->post('parent'),'instansi'),
							'created_at' 		=> date('Y-m-d H:i:s'),
							'status'			=> $status,
							'dept_type'			=> $this->input->post('kategori'),
							'created_by' 	 	=> $this->session->userdata('tpp_user_id'),
							'absen_online'		=> $absen_online,
							'alamat'			=> $this->input->post('alamat'),
							'latlong'			=> (!empty($this->input->post('latlong'))) ? $this->input->post('latlong') : NULL ,
							'radius'			=> (!empty($this->input->post('radius'))) ? $this->input->post('radius') : NULL,
							'simpeg_dept_id'	=> (!empty($this->input->post('simpeg_dept_id'))) ? decrypt_url($this->input->post('simpeg_dept_id'),'simpeg_dept_id') : NULL,
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

					if ($absen_online == 1) {
						$this->db->update('mf_users',['absen_online_app' => 1],['dept_id'=> $id_new,'att_status' => 1]);
					}
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
				$data = array('dept_name' => $this->input->post('nama'),
					'dept_alias' 		=> $this->input->post('alias'),
					'kecamatan_id' 		=> decrypt_url($this->input->post('kecamatan'),'kecamatan_id'),
					'position_order' 	=> $this->input->post('order'),
					'updated_at' 		=> date('Y-m-d H:i:s'),
					'status'			=> $status,
					'dept_type'			=> $this->input->post('kategori'),
					'updated_by' 	 	=> $this->session->userdata('tpp_user_id'),
					'absen_online'		=> $absen_online,
					'alamat'			=> $this->input->post('alamat'),
					'latlong'			=> (!empty($this->input->post('latlong'))) ? $this->input->post('latlong') : NULL ,
					'radius'			=> (!empty($this->input->post('radius'))) ? $this->input->post('radius') : NULL,
					'simpeg_dept_id'	=> (!empty($this->input->post('simpeg_dept_id'))) ? decrypt_url($this->input->post('simpeg_dept_id'),'instansi') : NULL,
				);
				$parent = decrypt_url($this->input->post('parent'),'instansi');
				if($parent != 0){
					$data['parent_id'] = $this->input->post('parent_id');
				}
				$this->return = $this->db->update('mf_departments', $data, ['id' => decrypt_url($this->input->post('id'),'instansi')]);
				$path = $this->db->select('path_info')->get_where('v_instansi_all_master', ['id' => decrypt_url($this->input->post('id'),'instansi')])->row();

				$new_path = attConverPathNumber($path->path_info);

				$data_att_dept = array('deptname' => $new_path.'_'.$this->input->post('alias'),
					'supdeptid' => $supdeptid, );
				$this->return = $this->m_server_att->Updatedepartments($data_att_dept, ['deptid' => decrypt_url($this->input->post('id'),'instansi')]);
				
				$this->db->update('mf_users',['absen_online_app' => $absen_online],['dept_id'=> decrypt_url($this->input->post('id'),'instansi'),'att_status' => 1]);
				
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
		// $this->data['user']			= $this->m_user->GetUser(decrypt_url($id, 'user_id'))->row();
		$this->data['instansi'] 	= $this->m_instansi->GetInstansi(decrypt_url($id,'instansi'))->row();
		$this->data['instansi_all']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->data['instansi_simpeg']  = $this->m_instansi->getOPDSimpeg()->result();
		
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

	public function tree_view()
	{
		$this->load->js('public/themes/material/global_assets/js/plugins/extensions/jquery_ui/core.min.js');
		$this->load->js('public/themes/material/global_assets/js/plugins/extensions/jquery_ui/effects.min.js');
		$this->load->js('public/themes/material/global_assets/js/plugins/extensions/jquery_ui/interactions.min.js');
		$this->load->js('public/themes/material/global_assets/js/plugins/trees/fancytree_all.min.js');
		$this->load->js('public/themes/material/global_assets/js/plugins/trees/fancytree_childcounter.js');
		
		$this->data['sub_title'] = "Instansi";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	 = $this->db->get('v_instansi_all_master')->result();
		$this->load->view('instansi/v_tree_view', $this->data);
	}

	public function SyncOpd($id)
	{
		$id = decrypt_url($id,'parent_id');
		$this->load->model('m_server_att');
		$this->output->unset_template();
		$data_opd_simpeg = $this->m_instansi->GetSyncOPD($id)->result();
		// var_dump($id);
		$data_update = array();
		if(!empty($data_opd_simpeg) || $data_opd_simpeg){
			$no = 1;
			$order = 1;
			foreach($data_opd_simpeg as $row){
				$data_order = $this->m_instansi->getNumOrder($id)->result();
				$kecamatan_id = $this->m_instansi->getKecamatan($row->id)->row();
				if(!empty($data_order[0]->num)){
					$order = $data_order[0]->num+$no++;
				}else {
					$order++;
				}

				$data_update = array(
							'simpeg_dept_id' => $row->id,
							'dept_name' => $row->nama_unor,
							'dept_alias' => (empty($row->akronim)) ? $row->nama_unor :  $row->akronim,
							'alamat' => $row->detail_lokasi,
							'status' => $row->status,
							'dept_type' => $row->type_unor,
							'parent_id' => $id,
							'position_order' => $order,
							'kecamatan_id' => $kecamatan_id->id,
							'created_at' => date('Y-m-d H:i:s'),
							'created_by'=> $this->session->userdata('tpp_user_id')
					);
				$res_ = $this->db->insert('mf_departments',$data_update);
				$id_new = $this->db->insert_id();
				$path = $this->db->select('path_info')->get_where('v_instansi_all_master', ['id' => $id_new])->row();
				$new_path = attConverPathNumber($path->path_info);
				$data_att_dept = array( 'deptid' 	=> $id_new,
										'deptname'  => $new_path.'_'.(empty($row->akronim)) ? $row->nama_unor :  $row->akronim,
										'supdeptid' => $id,
								);
				$this->return = $this->m_server_att->Newdepartments($data_att_dept);
			}
			$this->return = TRUE;
		}else {
			$this->return = TRUE;
		}
		
		if ($this->return) {
				$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Berhasil sinkron data' ]));	
		}else {
				$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal sinkron data.']));
		}
	}


}

/* End of file Instansi.php */
/* Location: ./application/modules/Master/controllers/Instansi.php */