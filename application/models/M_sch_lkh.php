<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_sch_lkh extends CI_Model {

	public function Getsch_lkh($dept_id, $tgl)
	{
		$this->db->select('ket, count_inday, count_verday')
				  ->where("$dept_id",'any(dept_id)',false)
				  ->where("(start_date <= '$tgl' and end_date >= '$tgl')",'',false);
		return $this->db->get('sch_lkh');
	}

}

/* End of file M_sch_lkh.php */
/* Location: ./application/models/M_sch_lkh.php */