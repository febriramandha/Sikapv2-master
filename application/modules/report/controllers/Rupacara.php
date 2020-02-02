<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Rupacara extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Laporan Upacara', 'report/rupacara');
		$this->data['title'] = "Laporan Umum";
		$this->load->model(['m_instansi','m_sch_upacara']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Laporan Upacara";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->data['shcupacara_tahun'] = $this->m_sch_upacara->GetTahun()->result();
		$this->load->view('rupacara/v_index', $this->data);
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('mod');
		if ($this->mod == "jadwalUpacara") {
			    $tahun = $this->input->get('tahun');
        		$data_upacara = $this->m_sch_upacara->GetJadwal($tahun)->result();

        		$res='';
        		foreach ($data_upacara as $row ){
		          		$res.= "<option value='".encrypt_url($row->id,'schupacara_id')."'>".tgl_ind_bulan($row->tanggal)." (".jm($row->jam_mulai).") : $row->ket</option>";
		          }
		        $this->output->set_output($res);
		}elseif ($this->mod == "a") {
			
			
		}
		
	}

	public function indexJson()
	{
		$this->output->unset_template();

		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$tahun   = $this->input->post('tahun');
		$jadwal  = decrypt_url($this->input->post('jadwal'),'schupacara_id');
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.nama,status_pegawai,gelar_dpn,gelar_blk,b.golongan, b.pangkat, a.eselon_id, c.hadir, c.id as absenupacara_id')
        	->from('absen_upacara c')
        	->join('v_users_all a','a.id=c.user_id')
        	->join('_golongan b','a.golongan_id=b.id','left')
        	->order_by('no_urut')
        	->add_column('nama_gelar','$1','nama_gelar(nama, gelar_dpn,gelar_blk)')
        	->add_column('golongan','$1/$2','golongan,pangkat')
        	->add_column('cek1','$1',"Cek_upacara_hadir(1,hadir)")
        	->add_column('cek2','$1',"Cek_upacara_hadir(2,hadir)")
        	->add_column('cek3','$1',"Cek_upacara_hadir(3,hadir)")
        	->add_column('ket','$1','upacara_ket(hadir)');
    	 if ($instansi) {
	        $this->datatables->where('c.dept_id', $instansi);
	     }else{
	     	 $this->datatables->where('c.dept_id', '0');
	     }	
	     if ($jadwal) {
	         $this->datatables->where('c.sch_upacara_id', $jadwal);
	     }else {
	     	$this->datatables->where('c.sch_upacara_id', '0');
	     }       
        return $this->output->set_output($this->datatables->generate());
	}

}

/* End of file Rupacara.php */
/* Location: ./application/modules/report/controllers/Rupacara.php */