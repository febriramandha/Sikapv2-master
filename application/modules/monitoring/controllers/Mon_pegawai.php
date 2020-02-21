<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Mon_pegawai extends  App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Monitoring Pegawai', 'monitoring/mon-pegawai');
		$this->data['title'] = "Monitoring";
		$this->load->model(['m_instansi','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
		$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
		$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
		$this->load->js('public/themes/plugin/datatables/dataTables.rowsGroup.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Pegawai";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('mon_pegawai/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$dept_id = $this->m_instansi->GetAdminDept($this->session->userdata('tpp_dept_id'));
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.nama, a.dept_alias, gelar_dpn,gelar_blk,jabatan')
        	->from('v_users_all a')
        	->where("a.id not in (995)")
        	->order_by('no_urut')
        	->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip,"monitoring/mon-pegawai/view",id," ","user_id_mon")')
        	->add_column('instansi','$1','dept_alias')
        	->add_column('jabatan','$1','jabatan');
		     $this->datatables->where("path_id['".$level."']='".$dept_id."'");
        	 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(nama)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(nip)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(a.dept_alias)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
	       
        return $this->output->set_output($this->datatables->generate());
	}

	public function view($id)
	{
		$this->data['sub_title'] 	= "Data Pegawai";
		$this->breadcrumbs->push('Lihat Pegawai', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$user_id = decrypt_url($id,"user_id_mon");

		if ($this->input->get('p') == 'absen') {
			$this->absen($user_id);
		}elseif ($this->input->get('p') == 'lkh') {
			$this->lkh($user_id);
		}elseif ($this->input->get('p') == 'ibadah') {
			$this->ibadah($user_id);
		}else {
			$this->data['user']  		= $this->m_user->GetDetailBiodata($user_id)->row();
			$this->load->view('mon_pegawai/v_view', $this->data);
		}
		
	}

	public function absen($id)
	{
		$this->load->view('mon_pegawai/v_absen', $this->data);
	}

	public function lkh($id)
	{
		$this->load->view('mon_pegawai/v_lkh', $this->data);
	}

	public function ibadah($id)
	{
		$data_agama = $this->db->select('agama_id')->get_where('sp_pegawai',['user_id' => $id])->row();
		$agama_id = 1;
		if ($data_agama) {
			if ($data_agama->agama_id == 1 || $data_agama->agama_id == 0 || $data_agama->agama_id == '') {
				$agama_id = 1;
			}else {
				$agama_id = $data_agama->agama_id;
			}
		}
		$this->data['agama_id'] = $agama_id;
		$this->load->view('mon_pegawai/v_ibadah', $this->data);
	}

	public function GetJson($id)
	{
		$user_id = decrypt_url($id,"user_id_mon");
		$page = $this->input->get('p');

		if ($page == "absen") {
				$this->absenJson($user_id);
		}elseif ($page == "lkh") {
				$this->lkhJson($user_id);
		}elseif ($page == "ibadah") {
				$this->ibadahJson($user_id);
		}
	}

	public function absenJson($user_id)
	{
		$this->output->unset_template();		
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
        $this->datatables->select("a.id, rentan_tanggal,
						        	b.schrun_id, b.start_date, b.end_date, b.start_time, b.end_time,
									min((c.checktime)::time ) AS jam_masuk,
									max((d.checktime)::time ) AS jam_pulang,
									e.kd_shift,
									e.start_time as start_time_shift,
									e.end_time as end_time_shift,
									min((f.checktime)::time ) AS jam_masuk_shift,
									max((g.checktime)::time ) AS jam_pulang_shift,
									i.kode as kode_cuti,
									j.tgl_lkh as lkhdl_id,
									k.user_id as dinasmanual_id,
									l.status_in,
									l.status_out,
									m.id as daysoff_id,
									n.start_time as start_time_notfixed, 
									n.end_time as end_time_notfixed,
									min((o.checktime)::time ) AS jam_masuk_notfixed,
									max((p.checktime)::time ) AS jam_pulang_notfixed")
        	//user dan tanggal
        	->from("(select * from mf_users, (select * from rentan_tanggal('$rank1_','$rank2_')) as tanggal) as a");
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
        	$this->db->group_by('1,2,3,4,5,6,7,10,11,12,15,16,17,18,19,20,21,22');
        	$this->db->order_by('rentan_tanggal');
        	$this->datatables->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)');
        	$this->datatables->add_column('tanggal','$1','tglInd_hrtabel(rentan_tanggal)');
        	$this->datatables->add_column('start_time_tabel','$1','start_time_tabel(start_time, start_time_shift,start_time_shift,start_time_notfixed)');
        	$this->datatables->add_column('jam_masuk_tabel','$1','jam_masuk_tabel(jam_masuk, jam_masuk_shift, status_in, start_time_notfixed, jam_masuk_notfixed)');
        	$this->datatables->add_column('terlambat_tabel','$1','terlambat_tabel(start_time, start_time_shift, jam_masuk, jam_masuk_shift, status_in, start_time_notfixed, jam_masuk_notfixed)');
        	$this->datatables->add_column('end_time_tabel','$1','start_time_tabel(end_time, end_time_shift, end_time_notfixed)');
        	$this->datatables->add_column('jam_pulang_tabel','$1','jam_pulang_tabel(jam_pulang, jam_pulang_shift, status_out, end_time_notfixed, jam_pulang_notfixed)');
        	$this->datatables->add_column('pulang_cepat_tabel','$1','pulang_cepat_tabel(end_time, end_time_shift, jam_pulang, jam_pulang_shift, status_out, end_time_notfixed, jam_pulang_notfixed)');
        	$this->datatables->add_column('dinas_luar_tabel','$1','dinas_luar_tabel(lkhdl_id, dinasmanual_id)');
        	$this->datatables->add_column('cuti','$1','kode_cuti');
        	$this->datatables->add_column('ket','$1','absen_ket_tabel(daysoff_id, jam_masuk, jam_pulang, jam_masuk_shift, jam_pulang_shift, lkhdl_id, dinasmanual_id, kode_cuti, rentan_tanggal, start_time, start_time_shift, status_in, status_out,end_time, end_time_shift, start_time_notfixed, jam_masuk_notfixed, end_time_notfixed, jam_pulang_notfixed)');

        	if ($rank1 && $rank2) {
				$this->datatables->where_in('a.id', $user_id);
			}else {
				$this->datatables->where_in('a.id', '0');
			}
        return $this->output->set_output($this->datatables->generate());
	}

	public function lkhJson($user_id)
	{
		$this->output->unset_template();
		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));
		$this->load->library('datatables');
        $this->datatables->select('a.id, tgl_lkh, jam_mulai, jam_selesai, kegiatan, hasil, jenis, a.status, verifikasi_by, b.nama as ver_nama, b.gelar_dpn as ver_gelar_dpn, b.gelar_blk as ver_gelar_blk, comment, a.jenis, a.persentase')
        	->from('data_lkh a')
        	->join('v_users_all b','a.verifikasi_by=b.id','left')
        	->join('lkh_rejected c','a.id=c.lkh_id','left')
        	->order_by('tgl_lkh,jam_mulai, id','desc')
        	->add_column('tgl_lkh_tabel','$1','tglInd_hrtabel(tgl_lkh)')
        	->add_column('jam_mulai','$1 <i class="icon-arrow-right16"></i> $2 $3 $4','jm(jam_mulai), jm(jam_selesai), cek_dltabel(jenis), persentase_lkh(persentase)')
        	->add_column('status_lkh','$1','status_lkh_tabel(status, comment)')
        	->add_column('ver','$1','pejabat_ptabel(verifikasi_by, ver_nama, ver_gelar_dpn, ver_gelar_blk, status)');
        	if ($rank1 && $rank2) {
        			$this->datatables->where("tgl_lkh::date BETWEEN '$rank1' and '$rank2'", NULL, FALSE );
        	}
        	if ($user_id) {
        		    $this->datatables->where('user_id', $user_id);
        	}else {
        			$this->datatables->where('user_id', '0');
        	}
        return $this->output->set_output($this->datatables->generate());
	}

	public function ibadahJson($user_id)
	{
		$this->output->unset_template();
		
		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));
		$agama_id = $this->input->post('agama_id');
		$this->load->library('datatables');
		if ($agama_id != 1) {
			 $this->datatables->select('id, tgl_ibadah, kegiatan, tempat')
        	->from('ibadah_nonmus')
        	->order_by('tgl_ibadah','desc')
        	->add_column('tgl_ibadah_tabel','$1','tglInd_hrtabel(tgl_ibadah)')
        	->add_column('tgl_ibadah_tabel','$1','tglInd_hrtabel(tgl_ibadah)')
	        				->add_column('tempat1','$1','kegiatan')
	        				->add_column('tempat2','$1','tempat');
		}else {
			$this->datatables->select('a.id, tgl_ibadah, ibadah_id, t_zuhur, t_ashar, b.nama as ket')
	        				->from('ibadah_muslim a')
	        				->join('ibadah b','a.ibadah_id=b.id','left')
	        				->order_by('tgl_ibadah','desc')
	        				->add_column('tgl_ibadah_tabel','$1','tglInd_hrtabel(tgl_ibadah)')
	        				->add_column('tempat1','$1','t_zuhur')
	        				->add_column('tempat2','$1','t_ashar');
		}
    	if ($rank1 && $rank2) {
    			$this->datatables->where("tgl_ibadah::date BETWEEN '$rank1' and '$rank2'", NULL, FALSE );
    	}
    	if ($user_id) {
    		$this->datatables->where('user_id', $user_id);
    	}else {
    		$this->datatables->where('user_id', '0');
    	}
        return $this->output->set_output($this->datatables->generate());
	}


}

/* End of file Mon_pegawai.php */
/* Location: ./application/modules/monitoring/controllers/Mon_pegawai.php */