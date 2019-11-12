<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_level extends CI_Migration {

	public function up() {
			$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'level' => array(
                                'type' => 'integer',
                                'null' => TRUE,
                        ),
                        'name' => array(
                                'type' => 'text[]',
                                'null' => TRUE,
                        ),
                        'ket' => array(
                                'type' => 'VARCHAR',
                                'null' => TRUE,
                                'constraint' => '255',
                        ), 
                        'status' => array(
                                'type' => 'integer',
                                'null' => TRUE,
                                'default' => 1
                        ),
               ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('_level');
	
	}

	public function down() {
		$this->dbforge->drop_table('_level');
	}


}

/* End of file 002_add_level.php */
/* Location: ./application/migrations/002_add_level.php */