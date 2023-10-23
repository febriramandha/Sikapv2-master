<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_absen_wirid extends CI_Model {

	public function GetAbsenWirid($user_id='', $rank1='', $rank2='')
	{
		$this->db->select('c.id, d.tgl_apel, d.start_time, d.ket, c.hadir')
			->from('apel_pegawai c')
			->join('sch_apel d','c.sch_apel_id=d.id')
			->order_by('d.tgl_apel','desc')
			->where('c.user_id', $user_id)
			->where("d.tgl_apel BETWEEN '$rank1' and '$rank2'", NULL, FALSE );
		return $this->db->get();
	}
}
