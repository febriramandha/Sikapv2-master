<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Dl_manual extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Dinas Luar Manual', 'kepegawaian/dl-manual');
		$this->data['title'] = "Kepegawaian";
		$this->load->model(['m_instansi','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/plugin/autocomplete/dist/latest/bootstrap-autocomplete.js');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->js('public/themes/plugin/ckeditor/ckeditor.js');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Data Dinas Luar Manual";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('dl_manual/v_index', $this->data);
	}

	public function view($id)
	{
		$this->data['sub_title'] 	= "Dinas Luar";
		$this->breadcrumbs->push('Dinas Luar', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->load->view('dl_manual/v_view', $this->data);
	}

	public function add($id)
	{
		$this->data['sub_title'] 	= "Dinas Luar";
		$this->breadcrumbs->push('Dinas Luar', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['user']			= $this->m_user->GetUserAllAktifDept(decrypt_url($id,'instansi'))->result();
		$this->load->view('dl_manual/v_add', $this->data);
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Dinas Luar";
		$this->breadcrumbs->push('Dinas Luar', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$dl_data 					= $this->db->get_where('dinasluar_manual', ['id' => decrypt_url($id,'dl_id')])->row();
		$this->data['dl_manual']	= $dl_data;
		$this->data['user']			= $this->m_user->GetUserAllAktifDept($dl_data->dept_id)->result();
		$this->load->view('dl_manual/v_edit', $this->data);
	}

	public function AjaxSave($id)
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('tanggal', 'tanggal', 'required')
							  ->set_rules('user[]', 'pegawai', 'required')
							  ->set_rules('kegiatan', 'kegiatan', 'required')
							  ->set_rules('hasil', 'hasil', 'required');		
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
				$data_user = $this->input->post('user');
				foreach ($data_user as $value) {
						$data_user_[] = decrypt_url($value,'user_id_dl');
				}
				$data_user_array = str_replace(['[', ']', '"'],['{', '}',''], json_encode($data_user_));

				$this->mod = $this->input->post('mod');
				if ($this->mod == "add") {
					$data = array(
							  'dept_id'	    => decrypt_url($id,'instansi'),
							  'user_id'	    => $data_user_array,
							  'tanggal'	    => format_tgl_eng($this->input->post('tanggal')),
							  'uraian'	    => $this->input->post('kegiatan'),
							  'hasil'	    => $this->input->post('hasil'),
							  'created_at' 	=> date('Y-m-d H:i:s'),
							  'created_by'  => $this->session->userdata('tpp_user_id'), );
					$this->return = $this->db->insert('dinasluar_manual', $data);
				}elseif ($this->mod == "edit") {
					$data = array(
							  'user_id'	    => $data_user_array,
							  'tanggal'	    => format_tgl_eng($this->input->post('tanggal')),
							  'uraian'	    => $this->input->post('kegiatan'),
							  'hasil'	    => $this->input->post('hasil'),
							  'updated_at' 	=> date('Y-m-d H:i:s'),
							  'updated_by'  => $this->session->userdata('tpp_user_id'), );
					$this->return = $this->db->update('dinasluar_manual', $data, ['id' => decrypt_url($this->input->post('id'),'dl_id')]);
				}

				if ($this->return) {
					$this->result = array('status'   => true,
		    	     		  		'message' => 'Data berhasil disimpan',);
				}else{
					$this->result = array('status'   => false,
		    	     		  		'message' => 'Data gagal disimpan',);
				}	
				

		}else {
			$this->result = array('status'   => false,
				    	     'message' => validation_errors(),);
		}
		if ($this->result) {
			$this->output->set_output(json_encode($this->result));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}

	public function indexJson($id)
	{
		$this->output->unset_template();
		$instansi = decrypt_url($id,'instansi');
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.tanggal, a.uraian, a.hasil, a.user_id, json_nama_nip as nama_nip')
        	->from('dinasluar_manual a')
        	->join("(select id as dl_id, 
					json_build_object(
							'data_pegawai', json_agg((nip, nama, gelar_dpn, gelar_blk) ORDER BY no_urut)
					) as json_nama_nip
					from (select a.id, a.user_id, b.nip, b.nama, b.gelar_dpn, b.gelar_blk, b.no_urut 
					from (select id, 	unnest(user_id) as user_id from dinasluar_manual) as a
					left join v_users_all b on a.user_id=b.id) as a
					GROUP BY 1) as b",'a.id=b.dl_id')
        	->add_column('tanggal','$1','tgl_ind_bulan(tanggal)')
        	->add_column('uraian','<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>','uraian')
        	->add_column('hasil','<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none; ">$1</div>','hasil')
        	->add_column('pegawai','<div class="m-0 p-1 panel-geser" style="max-height: 100px;max-width: 300px">$1</div>','pegawai_expl(nama_nip)')
        	->add_column('action', '<a href="'.base_url('kepegawaian/dl-manual/edit/').'$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>
					              <span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus data" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'encrypt_url(id,"dl_id")');
        	 if ($instansi) {
		        $this->datatables->where('a.dept_id', $instansi);
		     }
        	 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(uraian)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(hasil)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	         }
	       
        return $this->output->set_output($this->datatables->generate());
	}

}

/* End of file Dl_manual.php */
/* Location: ./application/modules/Kepegawaian/controllers/Dl_manual.php */