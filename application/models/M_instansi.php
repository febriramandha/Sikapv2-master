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
		}elseif ($this->session->userdata('tpp_level') == 1 || $this->session->userdata('tpp_level') == 4) {
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
		$level = $this->db->select('level,simpeg_dept_id')->get_where('v_instansi_all', ['id' => $dept_id_cek])->row();
		return $this->GetInstansiLevel($dept_id_cek, $level->level, $level->simpeg_dept_id);
	}

	

	public function GetInstansiLevel($dept_id='', $level='', $simpeg_dept_id= '')
	{
		$this->db->select('id, dept_name, dept_alias, parent_id, path_info, path_id, level,position_order');
		$this->db->where("path_id['".$level."']='".$simpeg_dept_id."'");
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

	public function GetInstansi_segment($instansi_id, $level)
	{
		if ($this->session->userdata('tpp_level') == 1 && $instansi_id == 2) {
			# code...
		}else {
			$this->db->where("path_id['".$level."']='".$instansi_id."'");
		}
		$this->db->where('id != 1');
		$this->db->where_not_in('id',"(select instansi_id from _jabatan where instansi_id != $instansi_id)", false);
		$this->db->order_by('path_order');
		// $this->db->limit(2);
		return $this->db->get('v_instansi_tree');
	}

	public function GetPejabatInstansiChatId($user_id,$dept_id)
	{
		$this->db->select('*');
		$this->db->where('dept_id',$dept_id);
		$this->db->where('user_id',$user_id);
		$this->db->where('pejabat_id',3);
		$query = $this->db->get('pejabat_instansi')->row();
		if(!empty($query)){
			if($query->pejabat_id == 3){
				if(!empty($query->telegram_chat_id)){
					return FALSE;
				}else {
					return TRUE;
				}
			}
		}
	}

	public function GetSyncOPD($id){
		// $id = 1;
		$this->db->select('a.* ');
		$this->db->from("(SELECT * FROM (SELECT * FROM simpeg_dev.unor WHERE parent = ".$id.") as aa WHERE aa.type_unor NOT IN ('NAGARI','JORONG','NAGARI_PERSIAPAN','KUK'))as a");
		$this->db->where('NOT EXISTS (SELECT * FROM mf_departments b WHERE a.id = b.simpeg_dept_id)','',FALSE);
		return $this->db->get();
	}

	public function getNumOrder($id){
		// $id = 1;
		$this->db->select('(position_order) as num');
		$this->db->from('mf_departments');
		$this->db->where('parent_id',$id);
		$this->db->order_by('position_order DESC');
		$this->db->limit(1);
		return $this->db->get();
	}

	public function getKecamatan($id){
		$this->db->select('_kecamatan.id');
		$this->db->where('simpeg_dev.unor.id',$id);
		$this->db->join('_kecamatan','_kecamatan.kecamatan_id_simpeg = simpeg_dev.unor.kecamatan_id');
		$this->db->from('simpeg_dev.unor');
		return $this->db->get();
	}

	
	
	public function GetSyncOPD2(){
		$this->db->select('a.*,b.id as dept_id_simpeg, b.nama_unor,b.akronim,b.detail_lokasi,b.status as status_simpeg, b.kecamatan_id as kecamatan_id_simpeg_, b.type_unor, b.parent as parent_simpeg, b.norut_unor');
		$this->db->join('simpeg_dev.unor b','b.id = a.simpeg_dept_id');
		return $this->db->get('mf_departments a');
	}

	public function getOPDSimpeg(){
		$this->db->select('*');
		$this->db->where("type_unor NOT IN('JORONG','NAGARI','NAGARI_PERSIAPAN')");
		$this->db->order_by('id');
		return $this->db->get('simpeg_dev.unor');
	}
}

/* End of file M_instansi.php */
/* Location: ./application/models/M_instansi.php */