<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Rlembur extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Laporan Lembur', 'report/rlembur');
		$this->data['title'] = "Laporan Umum";
		$this->load->model(['m_instansi','m_pejabat_instansi','m_absen']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->css('public/themes/plugin/clock/dist/bootstrap-clockpicker.min.css');
		$this->load->js('public/themes/plugin/clock/dist/bootstrap-clockpicker.min.js');
		$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
		$this->load->js('public/themes/plugin/datatables/dataTables.rowsGroup.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Laporan Lembur";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('rlembur/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		//$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$user_id  	= $this->input->post('pegawai');
		$user_id_in =array();
		if ($user_id) {
			foreach ($user_id as $r_v ) {
				$user_id_in[] = $r_v;
			}
		}

		$hari_id  	= $this->input->post('hari');
		$hari_id_in =array();
		if ($hari_id) {
			foreach ($hari_id as $r_v ) {
				$hari_id_in[] = $r_v;
			}
		}
		
		$rank1  	= format_tgl_eng($this->input->post('rank1'));
		$rank2  	= format_tgl_eng($this->input->post('rank2'));

		$rank1_  	= date('Y-m-d');
		$rank2_  	= date('Y-m-d');

		if ($rank1 && $rank2) {
			$rank1_  	= $rank1;
			$rank2_  	= $rank2;
		}
		$this->load->library('datatables');
		//select
        $this->datatables->select("nama,gelar_dpn,gelar_blk,nip, tanggal,jam_masuk,jam_pulang,id, start_time, end_time, start_time_shift, end_time_shift, daysoff_id")
        	->from("v_absen_lembur");
        	$this->datatables->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)');
        	$this->datatables->add_column('tanggal','$1','tglInd_hrtabel(tanggal)');
        	$this->datatables->add_column('jam_masuk_tabel','$1','jm(jam_masuk)');
        	$this->datatables->add_column('jam_pulang_tabel','$1','jm(jam_pulang)');
        	$this->datatables->add_column('jumlah','$1','jumlah_lembur(jam_masuk,jam_pulang,start_time, end_time,daysoff_id,start_time_shift, end_time_shift)');;
        	 $this->datatables->where("tanggal BETWEEN '$rank1_' and '$rank2_'", NULL, FALSE );
        	 if ($user_id_in) {
		        $this->datatables->where_in('id', $user_id_in);
		     }else {
		     	 $this->datatables->where_in('id','0');
		     }
		     if ($hari_id_in) {
		        $this->datatables->where_in('hari', $hari_id_in);
		     }else {
		     	 $this->datatables->where_in('hari','0');
		     }
        return $this->output->set_output($this->datatables->generate());
	}

	public function cetak()
	{
		$this->output->unset_template();
		$dept_id = decrypt_url($this->input->post('instansi'),'instansi');
		

		$this->form_validation->set_rules('instansi', 'nama instansi', 'required')
							  ->set_rules('pegawai[]', 'pegawai', 'required')
							  ->set_rules('rank1', 'tanggal awal', 'required')
							  ->set_rules('rank2', 'tanggal akhir', 'required')
							  ->set_rules('hari[]', 'hari', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
			$rank1 = format_tgl_eng(str_replace('_', '-', $this->input->post('rank1')));
			$rank2 = format_tgl_eng(str_replace('_', '-', $this->input->post('rank2')));
			$jum_hari = jumlah_hari_rank($rank1, $rank2);
			if ($jum_hari > 31) {
				echo 'maksimat tanggal yang diizinkan 31 hari';
			}else{
				$user_id  	= $this->input->post('pegawai');
				$user_id_in =array();
				if ($user_id) {
					foreach ($user_id as $r_v ) {
						$user_id_in[] = $r_v;
					}
				}
				$hari_id  	= $this->input->post('hari');
				$hari_id_in =array();
				if ($hari_id) {
					foreach ($hari_id as $r_v ) {
						$hari_id_in[] = $r_v;
					}
				}

				$this->data['jum_hari']	= $jum_hari;
				$this->data['rank1'] 	= $rank1;
				$this->data['pegawai_absen'] = $this->m_absen->PegawaiAbsenQueryLembur($user_id_in, $rank1, $rank2,$hari_id_in)->result();
				$this->data['priode']		= tgl_ind_bulan($rank1).' s/d '.tgl_ind_bulan($rank2);
				$this->data['datainstansi'] = $this->m_pejabat_instansi->GetPajabatByInstansi($dept_id, 7)->row();
				$this->load->library('Tpdf');
				$this->load->view('rlembur/v_cetak', $this->data);
			}
		}else {
			echo  validation_errors();
		}
	}


}

/* End of file Rlembur.php */
/* Location: ./application/modules/report/controllers/Rlembur.php */