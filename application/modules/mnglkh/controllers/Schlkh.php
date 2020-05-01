	<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Schlkh extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jadwal LKH', 'mnglkh/schlkh');
		$this->data['title'] = "Manajemen LKH";
		$this->load->model('m_instansi');
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->js('public/themes/plugin/ckeditor/ckeditor.js');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
    	
	}

	public function index()
	{
		$this->data['sub_title']  = "Jadwal Laporan Kerja Harian";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('schlkh/v_index', $this->data);
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Jadwal LKH";
		$this->breadcrumbs->push('Tambah Jadwal LKH', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptIDCountParent($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('schlkh/v_add', $this->data);
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('nama', 'nama skedul', 'required');
		$this->form_validation->set_rules('rank1', 'tanggal mulai', 'required');
		$this->form_validation->set_rules('rank2', 'tanggal berakhir', 'required');
		$this->form_validation->set_rules('lkh_in', 'waktu hari LKH input', 'required|numeric');
		$this->form_validation->set_rules('lkh_ver', 'waktu hari LKH verifikasi', 'required|numeric');
		$this->form_validation->set_rules('isi', 'pesan pengumuman', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));

		if ($rank1 >  $rank2) {
			$this->form_validation->set_rules('rank1', 'tanggal mulai dan tanggal berakhir', 'tidak sesuai');
		}
		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');	
			$instansi = str_replace(['[', ']', '"'],['{', '}',''], json_encode($this->input->post('instansi')));		
			if ($this->mod == "add") {
				$data = array(
							  'dept_id' 		=> $instansi,
							  'name' 		    => $this->input->post('nama'),
							  'start_date' 		=> $rank1,
							  'end_date' 		=> $rank2,
							  'count_inday' 	=> $this->input->post('lkh_in'),
							  'count_verday'	=> $this->input->post('lkh_ver'),
							  'ket' 			=> $this->input->post('isi'),
							  'created_at'		=> date('Y-m-d H:i:s'),
							  'created_by'		=> $this->session->userdata('tpp_user_id'),
				 );
				$this->return = $this->db->insert('sch_lkh',$data);

				if ($this->return) {
					 $this->result = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			    'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				$data = array(
							  'dept_id' 		=> $instansi,
							  'name' 		    => $this->input->post('nama'),
							  'start_date' 		=> $rank1,
							  'end_date' 		=> $rank2,
							  'count_inday' 	=> $this->input->post('lkh_in'),
							  'count_verday'	=> $this->input->post('lkh_ver'),
							  'ket' 			=> $this->input->post('isi'),
							  'updated_at'		=> date('Y-m-d H:i:s'),
							  'updated_by'		=> $this->session->userdata('tpp_user_id'),
				 );
				$this->return = $this->db->update('sch_lkh', $data, ['id' => $this->input->post('id')]);

				if ($this->return) {
					 $this->result = array('status' => true,
				    			   'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			   'message' => 'Data gagal disimpan');
				}
			}

		}else {
			$this->result = array('status' => false,
				    		'message' => validation_errors(),);
		}

		if ($this->result) {
			$this->output->set_output(json_encode($this->result));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$this->load->library('datatables');
        $this->datatables->select('a.id, name, start_date, end_date, count_inday, count_verday, ket,json_data_instansi')
        	->from('sch_lkh a')
        	->join("(select a.id as schlkh_id, 
					json_build_object(
							'data_instansi', json_agg((dept_name, path_info::text, level) ORDER BY path_info)
					) as json_data_instansi
					from (SELECT id, unnest(dept_id) as dept_id FROM sch_lkh) as a
					join v_instansi_all b on a.dept_id=b.id
					GROUP BY 1) as instansi_all",'instansi_all.schlkh_id=a.id','left')
        	->order_by('a.id','desc')
        	->add_column('start_date','$1 - $2','format_tgl_ind(start_date), format_tgl_ind(end_date)')
        	->add_column('sch_name','$1','sch_name(name, start_date)')
        	->add_column('dept_name','<div class="m-0 p-1 panel-geser">$1</div>','instansi_expl(json_data_instansi)')
        	->add_column('action', '<a href="'.base_url('mnglkh/schlkh/edit/').'$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>
					              <span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus jadwal" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'id');
        	if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(name)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
        return $this->output->set_output($this->datatables->generate());
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Jadwal LKH";
		$this->breadcrumbs->push('Edit Jadwal LKH', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptIDCountParent($this->session->userdata('tpp_dept_id'))->result();
		$this->data['sch_lkh']		= $this->db->get_where('sch_lkh',['id' => $id])->row();
		$this->load->view('schlkh/v_edit', $this->data);
	}

	public function AjaxDel()
	{
		$this->output->unset_template();
		$this->del = $this->db->delete('sch_lkh',['id' => $this->input->get('id')]);
		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}	
}

/* End of file Schlkh.php */
/* Location: ./application/modules/Mnglkh/controllers/Schlkh.php */