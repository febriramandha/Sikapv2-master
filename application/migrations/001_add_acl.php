<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_acl extends CI_Migration {


	public function up() {
			$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'controller' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100',
                                'null' => TRUE,
                        ),
                        'method' => array(
                                'type' => 'text[]',
                                'null' => TRUE,
                        ),
                        'url' => array(
                                'type' => 'VARCHAR',
                                'null' => TRUE,
                                'constraint' => '255',
                        ), 
                        'type' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '40',
                                'null' => TRUE,
                        ),   
                        'level' => array(
                                'type' => 'integer[]',
                                'null' => TRUE,
                        ),
                        'title' => array(
                                'type' => 'VARCHAR',
                                'null' => TRUE,
                                'constraint' => '255',
                        ), 
                        'icon' => array(
                                'type' => 'VARCHAR',
                                'null' => TRUE,
                                'constraint' => '255',
                        ), 
                        'parent' => array(
                                'type' => 'integer',
                                'null' => TRUE,
                        ),
                        'position' => array(
                                'type' => 'integer',
                                'null' => TRUE,
                                'default' => 0
                        ),
                        'status' => array(
                                'type' => 'integer',
                                'null' => TRUE,
                                'default' => 1
                        ),
               ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('_acl');
	
	}

	public function down() {
		$this->dbforge->drop_table('_acl');
	}

}

/* End of file 001_add_acl.php */
/* Location: ./application/migrations/001_add_acl.php */