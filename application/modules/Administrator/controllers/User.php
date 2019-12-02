<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Pengguna', 'administrator/user');
		$this->data['title'] = "Administrator";
		$this->load->model(['m_instansi','m_user_login','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['sub_title'] = "Pengguna";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('user/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		if ($instansi) {
			$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $instansi])->row()->level;
		}
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.key, a.nama, a.dept_alias, b.level, status, status_pegawai, att_status, count_finger')
        	->from('v_users_all a')
        	->where('key > 0')
        	->join('users_login b','a.id=b.user_id','left')
        	->order_by('path_info, eselon_id')
        	->add_column('nama_nip','$2<hr class="m-0">($1)','nip,nama')
        	->add_column('status_att','$1','status_user(att_status)')
        	->add_column('status_user','$1','status_user(status)')
        	->add_column('pegawai_status','$1','status_pegawai(status_pegawai)')
        	->add_column('action','<a href="'.base_url('administrator/user/edit/').'$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>
					              <span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus akun" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'encrypt_url(id,"user_id")');
        	 if ($instansi) {
		        $this->datatables->where("path_id['".$level."']='".$instansi."'");
		     }
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
		$this->load->view('user/v_add', $this->data);
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Data Pengguna";
		$this->breadcrumbs->push('Edit Pengguna', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['user']			= $this->m_user->GetUser(decrypt_url($id, 'user_id'))->row();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('user/v_edit', $this->data);
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
							  ->set_rules('nama', 'nama lengkap', 'required');
		if ($this->input->post('ketegori') == 1) {
			$this->form_validation->set_rules('nip', 'NIP', 'required|min_length[18]|max_length[18]|numeric|'.$nip_cek.'');
		}
		$this->form_validation->set_rules('instansi', 'instansi', 'required')
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
						if ($this->input->post('tpp')) {
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
					if ($this->input->post('tpp')) {
						$tpp = 1;
					}

					$data = array('nama' 		=> $this->input->post('nama'),
								  'dept_id' 	=> decrypt_url($this->input->post('instansi'),'instansi'),
								  'pns' 		=> $this->input->post('ketegori'),
								  'att_status' 	=> $att_status,
								  'tpp'			=> $tpp,
								  'updated_at' 	=> date('Y-m-d H:i:s'),
								  'updated_by'  => $this->session->userdata('tpp_user_id'), );
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

					if ($return) {
						$result = array('status'   => true,
			    	     		  		'message' => 'Data berhasil diubah',);
					}else{
						$result = array('status'   => false,
			    	     		  		'message' => 'Data gagal diubah',);
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

	public function AjaxDel()
	{
		$this->load->model('m_server_att');
		$this->output->unset_template();
		$del ='';
		$cek = $this->m_server_att->cekTemplate(['userid' =>  decrypt_url($this->input->get('id'),"user_id")]);
		if (!$cek) {
			$del = $this->db->delete('mf_users',['id' => decrypt_url($this->input->get('id'),"user_id")]);
			if ($del) {
				   $this->m_server_att->DelUserinfo(['userid' =>  decrypt_url($this->input->get('id'),"user_id")]);
				   $this->db->delete('users_login',['user_id' => decrypt_url($this->input->get('id'),"user_id")]);
			}
		}
		if ($del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}

	public function cetak($dept_id)
	{
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 300); //300 seconds = 5 minutes
		$this->output->unset_template();
		$this->load->library('Tpdf');
		$this->data['instansi'] = $this->m_instansi->GetInstansi(decrypt_url($dept_id, 'instansi'))->row();
		$this->data['user']		= $this->m_user->GetUserAll(decrypt_url($dept_id, 'instansi'))->result();
		$this->load->view('user/v_cetak', $this->data);
	}


}

/* End of file User.php */
/* Location: ./application/modules/Administrator/controllers/User.php */