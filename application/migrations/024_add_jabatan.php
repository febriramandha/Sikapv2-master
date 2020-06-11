<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_jabatan extends CI_Migration {

	public function up() {
		$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'instansi_id' => array(
                               'type'   => 'integer',
                                'null' => TRUE,
                        ),
                        'nama_jabatan' => array(
                               'type'   => 'VARCHAR',
                                'constraint' => '255',
                                'null' => TRUE,
                        ),
                        'parent' => array(
                               'type' 	=> 'integer',
                                'null' => TRUE,
                        ),
                        'jabatanterm_id' => array(
                               'type'   => 'integer',
                                'null' => TRUE,
                        ),
                        'created_at' => array(
                                'type' => 'TIMESTAMP',
                                'null' => TRUE,
                        ),
                        'created_by' => array(
                                'type' => 'integer',
                                'null' => TRUE,
                        ),
                        'updated_at' => array(
                                'type' => 'TIMESTAMP',
                                'null' => TRUE,
                        ),
                        'updated_by' => array(
                               'type' => 'integer',
                               'null' => TRUE,
                        ),
                        'status' => array(
                               'type'   => 'integer',
                                'null' => TRUE,
                                'default' => 1
                        ),
               ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('_jabatan');
	
	}

	public function down() {
		$this->dbforge->drop_table('_jabatan');
	}

}

/* End of file 024_add_jabatan.php */
/* Location: ./application/migrations/024_add_jabatan.php */