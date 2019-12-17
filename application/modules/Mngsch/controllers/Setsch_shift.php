<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setsch_shift extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jadwal Shift', 'mngsch/setsch-shift');
		$this->data['title'] = "Manajemen Jadwal";
		$this->load->model(['m_instansi','m_sch_run']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Jadwal Shift";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('setsch_shift/v_index', $this->data);
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Jadwal Shift";
		$this->breadcrumbs->push('Tambah Jadwal Shift', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptIDCountParent($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('setsch_shift/v_add', $this->data);
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('nama', 'nama jadwal', 'required');
		$this->form_validation->set_rules('rank1', 'tanggal mulai', 'required');
		$this->form_validation->set_rules('rank2', 'tanggal berakhir', 'required');
		$this->form_validation->set_rules('instansi[]', 'instansi', 'required');
		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));

		if ($rank1 >  $rank2) {
			$this->form_validation->set_rules('rank1', 'tanggal mulai dan tanggal berakhir', 'tidak sesuai');
		}
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		
		if ($this->form_validation->run() == TRUE) {

			$this->mod = $this->input->post('mod');			
			if ($this->mod == "add") {
				$instansi = str_replace(['[', ']', '"'],['{', '}',''], json_encode($this->input->post('instansi')));
				$data = array(
							  'name' 		    => $this->input->post('nama'),
							  'start_date' 		=> $rank1,
							  'end_date' 		=> $rank2,
							  'dept_id' 		=> $instansi,
							  'created_at'		=> date('Y-m-d H:i:s'),
							  'created_by'		=> $this->session->userdata('tpp_user_id'),
							  'type'			=> 2,
				 );
				$this->return = $this->db->insert('sch_run',$data);


				if ($this->return) {
					 $this->result = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			    'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				$instansi = str_replace(['[', ']', '"'],['{', '}',''], json_encode($this->input->post('instansi')));
				$data = array(
							  'name' 		    => $this->input->post('nama'),
							  'start_date' 		=> $rank1,
							  'end_date' 		=> $rank2,
							  'dept_id' 		=> $instansi,
							  'updated_at'		=> date('Y-m-d H:i:s'),
							  'updated_by'		=> $this->session->userdata('tpp_user_id')
				 );
				$this->return = $this->db->update('sch_run', $data, ['id' => decrypt_url($this->input->post('id'),'schrun_shift')]);

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
        $this->datatables->select('a.id, name, start_date, end_date,dept_name,path_info,level, schedule_status')
        	->from('sch_run a')
        	->join('(select a.id as schrun_id, 
					array_agg(dept_name::text ORDER BY path_info) as dept_name,
					array_agg(path_info::text ORDER BY path_info) as path_info,
					array_agg(level::text ORDER BY path_info) as level
					from (SELECT id, unnest(dept_id) as dept_id FROM sch_run) as a
					join v_instansi_all b on a.dept_id=b.id
					GROUP BY 1) as instansi_all','instansi_all.schrun_id=a.id','left')
        	->order_by('a.id','desc')
        	->where('type',2)
        	->add_column('start_date','$1 - $2','format_tgl_ind(start_date), format_tgl_ind(end_date)')
        	->add_column('sch_name','$1','sch_name(name, start_date)')
        	->add_column('dept_name','<div style="width: 100%;max-height: 60px;overflow-y: auto;">$1</div>','instansi_expl(dept_name,path_info,level)')
        	->add_column('status','$1','status_lock(schedule_status)')
        	->add_column('action', '<a href="'.base_url('mngsch/setsch-shift/edit/').'$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>
					              <span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus akun" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'encrypt_url(id,"schrun_shift")');
        	if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(name)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
        return $this->output->set_output($this->datatables->generate());
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Jadwal";
		$this->breadcrumbs->push('Edit Jadwal', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['sch_run']		= $this->db->get_where('sch_run', ['id' => decrypt_url($id,'schrun_shift')])->row();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptIDCountParent($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('setsch_shift/v_edit', $this->data);
	}

	public function AjaxDel()
	{
		$this->output->unset_template();
		$id = decrypt_url($this->input->get('id'),'schrun_shift');
		$cek = $this->db->get_where('shift_users_run',['schrun_id' => $id])->row();
		if (!$cek) {
			$this->del = $this->db->delete('sch_run',['id' => $id]);
		}
		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data telah dikunci.']));	
		}
	}	



}

/* End of file Setsch_shift.php */
/* Location: ./application/modules/Mngsch/controllers/Setsch_shift.php */