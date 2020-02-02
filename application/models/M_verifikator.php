<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_verifikator extends CI_Model {

   public function GetVerifikatorQry($notin='')
  {
      $this->db->where_not_in('a.id', $notin);
      $this->db->like('lower(a.nama)', strtolower($this->input->get('qry')));
      $this->db->or_like('a.nip', $this->input->get('qry'));
      $this->db->limit(3);
      $this->db->select('a.id, a.nama, a.nip,d.avatar, c.jabatan')
          ->from('mf_users a')
          ->join('users_login d','a.id=d.user_id','left')
          ->join('sp_pegawai c','a.id=c.user_id','left');
      return $this->db->get();
  }

  public function GetAllVerifikatorDept($dept_id='')
  {
        $level    = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
        $this->db->select('a.id, a.nip, a.nama, a.gelar_dpn,a.gelar_blk, d.nama as ver_nama, d.nip as ver_nip, d.gelar_dpn as ver_gelar_dpn, d.gelar_blk as ver_gelar_blk')
          ->from('v_users_all a')
          ->join('verifikator c','a.id=c.user_id','left')
          ->join('v_users_all d','c.user_id_ver=d.id','left')
          ->where('a.key > 0')
          ->where('a.att_status',1)
          ->where('a.pns',1);
        $this->db->where("a.path_id['".$level."']='".$dept_id."'");
        return $this->db->get();
  }

  public function GetVerifikator($user_id)
  {
        $this->db->select('a.id, d.id as user_id_ver, d.nama as ver_nama, d.nip as ver_nip, d.gelar_dpn as ver_gelar_dpn, d.gelar_blk as ver_gelar_blk, e.jabatan')
          ->from('v_users_all a')
          ->join('verifikator c','a.id=c.user_id','left')
          ->join('v_users_all d','c.user_id_ver=d.id','left')
          ->join('sp_pegawai e','d.id=e.user_id','left')
          ->where('a.id',$user_id);
        return $this->db->get();
  }

  public function GetVerifikatorCetak($user_id)
  {
        $this->db->select('a.id, a.nip, a.nama, a.gelar_dpn,a.gelar_blk, d.id as user_id_ver, d.nama as ver_nama, d.nip as ver_nip, d.gelar_dpn as ver_gelar_dpn, d.gelar_blk as ver_gelar_blk, e.jabatan as ver_jabatan, f.jabatan, a.dept_id, g.pangkat, h.pangkat as ver_pangkat')
          ->from('v_users_all a')
          ->join('verifikator c','a.id=c.user_id','left')
          ->join('v_users_all d','c.user_id_ver=d.id','left')
          ->join('sp_pegawai e','d.id=e.user_id','left')
          ->join('sp_pegawai f','a.id=f.user_id','left')
          ->join('_golongan g','a.golongan_id=g.id','left')
          ->join('_golongan h','d.golongan_id=h.id','left')
          ->limit(1)
          ->where('a.id',$user_id);
        return $this->db->get();
  }

  public function GetUserByverifikator($id)
  {
        $this->db->select('a.id, a.user_id, b.dept_id, b.nama, b.nip, b.gelar_dpn, b.gelar_blk, c.jabatan')
          ->from('verifikator a')
          ->join('v_users_all b','a.user_id=b.id','left')
          ->join('sp_pegawai c','b.id=c.user_id','left')
          ->where('a.id',$id);
        return $this->db->get();
  }


}

/* End of file M_verifikator.php */
/* Location: ./application/models/M_verifikator.php */