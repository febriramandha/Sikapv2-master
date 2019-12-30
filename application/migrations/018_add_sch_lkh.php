<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_sch_lkh extends CI_Migration {


	public function up() {
		$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'name' => array(
                                'type' 	=> 'varchar',
                                'null'  => TRUE,
                                'constraint' => '30',
                        ),
                        'count_inday' => array(
                               'type' 	=> 'integer',
                                'null' => TRUE,
                        ),
                        'count_verday' => array(
                               'type' 	=> 'integer',
                                'null' => TRUE,
                        ),
                        'ket' => array(
                                'type' 	=> 'varchar',
                                'null'  => TRUE,
                                'constraint' => '255',
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
                $this->dbforge->create_table('sch_lkh');
	
	}

	public function down() {
		$this->dbforge->drop_table('sch_lkh');
	}

}

/* End of file 018_add_sch_lkh.php */
/* Location: ./application/migrations/018_add_sch_lkh.php */