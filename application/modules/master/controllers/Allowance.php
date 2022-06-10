<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Allowance extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Tunjangan PNS', 'master/allowance');
		$this->data['title'] = "Master";
		$this->load->model(['m_allowance','m_instansi']);
	}

	private function _init()
	{
		$this->output->set_template('app');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
		$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');

	}
		public function tree()
	{
		$this->load->css('public/themes/plugin/jquery_treetable/css/jquery.treetable.css');
		$this->load->css('public/themes/plugin/jquery_treetable/css/jquery.treetable.theme.default.css');
		$this->load->js('public/themes/plugin/jquery_treetable/jquery.treetable.js');
		$this->data['sub_title'] = "Instansi";
		$this->db->select('a.id, name, kelas_jabatan, a.parent, position, tpp, jum_sub,path_info,status_tpp,position, a.sub')
		->from('v_tree_tpp a')
		->where('a.deleted','1')
		->join('(select count(*) as jum_sub, parent from _allowances where deleted =1 GROUP BY parent) as c','a.id=c.parent','left')
		->order_by('a.path_id', 'asc');
		$this->data['tpp']	 = $this->db->get()->result();

		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('allowance/v_tree', $this->data);
	}

	public function index()
	{
		$this->tree();
	}

	public function json()
	{
		$this->output->unset_template();
		
		$this->load->library('datatables');
		$this->datatables->select('a.id, a.name, b.eselon, c.golongan, a.tpp, a.position, a.status')
		->from('_allowances a')
		->order_by('position')
		->where('deleted',1)
		->add_column('status_tunjangan','$1','status_user(status)')
		->add_column('tpp','$1','rupiah(tpp)')
		->add_column('action', '<a href="'.base_url('master/allowance/edit/').'$1">
			<i class="icon-pencil5 text-info-400"></i>
			</a>
			<span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus akun" style="cursor:pointer;" id="$1">
			<i class="icon-bin"></i>
			</span>', 'encrypt_url(id,"allowance_id")');
		return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxSave()
	{
		$this->output->unset_template();

		if($this->input->post('mod') == "edit"){
			$sub = decrypt_url($this->input->post('sub'),'sub');
			if($sub == 2 || $sub == 1){
				$this->form_validation->set_rules('nama_jabatan', 'nama jabatan', 'required');
			}else{
				$this->form_validation->set_rules('nama_jabatan', 'nama jabatan', 'required')
				->set_rules('tpp', 'tpp', 'required|numeric')
				->set_rules('kelas_jabatan', 'kelas jabatan', 'required|numeric');
			}
		}else {
			$this->form_validation->set_rules('sub', 'Sub', 'required');
			if($this->input->post('sub') == '1' || $this->input->post('sub') == '2'){
				$this->form_validation->set_rules('nama_jabatan', 'nama jabatan', 'required');
			}else {
				$this->form_validation->set_rules('nama_jabatan', 'nama jabatan', 'required')
					->set_rules('tpp', 'tpp', 'required|numeric')
					->set_rules('kelas_jabatan', 'kelas jabatan', 'required|numeric')
					->set_rules('position', 'nomor urut', 'required|numeric');
			}
		}
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');			
			if ($this->input->post('status')) {
				$status = 1;
			}else {
				$status = 0;
			}
			if(empty($this->input->post('tpp'))){
				$tpp = 0;
			}else {
				$tpp = $this->input->post('tpp');
			}
			if ($this->mod == "add") {
				$data = array(
					'name' 		    => $this->input->post('nama_jabatan'),
					'tpp' 	 		=> empty($this->input->post('tpp')) ? NULL : $this->input->post('tpp'),
					'position' 	 	=> $this->input->post('position'),
					'sub' 	 	=> $this->input->post('sub'),
					'kelas_jabatan' 	 	=> empty($this->input->post('kelas_jabatan')) ? NULL : $this->input->post('kelas_jabatan'),
					'parent' 	 	=> decrypt_url($this->input->post('parent'),'tpp'),
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
					'name' 		    => $this->input->post('nama_jabatan'),
					'tpp' 	 		=> $this->input->post('tpp'),
					'kelas_jabatan' 	 	=> $this->input->post('kelas_jabatan'),
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

	public function add($id)
	{

		$position = $this->db->select('max(position)')->get_where('_allowances', ['parent' => decrypt_url($id,'tpp')])->row();
		if ($position) {
			$position_ = $position->max+1;
		}else {
			$position_ =1;
		}
		$this->data['sub_title'] 	= "Tambah Tunjangan";
		$this->breadcrumbs->push('Tambah Tunjangan', '/');
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['position'] 	= $position_;
		$this->data['tpp_induk'] 	=  $this->m_allowance->GetTpp(decrypt_url($id,'tpp'))->row();
		
		$this->load->view('allowance/v_add', $this->data);
	}
	public function addopd($id)
	{
		$position = $this->db->select('max(position)')->get_where('_allowances', ['parent' => decrypt_url($id,'tpp')])->row();
		if ($position) {
			$position_ = $position->max+1;
		}else {
			$position_ =1;
		}
		$this->data['sub_title'] 	= "Instansi Penerima Tunjangan";
		$this->breadcrumbs->push('Tambah Instansi Penerima', '/');
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['position'] 	= $position_;
		$this->data['tpp_induk'] 	=  $this->m_allowance->GetTpp(decrypt_url($id,'tpp'))->row();
		
		$this->load->view('allowance/v_addopd', $this->data);
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Tunjangan";
		$this->breadcrumbs->push('Edit Tunjangan', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['tpp_induk'] 	=  $this->m_allowance->GetTpp(decrypt_url($id,'tpp'))->row();
		$this->data['tunjangan']	= $this->db->get_where('_allowances',['id' => decrypt_url($id,'tpp')])->row();
		$this->load->view('allowance/v_edit', $this->data);
	}

	public function AjaxDel()
	{
		$this->output->unset_template();
	
		$del = $this->db->update('_allowances', ['deleted' => 0], ['id' => decrypt_url($this->input->get('id'),'tpp')]);
		
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

	public function AjaxSaveOpd()
	{
		$this->output->unset_template();
		$instansi = str_replace(['[', ']', '"'],['{', '}',''], json_encode($this->input->post('instansi')));
		$cek_allowances = $this->m_allowance->CekAllowance(decrypt_url($this->input->post('allowance_id'),'allowance'))->row();
		
		$data = array(
			'allowances_id' 		    => decrypt_url($this->input->post('allowance_id'),'allowance'),
			'dept_id' 	 		=> ($instansi === "null") ? "{}" : $instansi
		);
		if(empty($cek_allowances)){
			$data['created_by'] = $this->session->userdata('tpp_user_id');
			$data['created_at'] = date('Y-m-d H:i:s');
			$this->return = $this->db->insert('_opd_allowances',$data);
		}else {
			$data['updated_by'] = $this->session->userdata('tpp_user_id');
			$data['updated_at'] = date('Y-m-d H:i:s');

			$this->return = $this->db->update('_opd_allowances',$data);
		}

		if ($this->return) {
			$this->result = array('status' => true,
				'message' => 'Data berhasil disimpan');
		}else{
			$this->result = array('status' => false,
				'message' => 'Data gagal disimpan');
		}
			
		if ($this->result) {
			$this->output->set_output(json_encode($this->result));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}

}

/* End of file Allowance.php */
/* Location: ./application/modules/Master/controllers/Allowance.php */