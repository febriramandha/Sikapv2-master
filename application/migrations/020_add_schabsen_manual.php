<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_schabsen_manual extends CI_Migration {

	public function up() {
		$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'dept_id' => array(
                               'type'   => 'integer',
                                'null' => TRUE,
                        ),
                        'user_id' => array(
                               'type'   => 'integer[]',
                                'null' => TRUE,
                        ),
                        'hari_id' => array(
                               'type'   => 'integer[]',
                                'null' => TRUE,
                        ),
                        'name' => array(
                                'type' 	=> 'varchar',
                                'null'  => TRUE,
                                'constraint' => '100',
                        ),
                        'start_date' => array(
                                'type' 	=> 'date',
                                'null' => TRUE,
                        ),
                        'end_date' => array(
                                'type' 	=> 'date',
                                'null' => TRUE,
                        ),
                        'absen_in' => array(
                               'type'   => 'integer',
                                'null' => TRUE,
                        ),
                        'absen_out' => array(
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
                        'type_absen' => array(
                               'type' => 'varchar',
                               'null' => TRUE,
                               'constraint' => '20',
                        ),
               ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('schabsen_manual');
	
	}

	public function down() {
		$this->dbforge->drop_table('schabsen_manual');
	}

}

/* End of file 020_add_schabsen_manual.php */
/* Location: ./application/migrations/020_add_schabsen_manual.php */