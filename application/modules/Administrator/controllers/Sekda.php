<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Sekda extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Sekretaris Daerah', 'administrator/sekda');
		$this->data['title'] = "Administrator";
		$this->load->model('m_user');
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		
		$this->data['eselon2a'] 	= $this->m_user->GetUserByEselon('2a')->result();
		$id = $this->db->select('user_id')->get_where('pejabat_instansi', ['pejabat_id' => 1])->row();
		if ($id) {
			$data_sekda = $this->m_user->GetDetailBiodata($id->user_id)->row();
		}
		$this->data['sekda']		= $data_sekda;
		$this->data['sub_title'] 	= "Sekretariat Daerah";
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->load->view('sekda/v_index', $this->data);
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('nama', 'nama', 'required')
								->set_rules('nip', 'nip', 'required')
								->set_rules('jabatan', 'jabatan', 'required');
		$this->form_validation->set_error_delimiters('<div>', '</div>');

		if ($this->form_validation->run() == TRUE) {
				$this->db->select('dept_id')
						->from('mf_users')->where('id', $this->input->post('nama'));
				$data_sekda = $this->db->get()->row();
				$id = $this->db->select('id')->get_where('pejabat_instansi', ['pejabat_id' => 1])->row();

				if (!$id) {
					$data = array('user_id' 	 		=> $this->input->post('nama'),
								  'pejabat_id' 	 		=> 1,
								  'dept_id' 	 		=> $data_sekda->dept_id,
								  'created_at' 			=> date('Y-m-d H:i:s'),
								  'created_by' 	 		=> $this->session->userdata('tpp_user_id'),
					 );
					$res = $this->db->insert('pejabat_instansi',$data);
				}else {
					$data = array('user_id' 	 		=> $this->input->post('nama'),
								  'dept_id' 	 		=> $data_sekda->dept_id,
								  'updated_at' 			=> date('Y-m-d H:i:s'),
								  'updated_by' 	 		=> $this->session->userdata('tpp_user_id'),
					 );
					$res = $this->db->update('pejabat_instansi',$data, ['id' => $id->id]);
				}
				

				if ($res) {
					 $data_ = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					 $data_ = array('status' => false,
				    			    'message' => 'Data gagal disimpan');
				}

		}else {
			$data_ = array('status' => false,
				    		'message' => validation_errors(),);
		}

		if ($data_) {
			$this->output->set_output(json_encode($data_));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}

}

/* End of file Sekda.php */
/* Location: ./application/modules/Administrator/controllers/Sekda.php */