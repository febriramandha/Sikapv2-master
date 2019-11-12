<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_allowances_reduction extends CI_Migration {


	public function up() {
	$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'name' => array(
                                'type' 	=> 'VARCHAR',
                                'constraint' => '255',
                                'null' => TRUE,
                        ),
                        'kode' => array(
                                'type' 	=> 'VARCHAR',
                                'constraint' => '50',
                                'null' => TRUE,
                        ),
                        'reduction' => array(
                                'type' => 'decimal',
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
                $this->dbforge->create_table('_allowances_reduction');
	
	}

	public function down() {
		$this->dbforge->drop_table('_allowances_reduction');
	}


}

/* End of file 004_add_pemotongan_tunjangan.php */
/* Location: ./application/migrations/004_add_pemotongan_tunjangan.php */