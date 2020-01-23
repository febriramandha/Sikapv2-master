<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Absen_manualpe extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Absen Manual Pegawai', 'mngsch/setsch-start');
		$this->data['title'] = "Absen Manual";
		$this->load->model(['m_instansi','m_schabsen_manual']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Absen Manual Pegawai";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('absen_manualpe/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$this->load->library('datatables');
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $instansi])->row()->level;
        $this->datatables->select('a.id, a.name, a.start_date, a.end_date, json_nama_nip as nama_nip, c.dept_alias,a.absen_in,a.absen_out')
        	->from('schabsen_manual a')
        	->join("(select id as schabsenmanual_id, 
					json_build_object(
							'data_pegawai', json_agg((nip, nama, gelar_dpn, gelar_blk) ORDER BY no_urut)
					) as json_nama_nip
					from (select a.id, a.user_id, b.nip, b.nama, b.gelar_dpn, b.gelar_blk, b.no_urut 
					from (select id, 	unnest(user_id) as user_id from schabsen_manual) as a
					left join v_users_all b on a.user_id=b.id) as a
					GROUP BY 1) as b",'a.id=b.schabsenmanual_id')
        	->join('v_instansi_all c','a.dept_id=c.id','left')
        	->order_by('a.id','desc')
        	->add_column('start_date','$1 - $2','format_tgl_ind(start_date), format_tgl_ind(end_date)')
        	->add_column('sch_name','$1','sch_name(name, start_date)')
        	->add_column('pegawai','<div class="m-0 p-1 panel-geser" style="max-height: 100px;max-width: 300px">$1</div>','pegawai_expl(nama_nip)')
        	->add_column('akses','$1$2','span_label(absen_in,"Masuk","success"),span_label(absen_in,"Pulang","info")')
        	->add_column('action', '<a href="'.base_url('mngabsenmanual/absen-manualpe/view/').'$1" class="badge bg-info">
        							<i class="icon-pencil5"></i> Input Absen
					                </a>
					              ', 'encrypt_url(id,"schabsenmanual_id")')
        	->where("path_id['".$level."']='".$instansi."'");
        return $this->output->set_output($this->datatables->generate());
	}

	public function view($id)
	{
		$this->data['sub_title'] 	= "Input Absen Manual";
		$this->breadcrumbs->push('Input Absen Manual', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$user_data = $this->m_schabsen_manual->userAll_schabsen_manual(decrypt_url($id,'schabsenmanual_id'));
		$this->data['user_data']	= $user_data;
		$this->data['instansi']		= $this->m_instansi->GetInstansi($user_data->row()->dept_id)->row();
		$this->load->view('absen_manualpe/v_view', $this->data);
	}

	public function absen()
	{
		$schabsenmanual_id 	  		= decrypt_url($this->input->get('sch'),'schabsmanual_id');
		$user_id  					= decrypt_url($this->input->get('u'),'user_id_absenmanual');
		$user_data = $this->m_schabsen_manual->userAll_schabsen_manual($schabsenmanual_id, $user_id);
		$this->data['sub_title'] 	= "Isi Absen Pegawai";
		$this->breadcrumbs->push('Isi Absen Pegawai', '/');
		$this->data['user_data']	= $user_data;
		$cek = $this->db->select('tanggal, status_in, status_out')->get_where('absenmanual_data', ['user_id' => $user_id, 'schabsmanual_id' => $schabsenmanual_id])->row();
		$this->data['absen_data']	= $cek;
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->load->view('absen_manualpe/v_absen', $this->data);
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('u', 'pegawai', 'required');
		if (!$this->input->post('in') && !$this->input->post('out')) {
			$this->form_validation->set_rules('cekout1', 'masuk atau pulang', 'required');
		}
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');	
			
			$schabsenmanual_id 	  = decrypt_url($this->input->post('sch'),'schabsmanual_id');
			$user_id  			  = decrypt_url($this->input->post('u'),'user_id_absenmanual');

			$tanggal_c  = $this->input->post('tanggal');
			$in_c 		= $this->input->post('in');
			$out_c 		= $this->input->post('out');

			$in_data_ = array();
			$out_data_ = array();
			foreach ($tanggal_c as $tgl_val ) {
				$tanggal_data_[] = $tgl_val;
				$tanggal_vf 	 = tanggal_format($tgl_val,'Ymd');
				$in_data_[] 	 = $in_c[$tanggal_vf];
				$out_data_[] 	 = $out_c[$tanggal_vf];
			}

			$tanggal_data = str_replace(['[', ']', '"'],['{', '}',''], json_encode($tanggal_data_));
			$in_data 	  = str_replace(['[', ']', '"'],['{', '}',''], json_encode($in_data_));
			$out_data 	  = str_replace(['[', ']', '"'],['{', '}',''], json_encode($out_data_));

			$cek_id = $this->db->select('id')->get_where('absenmanual_data',['user_id' => $user_id, 'schabsmanual_id' => $schabsenmanual_id ])->row();

			if (!$cek_id) {
				$data = array(
							  'user_id' 		=> $user_id,
							  'schabsmanual_id' => $schabsenmanual_id,
							  'tanggal' 		=> $tanggal_data,
							  'status_in' 		=> $in_data,
							  'status_out' 		=> $out_data,
							  'created_at' 		=> date('Y-m-d H:i:s'),
							  'created_by' 		=> $this->session->userdata('tpp_user_id'),
				 );
				$this->return = $this->db->insert('absenmanual_data',$data);
	
			}elseif ($cek_id) {
				$data = array(
							  'tanggal' 		=> $tanggal_data,
							  'status_in' 		=> $in_data,
							  'status_out' 		=> $out_data,
							  'created_at' 		=> date('Y-m-d H:i:s'),
							  'created_by' 		=> $this->session->userdata('tpp_user_id'),
				 );
				$this->return = $this->db->update('absenmanual_data',$data,['id' => $cek_id->id]);
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

/* End of file Absen_manualpe.php */
/* Location: ./application/modules/MngAbsenManual/controllers/Absen_manualpe.php */