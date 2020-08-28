<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Push extends CI_Controller {

	public function index()
	{
		echo "Push";
	}

	public function Data($master)
	{
		if ($this->input->post('token') != "token_transfer_simpeg_agam") {show_404();}

		$this->db_master = $this->load->database($master,TRUE);
		$method 	= $this->input->post('method');
		$tabel 	    = $this->input->post('tabel');
		//data
		$data 		= $this->input->post('data');
        $data_json  = (array) json_decode($data);

        if ($this->input->post('method') == "insert") {
        	 if (!empty($data_json)) {
        	 	$data_json['created_at'] =  date('Y-m-d H:i:s');
    		 	$this->return = $this->db_master->insert($tabel, $data_json);
        	 }
    		 
        }elseif ($this->input->post('method') == "update") {
        	if (!empty($data_json)  && !empty($data_json['id'])) {
        		 $id = $data_json['id'];
	        	 unset($data_json['id']);
	        	 $data_json['updated_at'] =  date('Y-m-d H:i:s');
	    		$this->return = $this->db_master->update($tabel, $data_json, ['id' => $id]);
        	}
        }elseif ($this->input->post('method') == "delete") {
        	if (!empty($data_json['id'])) {
        		$this->return = $this->db_master->delete($tabel, ['id' => $data_json['id']]);
        	}
        	
        }

        if ($this->return) {
			$this->output->set_output(json_encode(['status' => TRUE, 'message' => 'Data berhasil disimpan']));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Data gagal menyimpan.']));
		}
	}

}

/* End of file Push.php */
/* Location: ./application/controllers/Push.php */