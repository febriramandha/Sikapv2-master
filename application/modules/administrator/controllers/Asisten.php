<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Asisten extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Admin', 'administrator/admin');
		$this->data['title'] = "Administrator";
		$this->load->model(['m_instansi','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
		$this->load->js('public/themes/plugin/datatables/dataTables.rowsGroup.js');
	}

	public function index()
	{
		$this->data['sub_title'] = "Pejabat Asisten";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('asisten/v_index', $this->data);
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Asisten";
		$this->breadcrumbs->push('Tambah Asisten', '/');
		$this->data['eselon2b'] 	= $this->m_user->GetUserByEselon(['2b'],2)->result();
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->load->view('asisten/v_add', $this->data);
	}

	public function JsonInstansi()
	{
		$this->output->unset_template();
		$data_instansi = '';
		if ($this->input->post('cek_instansi')) {
			$data_cek = array('user_id' => $this->input->post('cek_instansi'),
								  'pejabat_id' => 2);
			$cek_ada = $this->db->select('dept_id')->get_where('pejabat_instansi',$data_cek)->result();
			foreach ($cek_ada as $row ) {
				$data_instansi_[] = $row->dept_id;
			}

			if ($cek_ada) {
				$data_instansi = str_replace(['[', ']', '"'],[' ', ' ',' '],json_encode($data_instansi_));
			}
		}
		
		$this->load->library('datatables');
        $this->datatables->select('id, dept_name, dept_alias, parent_id, level, path_info, position_order')
        	->from('v_instansi_all')
        	->where('parent_id', 1)
        	->add_column('action', '<label class="pure-material-checkbox m-0">
										<input name="instansi[]" type="checkbox" class="checkbox" value="$1">
										<span></span>
							        </label> ', 'id');
        	if ($data_instansi) {
        		$this->db->where("id not in ($data_instansi)",'',false);
        	}
        return $this->output->set_output($this->datatables->generate());
	}

	public function JsonAsisten()
	{
		$this->output->unset_template();
		$this->load->library('datatables');
        $this->datatables->select('a.id, c.nama, b.dept_name, b.dept_alias, b.path_info, c.nip, d.jabatan,c.gelar_dpn,c.gelar_blk')
        	->from('pejabat_instansi a')
        	->join('v_instansi_all b','b.id=a.dept_id')
        	->join('v_users_all c','a.user_id=c.id')
        	->join('sp_pegawai d','a.user_id=d.user_id')
        	->where('pejabat_id',2)
        	->order_by('c.no_urut')
        	->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)')
        	->add_column('action', '<span class="deleted msclick text-danger" data="$1"><i class="icon-bin" data="$1"></i></span>', 'id');
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('pejabat', 'pejabat', 'required')
								->set_rules('instansi[]', 'Unit Kerja', 'required');
		$this->form_validation->set_error_delimiters('<div>', '</div>');

		if ($this->form_validation->run() == TRUE) {
				$mod = $this->input->post('mod');
				$instansi = $this->input->post('instansi');
				$res ='';
				if ($mod =="add") {
					for ($i=0; $i < count($instansi); $i++) { 
						$data_cek = array('user_id' => $this->input->post('pejabat'),
										  'dept_id' => $instansi[$i], 
										  'pejabat_id' => 2);
						$cek_ada = $this->db->select('id')->get_where('pejabat_instansi',$data_cek)->row();
						if (!$cek_ada) {
							$data = array('user_id' 	 		=> $this->input->post('pejabat'),
									  'pejabat_id' 	 		=> 2,
									  'dept_id' 	 		=> $instansi[$i],
									  'created_at' 			=> date('Y-m-d H:i:s'),
									  'created_by' 	 		=> $this->session->userdata('tpp_user_id'),
							 );
							$res = $this->db->insert('pejabat_instansi',$data);
						}
						
					}
				}
				if ($res) {
					 $data_ = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					 $data_ = array('status' => false,
				    			    'message' => 'Data gagal disimpan atau data telah ada');
				}

		}else {
			$data_ = array('status' => false,
				    		'message' => validation_errors(),);
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

		$del = $this->db->delete('pejabat_instansi',['id' => $this->input->get('id')]);

		if ($del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}

	public function cetak()
	{
		$this->output->unset_template();
		$this->load->library('Tpdf');
		$this->data['user']		= $this->m_user->GetPejabatInstansi(2)->result();
		$this->load->view('asisten/v_cetak', $this->data);
	}

}

/* End of file Asisten.php */
/* Location: ./application/modules/Administrator/controllers/Asisten.php */