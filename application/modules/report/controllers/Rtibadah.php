<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Rtibadah extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Laporan Ibadah', 'report/rtibadah');
		$this->data['title'] = "Laporan Umum";
		$this->load->model(['m_data_lkh','m_verifikator','m_instansi','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
    	$this->load->js('public/themes/plugin/datatables/dataTables.rowsGroup.js');
    	$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Laporan Ibadah";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('rtibadah/v_index', $this->data);
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('mod');
		if ($this->mod == "DataPegawai") {
			    $dept_id 		= decrypt_url($this->input->get('instansi'),'instansi');
        		$data_pegawai 	= $this->m_user->GetUserAllAktifDept($dept_id)->result();
        		$res='';
        		foreach ($data_pegawai as $row ){
		          		$res.= "<option value='".encrypt_url($row->id,'user_id_ibadah')."'>$row->nama ($row->nip)</option>";
		          }
		        $this->output->set_output($res);
		} elseif ($this->mod == "CekAgama") {
				$data_agama ='';
				if ($this->input->get('pegawai')) {
					$data_agama = $this->db->select('agama_id')->get_where('sp_pegawai',['user_id' => decrypt_url($this->input->get('pegawai'),'user_id_ibadah')])->row();
				}
				
				$agama_id = 1;
				if ($data_agama) {
						$agama_id = $data_agama->agama_id;
				}

				$data = array('agama_id' => $agama_id, );

				$this->result = array('status' => true,
			    			   		   'message' => 'Berhasil mengabil data',
			    			   		   'data' => $data);
				$this->output->set_output(json_encode($this->result));
		}
		
	}

	public function ibadahJson()
	{
		$this->output->unset_template();
		
		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));
		$user_id = decrypt_url($this->input->post('pegawai'),'user_id_ibadah');
		$agama_id = $this->input->post('agama_id');
		$this->load->library('datatables');
		if ($agama_id != 1) {
			 $this->datatables->select('id, tgl_ibadah, kegiatan, tempat')
        	->from('ibadah_nonmus')
        	->order_by('tgl_ibadah','desc')
        	->add_column('tgl_ibadah_tabel','$1','tglInd_hrtabel(tgl_ibadah)')
        	->add_column('tgl_ibadah_tabel','$1','tglInd_hrtabel(tgl_ibadah)')
	        				->add_column('tempat1','$1','kegiatan')
	        				->add_column('tempat2','$1','tempat');
		}else {
			$this->datatables->select('a.id, tgl_ibadah, ibadah_id, t_zuhur, t_ashar, b.nama as ket')
	        				->from('ibadah_muslim a')
	        				->join('ibadah b','a.ibadah_id=b.id','left')
	        				->order_by('tgl_ibadah','desc')
	        				->add_column('tgl_ibadah_tabel','$1','tglInd_hrtabel(tgl_ibadah)')
	        				->add_column('tempat1','$1','t_zuhur')
	        				->add_column('tempat2','$1','t_ashar');
		}
    	if ($rank1 && $rank2) {
    			$this->datatables->where("tgl_ibadah::date BETWEEN '$rank1' and '$rank2'", NULL, FALSE );
    	}
    	if ($user_id) {
    		$this->datatables->where('user_id', $user_id);
    	}else {
    		$this->datatables->where('user_id', '0');
    	}
        return $this->output->set_output($this->datatables->generate());
	}



}

/* End of file Rtibadah.php */
/* Location: ./application/modules/report/controllers/Rtibadah.php */