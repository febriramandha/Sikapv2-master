<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Dashboard', 'dashboard');
		$this->data['title'] = "Dashboard";
		$this->load->model('m_article');
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/plugin/Highcharts-7.2.0/code/highcharts.js');
	}

	public function index()
	{
		$this->data['breadcrumb'] 		= $this->breadcrumbs->show();
		$this->data['pos']		  		= $this->m_article->GetAticleAll();
		$this->data['user_all']	  	  	= $this->db->select('count(*)')->get('mf_users')->row();
		$this->data['instansi_all']	  	= $this->db->select('count(*)')->get('mf_departments')->row();
		$this->data['user_aktif_all']	= $this->db->select('count(*)')->where('status',1)->get('users_login')->row();
		$this->data['user_admin_all']	= $this->db->select('count(*)')->where('level',2)->or_where('level', 1)->get('users_login')->row();
		$this->data['user_aktif_all']	= $this->db->select('count(*)')->where('status',1)->get('users_login')->row();
		$this->data['opd_all']			= $this->db->select('count(*)')->where('parent_id',1)->get('mf_departments')->row();
		$this->data['puskesmas_all']	= $this->db->select('count(*)')->where('parent_id',12)->where('position_order not in(1)')->get('mf_departments')->row();
		$this->data['sekolah_all']		= $this->db->select('count(*)')->where('parent_id',43)->where('position_order not in(1)')->get('mf_departments')->row();

		//user
		$this->data['user_opd_all']		= $this->db->select('count(*)')->where('parent_id',1)->get('v_users_all')->row();


		$this->load->view('app/dashboard/v_dashboard', $this->data);
	}


}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */