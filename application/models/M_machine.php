<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_machine extends CI_Model {

	public function GetAllMesin()
	{
		$this->db->select('a.id, a.name, b.dept_name, b.dept_alias, b.path_info, a.ip, a.status, a.machine_number, a.ket')
        	->from('_mf_machines a')
        	->join('v_instansi_all b','b.id=a.dept_id')
        	->order_by('b.path_info,a.id')
        	->where('a.deleted', 1);
        return $this->db->get();
	}

}

/* End of file M_machine.php */
/* Location: ./application/models/M_machine.php */