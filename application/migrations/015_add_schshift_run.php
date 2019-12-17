<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_schshift_run extends CI_Migration {

	public function up() {
		$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'name' => array(
                                'type' => 'VARCHAR',
                                'null' => TRUE,
                                'constraint' => '100',
                        ),
                        'start_date' => array(
                                'type' => 'date',
                                'null' => TRUE,
                        ),
                        'end_date' => array(
                                'type' => 'date',
                                'null' => TRUE,
                        ),
                        'dept_id' => array(
                               'type' => 'integer[]',
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
                $this->dbforge->create_table('schshift_run');
	
	}

	public function down() {
		$this->dbforge->drop_table('schshift_run');
	}

}

/* End of file 015_add_schshift_run.php */
/* Location: ./application/migrations/015_add_schshift_run.php */