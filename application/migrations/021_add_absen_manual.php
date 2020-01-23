<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_absen_manual extends CI_Migration {

	public function up() {
		$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'user_id' => array(
                               'type'   => 'integer',
                                'null' => TRUE,
                        ),
                        'schabsmanual_id' => array(
                               'type'   => 'integer',
                                'null' => TRUE,
                        ),
                        'tanggal' => array(
                               'type'   => 'date[]',
                                'null' => TRUE,
                        ),                      
                        'status_in' => array(
                               'type'   => 'integer[]',
                                'null' => TRUE,
                        ),
                        'status_out' => array(
                               'type'   => 'integer[]',
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
                $this->dbforge->create_table('absenmanual_data');
	
	}

	public function down() {
		$this->dbforge->drop_table('absenmanual_data');
	}


}

/* End of file 021_add_absen_manual.php */
/* Location: ./application/migrations/021_add_absen_manual.php */