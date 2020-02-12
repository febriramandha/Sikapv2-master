<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_schlkh_manual extends CI_Migration {


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
               ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('schlkh_manual');
	
	}

	public function down() {
		$this->dbforge->drop_table('schlkh_manual');
	}

}

/* End of file 022_add_schlkh_manual.php */
/* Location: ./application/migrations/022_add_schlkh_manual.php */