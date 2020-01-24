<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_ibadah extends CI_Model {

	public function GetDataIbadahRank($user_id, $rank1, $rank2)
	{
		$this->db->select('*')
    			 ->from('ibadah_muslim')
    			 ->where('user_id', $user_id)
    			 ->where("tgl_ibadah::date BETWEEN '$rank1' and '$rank2'", NULL, FALSE )
    			 ->order_by('tgl_ibadah','asc');
    	return $this->db->get();
	}

	public function GetDataIbadahRankNonmuslim($user_id, $rank1, $rank2)
	{
		$this->db->select('*')
    			 ->from('ibadah_nonmus')
    			 ->where('user_id', $user_id)
    			 ->where("tgl_ibadah::date BETWEEN '$rank1' and '$rank2'", NULL, FALSE )
    			 ->order_by('tgl_ibadah','asc');
    	return $this->db->get();
	}

}

/* End of file M_ibadah.php */
/* Location: ./application/models/M_ibadah.php */