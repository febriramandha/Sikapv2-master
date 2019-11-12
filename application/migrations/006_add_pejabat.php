<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_pejabat extends CI_Migration {


	public function up() {
	$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'user_id' => array(
                                'type' 	=> 'VARCHAR',
                                'constraint' => '255',
                                'null' => TRUE,
                        ),
                        'type' => array(
                                'type' 	=> 'VARCHAR',
                                'null' => TRUE,
                                'constraint' => '30',
                                // 'constraint' => "'sekda','asisten','kadis','camat','kepuskes','kepsek'"
                        ),
                        'instansi' => array(
                                'type' => 'integer[]',
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
                $this->dbforge->create_table('pejabat_instansi');
	
	}

	public function down() {
		$this->dbforge->drop_table('pejabat_instansi');
	}

}

/* End of file 006_add_pejabat.php */
/* Location: ./application/migrations/006_add_pejabat.php */