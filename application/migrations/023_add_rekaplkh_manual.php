<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_rekaplkh_manual extends CI_Migration {


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
                        'schlkhmanual_id' => array(
                               'type'   => 'integer',
                                'null' => TRUE,
                        ),                    
                        'jumlah_laporan' => array(
                               'type'   => 'integer',
                                'null' => TRUE,
                        ),
                        'total_laporan' => array(
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
               ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('rekaplkh_manual');
	
	}

	public function down() {
		$this->dbforge->drop_table('rekaplkh_manual');
	}

	 

}

/* End of file 023_add_rekaplkh_manual.php */
/* Location: ./application/migrations/023_add_rekaplkh_manual.php */