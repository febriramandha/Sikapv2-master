<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_sch_run_users extends CI_Migration {


	public function up() {
		$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'schrun_id' => array(
                               'type' 	=> 'integer',
                               'null' => TRUE,
                        ),
                        'dept_id' => array(
                               'type' 	=> 'integer',
                                'null' => TRUE,
                        ),
                        'user_id' => array(
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
               ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('sch_run_users');
	
	}

	public function down() {
		$this->dbforge->drop_table('sch_run_users');
	}


}

/* End of file 014_add_sch_run_users.php */
/* Location: ./application/migrations/014_add_sch_run_users.php */