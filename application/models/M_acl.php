<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_acl extends CI_Model {

	public function privilage()
	{
		$acl  = '';
		$class_name = $this->router->fetch_class();
        $function 	= $this->router->fetch_method();
        $login 		= $this->session->userdata('tpp_loggedin');
        $level 		= $this->session->userdata('tpp_level');


       $this->db->select('id')
				->where('controller', $class_name)
				->where("'$function'",'any(method)', false)
				->where('status', 1);
				 if ($login && $level) {
				 	$this->db->group_start();
					 	$this->db->where("$level",'any(level)', false);
					 	$this->db->or_where('type','frontend');
				 	$this->db->group_end();
				 }else {
				 	$this->db->where('type','frontend');
				 }
		$acl = $this->db->get('_acl')->row();

		return $acl;
	}

	public function _acl($level)
	{
		$this->db->where("$level", "any(level)",false)
				 ->where('status', 1)
				 ->where_in('type', array('class','menu','class-lg'))
				 ->order_by('position');
		return $this->db->get('_acl')->result();
	}

	public function cekAksesDept($dept_id)
	{
		# code...
	}

}

/* End of file M_acl.php */
/* Location: ./application/models/M_acl.php */