<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2020
*/

class Data_instansi extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Data Instansi', 'master/data-instansi');
		$this->data['title'] = "Master";
		$this->load->model('m_instansi');
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
		$this->load->css('public/themes/plugin/jquery_treetable/css/jquery.treetable.css');
		$this->load->css('public/themes/plugin/jquery_treetable/css/jquery.treetable.theme.default.css');
		$this->load->js('public/themes/plugin/jquery_treetable/jquery.treetable.js');
	}

	public function index()
	{
		$this->data['sub_title'] = "Data Instansi";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->db->select('a.*, b.jum_sub');
		$this->db->join('(select count(*) as jum_sub, parent from _instansi GROUP BY parent) as b','a.id=b.parent','left');
		$this->data['instansi']	  = $this->db->get('v_instansi_tree a')->result();
		$this->load->view('data_instansi/v_index', $this->data);
	}

}

/* End of file Data_instansi.php */
/* Location: ./application/modules/master/controllers/Data_instansi.php */