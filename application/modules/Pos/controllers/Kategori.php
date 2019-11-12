<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Kategori', 'pos/kategori');
		$this->data['title'] = "Pos";
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['sub_title'] = "Kategori";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('kategori/v_index', $this->data);
	}

	public function json()
	{
		$this->output->unset_template();
		//header('Content-Type: application/json');
		$this->load->library('datatables');
        $this->datatables->select('id, name, description')
        	->from('_kategori')
        	->add_column('action', '
        							<span class="cek list-icons-item text-primary-600 ml-2 msclick" onClick="edit($1)">
        									<i class="icon-pencil5" ></i>
        							</span>
        							<span class="confirm-aksi list-icons-item text-danger-600 ml-2 msclick"  id="$1" msg="yakin ingin menghapus data ini">
        									<i class="icon-bin" ></i>
        							</span>', 'id');
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxSave()
	{
		$this->output->unset_template();

		$this->form_validation->set_rules('nama', 'nama', 'required')
								->set_rules('desck', 'deskripsi', 'required');
		$this->form_validation->set_error_delimiters('<div>', '</div>');

		if ($this->form_validation->run() == TRUE) {
			$config = array(
				    'table' => '_kategori',
				    'name'  => 'name',
				    'id' 	=> 'id',
				    'field' => 'slug',
				    'replacement' => 'dash' // Either dash or underscore
			);
			$this->load->library('slug', $config);

			$mod = $this->input->post('mod');
			if ($mod == "add") {
				$data = array('name' 	 		=> $this->input->post('nama'),
							  'description' 	=> $this->input->post('desck'),
							  'slug' 		    => $this->slug->create_uri($this->input->post('nama')),
							  'created_at' 		=> date('Y-m-d H:i:s'),
							  'created_by' 	 	=> $this->session->userdata('tpp_user_id'),
				 );
				$res = $this->db->insert('_kategori',$data);

				if ($res) {
					 $data_ = array('status' => true,
				    			    'alert' => 'Data berhasil disimpan');
				}else{
					 $data_ = array('status' => false,
				    			    'alert' => 'Data gagal disimpan');
				}
			}elseif ($mod == "edit") {
				$data = array('name' 	 		=> $this->input->post('nama'),
							  'description' 	=> $this->input->post('desck'),
							  'slug' 		    => $this->slug->create_uri($this->input->post('nama')),
							  'updated_at' 		=> date('Y-m-d H:i:s'),
							  'updated_by' 	 	=> $this->session->userdata('tpp_user_id'),
				 );
				$res = $this->db->update('_kategori', $data, ['id' => $this->input->post('id')]);

				if ($res) {
					 $data_ = array('status' => true,
				    			   'alert' => 'Data berhasil disimpan');
				}else{
					 $data_ = array('status' => false,
				    			   'alert' => 'Data gagal disimpan');
				}
			}

		}else {
			$validasi =  form_error('nama').
						 form_error('desc');
			$data_ = array('status' => false,
				    		'alert' => $validasi,);
		}
		if ($data_) {
			$this->output->set_output(json_encode($data_));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']));
		}
	}

	public function ajaxGet()
	{
		$this->output->unset_template();
		$cuti = $this->db->get_where('_kategori', ['id'=> $this->input->get('id')])->row();
		if ($cuti) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'msg'=> 'Berhasil mengambil data.', 'data'=> $cuti]));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']));	
		}
	}

	public function ajaxDel()
	{
		$this->output->unset_template();
		$del = $this->db->delete('_kategori',['id' => $this->input->get('id')]);

		if ($del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'msg'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal dihapus.']));	
		}
	}


}

/* End of file Kategori.php */
/* Location: ./application/modules/Pos/controllers/Kategori.php */