<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Setsch_start extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jadwal Jam Kerja', 'mngsch/setsch-start');
		$this->data['title'] = "Manajemen Jadwal";
		$this->load->model(['m_instansi','m_sch_class']);
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
		$this->data['sub_title']  = "Jadwal Jam Kerja";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('setsch_start/v_index', $this->data);
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Jadwal Jam Kerja";
		$this->breadcrumbs->push('Tambah Jadwal Jam Kerja', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['day']		  	= $this->db->order_by('id')->get('days')->result();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptIDCountParent($this->session->userdata('tpp_dept_id'))->result();
		$this->data['sch_class']	= $this->m_sch_class->GetSchClassType('umum')->result();
		$this->load->view('setsch_start/v_add', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		//header('Content-Type: application/json');
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
        	->where('type',1)
        	->add_column('start_date','$1 - $2','format_tgl_ind(start_date), format_tgl_ind(end_date)')
        	->add_column('sch_name','$1','sch_name(name, start_date)')
        	->add_column('dept_name','<div style="width: 100%;max-height: 60px;overflow-y: auto;">$1</div>','instansi_expl(dept_name,path_info,level)')
        	->add_column('status','$1','status_lock(schedule_status)')
        	->add_column('action', '<a href="'.base_url('mngsch/setsch-start/edit/').'$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>
					              <span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus akun" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'encrypt_url(id,"schrun_umum")');
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
		$this->data['sch_run']		= $this->db->get_where('sch_run', ['id' => decrypt_url($id,'schrun_umum')])->row();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptIDCountParent($this->session->userdata('tpp_dept_id'))->result();
		$this->data['sch_class']		= $this->m_sch_class->GetSchClassType('umum')->result();
		$this->data['day']		  		= $this->db->order_by('id')->get('days')->result();
		$this->data['sch_run_deil']		= $this->db->order_by('s_day')->join('days b','a.s_day=b.id')->where('run_id',decrypt_url($id,'schrun_umum'))->get('sch_run_deil a')->result();
		$this->load->view('setsch_start/v_edit', $this->data);
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('nama', 'nama skedul', 'required');
		$this->form_validation->set_rules('rank1', 'tanggal mulai', 'required');
		$this->form_validation->set_rules('rank2', 'tanggal berakhir', 'required');
		$this->form_validation->set_rules('instansi[]', 'instansi', 'required');
		// model move
		$data_day = $this->db->order_by('id')->get('days')->result();
		foreach ($data_day as $row ) {
			$this->form_validation->set_rules('h'.$row->id, $row->day_ind, 'required');
		}
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));

		if ($rank1 >  $rank2) {
			$this->form_validation->set_rules('rank1', 'tanggal mulai dan tanggal berakhir', 'tidak sesuai');
		}
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
							  'type' 			=> 1,
				 );
				$this->return = $this->db->insert('sch_run',$data);
				$run_id = $this->db->insert_id();

				$data = array();
				foreach ($data_day as $row ) {
					$data[] = array('run_id' 	 => $run_id,
								  'class_id' => $this->input->post('h'.$row->id),
								  's_day' 	 => $row->id,
								  'e_day' 	 => $row->id, );
				}

				$this->return = $this->db->insert_batch('sch_run_deil', $data);


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
				$this->return = $this->db->update('sch_run', $data, ['id' => decrypt_url($this->input->post('id'),'schrun_umum')]);

				// model move
				$data_sch_run_deil = $this->db->select('id,s_day')
											  ->order_by('s_day')
											  ->where('run_id',decrypt_url($this->input->post('id'),'schrun_umum'))
											  ->get('sch_run_deil a')->result();
				$data = array();
				foreach ($data_sch_run_deil as $row ) {
					$data[] = array(  'id'       => $row->id,
									  'class_id' => $this->input->post('h'.$row->s_day),
									  's_day' 	 => $row->s_day,
									  'e_day' 	 => $row->s_day, );
				}

				$this->return = $this->db->update_batch('sch_run_deil', $data, 'id');

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

	public function AjaxDel()
	{
		$this->output->unset_template();
		$id = decrypt_url($this->input->get('id'),'schrun_umum');
		$cek = $this->db->get_where('sch_run_users',['schrun_id' => $id])->row();
		if (!$cek) {
			$this->del = $this->db->delete('sch_run_deil',['run_id' => $id]);
			$this->del = $this->db->delete('sch_run',['id' => $id]);
		}
		
		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data telah dikunci.']));	
		}
	}	

	

}

/* End of file Setsch_start.php */
/* Location: ./application/modules/Mngsch/controllers/Setsch_start.php */