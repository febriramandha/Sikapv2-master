<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_sch_lkh extends CI_Model {

	public function Getsch_lkh($dept_id, $tgl)
	{
		$cek = $this->Getsch_lkh_user($this->session->userdata('tpp_user_id'), $tgl);
		if($cek->row()) {
			$data_return = $cek;
		}else {
			$this->db->select('ket, count_inday, count_verday')
				  ->where("$dept_id",'any(dept_id)',false)
				  ->where("(start_date <= '$tgl' and end_date >= '$tgl')",'',false);
			$data_return =  $this->db->get('sch_lkh');
		}
		return $data_return;	
	}

	public function Getsch_lkh_user($user_id, $tgl)
	{
		$this->db->select('ket, count_inday, count_verday')
				  ->where("$user_id",'any(user_id)',false)
				  ->where("(start_date <= '$tgl' and end_date >= '$tgl')",'',false);
		return $this->db->get('sch_lkh_users');
	}

}

/* End of file M_sch_lkh.php */
/* Location: ./application/models/M_sch_lkh.php */