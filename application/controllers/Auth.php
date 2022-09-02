<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Auth extends Frontend_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->load->model('m_user_login');
	}

	private function _init()
	{
		$this->output->set_template('auth');
	}

	public function index()
	{
		 // ambil cookie
        $cookie = get_cookie('tpp_cookie');

		if ($this->m_user_login->loggedin() != FALSE && $this->session->userdata('tpp_level') != false) {
			 redirect('dashboard');
		}else if($cookie <> '') {
            // cek cookie
            $row = $this->m_user_login->get_by_cookie($cookie)->row();
            if ($row) {
                $this->m_user_login->_daftarkan_session($row, $cookie);
                redirect('dashboard');
            }
         }
		$this->load->view('auth/v_auth', $this->data);
	}
// data simpeg
	// public function GetData()
	// {
	// 	$this->output->unset_template();
	// 	$this->form_validation->set_rules('username', 'nama pengguna', 'trim|required|max_length[50]');
	// 	$this->form_validation->set_rules('password', 'kata sandi', 'required|min_length[3]|max_length[50]');
	// 	$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		
	// 	if ($this->form_validation->run() == TRUE) {

	// 		$auth = $this->m_user_login->_auth_cek();
			
	// 		if($auth == true) {
	// 			if($auth->intro == 1 || $auth->level == 1 || $auth->pns == 2 ){
	// 				if ($this->input->post('remember')) {
	// 	                $key = random_string('alnum', 64);
	// 	                set_cookie('tpp_cookie', $key, 3600*24*30); // set expired 30 hari kedepan
		                
	// 	                // simpan key di database
	// 	                $this->m_user_login->save_cookie($key, $auth->id, 3600*24*30);
	// 	                $this->m_user_login->_daftarkan_session($auth, "");
	// 	            }else {
	// 	            	$key = random_string('alnum', 64);
	// 	                set_cookie('tpp_cookie', $key, 7300); // set expired 2 jam kedepan
		                
	// 	                // simpan key di database
	// 	                $this->m_user_login->save_cookie($key, $auth->id, 7200);
	// 	                $this->m_user_login->_daftarkan_session($auth, "");
		               
	// 	            }

	// 		   		$data = array('status' => true,
	// 		    			   'message' => '<i class="fa fa-check text-success"></i> Berhasil Masuk', );

	// 			}else {
	// 				$encrypt = $auth->pegawai_id."#".date("Y-m-d H:i:s");
	// 				// $url = "https://simpeg.agamkab.go.id/auth/login_service?key=encrypt_url_public(user_id#datetime,'login')
	// 				$data = array('status' => false,
	// 			    			 'message' => 'akun simpeg anda belum diaktivasi silahkan klik <i><a href="https://simpeg.agamkab.go.id/auth/login_service?key='.encrypt_url_public($encrypt,'login').'" target="_blank" class="btn btn-sm btn-info">disini</a></i> untuk aktivasi akun simpeg anda!' );					   
	// 			}		
	// 		}else {
	// 			  $data = array('status' => false,
	// 			    			 'message' => 'nama pengguna atau kata sandi salah' );
	// 		}
	// 	}else {
	// 		$data = array('status' => false,
	// 			    	   'message' => validation_errors(),);
	// 	}

	// 	if ($data) {
	// 		$this->output->set_output(json_encode($data));	
	// 	}else {
	// 		$this->output->set_output(json_encode(['message'=>FALSE, 'msg'=> 'Gagal mengambil data.']));
	// 	}
	// }

	// data sikap
	public function GetData()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('username', 'nama pengguna', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('password', 'kata sandi', 'required|min_length[3]|max_length[50]');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		
		if ($this->form_validation->run() == TRUE) {

			$auth = $this->m_user_login->_auth_cek();

			if($auth == true) {

				if ($this->input->post('remember')) {
		                $key = random_string('alnum', 64);
		                set_cookie('tpp_cookie', $key, 3600*24*30); // set expired 30 hari kedepan
		                
		                // simpan key di database
		                $this->m_user_login->save_cookie($key, $auth->id, 3600*24*30);
		                $this->m_user_login->_daftarkan_session($auth, "");
		            }else {
		            	$key = random_string('alnum', 64);
		                set_cookie('tpp_cookie', $key, 7300); // set expired 2 jam kedepan
		                
		                // simpan key di database
		                $this->m_user_login->save_cookie($key, $auth->id, 7200);
		                $this->m_user_login->_daftarkan_session($auth, "");
		               
		            }

			    $data = array('status' => true,
			    			   'message' => '<i class="fa fa-check text-success"></i> Berhasil Masuk', );
				   		
			}else {
				  $data = array('status' => false,
				    			 'message' => 'nama pengguna atau kata sandi salah' );
			}
		}else {
			$data = array('status' => false,
				    	   'message' => validation_errors(),);
		}

		if ($data) {
			$this->output->set_output(json_encode($data));	
		}else {
			$this->output->set_output(json_encode(['message'=>FALSE, 'msg'=> 'Gagal mengambil data.']));
		}
	}
	
	 public function logout()
    {
        // delete cookie dan session
        $cookie = get_cookie('tpp_cookie');
        $this->db->update('cookies',['deleted' => 0] , ['cookie' => $cookie ]);
        delete_cookie('tpp_cookie');
		//$this->session->unset_userdata(['tpp_username','tpp_level','tpp_loggedin']);
		$this->session->sess_destroy();
        $this->session->set_flashdata('msg', 'Terimakasih Anda Berhasil Keluar');
        redirect('auth');
    }

    public function reset_password()
    {
    	$this->load->view('auth/v_reset_password', $this->data);
    }

    public function email_reset_password_validation(){
    	$this->output->unset_template();
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
		if($this->form_validation->run()){

			$email = $this->input->post('email');
			$reset_key =  random_string('alnum', 50);

			if($this->m_user_login->update_reset_key($email,$reset_key))
			{
				//memanggil library email dan set konfigurasi untuk pengiriman email
				$config = Array(
				    'mailtype'  => 'html',
		            'charset'   => 'utf-8',
		            'protocol'  => 'smtp',
		            'smtp_host' => 'smtp.googlemail.com',
		            'smtp_user' => 'rianreski13@gmail.com',  // Email gmail
		            'smtp_pass'   => 'jjzahszdgqilivqs',  // Password gmail
		            'smtp_crypto' => 'ssl',
		            'smtp_port'   => 465,
		            'crlf'    => "\r\n",
		            'newline' => "\r\n"
				);

					
				$this->load->library('email', $config);
				$this->email->set_newline("\r\n");
				//konfigurasi pengiriman
				$this->email->from($config['smtp_user']);
				$this->email->to($this->input->post('email'));
				$this->email->subject("Reset your password");

				$message = "<p>Anda melakukan permintaan reset password</p>";
				$message .= "<a href='".site_url('auth/reset_password/'.$reset_key)."'>klik reset password</a>";
				$this->email->message($message);
				
				if($this->email->send())
				{
					$data = array('status' => true,
			    			   'alert' => "silahkan cek email <b>".$this->input->post('email').'</b> untuk melakukan reset password', );
				}else{
					$data = array('status' => false,
			    			   'alert' => 'Berhasil melakukan registrasi, gagal mengirim verifikasi email', );
				}

			}else {
				 $data = array('status' => false,
				    			 'alert' => 'Email yang anda masukan belum terdaftar' );
			}
		} else{
			$validasi = form_error('email');
			$data = array('status' => false,
				    	   'alert' => $validasi,);
		}

		if ($data) {
			$this->output->set_output(json_encode($data));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']));
		}
	}

}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */