<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Hook_akses {

	public function run() 
   {
		$this->ci =& get_instance();

		$class = $this->router->fetch_class();
		$white_list = array('push');

		if (array_search($class, $white_list) == FALSE) {
			$this->ci->load->model('M_acl');
			if($this->ci->M_acl->privilage()==FALSE)
			{
		   		show_404(); 
			}
		}
		
   }

}

/* End of file Hook_akses.php */
/* Location: ./application/hooks/Hook_akses.php */