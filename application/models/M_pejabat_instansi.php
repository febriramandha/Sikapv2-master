<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_pejabat_instansi extends CI_Model {

	public function GetPajabatByInstansi($dept_id, $id)
	{
		$this->db->select('a.dept_name, c.nama, c.nip,c.gelar_dpn, c.gelar_blk,c.jabatan, d.nama as kecamatan, e.pangkat')
				->from('mf_departments a')
				->join("(select user_id, dept_id from pejabat_instansi where pejabat_id=$id) as b",'b.dept_id=a.id','left')
				->join('v_users_all c','b.user_id=c.id','left')
				->join('_kecamatan d','a.kecamatan_id=d.id OR a.kecamatan_id = d.kecamatan_id_simpeg','left')
				->join('_golongan e','c.golongan_id=e.id','left')
				->where('a.id', $dept_id);
		return $this->db->get();
	}

}

/* End of file M_pejabat_instansi.php */
/* Location: ./application/models/M_pejabat_instansi.php */