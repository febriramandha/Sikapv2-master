<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {

	public function GetDetailBiodata($user_id='')
	{	
		$this->db->where('a.id', $user_id);
		$this->db->select('a.id, a.nama, a.nip, a.key, b.dept_name, c.gelar_dpn, c.gelar_blk, c.gender, c.lahir_tanggal, c.statkawin_id, c.jabatan, d.username, d.level, d.status, d.avatar, e.agama, f.nama as status_pegawai, g.golongan, g.pangkat, h.eselon, i.last_login, c.eselon_id, c.golongan_id')
				->from('mf_users a')
				->join('mf_departments b','a.dept_id=b.id')
				->join('sp_pegawai c','a.id=c.user_id','left')
				->join('users_login d','a.id=d.user_id','left')
				->join('_agama e','c.agama_id=e.id','left')
				->join('_statpeg f','c.statpeg_id=f.id','left')
				->join('_golongan g','c.golongan_id=g.id','left')
				->join('_eselon h','c.eselon_id=h.id','left')
				->join('v_last_login_users i','d.id=i.login_id','left');
		return $this->db->get();
	}

	public function GetUserAll($dept_id='')
	{
		$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
		$this->db->select('a.id, a.nip, a.key, a.nama, a.dept_alias, b.level, status, status_pegawai, att_status, count_finger')
				->from('v_users_all a')
				->where('key > 0')
				->join('users_login b','a.id=b.user_id','left')
				->order_by('path_info, eselon_id');
		$this->db->where("path_id['".$level."']='".$dept_id."'");
        return $this->db->get();
	}

  public function GetUserByEselon($eselon='', $dept_id='')
  {
      if ($dept_id) {
         $this->db->where('a.dept_id', $dept_id);
      }
      $this->db->where_in('h.eselon', $eselon);
      $this->db->select('a.id, a.nama, a.nip, a.key, b.dept_name, c.gelar_dpn, c.gelar_blk, c.gender, c.lahir_tanggal, c.statkawin_id, c.jabatan, d.username, d.level, d.status, d.avatar, e.agama, f.nama as status_pegawai, g.golongan, g.pangkat, h.eselon, i.last_login')
          ->from('mf_users a')
          ->join('mf_departments b','a.dept_id=b.id')
          ->join('sp_pegawai c','a.id=c.user_id','left')
          ->join('users_login d','a.id=d.user_id','left')
          ->join('_agama e','c.agama_id=e.id','left')
          ->join('_statpeg f','c.statpeg_id=f.id','left')
          ->join('_golongan g','c.golongan_id=g.id','left')
          ->join('_eselon h','c.eselon_id=h.id','left')
          ->join('v_last_login_users i','d.id=i.login_id','left')
          ->order_by('c.golongan_id, c.eselon_id');
      return $this->db->get();
  }

	public function upload_foto()
	{
		$config = array('upload_path' 		=> './uploads/avatar/',
    					 'allowed_types' 	=> 'gif|jpg|png',
    					 'file_name'		=> 'sikap_'.$this->session->userdata('tpp_login_id').'_'.md5($_FILES['file']['name']).'-'.date('ymdhis'),
    					 'max_size'			=> 2048);	
		$this->load->library('upload', $config);
 
		if ( ! $this->upload->do_upload('file')){
			$data_res =json_encode(['status'=> false, 'msg'=> $this->upload->display_errors()]);
		}else{
     		$gbr = $this->upload->data();
     		$img_properties  = array('image_library' => 'gd2',
     								 'source_image'	 => './uploads/avatar/'.$gbr['file_name'],
     								 'create_thumb'  => false,
     								 'maintain_ratio'=> false,
     								 'quality'		 => '50%',
     								 'width' 		 => 500,
     								 'height'		 => 500,
     								 'new_image'	 => './uploads/avatar/thumb/'.$gbr['file_name'],
     								 'master_dim'	 => 'auto');
            $this->load->library('image_lib', $img_properties);
            $this->image_lib->resize();
            $picture = $gbr['file_name'];

            $foto_last = $this->db->select('avatar')->get_where('users_login', ['id' => $this->session->userdata('tpp_login_id')])->row()->avatar;

            $data_foto = array('avatar' => $picture, );
            $res = $this->db->update('users_login', $data_foto, ['id' => $this->session->userdata('tpp_login_id')]);
			
			if ($res) {
				unlink('uploads/avatar/'.$picture);
				if ($foto_last != 'avatar.png') {
	            	unlink('uploads/avatar/thumb/'.$foto_last);
	            }
				$data_avatar = ([
				'tpp_avatar'		=> $picture]);
				$this->session->set_userdata($data_avatar);
				$data_res = json_encode(['status'=> true, 
														'msg'=> 'foto berhasil diperbarui',
														'url'=> base_url().'/uploads/avatar/thumb/'.$picture,]);
			}else {
				$data_res = json_encode(['status'=> false, 'msg'=> 'gagal mengabil data']);
			}
		}

		return 	$data_res;	
	}

}

/* End of file M_user.php */
/* Location: ./application/models/M_user.php */