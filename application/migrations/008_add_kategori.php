<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_kategori extends CI_Migration {


	public function up() {
		$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'name' => array(
                               'type' 	=> 'VARCHAR',
                                'constraint' => '100',
                                'null' => TRUE,
                        ),
                        'description' => array(
                                'type' 	=> 'text',
                                'null' => TRUE,
                        ),
                        'slug' => array(
                                'type' 	=> 'VARCHAR',
                                'constraint' => '100',
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
                $this->dbforge->create_table('_kategori');
	
	}

	public function down() {
		$this->dbforge->drop_table('_kategori');
	}
}

/* End of file 008_add_kategori.php */
/* Location: ./application/migrations/008_add_kategori.php */