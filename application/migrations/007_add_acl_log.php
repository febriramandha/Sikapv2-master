<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_acl_log extends CI_Migration {


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
                        'acl_id' => array(
                                'type' 	=> 'integer',
                                'null' => TRUE,
                        ),
                        'function' => array(
                                'type' 	=> 'VARCHAR',
                                'constraint' => '50',
                                'null' => TRUE,
                        ),
                        'hit' => array(
                                'type' 	=> 'integer',
                                'null' => TRUE,
                                'default' => 1
                        ),
                        'created_at' => array(
                                'type' => 'TIMESTAMP',
                                'null' => TRUE,
                        ),
                        'updated_at' => array(
                                'type' => 'TIMESTAMP',
                                'null' => TRUE,
                        ),
               ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('_acl_log');
	
	}

	public function down() {
		$this->dbforge->drop_table('_acl_log');
	}


}

/* End of file 007_add_acl_log.php */
/* Location: ./application/migrations/007_add_acl_log.php */