<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Setlkh_user extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Setup LKH Pengguna', 'mnglkh/setlkh-user');
		$this->data['title'] = "Manajemen LKH";
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['sub_title']  = "Setup LKH Pengguna";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('setlkh_user/v_index', $this->data);
	}

}

/* End of file Setlkh_user.php */
/* Location: ./application/modules/Mnglkh/controllers/Setlkh_user.php */