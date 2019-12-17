<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_sch_class extends CI_Model {

	public function GetSchClassType($type)
	{
		$this->db->order_by('id')
				->where_in('deleted','1,2',false)
				->where_in('sch_type',"'$type','0'",false);
		return $this->db->get('sch_class');
	}

}

/* End of file M_sch_class.php */
/* Location: ./application/models/M_sch_class.php */