<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_schlkh_manual extends CI_Model {

	public function userAll_schlkh_manual($id, $user_id='')
	{
		$this->db->select('b.id, a.id as schlkhmanual_id, a.name, a.dept_id, b.nip, b.nama,b.gelar_dpn, b.gelar_blk, start_date, end_date,c.id as rekapmanual_id, jumlah_laporan, total_laporan')
				->from('(SELECT id, unnest(user_id) as user_id, dept_id, start_date, end_date, name FROM schlkh_manual) as a')
				->join('v_users_all b','a.user_id=b.id','left')
				->join('rekaplkh_manual c','(a.id=c.schlkhmanual_id and a.user_id=c.user_id)','left')
				->where('a.id', $id)
				->order_by('b.no_urut');
				if ($user_id) {
					$this->db->where('b.id', $user_id);
				}
		return $this->db->get();
	}

}

/* End of file M_schlkh_manual.php */
/* Location: ./application/models/M_schlkh_manual.php */