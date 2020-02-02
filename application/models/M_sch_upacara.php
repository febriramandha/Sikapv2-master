<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_sch_upacara extends CI_Model {

	public function GetTahun()
	{
		$this->db->select("date_part('year',tanggal) as tahun")
						 ->group_by("date_part('year',tanggal)")
						 ->order_by("date_part('year',tanggal)",'desc',false);
		 return $this->db->get('sch_upacara');
	}

	public function GetJadwal($tahun)
	{
		$this->db->where("date_part('year',tanggal)",$tahun,false)
				 ->order_by('tanggal, jam_mulai','desc');
		return $this->db->get('sch_upacara');
	}

}

/* End of file M_sch_upacara.php */
/* Location: ./application/models/M_sch_upacara.php */