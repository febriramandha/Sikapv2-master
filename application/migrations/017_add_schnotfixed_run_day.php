<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_schnotfixed_run_day extends CI_Migration {

	public function up() {
		$this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'integer',
                                'auto_increment' => TRUE
                        ),
                        'run_id' => array(
                                'type' => 'integer',
                                'null' => TRUE,
                        ),
                        'class_id' => array(
                               'type' => 'integer[]',
                                'null' => TRUE,
                        ),
                        'day_id' => array(
                               'type' => 'integer[]',
                                'null' => TRUE,
                        ),
               ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('schnotfixed_run_day');
	
	}

	public function down() {
		$this->dbforge->drop_table('schnotfixed_run_day');
	}

}

/* End of file 017_add_schnotfixed_run_day.php */
/* Location: ./application/migrations/017_add_schnotfixed_run_day.php */