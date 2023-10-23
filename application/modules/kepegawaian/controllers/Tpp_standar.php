<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created By: Irvan Aulia
 * 2023
 */

class Tpp_standar extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Besaran TPP Sesuai Standar', 'kepegawaian/tpp_pegawai');
		$this->data['title'] = "Kepegawaian";
		$this->load->model(['m_absen','m_sch_run','m_user','m_instansi','m_tpp']);
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
		$dept_id = $this->session->userdata('tpp_dept_id');

		$bulan_nama = [
			'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
			'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
		];

		$this->data['nama_bulan'] = $bulan_nama;
		$this->data['sub_title']  = "Besaran TPP Sesuai Standar";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['laporan_tahun'] = $this->m_sch_run->GetTahun()->result();
		$this->load->view('tpp_standar/v_index', $this->data);

	}


	public function indexJson()
	{
		$this->output->unset_template();


		$this->load->library('datatables');

		$this->datatables->select('id, disiplin_kerja, produktivitas_kerja, tahun, bulan')
			->from('tpp_standar')
			->order_by('id','desc')

			//->add_column('id','<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>','id')
			->add_column('disiplin_kerja','<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>','format_persen(disiplin_kerja)')
			->add_column('produktivitas_kerja','<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>','format_persen(produktivitas_kerja)')
			->add_column('tahun','<div class="m-0 p-1 panel-geser" style="max-height: 100px;border:none;">$1</div>','format_date(bulan,tahun)')
			->add_column('action', '
			<span class="confirm-aksi list-icons-item text-danger-600 ml-2 msclick" style="display: flex; align-items: center;" id="$1" msg="yakin ingin menghapus data ini">
				<i class="icon-bin" style="margin: auto;"></i>
			</span>', 'id');

		return $this->output->set_output($this->datatables->generate());
	}

	public function edit($id){


		$data_tpp = $this->m_tpp->get_tpp_one($id)->row();

		$bulan_nama = [
			'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
			'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
		];

		$this->data['nama_bulan'] = $bulan_nama;
		$this->data['data_tpp'] = $data_tpp;
		$this->data['sub_title']  = "Edit Standar TPP";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['laporan_tahun'] = $this->m_sch_run->GetTahun()->result();
		$this->load->view('tpp_standar/v_edit', $this->data);
	}

	public function edit_data(){
		$this->output->unset_template();
		$id_standar = $this->input->post('id_standar');
		$disiplin_kerja = $this->input->post('disiplin_kerja');
		$produktivitas_kerja = $this->input->post('produktivitas_kerja');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');


		$data = [
			'disiplin_kerja' => $disiplin_kerja,
			'produktivitas_kerja' => $produktivitas_kerja,
			'bulan' => $bulan,
			'tahun' => $tahun
		];

		$return = $this->db->update('tpp_standar', $data, ['id' => $id_standar]);

		if ($return) {
			$result = array('status'   => true,
				'message' => 'Data berhasil disimpan',);
		}else{
			$result = array('status'   => false,
				'message' => 'Data gagal disimpan',);
		}

		if ($result) {
			$this->output->set_output(json_encode($result));
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}

	public function AjaxSave()
	{
		$this->output->unset_template();

		$data = array(
			'disiplin_kerja'		=> $this->input->post('disiplin_kerja'), //
			'produktivitas_kerja'	=> $this->input->post('produktivitas_kerja'),
			'bulan'					=> $this->input->post('bulan'),
			'tahun'					=> $this->input->post('tahun'),
			'created_at' 	=> date('Y-m-d H:i:s'),

		);

		$this->db->insert('tpp_standar', $data);
		echo "Berhasil";

	return redirect()->to('/kepegawaian/tpp_standar');
	}


	public function ajaxDel()
{
    $this->output->unset_template();
    $id = $this->input->get('id');
    
    $del = $this->db->delete('tpp_standar', ['id' => $id]);

    if ($del) {
        $this->output->set_output(json_encode(['status' => TRUE, 'message' => 'Data berhasil dihapus.']));
    } else {
        $this->output->set_output(json_encode(['status' => FALSE, 'message' => 'Gagal dihapus.']));    
    }
}


}

/* End of file Absen_upacara.php */
/* Location: ./application/modules/Kepegawaian/controllers/Absen_upacara.php */
