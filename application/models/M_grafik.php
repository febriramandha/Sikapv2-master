<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_grafik extends CI_Model {

	public function GetJUmlah_Pegawai($level, $dept_id)
	{
		$this->db->select('a.id, jum_user_pns,jum_user_non_pns,jum_user_non_aktif')
				 ->from('v_instansi_all_master a')
				 ->join('(SELECT count(*) as jum_user_pns, dept_id FROM mf_users where pns=1 and att_status=1 GROUP BY dept_id) as b','a.id=b.dept_id','left')
				 ->join('(SELECT count(*) as jum_user_non_pns, dept_id FROM mf_users where pns not in (1) and att_status=1 GROUP BY dept_id) as c','a.id=c.dept_id','left')
				 ->join(' (SELECT count(*) as jum_user_non_aktif, dept_id FROM "mf_users" where att_status not in (1)  GROUP BY dept_id) as d','a.id=d.dept_id','left')
				 ->where("path_id['".$level."']='".$dept_id."'");
		return $this->db->get();
	}

}

/* End of file M_grafik.php */
/* Location: ./application/models/M_grafik.php */