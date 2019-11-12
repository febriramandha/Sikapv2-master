<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_mf_machines extends CI_Migration {


	public function up() {
		$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'dept_id' => array(
                                'type' => 'integer',
                                'null' => TRUE,
                        ),
                        'name' => array(
                                'type' 	=> 'VARCHAR',
                                'constraint' => '255',
                                'null' => TRUE,
                        ),
                        'machine_number' => array(
                                'type'  => 'integer',
                        ),
                        'ip' => array(
                                'type' 	=> 'VARCHAR',
                                'constraint' => '20',
                                'null' => TRUE,
                        ),
                        'port' => array(
                                'type'  => 'VARCHAR',
                                'constraint' => '20',
                                'null' => TRUE,
                        ),
                        'password' => array(
                                'type'  => 'VARCHAR',
                                'constraint' => '35',
                                'null' => TRUE,
                        ),
                        'sn' => array(
                               'type' 	=> 'VARCHAR',
                                'constraint' => '100',
                                'null' => TRUE,
                        ),
                        'ket' => array(
                               'type'   => 'VARCHAR',
                                'constraint' => '100',
                                'null' => TRUE,
                        ),
                        'status' => array(
                                'type' => 'integer',
                                'null' => TRUE,
                                'default' =>1
                        ),
                        
               ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('_mf_machines');
	
	}

	public function down() {
		$this->dbforge->drop_table('_mf_machines');
	}


}

/* End of file 005_add_mf_machines.php */
/* Location: ./application/migrations/005_add_mf_machines.php */