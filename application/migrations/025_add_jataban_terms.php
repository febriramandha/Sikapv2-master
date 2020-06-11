<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_jataban_terms extends CI_Migration {


	public function up() {
		$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'eselon_id' => array(
                               'type'   => 'integer',
                                'null' => TRUE,
                        ),
                        'jabatan_tingkat' => array(
                               'type'   => 'VARCHAR',
                                'constraint' => '255',
                                'null' => TRUE,
                        ),
                        'kategori_instansi' => array(
                               'type'   => 'VARCHAR',
                                'constraint' => '20',
                                'null' => TRUE,
                        ),
                        'jabatanjenis_id' => array(
                               'type'   => 'integer',
                                'null' => TRUE,
                                'default' => 1
                        ),
                        'deleted' => array(
                               'type'   => 'integer',
                                'null' => TRUE,
                                'default' => 1
                        ),
               ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('_jabatan_terms');

                 $data = array(
					            array('eselon_id' => '3', 'jabatan_tingkat' => 'Sekretaris Daerah', 'kategori_instansi' => 'sekda'),
					            
					            array('eselon_id' => '4', 'jabatan_tingkat' => 'Asisten', 'kategori_instansi' => 'sekda'),
					            array('eselon_id' => '4', 'jabatan_tingkat' => 'Staf Ahli', 'kategori_instansi' => 'sekda'),
					            array('eselon_id' => '4', 'jabatan_tingkat' => 'Sekretaris DPRD', 'kategori_instansi' => 'setwan'),
					            array('eselon_id' => '4', 'jabatan_tingkat' => 'Inspektur', 'kategori_instansi' => 'inspektorat'),
					            array('eselon_id' => '4', 'jabatan_tingkat' => 'Kepala Badan', 'kategori_instansi' => 'badan'),
					            array('eselon_id' => '4', 'jabatan_tingkat' => 'Kepala Dinas', 'kategori_instansi' => 'dinas'),
					            array('eselon_id' => '4', 'jabatan_tingkat' => 'Direktur RSUD', 'kategori_instansi' => 'rs'),
					            array('eselon_id' => '4', 'jabatan_tingkat' => 'Kepala Satuan', 'kategori_instansi' => 'satpolpp'),

					            array('eselon_id' => '5', 'jabatan_tingkat' => 'Inspektur Pembantu', 'kategori_instansi' => 'inspektorat'),
					            array('eselon_id' => '5', 'jabatan_tingkat' => 'Kepala Bagian', 'kategori_instansi' => 'bagian'),
					            array('eselon_id' => '5', 'jabatan_tingkat' => 'Kepala Bagian', 'kategori_instansi' => 'setwan'),
					            array('eselon_id' => '5', 'jabatan_tingkat' => 'Camat', 'kategori_instansi' => 'camat'),
					            array('eselon_id' => '5', 'jabatan_tingkat' => 'Sekretaris', 'kategori_instansi' => 'inspektorat'),
					            array('eselon_id' => '5', 'jabatan_tingkat' => 'Sekretaris', 'kategori_instansi' => 'badan'),
					            array('eselon_id' => '5', 'jabatan_tingkat' => 'Sekretaris', 'kategori_instansi' => 'dinas'),
					            array('eselon_id' => '5', 'jabatan_tingkat' => 'Sekretaris', 'kategori_instansi' => 'satpolpp'),
					            array('eselon_id' => '5', 'jabatan_tingkat' => 'Wakil Direktur', 'kategori_instansi' => 'rs'),

					            array('eselon_id' => '6', 'jabatan_tingkat' => 'Kepala Bidang', 'kategori_instansi' => 'badan'),
					            array('eselon_id' => '6', 'jabatan_tingkat' => 'Kepala Bidang', 'kategori_instansi' => 'dinas'),
					            array('eselon_id' => '6', 'jabatan_tingkat' => 'Kepala Bidang', 'kategori_instansi' => 'satpolpp'),
					            array('eselon_id' => '6', 'jabatan_tingkat' => 'Kepala Bagian', 'kategori_instansi' => 'rs'),
					            array('eselon_id' => '6', 'jabatan_tingkat' => 'Kepala Bidang', 'kategori_instansi' => 'rs'),
					            array('eselon_id' => '6', 'jabatan_tingkat' => 'Sekretaris', 'kategori_instansi' => 'camat'),

					            array('eselon_id' => '7', 'jabatan_tingkat' => 'Lurah', 'kategori_instansi' => 'lurah'),
					            array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Sub Bagian', 'kategori_instansi' => 'setwan'),
					            array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Sub Bagian', 'kategori_instansi' => 'bagian'),
					            array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Sub Bagian', 'kategori_instansi' => 'badan'),
					            array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Sub Bagian', 'kategori_instansi' => 'dinas'),
					            array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Sub Bagian', 'kategori_instansi' => 'satpolpp'),
					           	array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Sub Bidang', 'kategori_instansi' => 'bagian'),
					           	array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Sub Bidang', 'kategori_instansi' => 'badan'),
					           	array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Sub Bidang', 'kategori_instansi' => 'dinas'),
					           	array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Sub Bidang', 'kategori_instansi' => 'satpolpp'),
					           	array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Seksi', 'kategori_instansi' => 'badan'),
					           	array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Seksi', 'kategori_instansi' => 'dinas'),
					           	array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Seksi', 'kategori_instansi' => 'satpolpp'),
					           	array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Seksi', 'kategori_instansi' => 'camat'),
					           	array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala UPT', 'kategori_instansi' => 'badan'),
					           	array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala UPT', 'kategori_instansi' => 'dinas'),
					           	array('eselon_id' => '7', 'jabatan_tingkat' => 'Kepala Puskesmas', 'kategori_instansi' => 'puskesmas'),

					           	array('eselon_id' => '8', 'jabatan_tingkat' => 'Sekretaris', 'kategori_instansi' => 'lurah'),
					           	array('eselon_id' => '8', 'jabatan_tingkat' => 'Kepala Seksi', 'kategori_instansi' => 'lurah'),
					           	array('eselon_id' => '8', 'jabatan_tingkat' => 'Kepala Sub Bagian', 'kategori_instansi' => 'camat'),
					           	array('eselon_id' => '8', 'jabatan_tingkat' => 'Kepala Sub Bagian', 'kategori_instansi' => 'upt'),
					           	array('eselon_id' => '8', 'jabatan_tingkat' => 'Kepala TU', 'kategori_instansi' => 'sekolah_smp'),
					           	// array('id' => 0,'eselon_id' => '11', 'jabatan_tingkat' => 'Tidak Ada Tingkat','jabatanjenis_id' => 0),
					         );
			         $this->db->insert_batch('_jabatan_terms', $data);
	
	}

	public function down() {
		$this->dbforge->drop_table('_jabatan_terms');
	}

}

/* End of file 025_add_jataban_terms.php */
/* Location: ./application/migrations/025_add_jataban_terms.php */