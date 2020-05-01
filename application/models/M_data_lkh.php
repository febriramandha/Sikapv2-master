<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_data_lkh extends CI_Model {

	public function cek_jam_lkh($user_id, $tgl)
	{
		$this->db->select('jam_selesai')
				 ->where('user_id', $user_id)
				 ->where('tgl_lkh', $tgl)
				 ->order_by('tgl_lkh, jam_selesai','desc')
				 ->limit(1);
		return $this->db->get('data_lkh');
	}

	public function update_status($id ='',$data_tgl_lkh)
    {   
        $this->db->where('user_id', $id);
		$this->db->where_in('status','0,4',false);
        $this->db->where_not_in('tgl_lkh',$data_tgl_lkh);
        $this->db->update('data_lkh',['status' => 1]);
    }

    public function GetDatalkhRank($user_id, $rank1, $rank2, $status)
    {
    	$this->db->select('*')
    			 ->from('data_lkh')
    			 ->where('user_id', $user_id)
    			 ->where('status',$status)
    			 ->where("tgl_lkh::date BETWEEN '$rank1' and '$rank2'", NULL, FALSE )
    			 ->order_by('tgl_lkh,jam_mulai','asc');
    	return $this->db->get();
    }

    public function jadwal_lkh_limit($user_id='', $limit='')
    {
        $tgl_now = date('Y-m-d');
        $tgl_end = tgl_minus($tgl_now, 32);

        $this->db->select('a.id, 
                            rentan_tanggal')
                ->from("(select * from mf_users a, (select * from rentan_tanggal('$tgl_end','$tgl_now')) as tanggal) as a")
                ->join('v_jadwal_kerja_users b',"((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day)and b.user_id=a.id)",'left',false)
                ->join('v_jadwal_kerja_users_shift c',"(a.id = c.user_id and c.start_shift=a.rentan_tanggal)",'left',false)
                ->join('v_jadwal_kerja_users_notfixed d',"((rentan_tanggal >= d.start_date and rentan_tanggal <= d.end_date and extract('isodow' from a.rentan_tanggal) = d.day_id)and d.user_id=a.id)",'left',false)
                ->join('days_off e',"(rentan_tanggal >= e.start_date and rentan_tanggal <= e.end_date)",'left',false)
                ->join('data_cuti f',"(a.id = f.user_id and f.deleted =1 and (rentan_tanggal >= f.start_date and rentan_tanggal <= f.end_date))",'left',false)
                ->where('a.id', $user_id)
                ->where("(b.start_time != '00:00:00' or c.start_time != '00:00:00' or d.start_time != '00:00:00') and e.id is null and f.user_id is null",null,false)
                ->group_by('1,2')
                ->order_by('rentan_tanggal','desc')
                ->limit($limit);
        return $this->db->get();

    }

    public function cekJadwalLkh($user_id, $tanggal)
    {
         $this->db->select('a.id, 
                            rentan_tanggal,
                            b.start_time, 
                            b.end_time,
                            c.start_time as start_time_shift,
                            c.end_time as end_time_shift,
                            d.start_time as start_time_notfixed, 
                            d.end_time as end_time_notfixed,
                            e.id as daysoff_id,
                            f.user_id as cuti')
                ->from("(select * from mf_users a, (select * from rentan_tanggal('$tanggal','$tanggal')) as tanggal) as a")
                ->join('v_jadwal_kerja_users b',"((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day)and b.user_id=a.id)",'left',false)
                ->join('v_jadwal_kerja_users_shift c',"(a.id = c.user_id and c.start_shift=a.rentan_tanggal)",'left',false)
                ->join('v_jadwal_kerja_users_notfixed d',"((rentan_tanggal >= d.start_date and rentan_tanggal <= d.end_date and extract('isodow' from a.rentan_tanggal) = d.day_id)and d.user_id=a.id)",'left',false)
                ->join('days_off e',"(rentan_tanggal >= e.start_date and rentan_tanggal <= e.end_date)",'left',false)
                ->join('data_cuti f',"(a.id = f.user_id and f.deleted =1 and (rentan_tanggal >= f.start_date and rentan_tanggal <= f.end_date))",'left',false)
                ->where('a.id', $user_id)
                ->where("(b.start_time != '00:00:00' or c.start_time != '00:00:00' or d.start_time != '00:00:00') and e.id is null and f.user_id is null",null,false)
                ->group_by('1,2,3,4,5,6,7,8,9,10')
                ->order_by('rentan_tanggal','desc')
                ->limit(1);
        return $this->db->get();
    }

    public function jumlah_nonver($user_id)
    {
        $this->db->select('count(*)')
                ->where('verifikator', $user_id)
                ->where_in('status','0,4', false);
        return $this->db->get('data_lkh')->row()->count;
    }

}

/* End of file M_data_lkh.php */
/* Location: ./application/models/M_data_lkh.php */