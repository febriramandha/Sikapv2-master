<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_data_cuti extends CI_Migration {


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
                        'cuti_id' => array(
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
                $this->dbforge->create_table('data_cuti');
	
	}

	public function down() {
		$this->dbforge->drop_table('data_cuti');
	}


}

/* End of file 013_add_data_cuti.php */
/* Location: ./application/migrations/013_add_data_cuti.php */