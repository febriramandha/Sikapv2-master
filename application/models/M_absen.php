<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_absen extends CI_Model {

	public function PegawaiAbsenQuery($user_id=array(), $start_date, $end_date)
	{	
			$this->db->select('a.id, a.nama, a.nip, a.gelar_dpn, a.gelar_blk, b.json_absen')
					 ->from('v_users_all a')
					 ->join("(select a.id,
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
											count_day_shift
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
											e.count_day as count_day_shift
											from 
											(select a.id, rentan_tanggal from mf_users a, (select * from rentan_tanggal('$start_date','$end_date')) as tanggal) as a
											left join v_jadwal_kerja_users b on ((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day)and b.user_id=a.id)
											left join mf_checkinout c on ((a.id = c.user_id) AND (a.rentan_tanggal = date(c.checktime)) AND ((c.checktime)::time without time zone >= b.check_in_time1) AND ((c.checktime)::time without time zone <= b.check_in_time2))
											left join mf_checkinout d on ((a.id = d.user_id) AND (a.rentan_tanggal = date(d.checktime)) AND ((d.checktime)::time without time zone >= b.check_out_time1) AND ((d.checktime)::time without time zone <= b.check_out_time2))
											left join v_jadwal_kerja_users_shift e on (a.id = e.user_id and e.start_shift=a.rentan_tanggal)
											left join mf_checkinout f on ((a.id = f.user_id) AND (e.start_shift = date(f.checktime)) AND ((f.checktime)::time without time zone >= e.check_in_time1) AND ((f.checktime)::time without time zone <= e.check_in_time2))
											left join mf_checkinout g on ((a.id = g.user_id) AND (e.end_shift = date(f.checktime)) AND ((f.checktime)::time without time zone >= e.check_in_time1) AND ((f.checktime)::time without time zone <= e.check_in_time2))
											left join data_cuti h on (a.id = h.user_id and h.deleted =1 and (rentan_tanggal >= h.start_date and rentan_tanggal <= h.end_date)) 
											left join _cuti i on h.cuti_id=i.id
											left join data_lkh j on (a.id = j.user_id and a.rentan_tanggal=j.tgl_lkh and j.status=1 and j.jenis=3)
											left join v_dinas_manual k on (a.id = k.user_id and k.tanggal=a.rentan_tanggal)
											left join v_absenmanual_data l on (a.id = l.user_id and l.tanggal=a.rentan_tanggal)
											left join days_off m on (rentan_tanggal >= m.start_date and rentan_tanggal <= m.end_date)
											left join v_jadwal_kerja_users_notfixed n on ((rentan_tanggal >= n.start_date and rentan_tanggal <= n.end_date and extract('isodow' from a.rentan_tanggal) = n.day_id)and n.user_id=a.id)
											left join mf_checkinout o on ((a.id = o.user_id) AND (a.rentan_tanggal = date(o.checktime)) AND ((o.checktime)::time without time zone >= n.check_in_time1) AND ((o.checktime)::time without time zone <= n.check_in_time2))
											left join mf_checkinout p on ((a.id = p.user_id) AND (a.rentan_tanggal = date(p.checktime)) AND ((p.checktime)::time without time zone >= n.check_out_time1) AND ((p.checktime)::time without time zone <= n.check_out_time2))
											group by 1,2,3,4,5,6,7,10,11,12,15,16,17,18,19,20,21,22,25
							) as b on a.id=b.id
							group by 1
							) as b",'a.id=b.id','left',false)
					 ->where_in('a.id', $user_id)
					 ->order_by('no_urut');
			return $this->db->get();

	}

	public function PegawaiAbsenQueryLembur($user_id='', $start_date, $end_date, $hari_id='')
	{
		$this->db->select("nama,gelar_dpn,gelar_blk,nip, tanggal,jam_masuk,jam_pulang,id, start_time, end_time, start_time_shift, end_time_shift, daysoff_id")
        	->from("v_absen_lembur");
		$this->db->where("tanggal BETWEEN '$start_date' and '$end_date'", NULL, FALSE );
		$this->db->where_in('id', $user_id);
		$this->db->where_in('hari', $hari_id);
		return $this->db->get();
	}

	public function AbsenPegawai($user_id, $rank1, $rank2)
	{
		 $this->db->select("a.id, rentan_tanggal,
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
        	->from("(select * from mf_users, (select * from rentan_tanggal('$rank1','$rank2')) as tanggal) as a");
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
        	$this->db->where_in('a.id', $user_id);
        	$this->db->group_by('1,2,3,4,5,6,7,10,11,12,15,16,17,18,19,20,21,22');
        	$this->db->order_by('rentan_tanggal');

        	return $this->db->get();
	}

	public function PegawaiAbsenQueryRekapitulasi($user_id='', $start_date, $end_date, $dept_id='')
	{	
			$this->db->select('a.id, a.nama, a.nip, a.gelar_dpn, a.gelar_blk, b.json_absen, a.agama_id')
					 ->from('v_users_all a')
					 ->join("(select a.id,
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
											ibadah_id
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
											r.ibadah_id
											from 
											(select a.id, rentan_tanggal from mf_users a, (select * from rentan_tanggal('$start_date','$end_date')) as tanggal) as a
											left join v_jadwal_kerja_users b on ((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day)and b.user_id=a.id)
											left join mf_checkinout c on ((a.id = c.user_id) AND (a.rentan_tanggal = date(c.checktime)) AND ((c.checktime)::time without time zone >= b.check_in_time1) AND ((c.checktime)::time without time zone <= b.check_in_time2))
											left join mf_checkinout d on ((a.id = d.user_id) AND (a.rentan_tanggal = date(d.checktime)) AND ((d.checktime)::time without time zone >= b.check_out_time1) AND ((d.checktime)::time without time zone <= b.check_out_time2))
											left join v_jadwal_kerja_users_shift e on (a.id = e.user_id and e.start_shift=a.rentan_tanggal)
											left join mf_checkinout f on ((a.id = f.user_id) AND (e.start_shift = date(f.checktime)) AND ((f.checktime)::time without time zone >= e.check_in_time1) AND ((f.checktime)::time without time zone <= e.check_in_time2))
											left join mf_checkinout g on ((a.id = g.user_id) AND (e.end_shift = date(f.checktime)) AND ((f.checktime)::time without time zone >= e.check_in_time1) AND ((f.checktime)::time without time zone <= e.check_in_time2))
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
											group by 1,2,3,4,5,6,7,10,11,12,15,16,17,18,19,20,21,22,25,26,27
							) as b on a.id=b.id
							group by 1
							) as b",'a.id=b.id','left',false);
					 if ($user_id) {
					 		$this->db->where_in('a.id', $user_id);
					 }else {
					 		$this->db->where('a.dept_id', $dept_id);
					 }
					 $this->db->order_by('no_urut');
			return $this->db->get();

	}

	public function PegawaiAbsenQueryRekapitulasiLkh($user_id=array(), $rank1, $rank2, $dept_id='')
	{
		 $this->db->select('a.id, a.nama, a.nip, a.gelar_dpn, a.gelar_blk, json_jadwal_lkh, jumlah_laporan, total_laporan')
		        	->from("v_users_all a")
		        	->order_by('no_urut');
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
										jumlah_lkh
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
						f.jum as jumlah_lkh
						from 
						(select a.id, rentan_tanggal from mf_users a, (select * from rentan_tanggal('$rank1','$rank2')) as tanggal) as a
						left join v_jadwal_kerja_users b on ((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day)and b.user_id=a.id)
						left join v_jadwal_kerja_users_shift c on (a.id = c.user_id and c.start_shift=a.rentan_tanggal)
						left join v_jadwal_kerja_users_notfixed d on ((rentan_tanggal >= d.start_date and rentan_tanggal <= d.end_date and extract('isodow' from a.rentan_tanggal) = d.day_id)and d.user_id=a.id)
						left join days_off e on (rentan_tanggal >= e.start_date and rentan_tanggal <= e.end_date)
						left join (SELECT user_id,tgl_lkh,count(DISTINCT id) AS jum FROM data_lkh where status=1 GROUP BY 1,2) as f on (a.id = f.user_id and rentan_tanggal = f.tgl_lkh)
						group by 1,2,3,4,5,6,7,8,9,10) as b on a.id=b.id
						group by 1) as b",'a.id=b.id','left',false);
        $this->db->join("(select start_date, b.user_id,jumlah_laporan, total_laporan  from schlkh_manual a 				join rekaplkh_manual b on a.id=b.schlkhmanual_id
							where start_date = '$rank1') as c",'a.id=c.user_id','left',false);
         if ($user_id) {
		 		$this->db->where_in('a.id', $user_id);
		 }else {
		 		$this->db->where('a.dept_id', $dept_id);
		 }
       return $this->db->get();
	}

	public function PegawaiAbsenQueryRekapitulasiLkhDetail($user_id=array(), $rank1, $rank2, $dept_id='')
	{
		 $this->db->select('a.id, a.nama, a.nip, a.gelar_dpn, a.gelar_blk, json_jadwal_lkh, json_jadwal_lkh, jumlah_laporan, total_laporan')
		        	->from("v_users_all a")
		        	->order_by('no_urut');
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
										jumlah_lkh_terverifasi,
										jumlah_lkh_menunggu,
										jumlah_lkh_ditolak,
										jumlah_lkh_terverikasi_atasan,
										jumlah_lkh_terverikasi_otomatis
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
						e.tanggal as daysoff_id,
						f.jum as jumlah_lkh_terverifasi,
						g.jum as jumlah_lkh_menunggu,
						h.jum as jumlah_lkh_ditolak,
						i.jum as jumlah_lkh_terverikasi_atasan,
						j.jum as jumlah_lkh_terverikasi_otomatis
						from 
						(select a.id, rentan_tanggal from mf_users a, (select * from rentan_tanggal('$rank1','$rank2')) as tanggal) as a
						left join v_jadwal_kerja_users b on ((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day)and b.user_id=a.id)
						left join v_jadwal_kerja_users_shift c on (a.id = c.user_id and c.start_shift=a.rentan_tanggal)
						left join v_jadwal_kerja_users_notfixed d on ((rentan_tanggal >= d.start_date and rentan_tanggal <= d.end_date and extract('isodow' from a.rentan_tanggal) = d.day_id)and d.user_id=a.id)
						left join days_off e on (rentan_tanggal >= e.start_date and rentan_tanggal <= e.end_date)
						left join (SELECT user_id,tgl_lkh,count(DISTINCT id) AS jum FROM data_lkh where status=1 GROUP BY 1,2) as f on (a.id = f.user_id and rentan_tanggal = f.tgl_lkh)
						left join (SELECT user_id,tgl_lkh,count(DISTINCT id) AS jum FROM data_lkh where status in (0,4) GROUP BY 1,2) as g on (a.id = g.user_id and rentan_tanggal = g.tgl_lkh)
						left join (SELECT user_id,tgl_lkh,count(DISTINCT id) AS jum FROM data_lkh where status in (2,3) GROUP BY 1,2) as h on (a.id = h.user_id and rentan_tanggal = h.tgl_lkh)
						left join (SELECT user_id,tgl_lkh,count(DISTINCT id) AS jum FROM data_lkh where verifikasi_by is not null and status=1 GROUP BY 1,2) as i on (a.id = i.user_id and rentan_tanggal = i.tgl_lkh)
						left join (SELECT user_id,tgl_lkh,count(DISTINCT id) AS jum FROM data_lkh where verifikasi_by is null and status=1 GROUP BY 1,2) as j on (a.id = j.user_id and rentan_tanggal = j.tgl_lkh)
						group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14) as b on a.id=b.id
						group by 1) as b",'a.id=b.id','left',false);
       	 $this->db->join("(select start_date, b.user_id,jumlah_laporan, total_laporan  from schlkh_manual a 				join rekaplkh_manual b on a.id=b.schlkhmanual_id
							where start_date = '$rank1') as c",'a.id=c.user_id','left',false);
         if ($user_id) {
		 		$this->db->where_in('a.id', $user_id);
		 }else {
		 		$this->db->where('a.dept_id', $dept_id);
		 }
       return $this->db->get();
	}

}

/* End of file M_absen.php */
/* Location: ./application/models/M_absen.php */