<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_shift_run_users extends CI_Migration {


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
                        'dept_id' => array(
                               'type' 	=> 'integer',
                                'null' => TRUE,
                        ),
                        'schrun_id' => array(
                               'type'   => 'integer',
                                'null' => TRUE,
                        ),
                        'shiftrun_id' => array(
                               'type' 	=> 'integer[]',
                                'null' => TRUE,
                        ),
                        'start_shift' => array(
                                'type' => 'date[]',
                                'null' => TRUE,
                        ),
                        'end_shift' => array(
                                'type' => 'date[]',
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
                $this->dbforge->create_table('shift_run_users');
	
	}

	public function down() {
		$this->dbforge->drop_table('shift_run_users');
	}


}

/* End of file 016_add_shift_run_users.php */
/* Location: ./application/migrations/016_add_shift_run_users.php */