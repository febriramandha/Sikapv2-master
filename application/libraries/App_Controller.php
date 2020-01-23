<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class App_Controller extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_user_login');
		$this->data['title'] 	 = "Home";
		$this->data['sub_title'] = "";
		$this->mod 				 = '';
		$this->return 			 = '';
		$this->result			 = '';
		$this->msg 				 = '';
		$this->del 				 = '';
		$this->output->set_common_meta(
			$this->data['site_name'], // Title
			$this->data['description'], // Description
			$this->data['keywords'] // Keywords
		);

		$cookie = get_cookie('tpp_cookie');
		if($this->m_user_login->loggedin() == FALSE) {
			if($cookie) {
				$row = $this->m_user_login->get_by_cookie($cookie)->row();

				if ($row) {
					$this->m_user_login->_daftarkan_session($row, $cookie);
				}
			}
		}

		if ($cookie) {
			$this->m_user_login->_cek_user($cookie);
		}else {
			if ($this->session->userdata('tpp_loggedin')) {
				$this->session->sess_destroy();
				redirect('/','refresh');
			}else {
				show_404();
			}
			
		}

		$this->load->section('sidebar', 'app/includes/sidebar');
		$this->load->section('nav', 'app/includes/navbar');
	}

}

/* End of file App_Controller.php */
/* Location: ./application/libraries/App_Controller.php */