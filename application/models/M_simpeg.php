<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Handika Putra
* 2022
*/

class M_simpeg extends CI_Model {
    public function getJabatan($id = NULL)
    {
        $query = $this->db->select('b.id as id_jabatan, b.nama_jabatan, c.kelas_jabatan,c.id as id_kelas_jabatan')
                ->join('simpeg_dev.jabatan b','a.jabatan_id = b.id')
                ->join('simpeg_dev.kelas_jabatan c','b.kelas_jabatan_id = c.id','left')
                ->where('a.unor_id',$id)
                ->get('simpeg_dev.detail_jabatan a')->result();

        return $query;
    }

    public function getKelasJabatan($id = NULL)
    {
        $query = $this->db->select('b.id,b.kelas_jabatan,a.kelas_jabatan_id')
                    ->join('simpeg_dev.kelas_jabatan b','a.kelas_jabatan_id = b.id','left')
                    ->where('a.id',$id)
                    ->get('simpeg_dev.jabatan a')->result();

        return $query;
    }
}

/* End of file M_simpeg.php */
/* Location: ./application/models/M_simpeg.php */