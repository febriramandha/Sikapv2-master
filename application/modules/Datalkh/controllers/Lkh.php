<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Lkh extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Data LKH', 'datalkh/lkh');
		$this->data['title'] = "Data LKH";
		$this->load->model(['m_schrun_user','m_sch_lkh','m_data_lkh','m_verifikator','m_instansi']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
		$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
		$this->load->js('public/themes/plugin/ckeditor/ckeditor.js');
		$this->load->css('public/themes/plugin/clock/dist/bootstrap-clockpicker.min.css');
		$this->load->js('public/themes/plugin/clock/dist/bootstrap-clockpicker.min.js');
		$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
		$this->load->js('public/themes/plugin/datatables/dataTables.rowsGroup.js');
		$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
	}

	public function index()
	{
		$tglshow = $this->m_schrun_user->CekTanggalLkh($this->session->userdata('tpp_user_id'), date('Y-m-d'))->row();
		$jumlkh  = $this->m_sch_lkh->Getsch_lkh($this->session->userdata('tpp_dept_id'),date('Y-m-d'))->row();
		if ($tglshow) {
		      if ($tglshow->shiftuserrun_id) {
		          $data_tgl_lkh = array();
		          for ($i=0; $i < $jumlkh->count_verday; $i++) { 
		                $data_tgl_lkh[] = tgl_minus(date('Y-m-d'), $i);
		          }
		      }else {
		         $data_tgl_lkh = tgl_minus_lkh(date('Y-m-d'), $jumlkh->count_verday, $tglshow->hari_kerja);
		      }
		      $this->m_data_lkh->update_status($this->session->userdata('tpp_user_id'), $data_tgl_lkh);
		}
		
		$this->data['sub_title']  = "Semua LKH";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['verifikator']	= $this->m_verifikator->GetVerifikator($this->session->userdata('tpp_user_id'))->row();
		$this->data['tglshow'] 		= $tglshow;
		$this->data['jumlkh']		= $jumlkh;
		$this->load->view('lkh/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$tglshow = $this->m_schrun_user->CekTanggalLkh($this->session->userdata('tpp_user_id'), date('Y-m-d'))->row();
		$jumlkh  = $this->m_sch_lkh->Getsch_lkh($this->session->userdata('tpp_dept_id'),date('Y-m-d'))->row();

		if ($tglshow) {
		      if ($tglshow->shiftuserrun_id) {
		          $data_tgl_lkh = array();
		          for ($i=0; $i < $jumlkh->count_inday; $i++) { 
		                $data_tgl_lkh[] = tgl_minus(date('Y-m-d'), $i);
		          }
		      }else {
		         $data_tgl_lkh = tgl_minus_lkh(date('Y-m-d'), $jumlkh->count_inday, $tglshow->hari_kerja);
		      }

		 }
		 $data_tgl ='';
		 if ($data_tgl) {
		 	$data_tgl = str_replace(['[', ']', '"',','],['', '','','+'],json_encode($data_tgl_lkh));
		 }

		

		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));
		$this->load->library('datatables');
        $this->datatables->select('a.id, tgl_lkh, jam_mulai, jam_selesai, kegiatan, hasil, jenis, a.status, verifikasi_by, b.nama as ver_nama, b.gelar_dpn as ver_gelar_dpn, b.gelar_blk as ver_gelar_blk, comment, a.jenis')
        	->from('data_lkh a')
        	->join('v_users_all b','a.verifikasi_by=b.id','left')
        	->join('lkh_rejected c','a.id=c.lkh_id','left')
        	->order_by('tgl_lkh,jam_mulai, id','desc')
        	->where('user_id', $this->session->userdata('tpp_user_id'))
        	->add_column('tgl_lkh_tabel','$1','tglInd_hrtabel(tgl_lkh)')
        	->add_column('jam_mulai','$1 <i class="icon-arrow-right16"></i> $2 $3','jm(jam_mulai), jm(jam_selesai), cek_dltabel(jenis)')
        	->add_column('status_lkh','$1','status_lkh_tabel(status, comment)')
        	->add_column('ver','$1','pejabat_ptabel(verifikasi_by, ver_nama, ver_gelar_dpn, ver_gelar_blk, status)')
        	->add_column('action','<div style="white-space: nowrap;">$1</div>', "aksi_status_lkh(id, status,$data_tgl, tgl_lkh)");
        	if ($rank1 && $rank2) {
        			$this->datatables->where("tgl_lkh::date BETWEEN '$rank1' and '$rank2'", NULL, FALSE );
        	}
        return $this->output->set_output($this->datatables->generate());
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah LKH";
		$this->breadcrumbs->push('Tambah LKH', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['tglshow'] 		= $this->m_schrun_user->CekTanggalLkh($this->session->userdata('tpp_user_id'), date('Y-m-d'))->row();
		$this->data['jumlkh']		= $this->m_sch_lkh->Getsch_lkh($this->session->userdata('tpp_dept_id'),date('Y-m-d'))->row();
		$this->data['last_jam']		= $this->m_data_lkh->cek_jam_lkh($this->session->userdata('tpp_user_id'),date('Y-m-d'))->row();
		$this->data['verifikator']	= $this->m_verifikator->GetVerifikator($this->session->userdata('tpp_user_id'))->row();
		$this->load->view('lkh/v_add', $this->data);
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('mod');

		if ($this->mod == "time" && $this->input->get('tgl_id')) {
			$tgl_id = $this->input->get('tgl_id')-1;
			$jumlkh = $this->m_sch_lkh->Getsch_lkh($this->session->userdata('tpp_dept_id'),date('Y-m-d'))->row();
			$tglshow = $this->m_schrun_user->CekTanggalLkh($this->session->userdata('tpp_user_id'), date('Y-m-d'))->row();
			if ($jumlkh && $tglshow) {
				$tglshow = $this->m_schrun_user->CekTanggalLkh($this->session->userdata('tpp_user_id'), date('Y-m-d'))->row();

				if ($tglshow->shiftuserrun_id) {
					$jam_mulai = date('H:i');
					$data_tgl_lkh = array();
					for ($i=0; $i < $jumlkh->count_inday; $i++) { 
					    $data_tgl_lkh[] = tgl_minus(date('Y-m-d'), $i);
					}
					$last_jam = $this->m_data_lkh->cek_jam_lkh($this->session->userdata('tpp_user_id'),$data_tgl_lkh[$tgl_id])->row();
					if ($last_jam) {
						$jam_mulai =  jm($last_jam->jam_selesai);
					}

				}else {
					$data_tgl_lkh = tgl_minus_lkh(date('Y-m-d'), $jumlkh->count_inday, $tglshow->hari_kerja);
					$for_hari = json_decode($tglshow->hari_kerja, true);

					$masuk_kerja = array();
					$i = 0;
					foreach ($for_hari as $v) {
					  $masuk_kerja[$for_hari[$i]['f1']] = jm($for_hari[$i]['f2']);
					  $i++;
					}

					$hari_id = tanggal_format($data_tgl_lkh[$tgl_id],'N');
					$jam_mulai = $masuk_kerja[$hari_id];

					$last_jam = $this->m_data_lkh->cek_jam_lkh($this->session->userdata('tpp_user_id'),$data_tgl_lkh[$tgl_id])->row();
					if ($last_jam) {
						$jam_mulai =  jm($last_jam->jam_selesai);
					}
				}
				$data = array('jam_masuk' => $jam_mulai, );

				$this->result = array('status' => true,
			    			   		   'message' => 'Berhasil mengabil data',
			    			   		   'data' => $data);
			}
				
		}

		if ($this->result) {
			$this->output->set_output(json_encode($this->result));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('tgl', 'tanggal kegiatan', 'required');
		$this->form_validation->set_rules('jam1', 'jam mulai kegiatan', 'required');
		$this->form_validation->set_rules('jam2', 'jam selesai kegiatan', 'required');
		$this->form_validation->set_rules('kegiatan', 'uraian kegiatan', 'required');
		$this->form_validation->set_rules('hasil', 'hasil', 'required');
		$this->form_validation->set_rules('verifikator', 'verifikator', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');	
			
			$verifikator 	  = decrypt_url($this->input->post('verifikator'),'verifikator');
			$jenis = 1;
			if ($this->input->post('dl')) {
				$jenis = 3;
			}

			if ($this->mod == "add") {
				$tgl_id 		= $this->input->post('tgl')-1;
				$data_tgl_lkh 	= array();
				$jumlkh 		= $this->m_sch_lkh->Getsch_lkh($this->session->userdata('tpp_dept_id'),date('Y-m-d'))->row();
				$tglshow 		= $this->m_schrun_user->CekTanggalLkh($this->session->userdata('tpp_user_id'), date('Y-m-d'))->row();
				if ($tglshow->shiftuserrun_id) {
					for ($i=0; $i < $jumlkh->count_inday; $i++) { 
					    $data_tgl_lkh[] = tgl_minus(date('Y-m-d'), $i);
					}
				}else {
			         $data_tgl = tgl_minus_lkh(date('Y-m-d'), $jumlkh->count_inday, $tglshow->hari_kerja);
			         foreach ($data_tgl as $v) {
			         	 $data_tgl_lkh[] = $v;
			         }
			    }

				$data = array(
							  'user_id' 		=> $this->session->userdata('tpp_user_id'),
							  'dept_id' 		=> $this->session->userdata('tpp_dept_id'),
							  'tgl_lkh' 		=> $data_tgl_lkh[$tgl_id],
							  'jam_mulai' 		=> $this->input->post('jam1'),
							  'jam_selesai' 	=> $this->input->post('jam2'),
							  'kegiatan' 		=> $this->input->post('kegiatan'),
							  'hasil' 			=> $this->input->post('hasil'),
							  'jenis' 			=> $jenis,
							  'verifikator' 	=> $verifikator,
							  'created_at' 		=> date('Y-m-d H:i:s'),
				 );
				$this->return = $this->db->insert('data_lkh',$data);
	
			}elseif ($this->mod == "edit") {
				$data = array(
							  'kegiatan' 		=> $this->input->post('kegiatan'),
							  'hasil' 			=> $this->input->post('hasil'),
							  'jenis' 			=> $jenis,
				 );
				$this->return = $this->db->update('data_lkh',$data,['id' => decrypt_url($this->input->post('id'),'datalkh_id_edit')]);
			}elseif ($this->mod == "update") {
				$datalkh_id 				= decrypt_url($this->input->post('id'),'datalkh_id_update');
				$lkh_data_update = $this->db->get_where('data_lkh', ['id' => $datalkh_id])->row();

				$data = array(
							  'user_id' 		=> $this->session->userdata('tpp_user_id'),
							  'dept_id' 		=> $this->session->userdata('tpp_dept_id'),
							  'tgl_lkh' 		=> $lkh_data_update->tgl_lkh,
							  'jam_mulai' 		=> $lkh_data_update->jam_mulai,
							  'jam_selesai' 	=> $lkh_data_update->jam_selesai,
							  'kegiatan' 		=> $this->input->post('kegiatan'),
							  'hasil' 			=> $this->input->post('hasil'),
							  'jenis' 			=> $jenis,
							  'status'			=> 4,
							  'verifikator' 	=> $lkh_data_update->verifikator,
							  'created_at' 		=> date('Y-m-d H:i:s'),
				 );
				$this->return = $this->db->insert('data_lkh',$data);
				$this->db->update('data_lkh', ['status' => 3],  ['id' => $datalkh_id]);
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

	public function edit($id)
	{
		$datalkh_id 				= decrypt_url($id,'datalkh_id_edit');
		$this->data['sub_title'] 	= "Edit LKH";
		$this->breadcrumbs->push('Edit LKH', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['verifikator']	= $this->m_verifikator->GetVerifikator($this->session->userdata('tpp_user_id'))->row();
		$this->data['datalkh']		= $this->db->get_where('data_lkh', ['id' => $datalkh_id])->row();
		$this->load->view('lkh/v_edit', $this->data);
	}

	public function update($id)
	{
		$datalkh_id 				= decrypt_url($id,'datalkh_id_update');
		$this->data['sub_title'] 	= "Perubahan LKH";
		$this->breadcrumbs->push('Perubahan LKH', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['verifikator']	= $this->m_verifikator->GetVerifikator($this->session->userdata('tpp_user_id'))->row();
									  $this->db->join('lkh_rejected b','a.id=b.lkh_id');
		$this->data['datalkh']		= $this->db->get_where('data_lkh a', ['a.id' => $datalkh_id])->row();
		$this->load->view('lkh/v_update', $this->data);
	}

	public function AjaxDel()
	{
		$this->output->unset_template();
		
		$this->del = $this->db->delete('data_lkh',['id' => decrypt_url($this->input->get('id'),'datalkh_id_edit')]);

		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}

	public function cetak($rank1,$rank2)
	{
		$this->output->unset_template();
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 300); //300 seconds = 5 minutes
		$this->output->unset_template();

		$rank1_ = format_tgl_eng(str_replace('_', '-', $rank1));
		$rank2_ = format_tgl_eng(str_replace('_', '-', $rank2));

		$this->data['priode']		= str_replace('_', '-', $rank1).' - '.str_replace('_', '-', $rank2);
		$this->load->library('Tpdf');
		$this->data['datalkh']		= $this->m_data_lkh->GetDatalkhRank($this->session->userdata('tpp_user_id'),$rank1_,$rank2_,1);
		$this->data['ttd_data']		= $this->m_verifikator->GetVerifikatorCetak($this->session->userdata('tpp_user_id'))->row();
		$this->data['instansi']		= $this->m_instansi->GetInstansi($this->session->userdata('tpp_dept_id'))->row();
		$this->load->view('lkh/v_cetak', $this->data);
	}



}

/* End of file Lkh.php */
/* Location: ./application/modules/Datalkh/controllers/Lkh.php */