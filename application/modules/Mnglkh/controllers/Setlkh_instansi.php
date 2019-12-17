<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Setlkh_instansi extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Setup LKH Instansi', 'mnglkh/setlkh-instansi');
		$this->data['title'] = "Manajemen LKH";
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['sub_title']  = "Setup LKH Instansi";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('setlkh_instansi/v_index', $this->data);
	}

}

/* End of file Setlkh_instansi.php */
/* Location: ./application/modules/Mnglkh/controllers/Setlkh_instansi.php */