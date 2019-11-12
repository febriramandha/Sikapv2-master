<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user_login extends CI_Model {

	public function _auth_cek()
	  {
	    $this->db->select('a.id, user_id, b.dept_id, username, password, level, b.nama, avatar');
	    $this->db->from('users_login a');
	    $this->db->join('mf_users b', 'a.user_id=b.id','left');
	    $this->db->where('username',$this->input->post('username'));
	    $this->db->where('status=1');
	    $this->db->limit(1);
	        $query = $this->db->get();
	        if ($query->num_rows() == 0) {
	            return FALSE;
	        } else {
				$data = $query->row();
				if (password_verify($this->input->post('password'), $data->password)) {
					// echo 'Password is valid!';
					return $data;
				} else {
					// echo 'Invalid password.';
					return FALSE;
				}
	            
	        }
	  }

	public function _cek_user($cookie)
	{
		$cek = $this->db->select('level, c.dept_id')
						->where(['cookie' => $cookie, 'a.deleted' => 1])	
						->join('users_login b','a.login_id=b.id')
						->join('mf_users c','b.user_id=c.id')		
						->get('cookies a')->row();
		if ($cek) {
				$this->db->update('cookies',['last_login' => date('Y-m-d H:i:s')], ['cookie' => $cookie]);
				$data = array('tpp_level'   => $cek->level,
		 			          'tpp_dept_id' => $cek->dept_id, );
		 		$this->session->set_userdata($data);	
		}else {
			$this->db->update('cookies',['deleted' => 0] , ['cookie' => $cookie ]);
			delete_cookie('tpp_cookie');
		 	$this->session->sess_destroy();
		 	redirect('/','refresh');
		}

	}

	public function save_cookie($key, $uid, $exp)
	{
		$data = array('login_id' 		=> $uid,
					  'cookie' 			=> $key, 
					  'user_agent' 		=> $this->agent->agent_string(),
					  'browser_agent' 	=> $this->agent->browser(),
					  'version_agent' 	=> $this->agent->version(),
					  'platform_agent' 	=> $this->agent->platform(),
					  'ip_address' 		=> $this->input->ip_address(),
					  'time_cookie' 	=> gmdate("Y-m-d H:i:s", time()+60*60*7),
					  'cookie_expires'  => date("Y-m-d H:i:s", time()+$exp),
					  'last_login'  	=> gmdate("Y-m-d H:i:s", time()+60*60*7));
		 	$this->db->insert('cookies',$data);
	}

	public function _daftarkan_session($row, $cookie) {
        // 1. Daftarkan Session
		$data = ([
				'tpp_login_id' 		=> $row->id,
				'tpp_user_id' 		=> $row->user_id,
				'tpp_dept_id' 		=> $row->dept_id,
				'tpp_username' 		=> $row->username,
				'tpp_level'			=> $row->level,
				'tpp_name'			=> $row->nama,
				'tpp_avatar'		=> $row->avatar,
				'tpp_loggedin'		=> TRUE,]);
			$this->session->set_userdata($data);
    }

    // ambil data berdasarkan cookie
    public function get_by_cookie($cookie)
    {
        return $this->db->select('b.id, b.dept_id, b.user_id, b.username, b.display_name, b.level, c.pns, b.avatar')
		        		 ->where(['a.cookie' => $cookie, 'a.deleted' => 1])
		        		 ->join('users_login b','a.login_id=b.id')
		        		 ->join('mf_users c','b.user_id=c.id', 'left')
						 ->get('cookies a');
    }

	public function ghash ($string) {
		$options = [
			'cost' => 12,
		];
	    return password_hash($string, PASSWORD_BCRYPT, $options);
	  }

	public function loggedin ()
	  {
	    return (bool) $this->session->userdata('tpp_loggedin');
	}

	public function AjaxSaveAkun()
	{
		$this->form_validation->set_rules('username', 'username', 'required|min_length[6]|trim');
		if ($this->input->post('password') || $this->input->post('password_confirmation')) {
			$this->form_validation->set_rules('password_confirmation', 'Kata Sandi Baru', 'trim|required|min_length[6]|matches[password]');
			$this->form_validation->set_rules('password', 'Konfirmasi Kata Sandi Baru', 'trim|required');
		}
		$this->form_validation->set_error_delimiters('<div>', '</div>');

		if ($this->form_validation->run() == TRUE) {
				$data = array('username' 	=> $this->input->post('username'),
					 );
				if ($this->input->post('password')) {
					$data['password'] = $this->m_user_login->ghash($this->input->post('password'));
					$data['recovery'] = $this->encryption->encrypt($this->input->post('password'));
				}
				$res = $this->db->update('users_login',$data, $this->session->userdata('tpp_login_id'));

				

				if ($res) {
					 $data_ = array('status' => true,
				    			   'msg' => 'Data berhasil disimpan');
					 if ($this->input->post('username') != $this->session->userdata('tpp_username')) {
			 			$this->session->set_userdata(['tpp_username'   => $this->input->post('username') ]);
					}
				}else{
					 $data_ = array('status' => false,
				    			   'msg' => 'Data gagal disimpan cek data kembali/kata pengguna telah digunakan');
				}
		}else {
			$validasi =  form_error('username').
						 form_error('password').
						 form_error('password_confirmation');
			$data_ = array('status' => false,
				    		'msg' => $validasi,);
		}

		if ($data_) {
			return json_encode($data_);	
		}else {
			return json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']);
		}
	}


}

/* End of file M_user_login.php */
/* Location: ./application/models/M_user_login.php */