<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
* Created By: Rian Reski A
* 2019
*/


class Absen_upacara extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Dinas Luar Manual', 'kepegawaian/absen_upacara');
		$this->data['title'] = "Kepegawaian";
		$this->load->model(['m_instansi','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
		$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Data Dinas Luar Manual";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('absen_upacara/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		//header('Content-Type: application/json');
		$this->load->library('datatables');
		$this->datatables->select('id, tanggal, jam_mulai, ket')
		->from('sch_upacara')
		->order_by('tanggal','desc')
		->add_column('tanggal', '$1 ($2)', 'tgl_ind_hari(tanggal),jm(jam_mulai)')
		->add_column('sch_name','$1','sch_name(ket, tanggal)')
		->add_column('action', '<a href="'.base_url('kepegawaian/absen-upacara/edit/').'$1" class="btn btn-sm badge-info p-1"> 
			<i class="icon-alarm-check"></i> 
			Input Absen Upacara</a>
			</span>', 'encrypt_url(id,"schupacara_id")');
		return $this->output->set_output($this->datatables->generate());acktion_sch_status();
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Absen Upacara";
		$this->breadcrumbs->push('Absen Upacara Pegawai', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		if ($this->session->userdata('tpp_level') == 1) {
			$not_in_eselon = '1,2';
		}else {
			$not_in_eselon = '1,2,3,4,5,6';
		}
		$this->data['eselon']		= $this->db->where_not_in('id',$not_in_eselon,false)->order_by('id')->get('_eselon')->result();
		$this->data['jadwal']			= $this->db->get_where('sch_upacara',['id' => decrypt_url($id,'schupacara_id')])->row();
		$this->load->view('absen_upacara/v_edit', $this->data);
	}

	public function PegawaiJson($id)
	{
		$this->load->helper('absen');
		$this->output->unset_template();
		$schupacara_id = decrypt_url($id,'schupacara_id');
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$eselon = $this->input->post('eselon');
		$eselon_ =0;
		if ($eselon) {
			foreach ($eselon as $value) {
				$eselon_ex[] = decrypt_url($value,'eselon_id');
			}

			$eselon_ = implode("+",$eselon_ex);


		}

		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.nama,status_pegawai,gelar_dpn,gelar_blk,b.golongan, b.pangkat, a.eselon_id, c.hadir, c.id as absenupacara_id')
        	->from('v_users_all a')
        	->join('_golongan b','a.golongan_id=b.id','left')
        	->join("(select id, user_id, hadir from absen_upacara where sch_upacara_id=$schupacara_id) c",'a.id=c.user_id','left')
        	->where('key > 0')
        	->where('att_status',1)
        	->order_by('no_urut')
        	->add_column('nama_gelar','$1','nama_gelar(nama, gelar_dpn,gelar_blk)')
        	->add_column('golongan','$1/$2','golongan,pangkat')
        	->add_column('cek1','$1',"cek_radio_disable(id,$eselon_,eselon_id,'yahadir',hadir,absenupacara_id)")
        	->add_column('cek2','$1',"cek_radio_disable(id,$eselon_,eselon_id,'thadir',hadir)")
        	->add_column('cek3','$1',"cek_radio_disable(id,$eselon_,eselon_id,'cuti',hadir)")
        	->add_column('ket','$1','upacara_ket(hadir)')
        	->add_column('action','$1',"action_upacara_id(absenupacara_id,$eselon_,eselon_id,hadir)");
    	 if ($instansi) {
	        $this->datatables->where('a.dept_id', $instansi);
	     }	       
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxSave($id)
	{
		ini_set('max_input_vars', 20000);
		$this->output->unset_template();
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_rules('user[]', 'pegawai', 'required');
		$this->form_validation->set_rules('absen[]', 'absen', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
				$instansi = decrypt_url($this->input->post('instansi'),'instansi');
				$schupacara_id = decrypt_url($id,'schupacara_id');
				$user  = $this->input->post('user');
				$absen = $this->input->post('absen');
				$hadir = $this->input->post('hadir');
				$absenupacara = $this->input->post('absenupacara_id');
				$data_update =array();
				$data_insert =array();
				foreach ($user as $value) {
					$user_id 	= decrypt_url($value,'user_id_upacara');
					$absen_type = decrypt_url($absen[$user_id],'type_upacara');
					$hadir_id	= $hadir[$user_id];
					

					if ($hadir_id) {
							//update 
					$absenupacara_id = decrypt_url($absenupacara[$user_id],'absenupacara_id');
					$data_update[] = array(	'id' 			=> $absenupacara_id,
											'hadir' 	 	=> $absen_type,
											'updated_at'	=> date('Y-m-d H:i:s'), 
											'updated_by'	=> $this->session->userdata('tpp_user_id'));
					}else {
							// insert
					$data_insert[] = array(	'user_id' 		=> $user_id,
											'dept_id'   	=> $instansi,
											'sch_upacara_id'=> $schupacara_id,
											'hadir' 	 	=> $absen_type,
											'created_at'	=> date('Y-m-d H:i:s'), 
											'created_by'	=> $this->session->userdata('tpp_user_id'));

					}

				}

				if ($data_update) {
					$this->return = $this->db->update_batch('absen_upacara', $data_update, 'id');
				}

				if ($data_insert) {
					$this->return = $this->db->insert_batch('absen_upacara', $data_insert);
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

	public function AjaxDel()
	{
		$this->output->unset_template();
		$this->del = $this->db->delete('absen_upacara',['id' => decrypt_url($this->input->get('id'),"absenupacara_id")]);

		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}

	public function cetak($dept_id,$schupacara_id)
	{
		$this->output->unset_template();
		$this->load->library('Tpdf');
		$this->load->helper('absen');
		$schupacara_id = decrypt_url($schupacara_id,'schupacara_id');
		$this->data['instansi'] = $this->m_instansi->GetInstansi(decrypt_url($dept_id, 'instansi'))->row();
		$this->db->select('a.id, a.nip, a.nama,status_pegawai,gelar_dpn,gelar_blk,b.golongan, b.pangkat, a.eselon_id, c.hadir, c.id as absenupacara_id')
        	->from('v_users_all a')
        	->join('_golongan b','a.golongan_id=b.id','left')
        	->join("(select id, user_id, hadir from absen_upacara where sch_upacara_id=$schupacara_id) c",'a.id=c.user_id','left')
        	->where('key > 0')
        	->where('att_status',1)
        	->order_by('no_urut')
        	->where('a.dept_id', decrypt_url($dept_id, 'instansi'));  
		$this->data['user_upacara']		= $this->db->get()->result();
		$this->data['jadwal']			= $this->db->get_where('sch_upacara',['id' => $schupacara_id])->row();
		$this->load->view('absen_upacara/v_cetak', $this->data);
	}



}

/* End of file Absen_upacara.php */
/* Location: ./application/modules/Kepegawaian/controllers/Absen_upacara.php */