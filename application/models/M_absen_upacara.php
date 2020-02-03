<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_absen_upacara extends CI_Model {

	public function GetAbsenPegawai($user_id='', $rank1='', $rank2='')
	{
		 $this->db->select('c.id, d.tanggal, d.jam_mulai, d.ket, c.hadir')
        	->from('absen_upacara c')
        	->join('sch_upacara d','c.sch_upacara_id=d.id')
        	->order_by('d.tanggal','desc')
        	->where('c.user_id', $user_id);
	        $this->db->where("d.tanggal BETWEEN '$rank1' and '$rank2'", NULL, FALSE );
	     return $this->db->get();    
	}

}

/* End of file M_absen_upacara.php */
/* Location: ./application/models/M_absen_upacara.php */