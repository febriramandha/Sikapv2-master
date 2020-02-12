<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Rekap_lkhmanualpe extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Rekap LKH Manual', 'mnglkh/rekap_lkhmanualpe');
		$this->data['title'] = "Manajemen LKH";
		$this->load->model(['m_instansi','m_sch_run','m_schlkh_manual']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
    	
	}

	public function index()
	{
		$this->data['sub_title']  = "Rekap LKH Manual";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('rekap_lkhmanualpe/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$this->load->library('datatables');
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $instansi])->row()->level;
        $this->datatables->select('a.id, a.name, a.start_date, a.end_date, json_nama_nip as nama_nip, c.dept_alias')
        	->from('schlkh_manual a')
        	->join("(select id as schlkhmanual_id, 
					json_build_object(
							'data_pegawai', json_agg((nip, nama, gelar_dpn, gelar_blk) ORDER BY no_urut)
					) as json_nama_nip
					from (select a.id, a.user_id, b.nip, b.nama, b.gelar_dpn, b.gelar_blk, b.no_urut 
					from (select id, 	unnest(user_id) as user_id from schlkh_manual) as a
					left join v_users_all b on a.user_id=b.id) as a
					GROUP BY 1) as b",'a.id=b.schlkhmanual_id')
        	->join('v_instansi_all c','a.dept_id=c.id','left')
        	->order_by('a.id','desc')
        	->add_column('start_date','$1 - $2','format_tgl_ind(start_date), format_tgl_ind(end_date)')
        	->add_column('sch_name','$1','sch_name(name, start_date)')
        	->add_column('pegawai','<div class="m-0 p-1 panel-geser" style="max-height: 100px;max-width: 300px">$1</div>','pegawai_expl(nama_nip)')
        	->add_column('action', '<a href="'.base_url('mnglkh/rekap-lkhmanualpe/view/').'$1" class="badge bg-info">
        							<i class="icon-pencil5"></i> Input Rekap
					                </a>
					              ', 'encrypt_url(id,"schlkhmanual_id")')
        	->where("path_id['".$level."']='".$instansi."'");
        return $this->output->set_output($this->datatables->generate());
	}

	public function view($id)
	{
		$this->data['sub_title'] 	= "Input Piket Manual";
		$this->breadcrumbs->push('Input Piket Manual', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$user_data = $this->m_schlkh_manual->userAll_schlkh_manual(decrypt_url($id,'schlkhmanual_id'));
		$this->data['user_data']	= $user_data;
		$this->data['instansi']		= $this->m_instansi->GetInstansi($user_data->row()->dept_id)->row();
		$this->load->view('rekap_lkhmanualpe/v_view', $this->data);
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('user[]', 'pegawai', 'required');
		$this->form_validation->set_rules('jumlah[]', 'jumlah', 'required');
		$this->form_validation->set_rules('total[]', 'total', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');	
				$schupacara_id = decrypt_url($this->input->post('sch'),'schlkhmanual_id');
				$user   = $this->input->post('user');
				$jumlah = $this->input->post('jumlah');
				$total  = $this->input->post('total');
				$cek_ 	= $this->input->post('cek');
				$rekapmanual = $this->input->post('rekapmanual_id');
				$data_update =array();
				$data_insert =array();
				foreach ($user as $value) {
					$user_id 	= decrypt_url($value,'user_id_rekap_lkh_manual');
					$jumlah_id  = $jumlah[$user_id];
					$total_id   = $total[$user_id];
					$hadir_id	= $cek_[$user_id];
					

					if ($hadir_id != null) {
							//update 
					$absenupacara_id = decrypt_url($rekapmanual[$user_id],'rekapmanual_id');
					$data_update[] = array(	'id' 					=> $absenupacara_id,
											'jumlah_laporan' 	 	=> $jumlah_id,
											'total_laporan' 	 	=> $total_id,
											'updated_at'			=> date('Y-m-d H:i:s'), 
											'updated_by'			=> $this->session->userdata('tpp_user_id'));
					}else {
							// insert
					$data_insert[] = array(	'user_id' 		=> $user_id,
											'schlkhmanual_id' 		=> $schupacara_id,
											'jumlah_laporan' 	 	=> $jumlah_id,
											'total_laporan' 	 	=> $total_id,
											'created_at'	=> date('Y-m-d H:i:s'), 
											'created_by'	=> $this->session->userdata('tpp_user_id'));
					}

				}

				if ($data_update) {
					$this->return = $this->db->update_batch('rekaplkh_manual', $data_update, 'id');
				}

				if ($data_insert) {
					$this->return = $this->db->insert_batch('rekaplkh_manual', $data_insert);
				}
				

				if ($this->return) {
					 $this->result = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			    'message' => 'Data gagal disimpan');
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

/* End of file Rekap_lkhmanualpe.php */
/* Location: ./application/modules/mnglkh/controllers/Rekap_lkhmanualpe.php */