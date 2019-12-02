<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jnscuti extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jenis Cuti', 'master/jnscuti');
		$this->data['title'] = "Master";
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['sub_title'] = "Jenis Cuti";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('jnscuti/v_index', $this->data);
	}

	public function Getjson()
	{
		$this->output->unset_template();
		//header('Content-Type: application/json');
		$this->load->library('datatables');
        $this->datatables->select('id, kode, nama')
        	->from('_cuti')
        	->where('deleted',1)
        	->add_column('action', '
        							<span class="cek list-icons-item text-info-400 ml-2 msclick" onClick="edit($1)">
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
		$this->form_validation->set_rules('kode', 'kode cuti', 'required')
								->set_rules('nama', 'nama cuti', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');
			if ($this->mod == "add") {
				$data = array('kode' 	 	=> $this->input->post('kode'),
							  'nama' 		=> $this->input->post('nama'),
				 );
				$this->return = $this->db->insert('_cuti',$data);

				if ($this->return) {
					 $this->result = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			    'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				$data = array('kode' 	 	=> $this->input->post('kode'),
							  'nama' 		=> $this->input->post('nama'),
				 );
				$this->return = $this->db->update('_cuti', $data, ['id' => $this->input->post('id')]);

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

	public function ajaxGet()
	{
		$this->output->unset_template();
		$cuti = $this->db->get_where('_cuti', ['id'=> $this->input->get('id')])->row();
		if ($cuti) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'msg'=> 'Berhasil mengambil data.', 'data'=> $cuti]));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']));	
		}
	}

	public function ajaxDel()
	{
		$this->output->unset_template();
		$del = $this->db->update('_cuti',['deleted' => 0],['id' => $this->input->get('id')]);

		if ($del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'msg'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal dihapus.']));	
		}
	}


}

/* End of file Jnscuti.php */
/* Location: ./application/modules/master/controllers/Jnscuti.php */