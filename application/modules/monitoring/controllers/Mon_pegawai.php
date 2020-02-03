<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Mon_pegawai extends  App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Monitoring Pegawai', 'monitorinbg/mon-pegawai');
		$this->data['title'] = "Monitoring";
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['sub_title']  = "Pegawai";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('mon_pegawai/v_index', $this->data);
	}

}

/* End of file Mon_pegawai.php */
/* Location: ./application/modules/monitoring/controllers/Mon_pegawai.php */