<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Irvan Aulia
* 2022
*/

class M_tpp extends CI_Model {
    public function getDataTpp($id = NULL)
    {
        $query = $this->db->select('*, a.id as id_tpp')
				->from('pegawai_tpp a')
				->join('mf_users b', 'a.id_pegawai=b.id')
                ->where('a.id',$id)
				->get()
				->result();

        return $query[0];
    }

	public function update_tpp($data,$id = NULL)
	{
		$query = $this->db->set($data)
				->where('id', $id)
				->update('pegawai_tpp');

		return $query;
	}

	public function get_tpp_standar($month, $year)
	{
		$query = $this->db->select("*")
			->from('tpp_standar')
			->where("tahun", $year)
			->where("bulan", $month)
			->get()
			->result();

		return $query[0];
	}


	public function get_tpp_one($id)
	{
		$query = $this->db->select("*")
			->from('tpp_standar')
			->where("id", $id)
			->get();
//			->result();

		return $query;
	}

	
	public function getTppCetak($idbulan = null, $dept_id)
	{

		$query = $this->db->select('a.id, a.id_pegawai, a.bbebankerja, a.bkondisikerja, a.bkelangkaan, a.totaltpp, a.potongan_disiplin, a.potongan_produktivitas, a.hasiltpp, b.nama, b.nip, (c.disiplin_kerja/100 * a.totaltpp) as disiplin_kerja, (c.produktivitas_kerja/100 * a.totaltpp) as produktivitas_kerja, a.potonganpph, a.potonganbpjs, a.setelahpotongpph, a.jml_setelah_potongbpjs, a.potonganzakat, a.jml_setelah_potongzakat, SUM(a.jml_setelah_potongzakat) OVER () as total_diterima_sum')
			->from('pegawai_tpp a')
			->join('mf_users b', 'a.id_pegawai=b.id')
			->join('tpp_standar c', 'a.tpp_standar=c.id')
			->join('users_login d', 'b.id=d.user_id')
			->where('b.att_status', 1)
			->where('b.dept_id', $dept_id)
			->where('c.id', $idbulan)
			->order_by('a.bbebankerja', 'desc')
			->get();
		return $query;
	}



	public function get_besar_tpp($rank1, $rank2, $pegawai_id)
	{
		$query = $this->db->select('a.id, a.nama, a.nip, a.gelar_dpn, a.gelar_blk, json_absen, a.agama_id, json_jadwal_lkh, jumlah_laporan, total_laporan')
			->from("v_users_all a")
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
							) as b",'a.id=b.id','left',false)
			->join("(select a.id,
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
										kode_cuti,
										poin,
										count_persen,
										set_day
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
						f.poin,
						f.count_persen,
						i.kode as kode_cuti,
						c.set_day
						from 
						(select a.id, rentan_tanggal from mf_users a, (select * from rentan_tanggal('$rank1','$rank2')) as tanggal) as a
						left join v_jadwal_kerja_users b on ((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day)and b.user_id=a.id)
						left join v_jadwal_kerja_users_shift_3 c on (a.id = c.user_id and c.start_shift=a.rentan_tanggal)
						left join v_jadwal_kerja_users_notfixed d on ((rentan_tanggal >= d.start_date and rentan_tanggal <= d.end_date and extract('isodow' from a.rentan_tanggal) = d.day_id)and d.user_id=a.id)
						left join days_off e on (rentan_tanggal >= e.start_date and rentan_tanggal <= e.end_date)
						left join (SELECT user_id,tgl_lkh,count(DISTINCT id) AS jum,poin, COUNT(*) FILTER(where persentase = 100) AS count_persen FROM data_lkh where status=1 GROUP BY 1,2,4) as f on (a.id = f.user_id and rentan_tanggal = f.tgl_lkh)
						left join data_cuti h on (a.id = h.user_id and h.deleted =1 and (rentan_tanggal >= h.start_date and rentan_tanggal <= h.end_date)) 
						left join _cuti i on h.cuti_id=i.id
						group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14) as b on a.id=b.id
						group by 1) as c",'a.id=c.id','left',false)
        	->join("(select start_date, b.user_id,jumlah_laporan, total_laporan  from schlkh_manual a join rekaplkh_manual b on a.id=b.schlkhmanual_id
							where start_date = '$rank1') as d",'a.id=d.user_id','left',false)
			->where('a.id', $pegawai_id);

			return $query;
	}

	public function get_besaran_tpp_standar($pegawai_id)
	{
		$query = $this->db->select('a.id, a.id_pegawai, a.bbebankerja, a.bkondisikerja, a.bkelangkaan, a.totaltpp, b.nama, b.nip, (c.disiplin_kerja/100 * a.totaltpp) as disiplin_kerja, (c.produktivitas_kerja/100 * a.totaltpp) as produktivitas_kerja')
			->from('pegawai_tpp a')
			->join('mf_users b', 'a.id_pegawai=b.id')
			->join('tpp_standar c', 'a.tpp_standar=c.id')
			->where('a.id_pegawai', $pegawai_id);
		return $query;
	}


	public function get_golongan_pegawai($pegawai_id)
	{
		$query = $this->db->select('golongan_id')
			->from('sp_pegawai a')
			->where('a.user_id', $pegawai_id);
		return $query;
	}


}

/* End of file M_simpeg.php */
/* Location: ./application/models/M_tpp.php */
