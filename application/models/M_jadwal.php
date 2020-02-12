<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jadwal extends CI_Model {

	public function GetJadwalUser($user_id, $rank1, $rank2)
	{
		$this->datatables->select('select a.id, 
							rentan_tanggal, 
							b.start_time, 
							b.end_time, 
							b.check_in_time1, 
							b.check_in_time2, 
							b.check_out_time1, 
							b.check_out_time2,
							c.start_time as start_time_shift, 
							c.end_time as end_time_shift, 
							c.check_in_time1 as check_in_time1_shift, 
							c.check_in_time2 as check_in_time2_shift, 
							c.check_out_time1 as check_out_time1_shift, 
							c.check_out_time2 as check_out_time2_shift,
							d.start_time as start_time_notfixed, 
							d.end_time as end_time_notfixed, 
							d.check_in_time1 as check_in_time1_notfixed, 
							d.check_in_time2 as check_in_time2_notfixed, 
							d.check_out_time1 as check_out_time1_notfixed, 
							d.check_out_time2 as check_out_time2_notfixed')
			->from("(select * from mf_users a, (select * from rentan_tanggal('$rank1','$rank2')) as tanggal) as a");
			$this->db->join("v_jadwal_kerja_users b","((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day) and b.user_id=a.id)",'left',false);
			$this->db->join('v_jadwal_kerja_users_shift c'," (a.id = c.user_id and c.start_shift=a.rentan_tanggal)",'left',false);
			$this->db->join('v_jadwal_kerja_users_notfixed d',"((rentan_tanggal >= d.start_date and rentan_tanggal <= d.end_date and extract('isodow' from a.rentan_tanggal) = d.day_id)and d.user_id=a.id)",'left',false);
			return $this->datatables->generate();
	}

}

/* End of file M_jadwal.php */
/* Location: ./application/models/M_jadwal.php */