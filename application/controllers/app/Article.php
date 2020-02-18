<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Article extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Pengumuman', 'article');
		$this->data['title'] = "Pengumuman";
		$this->load->model('m_article');
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['pos']		  = $this->m_article->GetAticleAll();
		$this->load->view('app/article/v_index', $this->data);
	}

}

/* End of file Article.php */
/* Location: ./application/controllers/App/Article.php */