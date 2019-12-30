<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_dinasluar_manual extends CI_Migration {


	public function up() {
		$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'dept_id' => array(
                               'type' 	=> 'integer',
                                'null' => TRUE,
                        ),
                        'user_id' => array(
                               'type' => 'integer[]',
                                'null' => TRUE,
                        ),
                        'tanggal' => array(
                               'type' 	=> 'date',
                               'null' => TRUE,
                        ),
                        'uraian' => array(
                               'type' 	=> 'text',
                               'null' => TRUE,
                        ),
                        'hasil' => array(
                               'type' 	=> 'text',
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
                $this->dbforge->create_table('dinasluar_manual');
	
	}

	public function down() {
		$this->dbforge->drop_table('dinasluar_manual');
	}

}

/* End of file 019_add_dinasluar_manual.php */
/* Location: ./application/migrations/019_add_dinasluar_manual.php */