<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Verifikasi extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Verifikasi LKH', 'datalkh/verifikasi');
		$this->data['title'] = "Verifiikasi";
		$this->load->model(['m_schrun_user','m_sch_lkh','m_data_lkh','m_verifikator','m_instansi']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
		$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
		$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
		$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
		$this->load->css('public/themes/plugin/toastr/toastr.css');
		$this->load->js('public/themes/plugin/toastr/toastr.min.js');
		$this->load->js('public/themes/plugin/datatables/dataTables.rowsGroup.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Verifikasi LKH";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['jumlah_nonver'] = $this->m_data_lkh->jumlah_nonver($this->session->userdata('tpp_user_id'));
		$this->load->view('verifikasi/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$this->load->library('datatables');
        $this->datatables->select('a.id, b.nip, b.nama, b.gelar_dpn, b.gelar_blk, c.jabatan, d.jum_non_ver')
        	->from('verifikator a')
        	->join('v_users_all b','a.user_id=b.id','left')
        	->join('sp_pegawai c','a.user_id=c.user_id','left')
        	->join('v_jum_non_ver d','a.user_id=d.user_id','left')
        	->where('a.user_id_ver', $this->session->userdata('tpp_user_id'))
        	->where('key > 0')
        	->where('att_status',1)
        	->order_by('no_urut')
        	->add_column('nama_nip','$1','nama_icon_nip_key(nama,gelar_dpn,gelar_blk,nip,"datalkh/verifikasi/view",id, jabatan,"verifikator_id")')
        	->add_column('jumlah','$1','jum_non_ver(jum_non_ver)');
        return $this->output->set_output($this->datatables->generate());
	}

	public function view($id)
	{
		$verifikator_id 		= decrypt_url($id, 'verifikator_id');
		$user 					= $this->m_verifikator->GetUserByverifikator($verifikator_id)->row();

		$jumlkh = $this->m_sch_lkh->Getsch_lkh($user->dept_id,date('Y-m-d'))->row();
		$tanggal_lkh_ver = $this->m_data_lkh->jadwal_lkh_limit($user->user_id, $jumlkh->count_verday)->result();
		$rank_tanggal = array();
		if ($tanggal_lkh_ver) {
				foreach ($tanggal_lkh_ver as $row) {
					$rank_tanggal[] = $row->rentan_tanggal;
				}
				$this->m_data_lkh->update_status($user->user_id, $rank_tanggal);
		}

		$this->data['sub_title'] 	= "Verifiikasi LKH";
		$this->breadcrumbs->push('Verifiikasi LKH', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['user']			= $user;
		$this->data['tanggal_lkh']  = $tanggal_lkh_ver;
		$this->data['jumlkh']		= $jumlkh;
		$this->load->view('verifikasi/v_view', $this->data);
	}

	public function viewJson($id)
	{
		$date_now = date('Y-m-d');
		$this->output->unset_template();
		$verifikator_id 		= decrypt_url($id, 'verifikator_id');
		$user 					= $this->m_verifikator->GetUserByverifikator($verifikator_id)->row();

		$jumlkh = $this->m_sch_lkh->Getsch_lkh($user->dept_id,date('Y-m-d'))->row();
		$tanggal_lkh_ver = $this->m_data_lkh->jadwal_lkh_limit($user->user_id, $jumlkh->count_verday)->result();
		$rank_tanggal =  array('0000:00:00');
		if ($tanggal_lkh_ver) {
				foreach ($tanggal_lkh_ver as $row) {
					$data_tgl_lkh[] = $row->rentan_tanggal;
				}
		}

		if ($this->input->post('tgl_id')) {
			$data_tgl_lkh = decrypt_url($this->input->post('tgl_id'),"tanggal_lkh_verifikasi_$date_now");
		}
		$this->load->library('datatables');
        $this->datatables->select('a.id, tgl_lkh, jam_mulai, jam_selesai, kegiatan, hasil, jenis, a.status, verifikasi_by, b.nama as ver_nama, b.gelar_dpn as ver_gelar_dpn, b.gelar_blk as ver_gelar_blk, comment, a.jenis, a.persentase')
        	->from('data_lkh a')
        	->join('v_users_all b','a.verifikasi_by=b.id','left')
        	->join('lkh_rejected c','a.id=c.lkh_id','left')
        	->where_in('date(tgl_lkh)::text', $data_tgl_lkh)
        	->order_by('tgl_lkh desc,  jam_mulai asc')
        	->where('user_id', $user->user_id)
        	->add_column('tgl_lkh_tabel','$1','tglInd_hrtabel(tgl_lkh)')
        	->add_column('jam_mulai','$1 <i class="icon-arrow-right16"></i> $2 $3 $4','jm(jam_mulai), jm(jam_selesai), cek_dltabel(jenis), persentase_lkh(persentase)')
        	->add_column('status_lkh','$1','status_lkh_tabel(status, comment)')
        	->add_column('action','<div style="white-space: nowrap;">$1</div>', 'aksi_verifikasi_lkh(id, status)')
        	->add_column('cek','$1','cekbox_verifikasi(id, status)'); 	
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('mod');
		$user_id = decrypt_url($this->input->get('id'),'user_id');

		if ($this->mod == "load_notif") {
				$tanggal = $this->input->get('tgl');
				
				$this->db->select('user_id, tgl_lkh, count(*) as jum')
						 ->where_in('status','0,4',false)
						 ->where_in('date(tgl_lkh)::text', $tanggal)
						 ->where('user_id', $user_id)
						 ->group_by('1,2');
				$data_count = $this->db->get('data_lkh')->result();

				$date_now = date('Y-m-d');
				foreach ($tanggal as $r_t) {
					$jum ='';
					foreach ($data_count as $r_v ) {
						if ($r_t == $r_v->tgl_lkh) {
								$jum = $r_v->jum;
						}
					}
					$tanggal_[] = $r_t;
					$tanggal_cek[] = $jum;

				}

				$data_notif = array('tanggal' => $tanggal_,
									'count' => $tanggal_cek );

				$this->result = array('status' => true,
			    			   		   'message' => 'Berhasil mengabil data',
			    			   		   'data' => $data_notif);	
		}elseif ($this->mod == "verifikasi") {

			$lkh_id = $this->input->get('lkh_id');
			$data = array(
					 'status'           => 1,
					 'verifikasi_time'  => date('Y-m-d H:i:s'), 
					 'verifikasi_by'  	=> $this->session->userdata('tpp_user_id'),  );		

			$this->db->where('id', $lkh_id);
			$this->db->where('user_id', $user_id);
			$this->return  = $this->db->update('data_lkh', $data);

			if ($this->return) {
				 $this->result = array('status' => true,
			    			           'message' => 'Data berhasil diverifikasi');
			}else{
				 $this->result = array('status' => false,
				 				 		'message' => 'Data berhasil gagal diverifikasi');
			 }
		}

		if ($this->result) {
			$this->output->set_output(json_encode($this->result));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}

	public function AjaxSaveVer()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('ver[]', 'data ceklis', 'required');

		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
			
			$user_id = decrypt_url($this->input->post('user_id_'),'user_id');
			$lkh_id = $this->input->post('ver');

			$data = array();
			foreach ($lkh_id as $v ) {
				$data[] = array('id'       			=> decrypt_url($v,'lkh_id_verifikasi'),
								'user_id'  			=> $user_id,
								'status'           	=> 1,
								'verifikasi_time'  	=> date('Y-m-d H:i:s'), 
								'verifikasi_by'  	=> $this->session->userdata('tpp_user_id'),);
			}

			$this->return = $this->db->update_batch('data_lkh', $data, 'id');

			if ($this->return) {
				 $this->result = array('status' => true,
			    			    'message' => 'Data berhasil diverifikasi');
			}else{
				 $this->result = array('status' => false,
			    			    'message' => 'Data gagal diverifikasi');
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

	public function AjaxSaveComment()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('komentar', 'komentar', 'required');
		$this->form_validation->set_rules('id', 'tombol tolak', 'required');

		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
			
			$user_id = decrypt_url($this->input->post('user_id_'),'user_id_comment');
			$lkh_id = $this->input->post('id');

				$data = array(
						 'status'           => 2,
						 'verifikasi_time'  => date('Y-m-d H:i:s'), 
						 'verifikasi_by'  	=> $this->session->userdata('tpp_user_id'),  );		

				$this->db->where('id', $lkh_id);
				$this->db->where('user_id', $user_id);
				$this->return = $this->db->update('data_lkh', $data);

				if ($this->return) {
					$data_komentar = array(	'lkh_id'  => $lkh_id, 
							     			'comment' => $this->input->post('komentar'));
					$this->return = $this->db->insert('lkh_rejected', $data_komentar);
				}

			if ($this->return) {
				 $this->result = array('status' => true,
			    			    'message' => 'Data berhasil diverifikasi');
			}else{
				 $this->result = array('status' => false,
			    			    'message' => 'Data gagal diverifikasi');
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

/* End of file Verifikasi.php */
/* Location: ./application/modules/Datalkh/controllers/Verifikasi.php */