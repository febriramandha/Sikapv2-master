<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_checkinout extends CI_Model {

	public function GetTahun()
	{
		$this->db->select("date_part('year',checktime) as tahun")
						 ->group_by("date_part('year',checktime)")
						 ->order_by("date_part('year',checktime)",'desc',false);
		 return $this->db->get('mf_checkinout');
	}

}

/* End of file M_checkinout.php */
/* Location: ./application/models/M_checkinout.php */