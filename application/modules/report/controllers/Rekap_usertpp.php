<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Handika putra
* 2021
*/

class Rekap_usertpp extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Rekapitulasi Daftar Penerima TPP', 'report/rekap-usertpp');
		$this->data['title'] = "Rekapitulasi Daftar Penerima TPP";
		$this->load->model(['m_instansi','m_pejabat_instansi','m_absen','m_sch_run']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
    	$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Rekapitulasi Daftar Penerima TPP";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->data['laporan_tahun'] = $this->m_sch_run->GetTahun()->result();
		$this->load->view('rekap_usertpp/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$user_id  	= $this->input->post('pegawai');
		$user_id_in =array();
		if ($user_id) {
			foreach ($user_id as $r_v ) {
				$user_id_in[] = $r_v;
			}
		}
		
		$tahun  	= $this->input->post('tahun');
		$bulan  	= $this->input->post('bulan');

		if ($tahun && $bulan) {
			$hari_ini 		= "$tahun-$bulan-01";
 			$rank1 			= date('Y-m-01', strtotime($hari_ini));
 			$rank2 			= date('Y-m-t', strtotime($hari_ini));
			
		}else {
			$rank1  	   = date('Y-m-d');
			$rank2  	   = date('Y-m-d');
		}
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nama, a.nip, a.gelar_dpn, f.eselon,g.golongan, a.gelar_blk,e.tpp, ((e.tpp*40)/100) as tpp1,((e.tpp*60)/100) as tpp2,  json_absen, a.agama_id, json_jadwal_lkh, jumlah_laporan, total_laporan')
        	->from("v_users_all a")
        	->order_by('no_urut');
			$this->db->join('_allowances e', 'e.eselon_id = a.eselon_id','left');
        	 $this->db->join("(select a.id,
								json_build_object(
										'data_absen',json_agg(
										(	rentan_tanggal, 
											schrun_id,
											start_date, 
											end_date, 
											start_time, 
											end_time,
											jam_masuk,
											jam_pulang,
											kd_shift,
											start_time_shift,
											end_time_shift,
											jam_masuk_shift,
											jam_pulang_shift,
											kode_cuti,
											lkhdl_id,
											dinasmanual_id,
											status_in,
											status_out,
											daysoff_id,
											start_time_notfixed,
											end_time_notfixed,
											jam_masuk_notfixed,
											jam_pulang_notfixed,
											count_day_shift,
											jumtidak_upacara,
											ibadah_id,
											jumtidak_apel
										) ORDER BY rentan_tanggal)
								) as json_absen
							from mf_users a
							left join (
											select a.id, 
											rentan_tanggal,
											b.schrun_id, 
											b.start_date, 
											b.end_date, 
											b.start_time, 
											b.end_time,
											min((c.checktime)::time without time zone) AS jam_masuk,
											max((d.checktime)::time without time zone) AS jam_pulang,
											e.kd_shift,
											e.start_time as start_time_shift,
											e.end_time as end_time_shift,
											min((f.checktime)::time without time zone) AS jam_masuk_shift,
											max((g.checktime)::time without time zone) AS jam_pulang_shift,
											i.kode as kode_cuti,
											j.tgl_lkh as lkhdl_id,
											k.user_id as dinasmanual_id,
											l.status_in,
											l.status_out,
											m.id as daysoff_id,
											n.start_time as start_time_notfixed, 
											n.end_time as end_time_notfixed,
											min((o.checktime)::time) AS jam_masuk_notfixed,
											max((p.checktime)::time) AS jam_pulang_notfixed,
											e.count_day as count_day_shift,
											q.jum as jumtidak_upacara,
											r.ibadah_id,
											s.jum as jumtidak_apel
											from 
											(select a.id, rentan_tanggal from mf_users a, (select * from rentan_tanggal('$rank1','$rank2')) as tanggal) as a
											left join v_jadwal_kerja_users b on ((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day)and b.user_id=a.id)
											left join mf_checkinout c on ((a.id = c.user_id) AND (a.rentan_tanggal = date(c.checktime)) AND ((c.checktime)::time without time zone >= b.check_in_time1) AND ((c.checktime)::time without time zone <= b.check_in_time2))
											left join mf_checkinout d on ((a.id = d.user_id) AND (a.rentan_tanggal = date(d.checktime)) AND ((d.checktime)::time without time zone >= b.check_out_time1) AND ((d.checktime)::time without time zone <= b.check_out_time2))
											left join v_jadwal_kerja_users_shift e on (a.id = e.user_id and e.start_shift=a.rentan_tanggal)
											left join mf_checkinout f on ((a.id = f.user_id) AND (e.start_shift = date(f.checktime)) AND ((f.checktime)::time without time zone >= e.check_in_time1) AND ((f.checktime)::time without time zone <= e.check_in_time2))
											left join mf_checkinout g on ((a.id = g.user_id) AND (e.end_shift = date(g.checktime)) AND ((g.checktime)::time without time zone >= e.check_out_time1) AND ((g.checktime)::time without time zone <= e.check_out_time2))
											left join data_cuti h on (a.id = h.user_id and h.deleted =1 and (rentan_tanggal >= h.start_date and rentan_tanggal <= h.end_date)) 
											left join _cuti i on h.cuti_id=i.id
											left join data_lkh j on (a.id = j.user_id and a.rentan_tanggal=j.tgl_lkh and j.status=1 and j.jenis=3)
											left join v_dinas_manual k on (a.id = k.user_id and k.tanggal=a.rentan_tanggal)
											left join v_absenmanual_data l on (a.id = l.user_id and l.tanggal=a.rentan_tanggal)
											left join days_off m on (rentan_tanggal >= m.start_date and rentan_tanggal <= m.end_date)
											left join v_jadwal_kerja_users_notfixed n on ((rentan_tanggal >= n.start_date and rentan_tanggal <= n.end_date and extract('isodow' from a.rentan_tanggal) = n.day_id)and n.user_id=a.id)
											left join mf_checkinout o on ((a.id = o.user_id) AND (a.rentan_tanggal = date(o.checktime)) AND ((o.checktime)::time without time zone >= n.check_in_time1) AND ((o.checktime)::time without time zone <= n.check_in_time2))
											left join mf_checkinout p on ((a.id = p.user_id) AND (a.rentan_tanggal = date(p.checktime)) AND ((p.checktime)::time without time zone >= n.check_out_time1) AND ((p.checktime)::time without time zone <= n.check_out_time2))
											left join v_tidak_hadir_upacara q on (a.id=q.user_id and a.rentan_tanggal=q.tanggal)
											left join ibadah_muslim r on (a.id=r.user_id and a.rentan_tanggal=r.tgl_ibadah)
											left join v_tidak_apel_pagi s on (a.id=s.user_id and a.rentan_tanggal=s.tgl_apel)
											group by 1,2,3,4,5,6,7,10,11,12,15,16,17,18,19,20,21,22,25,26,27,28
							) as b on a.id=b.id
							group by 1
							) as b",'a.id=b.id','left',false);
			$this->db->join("(select a.id,
					json_build_object(
									'data_jum_lkh',json_agg(
									(	rentan_tanggal,
										start_time, 
										end_time,
										start_time_shift,
										end_time_shift,
										start_time_notfixed, 
										end_time_notfixed,
										daysoff_id,
										jumlah_lkh,
										kode_cuti
									) ORDER BY rentan_tanggal)
							) as json_jadwal_lkh
						from mf_users a
						left join (
						select 
						a.id, 
						rentan_tanggal,
						b.start_time, 
						b.end_time,
						c.start_time as start_time_shift,
						c.end_time as end_time_shift,
						d.start_time as start_time_notfixed, 
						d.end_time as end_time_notfixed,
						e.id as daysoff_id,
						f.jum as jumlah_lkh,
						i.kode as kode_cuti
						from 
						(select a.id, rentan_tanggal from mf_users a, (select * from rentan_tanggal('$rank1','$rank2')) as tanggal) as a
						left join v_jadwal_kerja_users b on ((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day)and b.user_id=a.id)
						left join v_jadwal_kerja_users_shift c on (a.id = c.user_id and c.start_shift=a.rentan_tanggal)
						left join v_jadwal_kerja_users_notfixed d on ((rentan_tanggal >= d.start_date and rentan_tanggal <= d.end_date and extract('isodow' from a.rentan_tanggal) = d.day_id)and d.user_id=a.id)
						left join days_off e on (rentan_tanggal >= e.start_date and rentan_tanggal <= e.end_date)
						left join (SELECT user_id,tgl_lkh,count(DISTINCT id) AS jum FROM data_lkh where status=1 GROUP BY 1,2) as f on (a.id = f.user_id and rentan_tanggal = f.tgl_lkh)
						left join data_cuti h on (a.id = h.user_id and h.deleted =1 and (rentan_tanggal >= h.start_date and rentan_tanggal <= h.end_date)) 
						left join _cuti i on h.cuti_id=i.id
						group by 1,2,3,4,5,6,7,8,9,10,11) as b on a.id=b.id
						group by 1) as c",'a.id=c.id','left',false);
        	$this->db->join("(select start_date, b.user_id,jumlah_laporan, total_laporan  from schlkh_manual a 				join rekaplkh_manual b on a.id=b.schlkhmanual_id
							where start_date = '$rank1') as d",'a.id=d.user_id','left',false);
			$this->db->join("_eselon as f",'f.id = a.eselon_id','left',false);
			$this->db->join("_golongan as g",'g.id = a.golongan_id','left',false);
							

        	$this->datatables->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)');
        	$this->datatables->add_column('jum_terlambar_rekap','$1',"jum_terlambar_rekap(json_absen,'')");
        	$this->datatables->add_column('tpp_aspek_perilaku','$1',"besaran_tpp(tpp,'ADK','Y')");
        	$this->datatables->add_column('tpp_capaian_kerja','$1',"besaran_tpp(tpp,'APK','Y')");
    		$this->datatables->add_column('besar_potongan_aspek_disiplin_kerja','$1',"format_rp_aspek_disiplin_kerja(tpp1,json_absen)");
    		$this->datatables->add_column('besar_potongan_produktivitas_kerja','$1',"format_rp_produktivitas_kerja(tpp2,json_jadwal_lkh, total_laporan)");
    		$this->datatables->add_column('jumlah_tpp_dikurangi','$1',"format_rp_tpp_dikurangi(tpp1,tpp2,json_absen, json_jadwal_lkh, total_laporan)");
			$this->datatables->add_column('bpjs','$1',"format_rp_bpjs(tpp1,tpp2,json_absen, json_jadwal_lkh, total_laporan,'1')");
			$this->datatables->add_column('ptg_bpjs','$1',"format_rp_ptg_bpjs(tpp1,tpp2,json_absen, json_jadwal_lkh, total_laporan,'1')");
			$this->datatables->add_column('pph','$1',"format_rp_pph(tpp1,tpp2,json_absen, json_jadwal_lkh, total_laporan,golongan)");
			$this->datatables->add_column('jum_diterima','$1',"jum_diterima(tpp1,tpp2,json_absen,json_jadwal_lkh, total_laporan,golongan)");

			
        	if ($user_id_in) {
        	 	if (!$tahun || !$bulan) {
			     		$this->datatables->where_in('a.id','0');
			     }else {
			     	$this->datatables->where_in('a.id', $user_id_in);
			     }
		     }else {
		     	 $this->datatables->where_in('a.id','0');
		     }
		     
        return $this->output->set_output($this->datatables->generate());
	}

}

/* End of file Rekap_usertpp.php */
/* Location: ./application/modules/report/controllers/Rekap_usertpp.php */