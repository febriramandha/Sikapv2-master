<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Modul extends  App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Instansi', 'master/modul');
		$this->data['title'] = "Master";
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/jquery_treetable/css/jquery.treetable.css');
		$this->load->css('public/themes/plugin/jquery_treetable/css/jquery.treetable.theme.default.css');
		$this->load->js('public/themes/plugin/jquery_treetable/jquery.treetable.js');
		$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Modul";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->db->join('(select count(*) as jum_sub, parent as parent_id from _acl GROUP BY parent) as c','a.id=c.parent_id','left');
		$this->data['modul']	  = $this->db->get('v_tree_modul a')->result();
		$this->load->view('modul/v_index', $this->data);
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Modul";
		$this->breadcrumbs->push('Edit Modul', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['modul'] 		= $this->db->get_where('_acl', ['id' => $id])->row();
		$this->load->view('modul/v_edit', $this->data);
	}

	public function AjaxSave()
	{
		$this->mod = $this->input->post('mod');
		$this->output->unset_template();
		$this->form_validation->set_rules('title', 'title', 'required')
		->set_rules('level', 'title', 'required')
		->set_rules('order', 'no urut', 'required|numeric');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
			$status = 0;
			if ($this->input->post('status')) {
				$status = 1;
			}
			if ($this->mod == "add") {
				$data = array('title' 	 	=> $this->input->post('title'),
							'controller' 	=> $this->input->post('controller'),
							'method' 		=> $this->input->post('fungsi'),
							'url' 			=> $this->input->post('url'),
							'level' 		=> $this->input->post('level'),
							'icon' 			=> $this->input->post('icon'),
							'position' 		=> $this->input->post('order'),
							'status'		=> $status,
				);
				if ($this->input->post('controller')) {
					$data['controller']	= $this->input->post('controller');
				}
				if ($this->input->post('fungsi')) {
					$data['method']	= $this->input->post('fungsi');
				}
				$this->return = $this->db->insert('_acl',$data);

				if ($this->return) {
					$this->result = array('status' => true,
						'message' => 'Data berhasil disimpan');
				}else{
					$this->result = array('status' => false,
						'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				$data = array('title' 	 	=> $this->input->post('title'),
							'url' 			=> $this->input->post('url'),
							'level' 		=> $this->input->post('level'),
							'icon' 			=> $this->input->post('icon'),
							'position' 		=> $this->input->post('order'),
							'status'		=> $status,
				);
				if ($this->input->post('controller')) {
					$data['controller']	= $this->input->post('controller');
				}
				if ($this->input->post('fungsi')) {
					$data['method']	= $this->input->post('fungsi');
				}
				$this->return = $this->db->update('_acl', $data, ['id' => $this->input->post('id')]);
				if ($this->return) {
					$this->result = array('status' => true,
						'message' => 'Data berhasil disimpan');
				}else{
					$this->result = array('status' => false,
						'message' => 'Data gagal disimpan');
				}
			}

		}else {
			$this->result = array('status' => false,
				'message' => validation_errors(),);
		}

		if ($this->result) {
			$this->output->set_output(json_encode($this->result));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}

}

/* End of file Modul.php */
/* Location: ./application/modules/Master/controllers/Modul.php */