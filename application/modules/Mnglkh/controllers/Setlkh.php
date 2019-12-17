<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Setlkh extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Setup LKH Umum', 'mnglkh/setlkh');
		$this->data['title'] = "Manajemen LKH";
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['sub_title']  = "Setup LKH Umum";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['lkh']		  = $this->db->order_by('id')->get('_lkh_setting')->result();
		$this->load->view('setlkh/v_index', $this->data);
	}


	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('input', 'jumlah hari input', 'required|numeric');
		$this->form_validation->set_rules('verifikasi', 'jumlah hari verifikasi', 'required|numeric');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {

				$data = array('jumlah' 	 	    => $this->input->post('input'),);
				$this->return = $this->db->update('_lkh_setting',$data,['id' => 1]);

				$data = array('jumlah' 	 	    => $this->input->post('verifikasi'),);
				$this->return = $this->db->update('_lkh_setting',$data,['id' => 2]);

				if ($this->return) {
					 $this->result = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			    'message' => 'Data gagal disimpan');
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


}

/* End of file Setlkh.php */
/* Location: ./application/modules/Mnglkh/controllers/Setlkh.php */