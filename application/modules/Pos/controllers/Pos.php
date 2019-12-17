<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Pos extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Pos', 'pos');
		$this->data['title'] = "Pos";
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/plugin/ckeditor/ckeditor.js');
	}

	public function index()
	{
		$this->data['sub_title'] = "Semua Pos";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('pos/v_index', $this->data);
	}

	public function json()
	{
		$this->output->unset_template();
		//header('Content-Type: application/json');
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.created_at, a.title, a.description, a.status, b.name as kategori')
        	->from('articles a')
        	->join('_kategori b','a.kategori_id=b.id','left')
        	->add_column('created_at','$1','format_waktu_ind(created_at)')
        	->add_column('action', '
        						  <a href="'.base_url('pos/edit/').'$1">
        							<i class="icon-pencil5 text-info-400"></i>
					              </a>
					              <span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'id');
        return $this->output->set_output($this->datatables->generate());
	}

	public function add()
	{
		$this->data['sub_title'] = "Tambah Pos";
		$this->breadcrumbs->push('Tambah Pos', 'pos/add');
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['kategori']	  = $this->db->get('_kategori')->result();
		$this->load->view('pos/v_add', $this->data);
	}

	public function edit($id)
	{
		$this->data['sub_title'] = "Edit Pos";
		$this->breadcrumbs->push('Edit Pos', 'pos/edit');
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['kategori']	  = $this->db->get('_kategori')->result();
		$this->data['edit']		  = $this->db->get_where('articles', ['id' => $id])->row();
		$this->load->view('pos/v_edit', $this->data);
	}

	public function AjaxSave()
	{
		$this->output->unset_template();

		$this->form_validation->set_rules('title', 'judul', 'required')
								->set_rules('deskripsi', 'deskripsi', 'required')
								->set_rules('isi', 'isi konten', 'required')
								->set_rules('kategori', 'kategori', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
			$config = array(
				    'table' 		=> 'articles',
				    'name'  		=> 'title',
				    'id' 			=> 'id',
				    'field' 		=> 'slug',
				    'replacement' 	=> 'dash' // Either dash or underscore
			);
			$this->load->library('slug', $config);
			$this->mod = $this->input->post('mod');

			if ($this->input->post('status')) {
				$status = 'publish';
			}else {
				$status = 'unpublish';
			}

			if ($this->mod == "add") {
				$data = array('title' 	 		=> $this->input->post('title'),
							  'description' 	=> $this->input->post('deskripsi'),
							  'content' 		=> $this->input->post('isi'),
							  'status' 			=> $status,
							  'kategori_id' 	=> $this->input->post('kategori'),
							  'slug' 		    => $this->slug->create_uri($this->input->post('title')),
							  'created_at' 		=> date('Y-m-d H:i:s'),
							  'created_by' 	 	=> $this->session->userdata('tpp_user_id'),
				 );
				$this->return = $this->db->insert('articles',$data);

				if ($this->return) {
					 $this->result = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			    'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				$data = array('title' 	 		=> $this->input->post('title'),
							  'description' 	=> $this->input->post('deskripsi'),
							  'content' 		=> $this->input->post('isi'),
							  'status' 			=> $status,
							  'kategori_id' 	=> $this->input->post('kategori'),
							  'slug' 		    => $this->slug->create_uri($this->input->post('title')),
							  'updated_at' 		=> date('Y-m-d H:i:s'),
							  'updated_by' 	 	=> $this->session->userdata('tpp_user_id'),
				 );
				$this->return = $this->db->update('articles', $data, ['id' => $this->input->post('id')]);

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

	public function AjaxDel()
	{
		$this->output->unset_template();
		$del = $this->db->delete('articles',['id' => $this->input->get('id')]);

		if ($del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus.']));	
		}
	}

}

/* End of file Pos.php */
/* Location: ./application/modules/Pos/controllers/Pos.php */