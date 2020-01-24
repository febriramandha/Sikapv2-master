<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Worship extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Data Ibadah', 'datalkh/lkh');
		$this->data['title'] = "Data Ibadah";
		$this->load->model(['m_schrun_user','m_sch_lkh','m_ibadah','m_verifikator','m_instansi','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
		$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
		$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
		$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
	}

	public function index()
	{
		$agam_cek = $this->db->select('agama_id')->get_where('sp_pegawai',['user_id' => $this->session->userdata('tpp_user_id')])->row();

		if ($agam_cek) {
				if ($agam_cek->agama_id == 1 || $agam_cek->agama_id == 0 || $agam_cek->agama_id == '') {
					$this->index_muslim();
				}else{
					$this->index_nonmuslim();
				}
		}else {
			$this->index_muslim();
		}
	}

	public function index_muslim()
	{
		$this->data['sub_title']  = "Data Ibadah";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('worship/v_index', $this->data);
	}

	public function index_nonmuslim()
	{
		$this->data['sub_title']  = "Data Ibadah";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('worship/non_muslim/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$tglshow = $this->m_schrun_user->CekTanggalLkh($this->session->userdata('tpp_user_id'), date('Y-m-d'))->row();
		$jumlkh  = $this->m_sch_lkh->Getsch_lkh($this->session->userdata('tpp_dept_id'),date('Y-m-d'))->row();
		$data_tgl_lkh ='';
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
		 if ($data_tgl_lkh) {
		 		$data_tgl = str_replace(['[', ']', '"',','],['', '','','+'],json_encode($data_tgl_lkh));
		 }
		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));
		$this->load->library('datatables');
        $this->datatables->select('a.id, tgl_ibadah, ibadah_id, t_zuhur, t_ashar, b.nama as ket')
        	->from('ibadah_muslim a')
        	->join('ibadah b','a.ibadah_id=b.id','left')
        	->order_by('tgl_ibadah','desc')
        	->where('user_id', $this->session->userdata('tpp_user_id'))
        	->add_column('tgl_ibadah_tabel','$1','tglInd_hrtabel(tgl_ibadah)')
        	->add_column('action','<div style="white-space: nowrap;">$1</div>', "aksi_status_ibadah(id, $data_tgl, tgl_ibadah)");
        	if ($rank1 && $rank2) {
        			$this->datatables->where("tgl_ibadah::date BETWEEN '$rank1' and '$rank2'", NULL, FALSE );
        	}
        return $this->output->set_output($this->datatables->generate());
	}

	public function indexJsonNonMuslim()
	{
		$this->output->unset_template();
		$tglshow = $this->m_schrun_user->CekTanggalLkh($this->session->userdata('tpp_user_id'), date('Y-m-d'))->row();
		$jumlkh  = $this->m_sch_lkh->Getsch_lkh($this->session->userdata('tpp_dept_id'),date('Y-m-d'))->row();
		$data_tgl_lkh ='';
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
		 if ($data_tgl_lkh) {
		 		$data_tgl = str_replace(['[', ']', '"',','],['', '','','+'],json_encode($data_tgl_lkh));
		 }
		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));
		$this->load->library('datatables');
        $this->datatables->select('id, tgl_ibadah, kegiatan, tempat')
        	->from('ibadah_nonmus')
        	->order_by('tgl_ibadah','desc')
        	->where('user_id', $this->session->userdata('tpp_user_id'))
        	->add_column('tgl_ibadah_tabel','$1','tglInd_hrtabel(tgl_ibadah)')
        	->add_column('action','<div style="white-space: nowrap;">$1</div>', "aksi_status_ibadah(id, $data_tgl, tgl_ibadah)");
        	if ($rank1 && $rank2) {
        			$this->datatables->where("tgl_ibadah::date BETWEEN '$rank1' and '$rank2'", NULL, FALSE );
        	}
        return $this->output->set_output($this->datatables->generate());
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Ibadah";
		$this->breadcrumbs->push('Tambah Ibadah', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['tglshow'] 		= $this->m_schrun_user->CekTanggalLkh($this->session->userdata('tpp_user_id'), date('Y-m-d'))->row();
		$this->data['jumlkh']		= $this->m_sch_lkh->Getsch_lkh($this->session->userdata('tpp_dept_id'),date('Y-m-d'))->row();
		$this->data['verifikator']	= $this->m_verifikator->GetVerifikator($this->session->userdata('tpp_user_id'))->row();
		$this->data['user']			= $this->m_user->GetDetailBiodata($this->session->userdata('tpp_user_id'))->row();

		$agam_cek = $this->db->select('agama_id')->get_where('sp_pegawai',['user_id' => $this->session->userdata('tpp_user_id')])->row();

		if ($agam_cek) {
				if ($agam_cek->agama_id == 1 || $agam_cek->agama_id == 0 || $agam_cek->agama_id == '') {
					$this->load->view('worship/v_add', $this->data);
				}else{
					$this->load->view('worship/non_muslim/v_add', $this->data);
				}
		}else {
			$this->load->view('worship/v_add', $this->data);
		}
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
					$data_tgl_lkh = array();
					for ($i=0; $i < $jumlkh->count_inday; $i++) { 
					    $data_tgl_lkh[] = tgl_minus(date('Y-m-d'), $i);
					}

				}else {
					$data_tgl_lkh = tgl_minus_lkh(date('Y-m-d'), $jumlkh->count_inday, $tglshow->hari_kerja);
				}

				$tgl_cek = $data_tgl_lkh[$tgl_id];

				$data_ibadah = $this->db->get_where('ibadah_muslim',['tgl_ibadah' => $tgl_cek, 'user_id' => $this->session->userdata('tpp_user_id')])->row();

				$cek_zuhur = '';
				$cek_ashar = '';
				$cek_pms   = '';
				$cek_dl    = '';
				$t_zuhur   = '';
				$t_ashar   = '';

				$ibadah_id = '';
				if ($data_ibadah) {
					$ibadah_id = $data_ibadah->ibadah_id;
				}

				if ($ibadah_id == 1) {
					$cek_zuhur = 'checked';
					$t_zuhur   = $data_ibadah->t_zuhur;
				}else if($ibadah_id == 2) {
					$cek_ashar = 'checked';
					$t_ashar   = $data_ibadah->t_ashar;
				}elseif ($ibadah_id == 3) {
					$cek_zuhur = 'checked';
					$cek_ashar = 'checked';
					$t_zuhur   = $data_ibadah->t_zuhur;
					$t_ashar   = $data_ibadah->t_ashar;
				}elseif ($ibadah_id == 4) {
					$cek_pms   = 'checked';
				}elseif ($ibadah_id == 5) {
					$cek_dl   = 'checked';
				}

				$data_ibadah_ = array( 'cek_zuhur'  => $cek_zuhur,
										'cek_ashar' => $cek_ashar, 
									    'cek_pms' 	=> $cek_pms,
									    'cek_dl' 	=> $cek_dl,
									    't_zuhur' 	=> $t_zuhur,
									    't_ashar' 	=> $t_ashar,
									    'hari' 		=> date('D', strtotime($tgl_cek)), );

				$data = array('data_ibadah' => $data_ibadah_, );

				$this->result = array('status' => true,
			    			   		   'message' => 'Berhasil mengabil data',
			    			   		   'data' => $data);
			}
				
		}elseif ($this->mod == "ibadah_nonmuslim") {
			$tgl_id = $this->input->get('tgl_id')-1;
			$jumlkh = $this->m_sch_lkh->Getsch_lkh($this->session->userdata('tpp_dept_id'),date('Y-m-d'))->row();
			$tglshow = $this->m_schrun_user->CekTanggalLkh($this->session->userdata('tpp_user_id'), date('Y-m-d'))->row();
			if ($jumlkh && $tglshow) {
				$tglshow = $this->m_schrun_user->CekTanggalLkh($this->session->userdata('tpp_user_id'), date('Y-m-d'))->row();

				if ($tglshow->shiftuserrun_id) {
					$data_tgl_lkh = array();
					for ($i=0; $i < $jumlkh->count_inday; $i++) { 
					    $data_tgl_lkh[] = tgl_minus(date('Y-m-d'), $i);
					}

				}else {
					$data_tgl_lkh = tgl_minus_lkh(date('Y-m-d'), $jumlkh->count_inday, $tglshow->hari_kerja);
				}

				$tgl_cek = $data_tgl_lkh[$tgl_id];

				$data_ibadah = $this->db->get_where('ibadah_muslim',['tgl_ibadah' => $tgl_cek, 'user_id' => $this->session->userdata('tpp_user_id')])->row();


				$data_ibadah = $this->db->get_where('ibadah_nonmus',['tgl_ibadah' => $tgl_cek, 'user_id' => $this->session->userdata('tpp_user_id')])->row();
				$this->result = array('status' => true,
			    			   		   'message' => 'Berhasil mengabil data',
			    			   		   'data' => $data_ibadah);
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
		$this->form_validation->set_rules('verifikator', 'verifikator', 'required');

		$zuhur 		= $this->input->post('zuhur');
 		$asar 		= $this->input->post('ashar');
 		$haid 		= $this->input->post('pms');
 		$dl 		= $this->input->post('dl');
 		$tempat1 	= $this->input->post('t_zuhur');
 		$tempat2 	= $this->input->post('t_ashar');

 		$pesan ='';
 		if ($zuhur && $asar && $dl && $haid || $zuhur && $asar && $dl || $zuhur && $dl || $zuhur && $haid || $zuhur && $asar && $haid || $asar && $dl || $asar && $haid) {
 			$pesan = "peringatan! pastikan isi data dengan benar";
 			
 		}elseif($zuhur && $asar){
			$this->form_validation->set_rules('t_zuhur', 'Tempat Sholat Zhuhur', 'required');
			$this->form_validation->set_rules('t_ashar', 'Tempat Sholat Ashar', 'required');
		}elseif($zuhur && !$asar){
			$this->form_validation->set_rules('t_zuhur', 'Tempat Sholat Zhuhur', 'required');
		}elseif ($asar && !$zuhur) {
			$this->form_validation->set_rules('t_ashar', 'Tempat Sholat Ashar', 'required');
		}elseif ($haid) {
			$this->form_validation->set_rules('pms', 'Berhalangan ', 'required');
		}elseif ($dl) {
			$this->form_validation->set_rules('dl', 'dl ', 'required');
		}else {
			$this->form_validation->set_rules('data', 'peringatan! pastikan isi data ibadah', 'required');
		}

		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');	
			
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

		    $tanggal  = $data_tgl_lkh[$tgl_id];

		    $cek = $this->db->get_where('ibadah_muslim',['user_id' => $this->session->userdata('tpp_user_id'), 'tgl_ibadah' => $tanggal])->row();

			    if ($cek) {
			    	if ($zuhur && $asar) {
			 			$data1 = array (
							'ibadah_id'			 => 3,
							't_zuhur' 	     => $tempat1,
							't_ashar' 	     => $tempat2,
							
						);
		 		}elseif ($zuhur && !$asar) {
			 				$data1 = array (
								'ibadah_id'		=> 1,
								't_zuhur' 	    => $tempat1,
								't_ashar' 	    => '',
							);
		 		}elseif ($asar && !$zuhur) {
		 				$data1 = array (
							'ibadah_id'		=> 2,
							't_zuhur' 	    => '',
							't_ashar' 	    => $tempat2,
							
						);
		 		}elseif ($haid) {
			 			$data1 = array (
								'ibadah_id'	=> 4,
								't_ashar' 	=> "Berhalangan",
								't_zuhur' 	=> "Berhalangan",
							);
		 		}else {
	 					$data1 = false;
		 		}

		 		if ($data1) {
		 			$this->db->where(['user_id' => $this->session->userdata('tpp_user_id') , 'tgl_ibadah' => $tanggal]);
					$this->return = $this->db->update('ibadah_muslim', $data1);
		 		}
		    }else {
					$data = array (
								'user_id'		 	 => $this->session->userdata('tpp_user_id'),
								'dept_id' 			 => $this->session->userdata('tpp_dept_id'),
								'tgl_ibadah'         => $tanggal,
								'hadir' 	     	 => 1,
								'created_at' 		 => date('Y-m-d H:i:s'),
								'created_by' 		 => $this->session->userdata('tpp_user_id'),
							);

				 	if ($zuhur && $asar) {
				 		$id = 3;
				 		$tem1 = $tempat1;
				 		$tem2 = $tempat2;
				 	}elseif ($zuhur && !$asar) {
				 		$id = 1;
				 		$tem1 = $tempat1;
				 		$tem2 = '';
				 	}elseif ($asar && !$zuhur) {
				 		$id = 2;
				 		$tem1 = '';
				 		$tem2 = $tempat2;
				 	}elseif ($haid) {
				 		$id = 4;
				 		$tem1 = 'Berhalangan';
				 		$tem2 = 'Berhalangan';
				 	}else{
				 		$id = false;
				 	}

					$data['ibadah_id'] = $id;
			 		$data['t_zuhur']   = $tem1;
			 		$data['t_ashar']   = $tem2;

			 		if ($id) {
			 			$this->return = $this->db->insert('ibadah_muslim', $data);
			 		}
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
		
		if ($this->input->get('mod')=="nonmuslim") {
			$this->del = $this->db->delete('ibadah_nonmus',['id' => decrypt_url($this->input->get('id'),'ibadahmus_id_edit')]);
		}else {
			$this->del = $this->db->delete('ibadah_muslim',['id' => decrypt_url($this->input->get('id'),'ibadahmus_id_edit')]);
		}
		
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

		$rank1 = format_tgl_eng(str_replace('_', '-', $rank1));
		$rank2 = format_tgl_eng(str_replace('_', '-', $rank2));
		$this->data['priode']		= tgl_ind_bulan($rank1).' - '.tgl_ind_bulan($rank2);
		$this->load->library('Tpdf');
		$this->data['ttd_data']		= $this->m_verifikator->GetVerifikatorCetak($this->session->userdata('tpp_user_id'))->row();
		$this->data['instansi']		= $this->m_instansi->GetInstansi($this->session->userdata('tpp_dept_id'))->row();

		$agam_cek = $this->db->select('agama_id')->get_where('sp_pegawai',['user_id' => $this->session->userdata('tpp_user_id')])->row();

		if ($agam_cek) {
				if ($agam_cek->agama_id == 1 || $agam_cek->agama_id == 0 || $agam_cek->agama_id == '') {
					$this->data['data_ibadah']		= $this->m_ibadah->GetDataIbadahRank($this->session->userdata('tpp_user_id'),$rank1,$rank2);
					$this->load->view('worship/v_cetak', $this->data);
				}else{
					$this->data['data_ibadah']		= $this->m_ibadah->GetDataIbadahRankNonmuslim($this->session->userdata('tpp_user_id'),$rank1,$rank2);
					$this->load->view('worship/non_muslim/v_cetak', $this->data);
				}
		}else {
			$this->data['data_ibadah']		= $this->m_ibadah->GetDataIbadahRank($this->session->userdata('tpp_user_id'),$rank1,$rank2);
			$this->load->view('worship/v_cetak', $this->data);
		}
	}

	public function AjaxSaveNonMuslim()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('tgl', 'tanggal kegiatan', 'required');
		$this->form_validation->set_rules('kegiatan', 'kegiatan', 'required');
		$this->form_validation->set_rules('tempat', 'tempat', 'required');
		$this->form_validation->set_rules('verifikator', 'verifikator', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
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

			    $tanggal  = $data_tgl_lkh[$tgl_id];

			    $cek = $this->db->get_where('ibadah_nonmus',['user_id' => $this->session->userdata('tpp_user_id'), 'tgl_ibadah' => $tanggal])->row();

			    if ($cek) {
			    	$data = array(
							  'kegiatan' 		=> $this->input->post('kegiatan'),
							  'tempat' 			=> $this->input->post('tempat'),
				 	);
					$this->return = $this->db->update('ibadah_nonmus',$data,['id' => $cek->id]);
			    }else {
			    	$data = array(
			    			  'user_id'		    => $this->session->userdata('tpp_user_id'),
			    			  'tgl_ibadah'		=> $tanggal,
							  'kegiatan' 		=> $this->input->post('kegiatan'),
							  'tempat' 			=> $this->input->post('tempat'),
							  'created_at' 		=> date('Y-m-d H:i:s'),
							  'created_by' 		=> $this->session->userdata('tpp_user_id'),
				 	);
					$this->return = $this->db->insert('ibadah_nonmus',$data);
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

/* End of file Worship.php */
/* Location: ./application/modules/Datalkh/controllers/Worship.php */