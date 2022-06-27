<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Synchronize_data extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Synchronize Data', 'administrator/synchronize_data');
		$this->data['title'] = "Administrator";
		$this->load->model(['m_instansi','m_user_login','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
		$this->load->css('public/themes/plugin/toastr/toastr.css');
		$this->load->js('public/themes/plugin/toastr/toastr.min.js');
		$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
		$this->load->js('public/themes/material/global_assets/js/plugins/forms/wizards/steps.min.js');
	}

	public function index()
	{
		$this->data['sub_title'] = "Synchronize Data";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('synchronize_data/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $instansi])->row()->level;
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.key, a.nama, a.dept_alias, b.level, status, status_pegawai, att_status, count_finger,gelar_dpn,gelar_blk, level')
        	->from('v_users_all a')
        	->where('key > 0')
        	->join('users_login b','a.id=b.user_id','left')
        	->order_by('no_urut')
        	->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)')
        	->add_column('status_att','$1','status_user(att_status)')
        	->add_column('status_user','$1','status_user(status)')
        	->add_column('pegawai_status','$1','status_pegawai(status_pegawai)')
        	->add_column('level','$1','kewenangan_tabelicon(level)')
        	->add_column('action','<a href="'.base_url('administrator/user/edit/').'$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>
					              <span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus akun" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'encrypt_url(id,"user_id")');
		        $this->datatables->where("path_id['".$level."']='".$instansi."'");
        	 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(nama)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(nip)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
	       
        return $this->output->set_output($this->datatables->generate());
	}
	
	public function JsonOpd()
	{
		$this->output->unset_template();
		$this->load->library('datatables');
        $this->datatables->select('id,a.*')
        	->from(''.$this->data['db_connect'].'.unor a')
        	->where("id NOT IN (SELECT b.simpeg_dept_id FROM mf_departments b WHERE b.simpeg_dept_id IS NOT NULL) AND type_unor != 'NAGARI' AND type_unor != 'NAGARI_PERSIAPAN' AND type_unor != 'JORONG' AND status = '1'")
        	->add_column('cek','$1','cekbox_add_instansi(id)')
			->add_column('action','<div style="white-space: nowrap;">$1</div>', 'aksi_add_simpeg(id)');
        	 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(nama)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(nip)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
	       
        return $this->output->set_output($this->datatables->generate());
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Data Pengguna";
		$this->breadcrumbs->push('Tambah Pengguna', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->data['agama']		= $this->db->order_by('id')->get('_agama')->result();
		$this->data['eselon']		= $this->db->order_by('id')->get('_eselon')->result();
		$this->data['golongan']		= $this->db->order_by('id')->get('_golongan')->result();
		$this->data['status_peg']	= $this->db->order_by('id')->get('_statpeg')->result();
		$this->load->view('user/v_add', $this->data);
	}

	public function synchronize_opd()
	{
		$this->data['sub_title'] 	= "Sinkron Instansi";
		$this->breadcrumbs->push('Sinkron Instansi', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();

		$this->load->view('synchronize_data/v_index_sinkron', $this->data);
	}


	public function AjaxSave()
	{
		$this->load->model('m_server_att');
		$this->output->unset_template();
		$mod = $this->input->post('mod');
		// unik field 
		$username_cek = 'is_unique[users_login.username]';
		$nip_cek	  = 'is_unique[mf_users.nip]';
		if ($mod == "edit") {
			$username_cek = 'edit_unique[users_login.username.'.decrypt_url($this->input->post('login_id'),'login_id').']';
			$nip_cek = 'edit_unique[mf_users.nip.'.decrypt_url($this->input->post('user_id'),'user_id').']';
		}

		$this->form_validation->set_rules('username', 'nama pengguna', 'required|trim|min_length[6]|'.$username_cek.'');

		if ($this->input->post('password') || $this->input->post('password_confirmation') || $mod == "add") {
			$this->form_validation->set_rules('password_confirmation', 'kata sandi', 'trim|required|min_length[6]|matches[password]');
			$this->form_validation->set_rules('password', 'ulangi kata sandi', 'trim|required');
		}
		$this->form_validation->set_rules('ketegori', 'kategori', 'required')
							  ->set_rules('nama', 'nama lengkap', 'required')
							  ->set_rules('jabatan', 'jabatan', 'required')
							  ->set_rules('agama', 'agama', 'required')
							  ->set_rules('gender', 'jenis kelamin', 'required')
							  ->set_rules('status_pegawai', 'status pegawai', 'required');
		if ($this->input->post('ketegori') == 1) {
			$this->form_validation->set_rules('nip', 'NIP', 'required|min_length[18]|max_length[18]|numeric|'.$nip_cek.'')
								  ->set_rules('golongan', 'Golongan', 'required')
								  ->set_rules('eselon', 'Eselon', 'required');
		}
		$this->form_validation->set_rules('instansi', 'Unit Kerja', 'required')
							  ->set_rules('level', 'jenis pengguna', 'required');		
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
				if ($mod == "add") {
						// key
						$key = $this->db->select('max(key)')->get('mf_users')->row()->max+1;

						// nip user
						if ($this->input->post('ketegori') == 1) {
							$nip =  $this->input->post('nip'); 
						}else {
							$nip =  $key; 
						}
						// att status
						$defaultdeptid = '-1';
						$att_status = 0;
						if ($this->input->post('status_att')) {
							$att_status = 1;
							// aktif userinfo
							$defaultdeptid = decrypt_url($this->input->post('instansi'),'instansi');
						}
						// tpp
						$tpp = 0;
						if ($this->input->post('tpp') && $this->input->post('ketegori')==1) {
							$tpp = 1;
						}

						$data = array('key' 		=> $key,
									  'nip' 		=> $nip,
									  'nama' 		=> $this->input->post('nama'),
									  'dept_id' 	=> decrypt_url($this->input->post('instansi'),'instansi'),
									  'pns' 		=> $this->input->post('ketegori'),
									  'att_status' 	=> $att_status,
									  'tpp'			=> $tpp,
									  'created_at' 	=> date('Y-m-d H:i:s'),
									  'created_by'  => $this->session->userdata('tpp_user_id'), );

						if ($this->input->post('absen_online_app')) {
							$data['absen_online_app'] = 1;
						}
						$this->db->insert('mf_users', $data);
						$user_id = $this->db->insert_id();

						// insert to server 2
						$data_att = array('userid' 	 		=> $user_id,
										  'badgenumber' 	=> $key,			
										  'ssn' 	 		=> $nip,
								  		  'name' 	 		=> $this->input->post('nama'),
								  		  'defaultdeptid' 	=> $defaultdeptid);
						$this->m_server_att->NewUserinfo($data_att);
						// end
						$status = 0;
						if ($this->input->post('status_akun')) {
							$status = 1;
						}
						$data = array('user_id' 	=> $user_id,
									  'username' 	=> $this->input->post('username'),
									  'password' 	=> $this->m_user_login->ghash($this->input->post('password')),
									  'recovery' 	=> $this->encryption->encrypt($this->input->post('password')),  
									  'level' 		=> decrypt_url($this->input->post('level'),'level'),
									  'status' 		=> $status,
									  'created_at' 	=> date('Y-m-d H:i:s'),
									  'created_by'  => $this->session->userdata('tpp_user_id'), );
						$return = $this->db->insert('users_login', $data);

						// biodata pegawai
						$data_biodata = array('agama_id'   => $this->input->post('agama'),
											  'jabatan'    => $this->input->post('jabatan'),
											  'gelar_dpn'  => $this->input->post('gelar_dpn'),
											  'gelar_blk'  => $this->input->post('gelar_blk'),
											  'gender'     => $this->input->post('gender'),
											  'statpeg_id' => $this->input->post('status_pegawai'),
											  'user_id'    => $user_id,
										);
						if ($this->input->post('ketegori') == 1) {
							$data_biodata['golongan_id']= $this->input->post('golongan');
							$data_biodata['eselon_id']  = $this->input->post('eselon');
						}
						$return = $this->db->insert('sp_pegawai',$data_biodata);
						
						if ($return) {
							$result = array('status'   => true,
				    	     		  		'message' => 'Data berhasil ditambahkan',);
						}else{
							$result = array('status'   => false,
				    	     		  		'message' => 'Data gagal ditambahkan',);
						}	
				}elseif ($mod == "edit") {
					// att status
					$defaultdeptid = '-1';
					$att_status = 0;
					if ($this->input->post('status_att')) {
						$att_status = 1;
						// aktif userinfo
						$defaultdeptid = decrypt_url($this->input->post('instansi'),'instansi');
					}
					// tpp
					$tpp = 0;
					if ($this->input->post('tpp') && $this->input->post('ketegori')==1) {
						$tpp = 1;
					}

					$data = array('nama' 		=> $this->input->post('nama'),
								  'dept_id' 	=> decrypt_url($this->input->post('instansi'),'instansi'),
								  'pns' 		=> $this->input->post('ketegori'),
								  'att_status' 	=> $att_status,
								  'tpp'			=> $tpp,
								  'updated_at' 	=> date('Y-m-d H:i:s'),
								  'updated_by'  => $this->session->userdata('tpp_user_id'), );

					if ($this->input->post('absen_online_app')) {
						$data['absen_online_app'] = 1;
					}else {
						$data['absen_online_app'] = 0;
					}

					$this->db->update('mf_users', $data, ['id' => decrypt_url($this->input->post('user_id'),'user_id')]);

					// insert to server 2
					$data_att = array('name' 	 		=> $this->input->post('nama'),
							  		  'defaultdeptid' 	=> $defaultdeptid);
					$this->m_server_att->UpdateUserinfo($data_att, ['userid' => decrypt_url($this->input->post('user_id'),'user_id')]);
					// end

					$status = 0;
					if ($this->input->post('status_akun')) {
						$status = 1;
					}
					$data = array(
								  'username' 	=> $this->input->post('username'),
								  'level' 		=> decrypt_url($this->input->post('level'),'level'),
								  'updated_at' 	=> date('Y-m-d H:i:s'),
								  'status'		=> $status,
								  'updated_by'  => $this->session->userdata('tpp_user_id'), );
					if ($this->input->post('password')) {
						$data['password'] = $this->m_user_login->ghash($this->input->post('password'));
						$data['recovery'] = $this->encryption->encrypt($this->input->post('password'));
					}
					$return = $this->db->update('users_login', $data, ['id' => decrypt_url($this->input->post('login_id'),'login_id')]);

					// biodata pegawai
					$biodata_cek = $this->db->get_where('sp_pegawai', ['user_id' => decrypt_url($this->input->post('user_id'),'user_id')])->row();

					$data_biodata = array('agama_id'   => $this->input->post('agama'),
										  'gelar_dpn'  => $this->input->post('gelar_dpn'),
										  'gelar_blk'  => $this->input->post('gelar_blk'),
										  'gender'     => $this->input->post('gender'),
										  'jabatan'    => $this->input->post('jabatan'),
										  'statpeg_id' => $this->input->post('status_pegawai'),
									);
					if ($this->input->post('ketegori') == 1) {
						$data_biodata['golongan_id']= $this->input->post('golongan');
						$data_biodata['eselon_id']  = $this->input->post('eselon');
					}
					if ($biodata_cek) {
						$return = $this->db->update('sp_pegawai',$data_biodata, ['id' => $biodata_cek->id]);
					}else {
						$data_biodata['user_id']  = decrypt_url($this->input->post('user_id'),'user_id');
						$return = $this->db->insert('sp_pegawai',$data_biodata);
					}

					if ($return) {
						$result = array('status'   => true,
			    	     		  		'message' => 'Data berhasil disimpan',);
					}else{
						$result = array('status'   => false,
			    	     		  		'message' => 'Data gagal disimpan',);
					}	
				}

		}else {
			$result = array('status'   => false,
				    	     'message' => validation_errors(),);
		}
		if ($result) {
			$this->output->set_output(json_encode($result));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}

	public function AjaxSaveOpd($id=NULL)
	{		
		$this->load->model('m_server_att');
		$this->output->unset_template();
		$data_insert = array();
		$id_simpeg = decrypt_url($id,'id_unor_simpeg');
		
		if($id == NULL){
			$this->form_validation->set_rules('id_unor[]', 'data ceklis', 'required');
			$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
			if ($this->form_validation->run() == TRUE) {
				$arr_id_simpeg = $this->input->post('id_unor[]');
				foreach($arr_id_simpeg as $row)
				{
					$data_unor_simpeg = $this->db->where('a.id',decrypt_url($row,'id_unor_simpeg'))->get(''.$this->data['db_connect'].'.unor a')->row();
					$data_insert[] = array('dept_name' 	 	=> $data_unor_simpeg->nama_unor,
								'dept_alias' 		=> $data_unor_simpeg->akronim,
								'kecamatan_id' 		=> $data_unor_simpeg->kecamatan_id,
								'position_order' 	=> $data_unor_simpeg->norut_unor,
								'parent_id' 	 	=> $data_unor_simpeg->parent,
								'created_at' 		=> date('Y-m-d H:i:s'),
								'status'			=> $data_unor_simpeg->status,
								'dept_type'			=> strtolower($data_unor_simpeg->type_unor),
								'created_by' 	 	=> $this->session->userdata('tpp_user_id'),
								'alamat'			=> $data_unor_simpeg->detail_lokasi
					);
				} 
				$this->return = $this->db->insert_batch('mf_departments', $data_insert);

			}else {
				var_dump("tes");
				$this->result = array('status' => false,
								'message' => validation_errors(),);
			}

		}else {
	
			$data_unor_simpeg = $this->db->where('a.id',$id_simpeg)->get(''.$this->data['db_connect'].'.unor a')->row();
			$data = array('dept_name' 	 	=> $data_unor_simpeg->nama_unor,
								'dept_alias' 		=> $data_unor_simpeg->akronim,
								'kecamatan_id' 		=> $data_unor_simpeg->kecamatan_id,
								'position_order_simpeg' 	=> $data_unor_simpeg->norut_unor,
								'parent_id_simpeg' 	 	=> $data_unor_simpeg->parent,
								'simpeg_dept_id' 	 	=> $data_unor_simpeg->id,
								'created_at' 		=> date('Y-m-d H:i:s'),
								'status'			=> $data_unor_simpeg->status,
								'dept_type'			=> strtolower($data_unor_simpeg->type_unor),
								'created_by' 	 	=> $this->session->userdata('tpp_user_id'),
								'alamat'			=> $data_unor_simpeg->detail_lokasi
					);

			if($data_unor_simpeg->status == '1'){
				$supdeptid = $data_unor_simpeg->parent;
			}else {
				$supdeptid = -1;
			}

			if(empty($data_unor_simpeg->nama_unor)){
				$name_dept = $data_unor_simpeg->akronim;
			}else{
				$name_dept = $data_unor_simpeg->nama_unor;
			}
			$this->return = $this->db->insert('mf_departments',$data);
			$id_new = $this->db->insert_id();

			if ($this->return) {
				$path = $this->db->select('path_info')->get_where('v_instansi_all_master', ['id' => $id_new])->row();
				$new_path = attConverPathNumber($path->path_info);
				$data_att_dept = array( 'deptid' 	=> $id_new,
					'deptname'  => $new_path.'_'.$name_dept,
					'supdeptid' => $supdeptid,
				);
				// $this->return = $this->m_server_att->Newdepartments($data_att_dept);

			}

		}

		if ($this->return) {
			$this->result = array('status' => true,
				'message' => 'Data berhasil disimpan');
		}else{

			$this->result = array('status' => false,
				'message' => 'Data gagal disimpan');
			// $this->db->delete('mf_departments', ['id' => $id_new]);
		}

		if ($this->result) {
			$this->output->set_output(json_encode($this->result));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}


	public function SyncOpd()
	{
		$this->output->unset_template();
		$data_opd_simpeg = $this->m_instansi->GetSyncOPD()->result();
		$data_update = array();

		if(!empty($data_opd_simpeg) || $data_opd_simpeg){
			foreach($data_opd_simpeg as $row){
					$data_update[] = array(
							'simpeg_dept_id' => $row->dept_id_simpeg,
							'dept_name' => $row->nama_unor,
							'dept_alias' => (empty($row->akronim)) ? $row->nama_unor :  $row->akronim,
							'alamat' => $row->detail_lokasi,
							'status' => $row->status_simpeg,
							'kecamatan_id' => $row->kecamatan_id_simpeg_,
							'dept_type' => $row->type_unor,
							'parent_id_simpeg' => $row->parent_simpeg,
							'position_order_simpeg' => $row->norut_unor
					);
			}
			$this->return = $this->db->update_batch('mf_departments', $data_update, 'simpeg_dept_id');
		}
		if ($this->return) {
				$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Berhasil sinkron data' ]));	
		}else {
				$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal sinkron data.']));
		}
	}
	
	public function SyncPegawai()
	{
		$this->output->unset_template();
		$data_pegawai_simpeg = $this->m_user->GetSyncPegawai()->result();
		$data_mf_users = array();
		$data_users_login = array();
		$data_sp_pegawai = array();
		if(!empty($data_pegawai_simpeg) || $data_pegawai_simpeg){
			foreach($data_pegawai_simpeg as $row){
				$status_pegawai = 0;
				if($row->status_pegawai == 'status_pegawai_asn'){
					$status_pegawai = 1;
				}
				$data_mf_users[] = array(
					'id' => $row->id,
					'nama' => $row->nama_pegawai,
					'dept_id' => $row->sikap_dept_id_pindah,
					'pns' => $status_pegawai,
					'updated_at' 	=> date('Y-m-d H:i:s'),
					'updated_by'  => $this->session->userdata('tpp_user_id'),
				);	
				$level = 3;
				if($row->status_jabatan_simpeg == 'kepala_opd'){
					$level = 5;
				}else if($row->status_jabatan_simpeg == 'sekda'){
					$level = 4;
				}

				$data_users_login[] = array(
					'user_id' => $row->id,
					'username' => $row->username_simpeg,
					'password' => $row->password_simpeg,
					'level' => $level,
					'updated_at' 	=> date('Y-m-d H:i:s'),
					'status' => $row->status_akun_simpeg
				);

				$gender = '1';
				if($row->jenis_kelamin == 'P'){
					$gender = '2';
				}
				$data_sp_pegawai[] = array(
					'user_id' => $row->id,
					'agama_id' => $row->agama_id_sikap,
					'gelar_dpn'  => $row->glr_dpn_simpeg,
					'gelar_blk'  => $row->glr_blkng_simpeg,
					'gender'     => $gender,
					'jabatan'    => $row->nama_jabatan,
					'statpeg_id' => $row->status_pegawai_sikap_id
				);
			}
		}
		$data1 = $this->db->update_batch('mf_users', $data_mf_users, 'id');
		if($data1){
			$data2 = $this->db->update_batch('users_login', $data_users_login, 'user_id');
			if($data2){
				$data3 = $this->db->update_batch('sp_pegawai', $data_sp_pegawai, 'user_id');
				if($data3){
					$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Berhasil sinkron data pegawai.']));
				}else {
					$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal sinkron sp pegawai.']));
				}
			}else {
				$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal sinkron data login user.']));
			}
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal sinkron data user.']));
		}
	}
}

/* End of file Synchronize_data.php */
/* Location: ./application/modules/Administrator/controllers/Synchronize_data.php */