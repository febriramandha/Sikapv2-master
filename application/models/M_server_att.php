<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_server_att extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->db2 = $this->load->database('sqlsrv',TRUE);
	}

	public function NewUserinfo($data)
	{
		$this->db2->query("SET IDENTITY_INSERT userinfo ON");
		return $this->db2->insert('userinfo',$data);
	}

	public function UpdateUserinfo($data, $id)
	{
		return $this->db2->update('userinfo',$data, $id);
	}

	public function cekTemplate($where)
	{
		$this->db2->select('userid');
		return $this->db2->get_where('template',$where)->row();
	}

	public function DelUserinfo($where)
	{
		return $this->db2->delete('userinfo', $where);
	}


	public function Newdepartments($data)
	{
		$this->db2->query("SET IDENTITY_INSERT departments ON");
		return $this->db2->insert('departments', $data);
	}

	public function Updatedepartments($data, $id)
	{
		return $this->db2->update('departments',$data, $id);
	}

	public function Deldepartments($id)
	{
		return $this->db2->delete('departments',$id);
	}

	public function Newmachines($data)
	{
		$this->db2->query("SET IDENTITY_INSERT machines ON");
		return $this->db2->insert('machines', $data);
	}

	public function Updatemachines($data, $id)
	{
		return $this->db2->update('machines',$data, $id);
	}

	public function Delmachines($id)
	{
		return $this->db2->delete('machines',$id);
	}

}

/* End of file M_server_att.php */
/* Location: ./application/models/M_server_att.php */