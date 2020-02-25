<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Dashboard extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Dashboard', 'dashboard');
		$this->data['title'] = "Dashboard";
		$this->load->model(['m_article','m_instansi','m_sch_run','m_absen','m_cookie']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/plugin/Highcharts-7.2.0/code/highcharts.js');
		$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
	}

	public function index()
	{
		$this->data['breadcrumb'] 		= $this->breadcrumbs->show();
		$this->data['pos']		  		= $this->m_article->GetAticleAll();

		if ($this->session->userdata('tpp_level') == 1 || $this->session->userdata('tpp_level') == 4 ) {
			$this->data['user_all']	  	  	= $this->db->select('count(*)')->get('mf_users')->row();
			$this->data['instansi_all']	  	= $this->db->select('count(*)')->get('mf_departments')->row();
			$this->data['user_aktif_all']	= $this->db->select('count(*)')->where('status',1)->get('users_login')->row();
			$this->data['user_admin_all']	= $this->db->select('count(*)')->where('level',2)->or_where('level', 1)->get('users_login')->row();
			$this->data['user_aktif_all']	= $this->db->select('count(*)')->where('status',1)->get('users_login')->row();
		}
		$this->data['instansi']	  		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->data['laporan_tahun'] 	= $this->m_sch_run->GetTahun()->result();
		if ($this->session->userdata('tpp_level') == 1) {
			$this->data['online']	 		= $this->m_cookie->cekOnline();
		}
		$this->load->view('app/dashboard/v_dashboard', $this->data);
	}

	public function jadwalJson()
	{
		$this->output->unset_template();
		$this->load->library('datatables');

		$rank1 = date('Y-m-d');
		$rank2 = tgl_plus($rank1, 6);

		$this->datatables->select('a.id, 
							rentan_tanggal, 
							b.start_time, 
							b.end_time, 
							b.check_in_time1, 
							b.check_in_time2, 
							b.check_out_time1, 
							b.check_out_time2,
							c.start_time as start_time_shift, 
							c.end_time as end_time_shift, 
							c.check_in_time1 as check_in_time1_shift, 
							c.check_in_time2 as check_in_time2_shift, 
							c.check_out_time1 as check_out_time1_shift, 
							c.check_out_time2 as check_out_time2_shift,
							d.start_time as start_time_notfixed, 
							d.end_time as end_time_notfixed, 
							d.check_in_time1 as check_in_time1_notfixed, 
							d.check_in_time2 as check_in_time2_notfixed, 
							d.check_out_time1 as check_out_time1_notfixed, 
							d.check_out_time2 as check_out_time2_notfixed')
			->from("(select * from mf_users a, (select * from rentan_tanggal('$rank1','$rank2')) as tanggal) as a");
			$this->db->join("v_jadwal_kerja_users b","((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day) and b.user_id=a.id)",'left',false);
			$this->db->join('v_jadwal_kerja_users_shift c'," (a.id = c.user_id and c.start_shift=a.rentan_tanggal)",'left',false);
			$this->db->join('v_jadwal_kerja_users_notfixed d',"((rentan_tanggal >= d.start_date and rentan_tanggal <= d.end_date and extract('isodow' from a.rentan_tanggal) = d.day_id)and d.user_id=a.id)",'left',false);
			$this->db->order_by('rentan_tanggal','asc');
			$this->datatables->where('a.id',$this->session->userdata('tpp_user_id'));
			$this->datatables->add_column('tanggal','$1','tglInd_hrtabel(rentan_tanggal)');
			$this->datatables->add_column('start_time_tabel','$1','start_time_tabel_pegawai(start_time, start_time_shift,start_time_notfixed, check_in_time1, check_in_time2, check_in_time1_shift, check_in_time2_shift, check_in_time1_notfixed, check_in_time2_notfixed)');
			$this->datatables->add_column('end_time_tabel','$1','start_time_tabel_pegawai(end_time,end_time_shift, end_time_notfixed, check_out_time1, check_out_time2, check_out_time1_shift, check_out_time2_shift, check_out_time1_notfixed, check_out_time2_notfixed)');
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('mod');
		if ($this->mod == "Grafik") {
				$tahun 		= $this->input->get('tahun');
				$bulan 		= $this->input->get('bulan');

				$hari_ini 		= "$tahun-$bulan-01";
	 			$rank1 			= date('Y-m-01', strtotime($hari_ini));
	 			$rank2 			= date('Y-m-t', strtotime($hari_ini));

				$this->data['pegawai_lkh'] = $this->m_absen->PegawaiAbsenQueryRekapitulasiLkhDetail($this->session->userdata('tpp_user_id'), $rank1, $rank2)->result();
				$this->load->view('app/dashboard/v_grafik_lkh', $this->data);
		}
		
	}


}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */