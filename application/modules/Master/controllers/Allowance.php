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
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['sub_title']  = "Tunjangan PNS";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['eselon']     = $this->db->get('_eselon')->result();
		$this->data['golongan']  = $this->db->get('_golongan')->result();
		$this->load->view('allowance/v_index', $this->data);
	}

	public function json()
	{
		$this->output->unset_template();
		
		$this->load->library('datatables');
		$this->datatables->select('a.id, a.name, b.eselon, c.golongan, a.tpp, a.position, a.status')
		->from('_allowances a')
		->join('_eselon b','b.id=a.eselon_id')
		->join('_golongan c','c.id=a.golongan_id')
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

	public function add()
	{
		$jum = $this->db->select('max(position) as jum')->where('deleted',1)->get('_allowances')->row();

		if ($jum) {
			$position = $jum->jum+1;
		}else $position = 1;
		$this->data['sub_title'] 	= "Tambah Tunjangan";
		$this->breadcrumbs->push('Tambah Tunjangan', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['eselon']     	= $this->db->order_by('id')->get('_eselon')->result();
		$this->data['golongan']  	= $this->db->order_by('id')->get('_golongan')->result();
		$this->data['position'] 	= $position;
		$this->load->view('allowance/v_add', $this->data);
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Tunjangan";
		$this->breadcrumbs->push('Edit Tunjangan', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['eselon']     	= $this->db->order_by('id')->get('_eselon')->result();
		$this->data['golongan']  	= $this->db->order_by('id')->get('_golongan')->result();
		$this->data['tunjangan']	= $this->db->get_where('_allowances',['id' => decrypt_url($id,'allowance_id')])->row();
		$this->load->view('allowance/v_edit', $this->data);
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

}

/* End of file Allowance.php */
/* Location: ./application/modules/Master/controllers/Allowance.php */