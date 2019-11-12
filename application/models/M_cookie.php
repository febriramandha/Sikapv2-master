<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_cookie extends CI_Model {

	public function GetjsonPlatform($login_id='')
	{
		$this->db->where("cookie_expires < NOW() - INTERVAL '1 minute'");
		$this->db->where('login_id', $login_id);
		$this->db->where('deleted', 1);
		$this->db->update('cookies', ['deleted' => 0]);
		$this->load->library('datatables');
        $this->datatables->select('id, browser_agent, version_agent, platform_agent, last_login, cookie_expires, cookie')
        	->from('cookies')
        	->add_column('aksi', '$1', 'cek_cookie_login(cookie)')
        	->add_column('id', '$1', '_icon(browser_agent)')
        	->add_column('cookie_expires', '$1', 'format_waktu_ind(cookie_expires)')
        	->add_column('last_login', '$1', 'format_waktu_ind(last_login)')
        	->where('login_id', $login_id)
        	->where('deleted','1');
        return $this->datatables->generate();
	}

}

/* End of file M_cookie.php */
/* Location: ./application/models/M_cookie.php */