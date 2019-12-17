<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_shift_run extends CI_Migration {


	public function up() {
		$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'kd_shift' => array(
                               'type' 	=> 'varchar',
                                'null' => TRUE,
                                'constraint' => '30',
                        ),
                        'class_id' => array(
                               'type'   => 'varchar',
                                'null' => TRUE,
                                'constraint' => '30',
                        ),
                        'dept_id' => array(
                               'type' => 'integer[]',
                                'null' => TRUE,
                        ),
                        'ket' => array(
                               'type'   => 'varchar',
                                'null' => TRUE,
                                'constraint' => '255',
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
                               'type' => 'integer',
                               'null' => TRUE,
                               'default' => 1
                        ),
                        'deleted' => array(
                               'type' => 'integer',
                               'null' => TRUE,
                               'default' => 1
                        ),
               ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('shift_run');
	
	}

	public function down() {
		$this->dbforge->drop_table('shift_run');
	}

}

/* End of file 011_add_shift_run.php */
/* Location: ./application/migrations/011_add_shift_run.php */