<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/


class Grafik_absen extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Grafik Kehadiran', 'mnglkh/schlkh');
		$this->data['title'] = "Laporan Khusus";
		$this->load->model(['m_instansi','m_sch_run','m_absen']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/plugin/Highcharts-7.2.0/code/highcharts.js');
    	$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
    	$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Grafik Kehadiran";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->data['laporan_tahun'] = $this->m_sch_run->GetTahun()->result();
		$this->load->view('grafik_absen/v_index', $this->data);
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('mod');
		if ($this->mod == "DataPegawai") {
			$dept_id = decrypt_url($this->input->get('id'),'instansi');
				$this->db->select('*')
						->from('v_users_all')
						->where('key > 0')
        				->where('att_status',1)
        				->where('dept_id', $dept_id);
        		$data_pegawai = $this->db->get()->result();

        		$res='<select class="form-control multiselect-clickable-groups" name="pegawai[]" multiple="multiple" data-fouc>';
        		$no = 1;
        		foreach ($data_pegawai as $row ){
		          		$res.= "<option value='".$row->id."'>($no) $row->nama/$row->nip</option>";
		          		$no++;
		          }
		        $res .='</select>
		        		<script type="text/javascript" language="javascript" > 
		        			$(".multiselect-clickable-groups").multiselect({
							    includeSelectAllOption: true,
							    enableFiltering: true,
							    enableCaseInsensitiveFiltering: true,
							    placeholder: "Pilih Pegawai",
							});
		        		</script>';

		        $this->output->set_output($res);
		}elseif ($this->mod == "Grafik") {
				$dept_id 	= decrypt_url($this->input->get('instansi'),'instansi');
				$tahun 		= $this->input->get('tahun');
				$bulan 		= $this->input->get('bulan');

				$hari_ini 		= "$tahun-$bulan-01";
	 			$rank1 			= date('Y-m-01', strtotime($hari_ini));
	 			$rank2 			= date('Y-m-t', strtotime($hari_ini));

	 			$user_id  	= $this->input->get('pegawai');
				$user_id_in = array();
				if ($user_id) {
					foreach ($user_id as $r_v ) {
						$user_id_in[] = $r_v;
					}
				}
				$this->data['pegawai_absen'] = $this->m_absen->PegawaiAbsenQueryRekapitulasi($user_id_in, $rank1, $rank2, $dept_id)->result();
				$this->load->view('grafik_absen/v_grafik', $this->data);
		}
		
	}
}

/* End of file Grafik_absen.php */
/* Location: ./application/modules/reportgk/controllers/Grafik_absen.php */