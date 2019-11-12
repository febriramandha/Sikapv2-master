<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_allowances extends CI_Migration {


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
                        'eselon_id' => array(
                                'type' => 'integer',
                                'null' => TRUE,
                        ),
                        'golongan_id' => array(
                                'type' => 'integer',
                                'null' => TRUE,
                        ),
                        'tpp' => array(
                                'type' => 'float',
                                'null' => TRUE,
                        ),
                        'position' => array(
                                'type' => 'integer',
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
                $this->dbforge->create_table('_allowances');
	
	}

	public function down() {
		$this->dbforge->drop_table('_allowances');
	}


}

/* End of file 003_add_tunjangan_pns.php */
/* Location: ./application/migrations/003_add_tunjangan_pns.php */