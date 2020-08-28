<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Push extends CI_Controller {

	public function index()
	{
		echo "Push";
	}

	public function Data($master)
	{
		$this->return = '';
		if ($this->input->post('token') != "token_transfer_simpeg_agam") {show_404();}

		$this->db_master = $this->load->database($master,TRUE);
		$method 	= $this->input->post('method');
		$tabel 	    = $this->input->post('tabel');
		//data
		$data 		= $this->input->post('data');
        $data_json  = json_decode($data);

        foreach ($data_json as $key => $v) {
            if (!empty($v) || $v == '0') {
                $data_array[$key] = $v;
            }
        }

        if ($this->input->post('method') == "insert") {
        	 if (!empty($data_array)) {
    		 	$this->return = $this->db_master->insert($tabel, $data_array);
        	 }
    		 
        }elseif ($this->input->post('method') == "update") {
        	if (!empty($data_array)  && !empty($data_json['id'])) {
        		 $id = $data_array['id'];
	        	 unset($data_array['id']);
	    		$this->return = $this->db_master->update($tabel, $data_array, ['id' => $id]);
        	}
        }elseif ($this->input->post('method') == "delete") {
        	if (!empty($data_array['id'])) {
        		$this->return = $this->db_master->delete($tabel, ['id' => $data_array['id']]);
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