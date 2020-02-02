<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_schrun_user extends CI_Model {

	public function CekTanggalLkh($user_id, $tgl)
	{
		$this->db->select('a.id, a.schrun_id,start_date, end_date, c.json_data as hari_kerja, d.id as shiftuserrun_id')
				 ->from('sch_run_users a')
				 ->join('sch_run b', 'a.schrun_id=b.id')
				 ->join('(select run_id, json_agg((s_day, start_time, end_time) ORDER BY s_day) as json_data from (select run_id, s_day, start_time, end_time from sch_run_deil a join sch_class b on a.class_id=b.id where class_id not in (0)) as a GROUP BY 1) as c','b.id=c.run_id','left')
				 ->join('shift_run_users d','a.schrun_id=d.schrun_id','left')
				 ->where("$user_id",'any(a.user_id)',false)
				 ->where("(start_date <= '$tgl' and end_date >= '$tgl')",'',false);
		return $this->db->get();
	}

}

/* End of file M_schrun_user.php */
/* Location: ./application/models/M_schrun_user.php */