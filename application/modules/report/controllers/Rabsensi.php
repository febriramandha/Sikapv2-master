<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Rabsensi extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Laporan Kehadiran', 'mnglkh/schlkh');
		$this->data['title'] = "Laporan Umum";
		$this->load->model(['m_instansi','m_pejabat_instansi','m_absen']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
    	$this->load->js('public/themes/plugin/datatables/dataTables.rowsGroup.js');
    	$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Laporan Kehadiran";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('rabsensi/v_index', $this->data);
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
        $this->datatables->select("a.id,a.nip, a.nama, rentan_tanggal,
						        	b.schrun_id, b.start_date, b.end_date, b.start_time, b.end_time,
									min((c.checktime)::time ) AS jam_masuk,
									max((d.checktime)::time ) AS jam_pulang,
									e.kd_shift,
									e.start_time as start_time_shift,
									e.end_time as end_time_shift,
									min((f.checktime)::time ) AS jam_masuk_shift,
									max((g.checktime)::time ) AS jam_pulang_shift,
									i.kode as kode_cuti,
									a.gelar_dpn, a.gelar_blk,
									j.tgl_lkh as lkhdl_id,
									k.dinasmanual_id,
									l.status_in,
									l.status_out,
									m.id as daysoff_id,
									n.start_time as start_time_notfixed, 
									n.end_time as end_time_notfixed,
									min((o.checktime)::time ) AS jam_masuk_notfixed,
									max((p.checktime)::time ) AS jam_pulang_notfixed")
        	//user dan tanggal
        	->from("(select * from v_users_all, (select * from rentan_tanggal('$rank1_','$rank2_')) as tanggal) as a");
        	// jadwal user sesuai jam kerja
        	 $this->db->join('v_jadwal_kerja_users b',"((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day)and b.user_id=a.id)",'left',false);
        	// cari ceklok sesuai jadwal = jam masuk
        	$this->db->join('mf_checkinout c',"((a.id = c.user_id) AND (a.rentan_tanggal = date(c.checktime)) AND ((c.checktime)::time  >= b.check_in_time1) AND ((c.checktime)::time  <= b.check_in_time2))",'left',false);
        	// cari ceklok sesuai jadwal = jam keluar
        	$this->db->join('mf_checkinout d',"((a.id = d.user_id) AND (a.rentan_tanggal = date(d.checktime)) AND ((d.checktime)::time  >= b.check_out_time1) AND ((d.checktime)::time  <= b.check_out_time2))",'left',false);
        	// jadwal shift user
        	$this->db->join('v_jadwal_kerja_users_shift e',"(a.id = e.user_id and e.start_shift=a.rentan_tanggal)",'left',false);
        	// cari ceklok sesuai jadwal shift = jam masuk shift
        	$this->db->join('mf_checkinout f',"((a.id = f.user_id) AND (e.start_shift = date(f.checktime)) AND ((f.checktime)::time  >= e.check_in_time1) AND ((f.checktime)::time  <= e.check_in_time2))",'left',false);
        	// cari ceklok sesuai jadwal shift = jam keluar shift
        	$this->db->join('mf_checkinout g',"((a.id = g.user_id) AND (e.end_shift = date(f.checktime)) AND ((f.checktime)::time  >= e.check_in_time1) AND ((f.checktime)::time  <= e.check_in_time2))",'left',false);
        	// data cuti
        	$this->db->join('data_cuti h',"(a.id = h.user_id and h.deleted =1 and (rentan_tanggal >= h.start_date and rentan_tanggal <= h.end_date))",'left',false);
        	$this->db->join('_cuti i','h.cuti_id=i.id','left');
        	// dinas luar lkh
        	$this->db->join('data_lkh j',"(a.id = j.user_id and a.rentan_tanggal=j.tgl_lkh and j.status=1 and j.jenis=3)",'left',false);
        	// dinas muanual
        	$this->db->join('v_dinas_manual k',"(a.id = k.user_id and k.tanggal=a.rentan_tanggal)",'left',false);
        	// absen manual data
        	$this->db->join('v_absenmanual_data l',"(a.id = l.user_id and l.tanggal=a.rentan_tanggal)",'left',false);
        	// hari libur
        	$this->db->join('days_off m',"(rentan_tanggal >= m.start_date and rentan_tanggal <= m.end_date)",'left',false);
        	//jadwal kerja tidak tetap
        	 $this->db->join('v_jadwal_kerja_users_notfixed n',"((rentan_tanggal >= n.start_date and rentan_tanggal <= n.end_date and extract('isodow' from a.rentan_tanggal) = n.day_id)and n.user_id=a.id)",'left',false);
        	 // cari ceklok sesuai jadwal = jam masuk
        	$this->db->join('mf_checkinout o',"((a.id = o.user_id) AND (a.rentan_tanggal = date(o.checktime)) AND ((o.checktime)::time >= n.check_in_time1) AND ((o.checktime)::time <= n.check_in_time2))",'left',false);
        	// cari ceklok sesuai jadwal = jam keluar
        	$this->db->join('mf_checkinout p',"((a.id = p.user_id) AND (a.rentan_tanggal = date(p.checktime)) AND ((p.checktime)::time >= n.check_out_time1) AND ((p.checktime)::time <= n.check_out_time2))",'left',false);


        	$this->db->group_by('1,2,3,4,5,6,7,8,9,12,13,14,17,18,19,no_urut,20,21,22,23,24,25,26');
        	$this->db->order_by('a.no_urut, rentan_tanggal');
        	$this->datatables->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)');
        	$this->datatables->add_column('tanggal','$1','tglInd_hrtabel(rentan_tanggal)');
        	$this->datatables->add_column('start_time_tabel','$1','start_time_tabel(start_time, start_time_shift,start_time_notfixed)');
        	$this->datatables->add_column('jam_masuk_tabel','$1','jam_masuk_tabel(jam_masuk, jam_masuk_shift, status_in,start_time_notfixed, jam_masuk_notfixed)');
        	$this->datatables->add_column('terlambat_tabel','$1','terlambat_tabel(start_time, start_time_shift, jam_masuk, jam_masuk_shift, status_in, start_time_notfixed, jam_masuk_notfixed)');
        	$this->datatables->add_column('end_time_tabel','$1','start_time_tabel(end_time, end_time_shift, end_time_notfixed)');
        	$this->datatables->add_column('jam_pulang_tabel','$1','jam_pulang_tabel(jam_pulang, jam_pulang_shift, status_out, end_time_notfixed, jam_pulang_notfixed)');
        	$this->datatables->add_column('pulang_cepat_tabel','$1','pulang_cepat_tabel(end_time, end_time_shift, jam_pulang, jam_pulang_shift, status_out, end_time_notfixed, jam_pulang_notfixed)');
        	$this->datatables->add_column('dinas_luar_tabel','$1','dinas_luar_tabel(lkhdl_id, dinasmanual_id)');
        	$this->datatables->add_column('cuti','$1','kode_cuti');
        	$this->datatables->add_column('ket','$1','absen_ket_tabel(daysoff_id, jam_masuk, jam_pulang, jam_masuk_shift, jam_pulang_shift, lkhdl_id, dinasmanual_id, kode_cuti, rentan_tanggal, start_time, start_time_shift, status_in, status_out,end_time, end_time_shift, start_time_notfixed, jam_masuk_notfixed, end_time_notfixed, jam_pulang_notfixed)');

        	 if ($user_id_in) {
		        $this->datatables->where_in('a.id', $user_id_in);
		     }else {
		     	 $this->datatables->where_in('a.id','0');
		     }
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('mod');
		if ($this->mod == "DataPegawai") {
			$dept_id = decrypt_url($this->input->get('id'),'instansi');
			$pns 	 = $this->input->get('pns');
			$tpp 	 = $this->input->get('tpp');
				$this->db->select('*')
						->from('v_users_all')
						->where('key > 0')
        				->where('att_status',1)
        				->where('dept_id', $dept_id);
        		if ($pns) {
        			$this->db->where('pns',$pns);
        		}
        		if ($tpp != 'false' && $tpp) {
        			$this->db->where('tpp',1);
        		}
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
		}elseif ($this->mod == "a") {
			
			
		}
		
	}

	public function cetak()
	{
		$this->output->unset_template();
		$dept_id = decrypt_url($this->input->post('instansi'),'instansi');
		

		$this->form_validation->set_rules('instansi', 'nama instansi', 'required')
							  ->set_rules('pegawai[]', 'pegawai', 'required')
							  ->set_rules('rank1', 'tanggal awal', 'required')
							  ->set_rules('rank2', 'tanggal akhir', 'required');
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
				$this->data['jum_hari']	= $jum_hari;
				$this->data['rank1'] 	= $rank1;
				$this->data['pegawai_absen'] = $this->m_absen->PegawaiAbsenQuery($user_id_in, $rank1, $rank2)->result();
				$this->data['priode']		= tgl_ind_bulan($rank1).' s/d '.tgl_ind_bulan($rank2);
				$this->data['datainstansi'] = $this->m_pejabat_instansi->GetPajabatByInstansi($dept_id, 7)->row();
				$this->load->library('Tpdf');
				$this->load->view('rabsensi/v_cetak', $this->data);
			}
		}else {
			echo  validation_errors();
		}
	}

}

/* End of file Rabsensi.php */
/* Location: ./application/modules/Report/controllers/Rabsensi.php */