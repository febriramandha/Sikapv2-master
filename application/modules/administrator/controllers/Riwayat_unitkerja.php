<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Handika Putra
* 2022
*/

class Riwayat_unitkerja extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Riwayat Unit Kerja', 'administrator/riwayat_unitkerja');
		$this->data['title'] = "Riwayat Unit Kerja";
		$this->load->model('m_user');

		
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['sub_title'] 	= "Riwayat Unit Kerja";
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->load->view('riwayat_unitkerja/v_index', $this->data);
	}

    public function indexJson()
	{
		$this->output->unset_template();
		$this->load->library('datatables');
		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));
        $this->datatables->select('a.id, b.dept_name as dept_from, c.dept_name as dept_to,d.nama,d.nip,e.gelar_dpn,e.gelar_blk, a.tgl_pindah')
        	 ->from('mutasi a')
             ->join('mf_departments b','b.id = a.dept_from','left')
            ->join('mf_departments c','c.id = a.dept_to','left')
            ->join('mf_users d','d.id = a.user_id','left')
            ->join('sp_pegawai e','e.user_id = d.id','left')
            ->order_by('a.tgl_pindah','desc')
        	->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)')
        	->add_column('riwayat_pindah','$1','format_tgl_ind(tgl_pindah)')
        	->add_column('action','<span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus data" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'encrypt_url(id,"mutasi_id")');
     		 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(nama)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(nip)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
			if ($rank1 && $rank2) {
        			$this->datatables->where("tgl_pindah::date BETWEEN '$rank1' and '$rank2'", NULL, FALSE );
        	}
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxDel(){
		$this->output->unset_template();

		$del = $this->db->delete('mutasi',['id' => decrypt_url($this->input->get('id'),"mutasi_id")]);

		if ($del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}
}

/* End of file Riwayat_unitkerja.php */
/* Location: ./application/modules/Administrator/controllers/Riwayat_unitkerja.php */