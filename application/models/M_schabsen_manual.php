<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_schabsen_manual extends CI_Model {

	public function userAll_schabsen_manual($id, $user_id='')
	{
		$this->db->select('b.id, a.id as schabsmanual_id, a.name, a.dept_id, b.nip, b.nama,b.gelar_dpn, b.gelar_blk, c.json_data, start_date, end_date, hari_id, absen_in, absen_out')
				->from('(SELECT id, unnest(user_id) as user_id, dept_id, start_date, end_date, name, hari_id, absen_in, absen_out FROM schabsen_manual) as a')
				->join('v_users_all b','a.user_id=b.id','left')
				->join('(select id, user_id, schabsmanual_id, json_agg((tanggal, status_in, status_out) ORDER BY tanggal) as json_data from (
					SELECT id, user_id, schabsmanual_id, unnest(tanggal) as tanggal, unnest(status_in) as status_in, unnest(status_out) as status_out  FROM absenmanual_data
					) as a GROUP BY 1,2,3) as c','(a.user_id=c.user_id and a.id=c.schabsmanual_id)','left')
				->where('a.id', $id)
				->order_by('b.no_urut');
				if ($user_id) {
					$this->db->where('b.id', $user_id);
				}
		return $this->db->get();
	}

}

/* End of file M_schabsen_manual.php */
/* Location: ./application/models/M_schabsen_manual.php */