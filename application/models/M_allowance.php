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

}

/* End of file M_allowance.php */
/* Location: ./application/models/M_allowance.php */