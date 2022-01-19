<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_apel extends CI_Model {
    
    public function get_sch_apel($id)
    {
        $this->db->select('a.id, a.tgl_apel, a.start_time, a.ket, b.name')
            ->from('sch_apel a')
            ->join('_jenis_apel b','a.jenis_apel_id=b.id')
            ->where('a.id', $id);
        return $this->db->get()->row();
    }

    public function get_apel()
    {
        $this->db->select('*')
                 ->order_by('id','desc');   
		return $this->db->get('sch_apel');
    }
}