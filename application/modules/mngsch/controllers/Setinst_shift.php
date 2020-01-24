<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Setinst_shift extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jam Shift', 'mngsch/setinst-shift');
		$this->data['title'] = "Manajemen Jadwal";
		$this->load->model(['m_instansi','m_sch_class']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Jam Shift";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('setinst_shift/v_index', $this->data);
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Jam Shift";
		$this->breadcrumbs->push('Tambah Shift', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptIDCountParent($this->session->userdata('tpp_dept_id'))->result();
		$this->data['sch_class']	= $this->m_sch_class->GetSchClassType('shift')->result();
		$this->load->view('setinst_shift/v_add', $this->data);
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Jam Shift";
		$this->breadcrumbs->push('Edit Shift', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptIDCountParent($this->session->userdata('tpp_dept_id'))->result();
		$this->data['sch_class']	= $this->m_sch_class->GetSchClassType('shift')->result();
		$this->data['shift_run']	= $this->db->get_where('shift_run',['id' => $id])->row();
		$this->load->view('setinst_shift/v_edit', $this->data);
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('class', 'jam shift', 'required');
		$this->form_validation->set_rules('kode', 'kode', 'required');
		$this->form_validation->set_rules('ket', 'detail', 'required');
		$this->form_validation->set_rules('instansi[]', 'instansi', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
			$instansi = str_replace(['[', ']', '"'],['{', '}',''], json_encode($this->input->post('instansi')));
			$this->mod = $this->input->post('mod');		
			if ($this->input->post('status')) {
				$status =  1; 
			}else {
				$status =  0; 
			}	

			if ($this->mod == "add") {
				$data = array(
							  'kd_shift' 		=> $this->input->post('kode'),
							  'class_id' 		=> $this->input->post('class'),
							  'dept_id' 		=> $instansi,
							  'ket' 			=> $this->input->post('ket'),
							  'status' 		=> $status,
							  'created_at'		=> date('Y-m-d H:i:s'),
							  'created_by'		=> $this->session->userdata('tpp_user_id')
				 );
				$this->return = $this->db->insert('shift_run',$data);

				if ($this->return) {
					 $this->result = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			    'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				
				$data = array(
							  'kd_shift' 		=> $this->input->post('kode'),
							  'class_id' 		=> $this->input->post('class'),
							  'dept_id' 		=> $instansi,
							  'ket' 			=> $this->input->post('ket'),
							  'status' 			=> $status,
							  'created_at'		=> date('Y-m-d H:i:s'),
							  'created_by'		=> $this->session->userdata('tpp_user_id')
				 );
				$this->return = $this->db->update('shift_run', $data, ['id' => $this->input->post('id')]);

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
		//header('Content-Type: application/json');
		$this->load->library('datatables');
        $this->datatables->select('a.id, kd_shift, class_id, ket, a.status, start_time, end_time, json_data_instansi')
        	->from('shift_run a')
        	->join("(select a.id as shiftrun_id, 
					json_build_object(
							'data_instansi', json_agg((dept_name, path_info::text, level) ORDER BY path_info)
					) as json_data_instansi
					from (SELECT id, unnest(dept_id) as dept_id FROM shift_run) as a
					join v_instansi_all b on a.dept_id=b.id
					GROUP BY 1) as instansi_all",'instansi_all.shiftrun_id=a.id','left')
        	->join('sch_class c','a.class_id=c.id','left')
        	->order_by('a.id','desc')
        	->where('a.deleted',1)
        	->add_column('dept_name','<div class="m-0 p-1 panel-geser">$1</div>','instansi_expl(json_data_instansi)')
        	->add_column('start_time','$1 - $2','jm(start_time), jm(end_time)')
        	->add_column('status','$1','status_user(status)')
        	->add_column('action', '<a href="'.base_url('mngsch/setinst-shift/edit/').'$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>
					              <span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus akun" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'id');
        	if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(kd_shift)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxDel()
	{
		$this->output->unset_template();
		$this->del = $this->db->update('shift_run',['deleted' => 0],['id' => $this->input->get('id')]);
		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus.']));	
		}
	}	

}

/* End of file Setinst_shift.php */
/* Location: ./application/modules/Mngsch/controllers/Setinst_shift.php */