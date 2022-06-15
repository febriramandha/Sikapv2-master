<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_sch_class extends CI_Model {

	public function GetSchClassType($type)
	{
		$this->db->order_by('id')
				->where_in('deleted','1,2',false)
				->where_in('sch_type',"'$type','0'",false);
		return $this->db->get('sch_class');
	}

	public function Getschnotfixed_run_day($run_id)
	{
		$this->db->select('a.id, a.day_ind, b.class_id')
				->from('days a')
				->join("(select run_id, unnest(class_id) as class_id,unnest(day_id) as day_id from schnotfixed_run_day where run_id=$run_id) as b",'a.id=b.day_id','left')
				->order_by('a.id');
		return $this->db->get();
	}

	public function GetPegawaiInstansi($dept_id)
	{
		$this->db->select('id')->from('v_users_all')->where('dept_id',$dept_id);

		return $this->db->get();

	}

	public function GetPegawaiSchRun($dept_id,$user_id)
	{
		$date = date('Y-m-d');
		
		$this->db->select('a.id')
					->from('sch_run_users a')
					->join('sch_run b', 'a.sch_run_id = b.id')
					->where('b.start_date <=', $date)
					->where('b.end_date >=', $date)
					->where('a.dept_id',$dept_id)
					->where($user_id,'any(user_id)', false);
	
		return $this->db->get();
	}
}

/* End of file M_sch_class.php */
/* Location: ./application/models/M_sch_class.php */