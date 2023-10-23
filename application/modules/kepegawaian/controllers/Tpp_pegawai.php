<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Created By: Irvan Aulia
 * 2023
 */

class Tpp_pegawai extends App_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Rekapitulasi Penerimaan TPP', 'kepegawaian/tpp_pegawai');
		$this->data['title'] = "Kepegawaian";
		$this->load->model(['m_absen', 'm_sch_run', 'm_user', 'm_instansi', 'm_tpp', 'm_verifikator', 'm_pejabat_instansi']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/plugin/autocomplete/dist/latest/bootstrap-autocomplete.js');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
		$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
		$this->load->js('public/themes/plugin/ckeditor/ckeditor.js');
		$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
		$this->load->js('public/themes/plugin/rupiah/rupiah.js');
	}


	public function index()
	{
		$dept_id = $this->session->userdata('tpp_dept_id');
		$this->db->select('*')
			->from('pegawai_tpp')
			->join('mf_users', 'pegawai_tpp.id_pegawai=mf_users.id');
		$data_tpp = $this->db->get()->result();

		$data_user_isi = array();

		$data_exlclude = null;

		$this->db->select('*')
			->from('v_users_all')
			->where_not_in('id', $data_exlclude)
			->where('pns', '1')
			->where('key > 0')
			->where('att_status', 1)
			->where('dept_id', $dept_id);

		$data_pegawai = $this->db->get()->result();

		$data_tpp_standar = $this->db->get('tpp_standar')->result();

		$this->data['listpeg'] = $data_pegawai;
		$this->data['listtppstandar'] = $data_tpp_standar;
		$this->data['listtpp'] = $data_tpp;
		$this->data['sub_title'] = "Rekapitulasi Penerimaan TPP";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['laporan_tahun'] = $this->m_sch_run->GetTahun()->result();
		$this->load->view('tpp_pegawai/v_index', $this->data);
	}

	public function add()
	{

		$this->data['sub_title'] 	= "Tambah Data Penerima TPP";
		$this->breadcrumbs->push('', '/');
		$this->data['breadcrumb'] = $this->breadcrumbs->show();

		$dept_id = $this->session->userdata('tpp_dept_id');
		$this->db->select('*')
			->from('pegawai_tpp')
			->join('mf_users', 'pegawai_tpp.id_pegawai=mf_users.id');
		$data_tpp = $this->db->get()->result();

		$data_user_isi = array();

		$data_exlclude = null;

		$this->db->select('*')
			->from('v_users_all')
			->where_not_in('id', $data_exlclude)
			->where('pns', '1')
			->where('key > 0')
			->where('att_status', 1)
			->where('dept_id', $dept_id);

		$data_pegawai = $this->db->get()->result();

		$data_tpp_standar = $this->db->get('tpp_standar')->result();

		$this->data['listpeg'] = $data_pegawai;
		$this->data['listtppstandar'] = $data_tpp_standar;
		$this->data['listtpp'] = $data_tpp;

		$this->load->view('tpp_pegawai/v_add', $this->data);
	}

	public function AjaxGet()
	{
		$this->output->unset_template();

		$bulan_id = decrypt_url($this->input->post('bulan'), 'standar');
		$get_data = $this->m_tpp->get_tpp_one($bulan_id)->first_row();
		$get_month = $get_data->bulan;
		$get_year = $get_data->tahun;

		$this->load->library('datatables');

		$dept_id = $this->session->userdata('tpp_dept_id');
		$this->datatables->select('a.id, a.id_pegawai, a.bbebankerja, a.bkondisikerja, a.bkelangkaan, a.totaltpp, a.potongan_disiplin, a.potongan_produktivitas, a.hasiltpp, b.nama, b.nip, (c.disiplin_kerja/100 * a.totaltpp) as disiplin_kerja, (c.produktivitas_kerja/100 * a.totaltpp) as produktivitas_kerja, a.potonganpph, a.potonganbpjs, a.setelahpotongpph, a.jml_setelah_potongbpjs, a.potonganzakat, a.jml_setelah_potongzakat, SUM(a.bbebankerja) OVER () as total_bbebankerja_sum')
			->from('pegawai_tpp a')
			->join('mf_users b', 'a.id_pegawai=b.id')
			->join('tpp_standar c', 'a.tpp_standar=c.id')
			->where('b.att_status', 1)
			->where('b.dept_id', $dept_id)
			->order_by('a.bbebankerja', 'desc');



		$this->datatables->order_by('a.id', 'asc')
			->add_column('pegawai2', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', 'nama_icon_nip(nama,nip)')
			->add_column('bbebankerja', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none; ">$1</div>', 'format_rupiah(bbebankerja)')
			->add_column('bkondisikerja', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', 'format_rupiah(bkondisikerja)')
			->add_column('bkelangkaan', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', 'format_rupiah(bkelangkaan)')
			->add_column('totaltpp2', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', "format_rupiah(totaltpp)")
			->add_column('disiplin_kerja', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', "format_rupiah(disiplin_kerja)")
			->add_column('produktivitas_kerja', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', "format_rupiah(produktivitas_kerja)")
			->add_column('potongan_disiplin', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', "format_rupiah(potongan_disiplin)")
			->add_column('potongan_produktivitas', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', "format_rupiah(potongan_produktivitas)")
			->add_column('hasiltpp', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', "format_rupiah(hasiltpp)")
			->add_column('potongan_bpjs', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', "format_rupiah(potonganbpjs)")
			->add_column('setelah_potongbpjs', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', "format_rupiah(jml_setelah_potongbpjs)")
			->add_column('potongan_pph', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', "format_rupiah(potonganpph)")
			->add_column('setelah_potongpph', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', "format_rupiah(setelahpotongpph)")
			->add_column('potongan_zakat', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', "format_rupiah(potonganzakat)")
			->add_column('setelah_potongzakat', '<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>', "format_rupiah(jml_setelah_potongzakat)")
			->add_column('aksi', '
			<span class="confirm-aksi list-icons-item text-danger-600 ml-2 msclick" style="display: flex; align-items: center;" id="$1" msg="yakin ingin menghapus data ini">
				<i class="icon-bin" style="margin: auto;"></i>
			</span>', 'id');

		if ($bulan_id) {
			$this->datatables->where(array('bulan' => $get_month, 'tahun' => $get_year));
		}

		return $this->output->set_output($this->datatables->generate());
	}


	public function AjaxSave()
	{
		$this->output->unset_template();

		$this->form_validation->set_rules('pegawai2', 'Pegawai', 'required')
			->set_rules('tpp_standar', 'TPP Standar', 'required')
			->set_rules('bebanKerja', 'Beban Kerja', 'required');


		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ', '</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {

			$pegawai_id  	= decrypt_url($this->input->post('pegawai2'), 'pegawai');
			$standar  	= decrypt_url($this->input->post('tpp_standar'), 'standar');
			$cek_tpp = $this->m_tpp->get_tpp_one($standar)->result();

			$tahun  	= $cek_tpp[0]->tahun;
			$bulan  	= $cek_tpp[0]->bulan;

			if ($tahun && $bulan) {
				$hari_ini 		= "$tahun-$bulan-01";
				$rank1 			= date('Y-m-01', strtotime($hari_ini));
				$rank2 			= date('Y-m-t', strtotime($hari_ini));
			} else {
				$rank1  	   = date('Y-m-d');
				$rank2  	   = date('Y-m-d');
			}

			$this->m_tpp->get_besar_tpp($rank1, $rank2, $pegawai_id);
			$cek = $this->db->get()->result()[0];

			$total_persen_aspek_disiplin = total_persen_aspek_disiplin($cek->json_absen);
			$persen_tidak_buat_lkh = persen_tidak_buat_lkh($cek->json_jadwal_lkh, $cek->jumlah_laporan);

			$bebankerja = preg_replace("/[^0-9]/", "", $this->input->post('bebanKerja'));
			$bkondisikerja = preg_replace("/[^0-9]/", "", $this->input->post('kondisiKerja'));
			$bkelangkaan = preg_replace("/[^0-9]/", "", $this->input->post('kelangkaanProfesi'));

			if ($bkondisikerja != FALSE) {
				$bkondisikerja;
			} else {
				$bkondisikerja = 0;
			};
			if ($bkelangkaan != FALSE) {
				$bkelangkaan;
			} else {
				$bkelangkaan = 0;
			};

			//Rumus Total TPP
			$totaltpp = (int)$bebankerja + (int)$bkondisikerja + (int)$bkelangkaan;
			$disiplin_kerja = $cek_tpp[0]->disiplin_kerja * $totaltpp / 100;
			$produktivitas_kerja = $cek_tpp[0]->produktivitas_kerja * $totaltpp / 100;
			$aspek_disiplin = $disiplin_kerja * $total_persen_aspek_disiplin / 100;
			$aspek_tidak_buat_lkh = $produktivitas_kerja * $persen_tidak_buat_lkh / 100;
			$potongan_disiplin = $aspek_disiplin;
			$potongan_produktivitas = $aspek_tidak_buat_lkh;
			$hasildisiplin = $disiplin_kerja - $potongan_disiplin;
			$hasilproduktivitas = $produktivitas_kerja - $potongan_produktivitas;
			$hasiltpp1 = $hasildisiplin + $hasilproduktivitas;
			$hasil_tpp = $hasiltpp1;

			$golongan = $this->m_tpp->get_golongan_pegawai($pegawai_id);
			$golongan_id = $this->db->get()->result()[0]->golongan_id;

			//Potongan BPJS
			$BPJS = 0.01;
			$PotonganBPJS = $hasil_tpp * $BPJS;
			$setelahpotong_bpjs = $hasil_tpp - $PotonganBPJS;

			//Potongan PPH
			if ($golongan_id == '1' || $golongan_id == '2' || $golongan_id == '3' || $golongan_id == '4' || $golongan_id == '5') {
				$potonganpph = $setelahpotong_bpjs * 0.15;
			} elseif ($golongan_id == '6' || $golongan_id == '7' || $golongan_id == '8' || $golongan_id == '9') {
				$potonganpph = $setelahpotong_bpjs * 0.05;
			} else {
				$potonganpph = 0;
			}

			$setelahpotong_pph = $setelahpotong_bpjs - $potonganpph;

			//Potongan Zakat
			if ($golongan_id == '1' || $golongan_id == '2' || $golongan_id == '3' || $golongan_id == '4' || $golongan_id == '5' || $golongan_id == '6' || $golongan_id == '7' || $golongan_id == '8' || $golongan_id == '9') {
				$potongan_zakat = $setelahpotong_pph * 0.025;
			} else {
				$potongan_zakat = 0;
			}

			$setelahpotong_zakat = $setelahpotong_pph - $potongan_zakat;


			$data = array(
				'id_pegawai'	=> $pegawai_id,
				'bbebankerja'	=> $bebankerja,
				'bkondisikerja'	=> $bkondisikerja,
				'bkelangkaan'	=> $bkelangkaan,
				'totaltpp'	=> $totaltpp,
				'potongan_disiplin'	=> $potongan_disiplin,
				'potongan_produktivitas'	=> $potongan_produktivitas,
				'hasiltpp'	=> $hasil_tpp,
				'potonganbpjs'	=> $PotonganBPJS,
				'jml_setelah_potongbpjs'	=> $setelahpotong_bpjs,
				'potonganpph'	=> $potonganpph,
				'setelahpotongpph'	=> $setelahpotong_pph,
				'potonganzakat'	=> $potongan_zakat,
				'jml_setelah_potongzakat'	=> $setelahpotong_zakat,
				'update_by' => $this->session->userdata('tpp_user_id'),
				'created_at' 	=> date('Y-m-d H:i:s'),
				'tpp_standar' => $standar,

			);

			$this->return = $this->db->insert('pegawai_tpp', $data);
			$this->msg = 'Data berhasil ditambah';

			if ($this->return) {
				$this->result = array(
					'status' => true,
					'message' => $this->msg
				);
			} else {
				$this->result = array(
					'status' => false,
					'message' => 'Data gagal disimpan atau data telah ada'
				);
			}
		} else {
			$this->result = array(
				'status' => false,
				'message' => validation_errors(),
			);
		}
		if ($this->result) {
			$this->output->set_output(json_encode($this->result));
		} else {
			$this->output->set_output(json_encode(['status' => FALSE, 'message' => 'Gagal mengambil data.']));
		}
	}


	public function cetak($bulan_id)
	{
		$this->output->unset_template();

		$bulan_id = decrypt_url($bulan_id, 'standar');
		$dept_id = $this->session->userdata('tpp_dept_id');
		$get_data = $this->m_tpp->get_tpp_one($bulan_id)->first_row();
		$get_month = $get_data->bulan;
		$get_year = $get_data->tahun;


		$this->load->library('Tpdf');
		$this->data['ttd_data']		= $this->m_verifikator->GetVerifikatorCetak($this->session->userdata('tpp_user_id'))->row();
		$this->data['instansi']		= $this->m_instansi->GetInstansi($dept_id)->row();
		$this->data['data_tpp']		= $this->m_tpp->getTppCetak($bulan_id, $dept_id)->result();
		$this->data['total_diterima_sum'] = $this->m_tpp->getTppCetak($bulan_id, $dept_id)->row()->total_diterima_sum;
		$this->data['priode'] = bulan($get_month) . ' ' . $get_year;
		$this->data['priode'] = bulan($get_month) . ' ' . $get_year;
		$this->data['datainstansi_kepala']  = $this->m_pejabat_instansi->GetPajabatByInstansi($dept_id, 3)->row();
		$this->load->view('tpp_pegawai/v_cetak', $this->data);
	}


	public function ajaxDel()
	{
		$this->output->unset_template();
		$id = $this->input->get('id');

		$del = $this->db->delete('pegawai_tpp', ['id' => $id]);

		if ($del) {
			$this->output->set_output(json_encode(['status' => TRUE, 'message' => 'Data berhasil dihapus.']));
		} else {
			$this->output->set_output(json_encode(['status' => FALSE, 'message' => 'Gagal dihapus.']));
		}
	}
}
/* End of file Tpp_Pegawai.php */
/* Location: ./application/modules/Kepegawaian/controllers/Tpp_pegawai.php */
