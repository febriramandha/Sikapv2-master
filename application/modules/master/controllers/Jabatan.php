<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2020
*/

class Jabatan extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jenjang Jabatan', 'master/jabatan');
		$this->data['title'] = "Master";
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/hierarchy/css/hierarchy-view.css');
		$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
		$this->load->css('public/themes/plugin/jquery_treetable/css/jquery.treetable.css');
		$this->load->css('public/themes/plugin/jquery_treetable/css/jquery.treetable.theme.default.css');
		$this->load->js('public/themes/plugin/jquery_treetable/jquery.treetable.js');
	}

	public function index()
	{
		$this->data['sub_title'] = "Jenjang Jabatan";
		$this->db->select('a.*, b.jum_sub');
		$this->db->join('(select count(*) as jum_sub, parent from _instansi GROUP BY parent) as b','a.id=b.parent','left');
		$this->data['instansi']	  = $this->db->get('v_instansi_tree a')->result();
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('jabatan/v_index', $this->data);
	}

	public function struktur($id)
	{

		$this->data['instansi']  = $this->db->get_where('_instansi',['id' => decrypt_url($id,'instansi_id')])->row();
		$this->db->select('a.*, b.jum_sub');
		$this->db->order_by('path_id');
		$this->db->join('(select count(*) as jum_sub, parent from _jabatan GROUP BY parent) as b','a.id=b.parent','left');
		$this->data['jabatan']	 = $this->db->get_where('v_jabatan_tree a', ['instansi_id' => decrypt_url($id,'instansi_id')]);
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('jabatan/v_struktur', $this->data);
	}

	public function add($id)
	{
		$atasan = $this->input->get('at');

		$this->data['instansi']  = $this->db->get_where('v_instansi_tree',['id' => decrypt_url($id,'instansi_id')])->row();
		$this->data['tingkat']	 = $this->db->get_where('_jabatan_terms', ['kategori_instansi' => $this->data['instansi']->kategori_instansi])->result();
		if ($atasan) {
			$this->data['atasan']	 = $this->db->get_where('_jabatan', ['id' => decrypt_url($atasan,'jabatan_id')])->row();
		}
		$jabatan_tertinggi ='';
		if ($this->data['instansi']->level == 2 && $this->data['instansi']->id != 2 || $this->data['instansi']->level == 3 && $this->data['instansi']->parent == 2) {
				$this->db->order_by('id');
				$this->db->limit(3);
				$jabatan_tertinggi = $this->db->get_where('_jabatan',['instansi_id' => 2,'id != 1' => null])->result();
		}

		$this->data['jabatan_tertinggi'] = $jabatan_tertinggi;
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('jabatan/v_add', $this->data);
	}

	public function edit($id)
	{
		$atasan = $this->input->get('at');
		$this->data['jabatan']		= $this->db->get_where('_jabatan', ['id' => decrypt_url($id,'jabatan_id')])->row();
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('jabatan/v_edit', $this->data);
	}

	public function AjaxSave($id)
	{
		$this->output->unset_template();
		if ($this->input->post('parent') != '') {
			$this->form_validation->set_rules('atasan', 'atasan', 'required');
		}
		if ($this->input->post('mod') =="add") {
			$this->form_validation->set_rules('jenis_jabatan', 'jenis jabatan', 'required');
		}

		if ($this->input->post('jenis_jabatan') == 1) {
			$this->form_validation->set_rules('jabatan_tingkat', 'jabatan tingkat', 'required');
		}
		
		$this->form_validation->set_rules('nama_jabatan', 'nama jabatan', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');			
			if ($this->mod == "add") {
				$parent_id = 0;
				if ($this->input->post('parent') != '') {
					$parent_id = decrypt_url($this->input->post('parent'),'jabatan_id');
				}
				$data = array(
					'instansi_id'   	=> decrypt_url($id,'instansi_id'),
					'nama_jabatan' 		=> $this->input->post('nama_jabatan'),
					'jabatanterm_id'	=> $this->input->post('jabatan_tingkat'),
					'parent' 			=> $parent_id,
					'created_at'		=> date('Y-m-d H:i:s'),
					'created_by'		=> $this->session->userdata('tpp_user_id')
				);
				$this->return = $this->db->insert('_jabatan',$data);
				if ($this->return) {
					$this->result = array('status' => true,
						'message' => 'Data berhasil disimpan');
				}else{
					$this->result = array('status' => false,
						'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				if ($this->input->post('status')) {
					$status = 1;
				}else {
					$status = 0;
				}
				$data = array(
					'nama_jabatan' 		=> $this->input->post('nama_jabatan'),
					'status' 	 		=> $status,
					'updated_at'		=> date('Y-m-d H:i:s'),
					'updated_by'		=> $this->session->userdata('tpp_user_id')
				);
				$this->return = $this->db->update('_jabatan', $data, ['id' => decrypt_url($id,'jabatan_id')]);
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

	public function AjaxDel()
	{
		$this->output->unset_template();

		$this->del = $this->db->delete('_jabatan',['id' => decrypt_url($this->input->get('id'),'jabatan_id')]);
		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}




}

/* End of file Jabatan.php */
/* Location: ./application/modules/master/controllers/Jabatan.php */