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

        $array_master = array('simpegMaster');
        if (is_integer(array_search($master, $array_master)) == FALSE) {show_404();}

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
        	if (!empty($data_array)  && !empty($data_json->id)) {
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
            $array_log = array('tabel_name'        => $tabel,
                               'tabel_id'          => $data_json->id,
                               'tabel_method'      => $method);
            $this->db_master->insert('_log_update', $array_log);

			$this->output->set_output(json_encode(['status' => TRUE, 'message' => 'Data berhasil disimpan']));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Data gagal menyimpan.']));
		}
	}

    public function Ceklok($token)
    {
        if ($token != "GGG3KALI") {show_404();}
        $this->db3 = $this->load->database('sqlsrv',TRUE);
        
        $this->db3->select('a.userid, a.checktime, a.sensorid, a.load_time, b.defaultdeptid');
        $this->db3->join('userinfo b','a.userid=b.userid');
        $this->db3->where('a.status_job=1');
        $this->db3->order_by('a.userid', 'asc');
        $data = $this->db3->get('checkinout a');
        
        if ($data->num_rows() > 0) {
                $this->db->trans_begin();
                foreach($data->result() as $row) {
                    $filter_data = array(
                        "user_id"        => $row->userid,
                        "checktime"      => $row->checktime,
                        "machine_id"     => $row->sensorid,
                        "load_time"      => $row->load_time,
                        "dept_id"        => $row->defaultdeptid,
                        "group"          => 1,
                        "created_by"     => 995,
                    );
                    
                   $this->db->insert('mf_checkinout', $filter_data);
                   $this->db3->where('userid', $row->userid)
                             ->where('checktime', $row->checktime)
                             ->update('checkinout', ['status_job' => 2]);

                }
            
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $respon = "Failed to Save Data ceklok";
                } else {
                    $this->db->trans_commit();
                    $respon = "sukses perbarui data ceklok";
                }
                $this->db->insert('_log_cron', ['status' => $respon, 'waktu' => date('Y-m-d H:i:s'), 'log' => 1]);
          }
     
    }

}

/* End of file Push.php */
/* Location: ./application/controllers/Push.php */