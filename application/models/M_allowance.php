<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_allowance extends CI_Model {

	public function GetallowanceEsolonGolongan($eselon='', $golongan='')
	{
		$this->db->where(['eselon_id' 	=> $eselon,
						  'golongan_id' => $golongan,
						  'status'		=> 1])
				->order_by('position');
		return $this->db->get('_allowances');
	}

	public function GetTpp($id='')
	{
		$this->db->select('a.*,b.id as allowances_id,b.dept_id')
				->join('_opd_allowances b','b.allowances_id = a.id','left')
			     ->where('a.id', $id);
		return $this->db->get('_allowances a');
	}

	public function CekAllowance($id='')
	{
		$this->db->select('*')
				  ->where('allowances_id',$id);

		return $this->db->get('_opd_allowances');
	}

	public function GetAllowance($id= '')
	{
		// $this->db->select('*')
		// 		 ->join('')
		// 		 ->where('')
	}
}

/* End of file M_allowance.php */
/* Location: ./application/models/M_allowance.php */