<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_user extends CI_Model {

	public function GetUser($user_id)
	{
		$this->db->select('a.id as user_id, b.id as login_id, b.username, a.nama, a.nip, a.tpp, a.dept_id, b.level, a.att_status, a.pns, b.status, c.gelar_dpn, c.gelar_blk, c.jabatan, c.golongan_id, c.eselon_id, c.gender, c.agama_id,c.statpeg_id, a.absen_online_app,d.kelas_jabatan,a.simpeg_pegawai_id')
				 ->from('mf_users a')
				 ->join('users_login b','a.id=b.user_id')
         ->join('sp_pegawai c','a.id=c.user_id','left')
         ->join('v_kelas_jabatan d','c.kelas_jabatan = d.id','left')
				 ->where('a.id', $user_id);
		return $this->db->get();
	}

	public function GetDetailBiodata($user_id='')
	{	
		$this->db->where('a.id', $user_id);
		$this->db->select('a.id, a.nama, a.nip, a.key, b.dept_name, c.gelar_dpn, c.gelar_blk, c.gender, c.lahir_tanggal, c.statkawin_id, c.jabatan, d.username, d.level, d.status, d.avatar, e.agama, f.nama as status_pegawai, g.golongan, g.pangkat, h.eselon, i.last_login, c.eselon_id, c.golongan_id,a.dept_id')
				->from('mf_users a')
				->join('mf_departments b','a.dept_id=b.id')
				->join('sp_pegawai c','a.id=c.user_id','left')
				->join('users_login d','a.id=d.user_id','left')
				->join('_agama e','c.agama_id=e.id','left')
				->join('_statpeg f','c.statpeg_id=f.id','left')
				->join('_golongan g','c.golongan_id=g.id','left')
				->join('_eselon h','c.eselon_id=h.id','left')
				->join('v_last_login_users i','d.id=i.login_id','left')
        ->limit(1);
		return $this->db->get();
	}

	public function GetUserAll($dept_id='',$all= '')
	{
		$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
		$this->db->select('a.id,a.dept_id_simpeg, a.nip, a.key, a.nama, a.dept_alias, b.level, status, status_pegawai, att_status, count_finger, a.gelar_dpn, a.gelar_blk, b.avatar')
				->from('v_users_all a')
				->where('key > 0')
				->join('users_login b','a.id=b.user_id','left')
				->order_by('path_info, eselon_id,pns');
        if($all == "1"){
      		$this->db->where("path_id['".$level."']='".$dept_id."'");
        }else {
      		$this->db->where("a.dept_id_simpeg='".$dept_id."'");
        }
        return $this->db->get();
	}

  public function GetUserAllLimitQry($dept_id='',$limit='')
  {
    $level    = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
    $this->db->group_start();
      $this->db->like('lower(a.nama)', strtolower($this->input->get('qry')));
      $this->db->or_like('a.nip', $this->input->get('qry'));
    $this->db->group_end();
    $this->db->select('a.id, a.nip, a.nama,a.gelar_dpn, a.gelar_blk, b.avatar')
        ->from('v_users_all a')
        ->where('key > 0')
        ->limit($limit)
        ->join('users_login b','a.id=b.user_id','left')
        ->order_by('path_info, eselon_id,pns');
    $this->db->where("path_id['".$level."']='".$dept_id."'");
        return $this->db->get();
  }

  public function GetUserAllAktif($dept_id='')
  {
    
    $level    = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
    $this->db->select('a.id, a.nip, a.key, a.nama, a.dept_alias, b.level, status, status_pegawai, att_status, count_finger, a.gelar_dpn, a.gelar_blk')
        ->from('v_users_all a')
        ->where('key > 0')
        ->where('att_status',1)
        ->join('users_login b','a.id=b.user_id','left')
        ->order_by('path_info, eselon_id,pns');
    $this->db->where("path_id['".$level."']='".$dept_id."'");
        return $this->db->get();
  }

   public function GetUserAllAktifDept($dept_id='')
  {
    $this->db->select('a.id, a.nip, a.key, a.nama, a.dept_alias, att_status, a.gelar_dpn, a.gelar_blk')
        ->from('v_users_all a')
        ->where('key > 0')
        ->where('att_status',1)
        ->order_by('no_urut');
    $this->db->where('dept_id', $dept_id);
        return $this->db->get();
  }

  public function GetUserAdmin($dept_id='')
  {
  	$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
	   $this->db->select('a.id, a.nip, a.key, a.nama, a.dept_alias, b.level, status, status_pegawai, att_status, count_finger,b.username, a.gelar_dpn, a.gelar_blk')
			->from('v_users_all a')
			->where('key > 0')
			->where('b.level in(1,2)')
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

   public function GetUserByEselonQry($eselon='', $dept_id='')
  {
      if ($dept_id) {
         $this->db->where('a.dept_id', $dept_id);
      }
      $this->db->where_in('h.eselon', $eselon);
      $this->db->like('lower(a.nama)', strtolower($this->input->get('qry')));
      $this->db->or_like('a.nip', $this->input->get('qry'));
      $this->db->limit(3);
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

  public function GetUserAllPejabat($dept_id='', $pejabat_id)
  {
    $level    = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
    $this->db->select('a.id, c.nama, b.dept_name, b.dept_alias, b.path_info, c.nip, d.jabatan, c.gelar_dpn, c.gelar_blk')
          ->from('pejabat_instansi a')
          ->join('v_instansi_all b','b.id=a.dept_id')
          ->join('v_users_all c','a.user_id=c.id')
          ->join('sp_pegawai d','a.user_id=d.user_id')
          ->where('pejabat_id',$pejabat_id)
          ->order_by('b.path_info')
          ->where("b.path_id['".$level."']='".$dept_id."'");
    return $this->db->get();

  }

  public function GetUserPejabat($id, $pejabat_id)
  {
    $this->db->select('a.id, c.nama, b.dept_name, b.dept_alias, b.path_info, c.nip, d.jabatan, c.gelar_dpn, c.gelar_blk, a.dept_id, a.user_id')
          ->from('pejabat_instansi a')
          ->join('v_instansi_all b','b.id=a.dept_id')
          ->join('v_users_all c','a.user_id=c.id')
          ->join('sp_pegawai d','a.user_id=d.user_id')
          ->where('pejabat_id',$pejabat_id)
          ->order_by('b.path_info')
          ->where('a.id', $id);
    return $this->db->get();
  }

   		

  public function GetPejabatInstansi($id)
  {
  	 $this->db->select('a.id, c.nama, b.dept_name, b.dept_alias, b.path_info, c.nip, d.jabatan, c.gelar_dpn, c.gelar_blk')
        	->from('pejabat_instansi a')
        	->join('v_instansi_all b','b.id=a.dept_id')
        	->join('v_users_all c','a.user_id=c.id')
        	->join('sp_pegawai d','a.user_id=d.user_id')
        	->where('pejabat_id',2)
        	->order_by('d.golongan_id, b.path_info');
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


  // integrasi simpeg
  public function get_dept_simpeg($instansi){
    $this->db->select('simpeg_dept_id as id')
        	->from('v_instansi_all')
        	->where('id',$instansi);
     return $this->db->get();
  }

  public function GetSyncPegawai($id){
    $this->db->select('a.id, b.nip,b.nama_pegawai,d.nama_unor,d.id AS unor_id,e.dept_name AS nama_unor_sikap,e.id AS sikap_dept_id,ee.id AS sikap_dept_id_pindah,ee.dept_name AS sikap_dept_pindah,f.type_kamus AS status_pegawai,g.username AS username_simpeg,g.password AS password_simpeg,h.kamus_data AS agama,h.id AS agama_id,i.nama_jabatan,j.id AS pejabat_id,j.nama_pejabat AS status_jabatan_simpeg,b.gelar_depan AS glr_dpn_simpeg,b.gelar_blkng AS glr_blkng_simpeg,b.jenis_kelamin,b.tgl_lahir,l.id AS golongan_id,l.nama_golongan,l.ruang,l.pangkat,k.status,m.id AS eselon_id_simpeg,m.nama_eselon,g.status AS status_akun_simpeg, n.id AS agama_id_sikap, n.agama AS agama_sikap, o.id AS status_pegawai_sikap_id, o.nama AS status_pegawai_sikap,p.id as golongan_id_sikap, q.id as eselon_id_sikap, r.id as kelas_id_jabatan, r.id as kelas_jabatan_id,s.level as level_sikap');
    $this->db->join('simpeg_dev.pegawai b','b.nip= a.nip');
    $this->db->join('simpeg_dev.detail_jabatan c','c.id = b.detail_jabatan_id');
    $this->db->join('simpeg_dev.unor d','d.id = c.unor_id');
    $this->db->join('mf_departments e','e.id = a.dept_id');
    $this->db->join('simpeg_dev.kamus_data f','f.id = b.status_pegawai');
    $this->db->join('simpeg_dev.users g','g.pegawai_id = b.id');
    $this->db->join('simpeg_dev.kamus_data h','h.id = b.agama','LEFT');
    $this->db->join('simpeg_dev.jabatan i','i.id = c.jabatan_id');
    $this->db->join('simpeg_dev._pejabat_level j','j.id = i.pejabat_level_id');
    $this->db->join('simpeg_dev.riwayat_golongan k','k.pegawai_id = b.id');
    $this->db->join('simpeg_dev.golongan l','l.id = k.golongan_id');
    $this->db->join('simpeg_dev._eselon m','m.id = i.eselon_id');
    $this->db->join('mf_departments ee','ee.simpeg_dept_id = d.id');
    $this->db->join('_agama n','n.simpeg_agama_id = h.id','LEFT');
    $this->db->join('_statpeg o','o.simpeg_statpeg_id = b.status_pegawai','LEFT');
    $this->db->join('_golongan p','p.golongan_id_simpeg = l.id');
    $this->db->join('_eselon q','q.simpeg_id_eselon = m.id');
    $this->db->join('simpeg_dev.kelas_jabatan r','r.id = i.kelas_jabatan_id','LEFT');
    $this->db->join('users_login s','s.user_id = a.id');
    $this->db->where('k.status','1');
    $this->db->where('ee.id',$id);
    $this->db->order_by('a.id');
    return $this->db->get('mf_users a');
  }

  public function getPegawaiAsnNewSimpeg($id){
    $this->db->select('*');
    if($id != '1'){
      $this->db->where('sikap_dept_id',$id);
    }
    return $this->db->get('v_users_new_asn_simpeg');
  }

  public function getKelasJabatan()
  {
    $this->db->select('*');
    return $this->db->get('v_kelas_jabatan');
  }

  public function getSimpegDeptUser($dept_id)
  {
    $this->db->select('d.id, d.nama_pegawai');
    $this->db->from('mf_departments a');
    $this->db->join('simpeg_dev.unor b','a.simpeg_dept_id = b.id');
    $this->db->join('simpeg_dev.detail_jabatan c','b.id = c.unor_id');
    $this->db->join('simpeg_dev.pegawai d','c.id = d.detail_jabatan_id');
    $this->db->join('simpeg_dev.kamus_data e','d.status_pegawai = e.id');
    $this->db->join('mf_users f','d.id = f.simpeg_pegawai_id','left');
    $this->db->where('d.type','kabupaten');
    $this->db->where('e.type_kamus','status_pegawai_non_asn');
    $this->db->where('a.id',$dept_id);
    return $this->db->get()->result();
  }

   public function GetSyncPegawaiNon($id){
    $this->db->select('a.id, b.nip,b.nama_pegawai,d.nama_unor,d.id AS unor_id,e.dept_name AS nama_unor_sikap,e.id AS sikap_dept_id,ee.id AS sikap_dept_id_pindah,ee.dept_name AS sikap_dept_pindah,f.type_kamus AS status_pegawai,g.username AS username_simpeg,g.password AS password_simpeg,h.kamus_data AS agama,h.id AS agama_id,i.nama_jabatan,j.id AS pejabat_id,j.nama_pejabat AS status_jabatan_simpeg,b.gelar_depan AS glr_dpn_simpeg,b.gelar_blkng AS glr_blkng_simpeg,b.jenis_kelamin,b.tgl_lahir,l.id AS golongan_id,l.nama_golongan,l.ruang,l.pangkat,k.status,m.id AS eselon_id_simpeg,m.nama_eselon,g.status AS status_akun_simpeg, n.id AS agama_id_sikap, n.agama AS agama_sikap, o.id AS status_pegawai_sikap_id, o.nama AS status_pegawai_sikap,p.id as golongan_id_sikap, q.id as eselon_id_sikap, r.id as kelas_id_jabatan, r.id as kelas_jabatan_id,s.level as level_sikap');
    $this->db->join('simpeg_dev.pegawai b','b.id= a.simpeg_pegawai_id');
    $this->db->join('simpeg_dev.detail_jabatan c','c.id = b.detail_jabatan_id');
    $this->db->join('simpeg_dev.unor d','d.id = c.unor_id');
    $this->db->join('mf_departments e','e.id = a.dept_id');
    $this->db->join('simpeg_dev.kamus_data f','f.id = b.status_pegawai');
    $this->db->join('simpeg_dev.users g','g.pegawai_id = b.id');
    $this->db->join('simpeg_dev.kamus_data h','h.id = b.agama','LEFT');
    $this->db->join('simpeg_dev.jabatan i','i.id = c.jabatan_id');
    $this->db->join('simpeg_dev._pejabat_level j','j.id = i.pejabat_level_id');
    $this->db->join('simpeg_dev.riwayat_golongan k','k.pegawai_id = b.id');
    $this->db->join('simpeg_dev.golongan l','l.id = k.golongan_id');
    $this->db->join('simpeg_dev._eselon m','m.id = i.eselon_id');
    $this->db->join('mf_departments ee','ee.simpeg_dept_id = d.id');
    $this->db->join('_agama n','n.simpeg_agama_id = h.id','LEFT');
    $this->db->join('_statpeg o','o.simpeg_statpeg_id = b.status_pegawai','LEFT');
    $this->db->join('_golongan p','p.golongan_id_simpeg = l.id');
    $this->db->join('_eselon q','q.simpeg_id_eselon = m.id');
    $this->db->join('simpeg_dev.kelas_jabatan r','r.id = i.kelas_jabatan_id','LEFT');
    $this->db->join('users_login s','s.user_id = a.id');
    $this->db->where('k.status','1');
    $this->db->where('ee.id',$id);
    $this->db->order_by('a.id');
    return $this->db->get('mf_users a');
  }
  // end integrasi simpeg
  
  // semntara export data
  public function GetUserAllAktifExport($dept_id='')
  {
    $level    = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
    $this->db->select('a.id, a.nip, a.key, a.nama, a.dept_alias, b.level, status, status_pegawai, att_status, count_finger, a.gelar_dpn, a.gelar_blk,a.jabatan,c.simpeg_agama_id as agama,a.pns')
        ->from('v_users_all a')
        ->where('key > 0')
        ->where('att_status',1)
        ->where('a.pns',2)
        ->join('users_login b','a.id=b.user_id','left')
        ->join('_agama c','a.agama_id = c.id','left')
        ->order_by('path_info, eselon_id,pns');
    $this->db->where("path_id['".$level."']='".$dept_id."'");
        return $this->db->get();
  }
  
  public function cek_data($data='',$cek='',$params='')
  {
      $query = $this->db->select('*')->where($params,$data)->from($cek)->count_all_results();
      return $query;
  }
}



/* End of file M_user.php */
/* Location: ./application/models/M_user.php */