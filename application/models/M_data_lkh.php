<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_data_lkh extends CI_Model {

	public function cek_jam_lkh($user_id, $tgl)
	{
		$this->db->select('jam_selesai')
				 ->where('user_id', $user_id)
				 ->where('tgl_lkh', $tgl)
				 ->order_by('tgl_lkh','desc')
				 ->limit(1);
		return $this->db->get('data_lkh');
	}

	public function update_status($id ='',$data_tgl_lkh)
    {   
		$this->db->where_in('status','0,4',false);
        $this->db->where_not_in('tgl_lkh',$data_tgl_lkh);
        $this->db->update('data_lkh',['status' => 1]);
    }

    public function GetDatalkhRank($user_id, $rank1, $rank2, $status)
    {
    	$this->db->select('*')
    			 ->from('data_lkh')
    			 ->where('user_id', $user_id)
    			 ->where('status',$status)
    			 ->where("tgl_lkh::date BETWEEN '$rank1' and '$rank2'", NULL, FALSE )
    			 ->order_by('tgl_lkh,jam_mulai','asc');
    	return $this->db->get();
    }

}

/* End of file M_data_lkh.php */
/* Location: ./application/models/M_data_lkh.php */