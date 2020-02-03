<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_instansi extends CI_Model {

	public function GetInstansi_tree()
	{
		$this->db->select('id, dept_name, parent_id');
		$this->db->order_by('position_order, id');
		$this->db->where('status',1);
		return $this->db->get('mf_departments')->result();
	}

	public function GetInstansi($id='')
	{
		$this->db->select('a.*,b.nama as kecamatan')
			     ->where('a.id', $id)
			     ->join('_kecamatan b','a.kecamatan_id=b.id');
		return $this->db->get('mf_departments a');

	}

	public function AjaxGetTree($key_url='')
	{
		foreach ($this->GetInstansi_tree() as $row) {
			$sub_data['id'] 		= encrypt_url($row->id,$key_url); 
			$sub_data['name'] 		= $row->dept_name;
			$sub_data['value'] 		= encrypt_url($row->id,$key_url);
			$sub_data['parent_id'] 	= encrypt_url($row->parent_id,$key_url); 
			$data[] = $sub_data;
		}

		foreach($data as $key => &$value)
		{
		 $output[$value["id"]] = &$value;
		}

		foreach($data as $key => &$value)
		{
		 if($value["parent_id"] && isset($output[$value["parent_id"]]))
		 {
		  $output[$value["parent_id"]]["items"][] = &$value;
		 }
		}
		foreach($data as $key => &$value)
		{
		 if($value["parent_id"] && isset($output[$value["parent_id"]]))
		 {
		  unset($data[$key]);
		 }
		}
	    // header('Content-Type: application/json');
		return  json_encode($data);

	}

	public function GetAdminDept($dept_id='')
	{
		$cek = $this->db->select('id, parent_id, position_order, level')->get_where('v_instansi_all', ['id' => $dept_id])->row();

		if ($cek->parent_id > 1 && $cek->position_order == 1 && $cek->level <= 3) {
			$dept_id_cek = $cek->parent_id;
		}elseif ($this->session->userdata('tpp_level') == 1) {
			$id = $this->db->select('id')->order_by('id','asc')->limit(1)->get('mf_departments')->row();
			$dept_id_cek = $id->id;
		}else {
			$dept_id_cek = $dept_id;
		}

		return $dept_id_cek;
	}

	public function GetInstasiDeptID($dept_id='')
	{
		$dept_id_cek = $this->GetAdminDept($dept_id);
		$level = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id_cek])->row()->level;
		return $this->GetInstansiLevel($dept_id_cek, $level);

	}

	

	public function GetInstansiLevel($dept_id='', $level='')
	{
		$this->db->select('id, dept_name, dept_alias, parent_id, path_info, path_id, level,position_order');
		$this->db->where("path_id['".$level."']='".$dept_id."'");
		return $this->db->get('v_instansi_all');
	}

	public function GetInstasiDeptIDCountParent($dept_id='')
	{
		$dept_id_cek = $this->GetAdminDept($dept_id);
		$level = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id_cek])->row()->level;
		return $this->GetInstansiLevelCountParent($dept_id_cek, $level);

	}

	public function GetInstansiLevelCountParent($dept_id='', $level='')
	{
		$this->db->select('id, dept_name, dept_alias, a.parent_id, path_info, path_id, level,position_order,jum_sub');
		$this->db->join('(select count(*) as jum_sub, parent_id from mf_departments group by parent_id) as jum_sub','a.id=jum_sub.parent_id','left');
		$this->db->where("path_id['".$level."']='".$dept_id."'");
		$this->db->order_by('path_info');
		return $this->db->get('v_instansi_all a');
	}

}

/* End of file M_instansi.php */
/* Location: ./application/models/M_instansi.php */