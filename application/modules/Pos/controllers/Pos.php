<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
        	->add_column('action', '
        							<a href="'.base_url('pos/edit/').'$1" class="list-icons-item text-primary-600" >
        									<i class="icon-pencil5" ></i>
        							</a>
        							<span class="confirm-aksi list-icons-item text-danger-600  msclick"  id="$1" msg="yakin ingin menghapus data ini">
        									<i class="icon-bin" ></i>
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
								->set_rules('isi', 'isi conten', 'required')
								->set_rules('kategori', 'kategori', 'required')
								->set_rules('status', 'status', 'required');
		$this->form_validation->set_error_delimiters('<div>', '</div>');

		if ($this->form_validation->run() == TRUE) {
			$config = array(
				    'table' 		=> 'articles',
				    'name'  		=> 'title',
				    'id' 			=> 'id',
				    'field' 		=> 'slug',
				    'replacement' 	=> 'dash' // Either dash or underscore
			);
			$this->load->library('slug', $config);

			$mod = $this->input->post('mod');
			if ($mod == "add") {
				$data = array('title' 	 		=> $this->input->post('title'),
							  'description' 	=> $this->input->post('deskripsi'),
							  'content' 		=> $this->input->post('isi'),
							  'status' 			=> $this->input->post('status'),
							  'kategori_id' 	=> $this->input->post('kategori'),
							  'slug' 		    => $this->slug->create_uri($this->input->post('title')),
							  'created_at' 		=> date('Y-m-d H:i:s'),
							  'created_by' 	 	=> $this->session->userdata('tpp_user_id'),
				 );
				$res = $this->db->insert('articles',$data);

				if ($res) {
					 $data_ = array('status' => true,
				    			    'alert' => 'Data berhasil disimpan');
				}else{
					 $data_ = array('status' => false,
				    			    'alert' => 'Data gagal disimpan');
				}
			}elseif ($mod == "edit") {
				$data = array('title' 	 		=> $this->input->post('title'),
							  'description' 	=> $this->input->post('deskripsi'),
							  'content' 		=> $this->input->post('isi'),
							  'status' 			=> $this->input->post('status'),
							  'kategori_id' 	=> $this->input->post('kategori'),
							  'slug' 		    => $this->slug->create_uri($this->input->post('title')),
							  'updated_at' 		=> date('Y-m-d H:i:s'),
							  'updated_by' 	 	=> $this->session->userdata('tpp_user_id'),
				 );
				$res = $this->db->update('articles', $data, ['id' => $this->input->post('id')]);

				if ($res) {
					 $data_ = array('status' => true,
				    			   'alert' => 'Data berhasil disimpan');
				}else{
					 $data_ = array('status' => false,
				    			   'alert' => 'Data gagal disimpan');
				}
			}

		}else {
			$validasi =  form_error('title').
						 form_error('deskripsi').
						 form_error('isi').
						 form_error('kategori').
						 form_error('status');
			$data_ = array('status' => false,
				    		'alert' => $validasi,);
		}
		if ($data_) {
			$this->output->set_output(json_encode($data_));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']));
		}
	}

	public function AjaxDel()
	{
		$this->output->unset_template();
		$del = $this->db->delete('articles',['id' => $this->input->get('id')]);

		if ($del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'msg'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal dihapus.']));	
		}
	}

}

/* End of file Pos.php */
/* Location: ./application/modules/Pos/controllers/Pos.php */