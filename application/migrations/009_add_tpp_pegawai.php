<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_tpp_pegawai extends CI_Migration {


	public function up() {
		$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'user_id' => array(
                               'type' 	=> 'integer',
                                'null' => TRUE,
                        ),
                        'allowance_id' => array(
                               'type' 	=> 'integer',
                                'null' => TRUE,
                        ),
                        'start_date' => array(
                                'type' 	=> 'date',
                                'null' => TRUE,
                        ),
                        'end_date' => array(
                                'type' 	=> 'date',
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
                        'deleted' => array(
                               'type' => 'integer',
                               'null' => TRUE,
                               'default' => 1
                        ),
               ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('tpp_pegawai');
	
	}

	public function down() {
		$this->dbforge->drop_table('tpp_pegawai');
	}

}

/* End of file 009_add_tpp_pegawai.php */
/* Location: ./application/migrations/009_add_tpp_pegawai.php */