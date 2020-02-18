<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Profile extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Profile', 'app/profile');
		$this->data['title'] = "Profile";
		$this->load->model(['m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('app/profile/v_index', $this->data);
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$mod = $this->input->get('id');

		if ($mod == "biodata") {
			$this->data['judul'] ='';
			$this->data['user']  = $this->m_user->GetDetailBiodata($this->session->userdata('tpp_user_id'))->row();
			$this->load->view('app/profile/v_biodata', $this->data);
		}elseif ($mod == "akun") {
			$this->load->view('app/profile/v_akun', $this->data);
		}elseif ($mod == "perangkat") {
			$this->data['judul'] ='';
			$this->load->view('app/profile/v_perangkat', $this->data);
		}elseif ($this->input->post('mod') == "upload_photo") {
			echo $this->m_user->upload_foto();
		}elseif ($mod == "verifikator") {
			$cek = $this->db->select('user_id_ver')->get_where('verifikator',['user_id' => $this->session->userdata('tpp_user_id')])->row();
			$user = '';
			if ($cek) {
				$user = $this->m_user->GetDetailBiodata($cek->user_id_ver)->row();
			}
			$this->data['judul'] ='';
			$this->data['user']  = $user;
			$this->load->view('app/profile/v_verifikator', $this->data);
		}elseif ($this->input->post('mod') == "jsonPlatform") {
			$this->load->model('m_cookie');
			echo $this->m_cookie->GetjsonPlatform($this->session->userdata('tpp_login_id'));
		}elseif ($mod == "PlatformDel") {
			$del = $this->db->update('cookies',['deleted' => 0],['cookie' => $this->input->get('cookie')]);

			if ($del) {
				$this->output->set_output(json_encode(['status'=>TRUE, 'msg'=> 'Data berhasil dihapus.']));
			} else{
				$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal dihapus.']));	
			}
		}elseif ($this->input->post('mod') == "AjaxSaveAkun") {
			$this->load->model('m_user_login');
			echo $this->m_user_login->AjaxSaveAkun();
		}
	}

}

/* End of file Profile.php */
/* Location: ./application/controllers/app/Profile.php */