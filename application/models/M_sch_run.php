<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_sch_run extends CI_Model {

	public function GetSchRunInstansi($id='',$dept_id='',$level='')
	{

		$this->db->select('b.id,a.dept_id,b.dept_name, b.dept_alias, b.path_info, path_id, b.level, b.position_order');
		$this->db->from('(SELECT id, unnest(dept_id) as dept_id FROM sch_run) as a');
		$this->db->join('v_instansi_all_master b','a.dept_id=b.id','left');
		$this->db->where('a.id', $id);
		$this->db->where("path_id['".$level."']='".$dept_id."'");
		$this->db->order_by('path_info');
		return $this->db->get();
	}

	public function GetSchRun($id='')
	{
		$this->db->select('a.id, a.name, a.start_date, a.end_date, b.class_id, b.s_day, c.start_time, c.end_time, c.check_in_time1, c.check_in_time2, c.check_out_time1, c.check_out_time2, d.day_ind')
					->from('sch_run a')
					->join('sch_run_deil b','a.id=b.run_id','left')
					->join('sch_class c','c.id=b.class_id','left')
					->join('days d','b.s_day=d.id','left')
					->order_by('d.id')
					->where('a.id', $id);
		return $this->db->get();
	}

	public function GetSchRunNotfixed($id='')
	{
		$this->db->select('a.id, a.name, a.start_date, a.end_date, b.class_id, b.day_id, c.start_time, c.end_time, c.check_in_time1, c.check_in_time2, c.check_out_time1, c.check_out_time2, d.day_ind')
					->from('sch_run a')
					->join('(select run_id, unnest(class_id) as class_id,unnest(day_id) as day_id from schnotfixed_run_day) as b','a.id=b.run_id','left')
					->join('sch_class c','c.id=b.class_id','left')
					->join('days d','b.day_id=d.id','left')
					->order_by('d.id')
					->where('a.id', $id);
		return $this->db->get();
	}

	public function GetSchRunShiftInstansi($dept_id='',$level='')
	{

		$this->db->select('b.id,a.dept_id,b.dept_name, b.dept_alias, b.path_info, path_id, b.level, b.position_order');
		$this->db->from('(SELECT unnest(dept_id) as dept_id FROM shift_run group by 1 ) as a');
		$this->db->join('v_instansi_all_master b','a.dept_id=b.id','left');
		$this->db->where("path_id['".$level."']='".$dept_id."'");
		$this->db->order_by('path_info');
		return $this->db->get();
	}

	public function GetSchRunShift($dept_id='', $level='')
	{
		$this->db->select('a.id, kd_shift, a.ket,c.start_time, c.end_time, c.check_in_time1, c.check_in_time2, c.check_out_time1, c.check_out_time2, c.work_day')
					->from('(SELECT id, kd_shift, class_id, ket, unnest(dept_id) as dept_id FROM shift_run group by 1,2,3,4) as a')
					->join('sch_class c','c.id=a.class_id','left')
					->join('v_instansi_all_master d','a.dept_id=d.id','left')
					->where("path_id['".$level."']='".$dept_id."'")
					->group_by('1,2,3,4,5,6,7,8,9,10')
					->order_by('a.id');
		return $this->db->get();
	}

	public function sch_run_user($id)
	{
		$this->db->select('b.id, start_date, end_date')
				->from('sch_run_users a')
				->join('sch_run b','a.schrun_id=b.id','left')
				->where('a.id',$id)
				->group_by('1,2,3');
		return $this->db->get();
	}

	public function userAll_schRunUser($id)
	{
		$this->db->select('b.id, b.nip, b.nama,b.gelar_dpn, b.gelar_blk, c.kd_shift')
				->from('(SELECT id, unnest(user_id) as user_id, schrun_id FROM sch_run_users) as a')
				->join('v_users_all b','a.user_id=b.id','left')
				->join('(select user_id,schrun_id,array_agg(b.kd_shift::text ORDER BY start_shift) as kd_shift
						from (SELECT unnest(shiftrun_id) as shiftrun_id, unnest(start_shift) as start_shift, user_id, schrun_id FROM shift_run_users) as a
							join shift_run b on a.shiftrun_id=b.id
							GROUP BY 1,2) as c','(a.user_id=c.user_id and a.schrun_id=c.schrun_id)','left')
				->where('a.id', $id)
				->order_by('b.no_urut');
		return $this->db->get();
	}

	public function GetUserSchRun($user_id,$schrun_id)
	{
		$this->db->select('b.id, a.user_id, b.nip, b.nama,b.gelar_dpn, b.gelar_blk, c.start_date, c.end_date, b.dept_id, a.schrun_id')
				->from('(SELECT id, unnest(user_id) as user_id, schrun_id FROM sch_run_users) as a')
				->join('v_users_all b','a.user_id=b.id','left')
				->join('sch_run c','a.schrun_id=c.id','left')
				->where('a.schrun_id', $schrun_id)
				->where('a.user_id', $user_id);
		return $this->db->get();
	}

	


}

/* End of file M_sch_run.php */
/* Location: ./application/models/M_sch_run.php */