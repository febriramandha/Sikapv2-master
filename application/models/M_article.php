<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class M_article extends CI_Model {

	public function GetAticleAll()
	{
		$this->db->select('a.id,a.created_at, a.title, a.description, b.name as kategori, a.content')
				 ->join('_kategori b','a.kategori_id=b.id','left')
				 ->where('a.status','publish')
				 ->order_by('id','desc');
		return $this->db->get('articles a');

	}

}

/* End of file M_article.php */
/* Location: ./application/models/M_article.php */