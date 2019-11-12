<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_acl extends CI_Model {

	public function privilage()
	{
		$acl1  = '';
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
		$acl1 = $this->db->get('_acl')->row();

        if ($acl1) {
        	$data_log = array('user_id'  => $this->session->userdata('tpp_user_id'),
        					  'acl_id'   => $acl1->id,
        					  'function' => $function, );
        	$data_update = $data_log;
        	$data_update['updated_at'] = date('Y-m-d H:i:s');
        	$this->db->set('hit', 'hit +1',false);
        	$this->db->update('_acl_log', $data_update, $data_log);

        	if ($this->db->affected_rows() == 0) {
        		$data_insert = $data_log;
        		$data_insert['created_at'] = date('Y-m-d H:i:s');
        		$this->db->insert('_acl_log', $data_insert);
        	}
        }
		return $acl1;
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