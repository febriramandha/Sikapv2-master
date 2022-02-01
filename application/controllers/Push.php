<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Push extends CI_Controller {

	public function index()
	{
		echo "Push";
	}

  public function jadwal_sekolah()
  {
        $this->db->select('62 as sch_run_id, a.id as dept_id, a.dept_name, b.user_id')
                 ->from('v_instansi_all a')
                 ->join("(SELECT dept_id, array_agg(id) as user_id
                          FROM mf_users
                          GROUP BY dept_id) as b",'a.id=b.dept_id')
                 ->where_not_in('id','select dept_id from sch_run_users where schrun_id=62',FALSE)
                 ->where('parent_id',43)
                 ->where('id !=',1798);
        $qr = $this->db->get()->result();

        foreach ($qr as $row) {
             $data_in = array('schrun_id' => $row->sch_run_id,
                              'dept_id' => $row->dept_id,
                              'user_id' => $row->user_id, 
                            );
             // $this->db->insert('sch_run_users', $data_in);
        }
  }

    public function in()
    {
        $this->db->where("tgl_lkh >= '2021-04-01' and tgl_lkh <= '2021-04-16'",NULL,FALSE);
        $re = $this->db->get_where('data_lkh',['user_id' => '295']);
        foreach ($re->result() as $row) {
             $data_in = array('user_id' => 4180,
                              'dept_id' => 101,
                              'tgl_lkh' => $row->tgl_lkh,
                              'jam_mulai' => $row->jam_mulai,
                              'jam_selesai' => $row->jam_selesai,
                              'kegiatan' => $row->kegiatan,
                              'hasil' => $row->hasil,
                              'jenis' => $row->jenis,
                              'status' => $row->status,
                              'persentase' => $row->persentase,
                              'poin' => $row->poin,
                             );
             $ad= $this->db->get_where('data_lkh',['user_id' => 4180 ,'tgl_lkh'=> $row->tgl_lkh])->row();
             if (!$ad || empty($ad->verifikator)) {
                // $this->db->insert('data_lkh', $data_in);
             }
            
        }

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

    public function Ceklok()
    {
        // update data ceklok
        if ($_SERVER['HTTP_HOST'] != "localhost") {show_404();}
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

    public function Telegramnotifapel()
    {
        $this->load->model('m_absen');
        $chat_telegram = $this->db->select('a.telegram_chat_id,b.nama,a.dept_id,c.dept_name')
                        ->join('v_users_all b','a.user_id = b.id')
                        ->join('v_instansi_all c','a.dept_id = c.id')
                        ->where('a.pejabat_id', 3)
                        ->where('a.telegram_chat_id is not null')
                        ->get('pejabat_instansi a')->result();

                
        $this->return = FALSE;
        foreach ($chat_telegram as $key) {
                 $apel = $this->m_absen->getApelTelegram($key->dept_id)->result();
                if(!empty($apel)){
                    $msg = "\n<b>Laporan Absen ".$apel[0]->nama_apel." Pada Hari ".tgl_ind_hari(date('Y-m-d'))." di ".$key->dept_name."</b>";
                    $no = 1;
                    foreach ($apel as $row) {
                        $msg .= "\n".$no.". ".$row->nama." ".tanggal_format($row->checktime, 'H:i:s');
                        $no++;
                    }
                    telegram_send($key->telegram_chat_id, "Hai <b>".$key->nama."</b>". $msg);
                    $this->return = TRUE;
                }else {
                    $this->return = NULL;
                }   
        }
        if($this->return !== NULL){
            if($this->return === TRUE){
                    $this->db->insert('_log_cron', ['status' => "sukses notif data apel telegram", 'waktu' => date('Y-m-d H:i:s'), 'log' => 1]);
            }else if($this->return === FALSE) {
                    $this->db->insert('_log_cron', ['status' => "gagal notif data apel telegram", 'waktu' => date('Y-m-d H:i:s'), 'log' => 1]);
            }
        }
    }
    public function Telegramnotifabsenmasuk()
    {
        $this->load->model('m_absen');
        $chat_telegram = $this->db->select('a.telegram_chat_id,b.nama,a.dept_id,c.dept_name')
                        ->join('v_users_all b','a.user_id = b.id')
                        ->join('v_instansi_all c','a.dept_id = c.id')
                        ->where('a.pejabat_id', 3)
                        ->where('a.telegram_chat_id is not null')
                        ->get('pejabat_instansi a')->result();
        
        $this->return = FALSE;
        foreach ($chat_telegram as $key) {
                 $absen = $this->m_absen->getAbsenOnline($key->dept_id,"masuk")->result();
                if(!empty($absen)){
                    $msg = "\n<b>Laporan Absen Masuk Pada Hari ".tgl_ind_hari(date('Y-m-d'))." di ".$key->dept_name."</b>";
                    
                    $no = 1;
                    foreach ($absen as $row) {
                        $msg .= "\n".$no.". ".$row->nama." ".tanggal_format($row->checktime, 'H:i:s');
                        $no++;
                    }
                    telegram_send($key->telegram_chat_id, "Hai <b>".$key->nama."</b>". $msg);
                    $this->return = TRUE;    
                }else {
                    $this->return = NULL;
                }      
        }
        if($this->return !== NULL){
            if($this->return === TRUE){
                    $this->db->insert('_log_cron', ['status' => "sukses notif data absen masuk telegram", 'waktu' => date('Y-m-d H:i:s'), 'log' => 1]);
            }else if($this->return === FALSE){
                    $this->db->insert('_log_cron', ['status' => "gagal notif data absen masuk telegram", 'waktu' => date('Y-m-d H:i:s'), 'log' => 1]);
            }
        }
    }
    public function Telegramnotifabsenpulang()
    {
        $this->load->model('m_absen');
        $chat_telegram = $this->db->select('a.telegram_chat_id,b.nama,a.dept_id,c.dept_name')
                        ->join('v_users_all b','a.user_id = b.id')
                        ->join('v_instansi_all c','a.dept_id = c.id')
                        ->where('a.pejabat_id', 3)
                        ->where('a.telegram_chat_id is not null')
                        ->get('pejabat_instansi a')->result();
     
        $this->return = FALSE;
        foreach ($chat_telegram as $key) {
                 $absen = $this->m_absen->getAbsenOnline($key->dept_id,"pulang")->result();
                if(!empty($absen)){
                    $msg = "\n<b>Laporan Absen Pulang Pada Hari ".tgl_ind_hari(date('Y-m-d'))." di ".$key->dept_name."</b>";
                    $no = 1;
                    foreach ($absen as $row) {
                        $msg .= "\n".$no.". ".$row->nama." ".tanggal_format($row->checktime, 'H:i:s');
                        $no++;
                    }
                    telegram_send($key->telegram_chat_id, "Hai <b>".$key->nama."</b>". $msg);    
                    $this->return = TRUE;
                }else {
                    $this->return = NULL;   
                }   
        }
        if($this->return !== NULL){
            if($this->return === TRUE){
                    $this->db->insert('_log_cron', ['status' => "sukses notif data absen pulang telegram", 'waktu' => date('Y-m-d H:i:s'), 'log' => 1]);
            }else if($this->return === FALSE){
                    $this->db->insert('_log_cron', ['status' => "gagal notif data absen pulang telegram", 'waktu' => date('Y-m-d H:i:s'), 'log' => 1]);
            }
        }
    }

}

/* End of file Push.php */
/* Location: ./application/controllers/Push.php */