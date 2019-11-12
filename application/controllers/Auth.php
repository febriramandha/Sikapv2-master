<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

	public function GetData()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('username', 'nama pengguna', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('password', 'kata sandi', 'required|min_length[3]|max_length[50]');
		$this->form_validation->set_error_delimiters('<div>', '</div>');	
		
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
			    			   'alert' => 'Berhasil Masuk', );
				   		
			}else {
				  $data = array('status' => false,
				    			 'alert' => '<strong>Peringatan!</strong> username atau password salah', );
			}
		}else {
			$validasi = form_error('username').
						form_error('password');
			$data = array('status' => false,
				    	   'alert' => $validasi,);
		}

		if ($data) {
			$this->output->set_output(json_encode($data));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']));
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

}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */