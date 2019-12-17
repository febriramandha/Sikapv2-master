<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Frontend_Controller extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->data['title'] = "Judul";
		$this->output->set_common_meta(
			$this->data['site_name'], // Title
			$this->data['description'], // Description
			$this->data['keywords'] // Keywords
			
		);
	}

}

/* End of file Frontend_Controller.php */
/* Location: ./application/libraries/Frontend_Controller.php */