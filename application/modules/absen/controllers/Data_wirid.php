<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created By: Irvan Aulia
 * 2023
 */

class Data_wirid extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Data Kehadiran Wirid', 'absen/data-wirid');
		$this->data['title'] = "Kehadiran";
		$this->load->model(['m_absen_wirid','m_user']);
	}
	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
		$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
	}
	public function index()
	{
		$this->data['sub_title']  = "Data Kehadiran Wirid";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('data_wirid/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();

		$rank1  	= format_tgl_eng($this->input->post('rank1'));
		$rank2  	= format_tgl_eng($this->input->post('rank2'));

		$this->load->library('datatables');
		$this->datatables->select('c.id, d.tgl_apel,c.checktime, d.ket, c.hadir')
			->from('apel_pegawai c')
			->join('sch_apel d','c.sch_apel_id=d.id')
			->order_by('d.tgl_apel','desc')
			->where('c.user_id', $this->session->userdata('tpp_user_id'))
			->where('d.jenis_apel_id',2)
			->add_column('tanggal','$1 ($2)','tglInd_hrtabel(tgl_apel),jm(checktime)')
			->add_column('cek1','$1',"Cek_upacara_hadir(1,hadir)")
			->add_column('cek2','$1',"Cek_upacara_hadir(2,hadir)")
			->add_column('cek3','$1',"Cek_upacara_hadir(3,hadir)")
			->add_column('keterangan','$1','upacara_ket(hadir)');
		if ($rank1 && $rank2) {
			$this->datatables->where("d.tgl_apel BETWEEN '$rank1' and '$rank2'", NULL, FALSE );
		}
		return $this->output->set_output($this->datatables->generate());
	}

	public function cetak()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('rank1', 'tanggal awal', 'required')
			->set_rules('rank2', 'tanggal akhir', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
			$rank1 = format_tgl_eng(str_replace('_', '-', $this->input->post('rank1')));
			$rank2 = format_tgl_eng(str_replace('_', '-', $this->input->post('rank2')));

//			var_dump($rank1);
//			var_dump($rank2);
//			die();

			$jum_hari = jumlah_hari_rank($rank1, $rank2);
			if ($jum_hari > 365) {
				echo 'maksimat tanggal yang diizinkan 365 hari';
			}else{

				$this->data['user']		= $this->m_user->GetDetailBiodata($this->session->userdata('tpp_user_id'))->row();
				$this->data['data_wirid'] = $this->m_absen_wirid->GetAbsenWirid($this->session->userdata('tpp_user_id'), $rank1, $rank2)->result();
				$this->data['jum_hari']	= $jum_hari;
				$this->data['rank1'] 	= $rank1;
				$this->data['priode']	= tgl_ind_bulan($rank1).' s/d '.tgl_ind_bulan($rank2);
				$this->load->library('Tpdf');
				$this->load->view('data_wirid/v_cetak', $this->data);

//				var_dump($this->data['data_wirid']);die();

			}
		}else {
			echo  validation_errors();
		}
	}


}
