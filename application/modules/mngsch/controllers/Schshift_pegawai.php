<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Schshift_pegawai extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jadwal Shift Pegawai', 'mngsch/schshift-pegawai');
		$this->data['title'] = "Manajemen Jadwal";
		$this->load->model(['m_instansi','m_sch_run']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Jadwal Shift Pegawai";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('schshift_pegawai/v_index', $this->data);
	}

	public function indexJson()
	{
		$dept_id = $this->m_instansi->GetAdminDept($this->session->userdata('tpp_dept_id'));
		$level = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
		$this->output->unset_template();
		$dept_id = $this->m_instansi->GetAdminDept($this->session->userdata('tpp_dept_id'));
		$this->load->library('datatables');
        $this->datatables->select('a.id, name, start_date, end_date, tot_user_id, a.schedule_status')
        	->from('sch_run a')
        	->join('(select sum(jum_user_id) as tot_user_id, schrun_id from (select schrun_id, count(user_id) jum_user_id, 
        		a.dept_id, path_id from (SELECT id, unnest(user_id) as user_id, dept_id,schrun_id FROM 
        		sch_run_users GROUP BY 1,2,3) as a
				left join sch_run b on a.schrun_id=b.id
				left join v_instansi_all_master c on a.dept_id=c.id
				GROUP BY 1,3,4) as a where a.path_id['.$level.'] = '.$dept_id.' GROUP BY 2) as b','a.id=b.schrun_id','left')
        	->order_by('a.id','desc')
        	->where('type',2)
        	->add_column('status','$1','status_lock(schedule_status)')
        	->add_column('start_date','$1 - $2','format_tgl_ind(start_date), format_tgl_ind(end_date)')
        	->add_column('sch_name','$1','sch_name(name, start_date)')
        	->add_column('action', '$1', 'acktion_schshift_status(id,schedule_status)');
        	if ($this->session->userdata('tpp_level') != 1) {
        		$this->datatables->where("$dept_id",'any(dept_id)', false);
        	}
        	if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(name)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
        return $this->output->set_output($this->datatables->generate());
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Atur Shift Pegawai";
		$this->breadcrumbs->push('Atur Shift', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$dept_id = $this->m_instansi->GetAdminDept($this->session->userdata('tpp_dept_id'));
		$level = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
		$schrun_id = decrypt_url($id, 'schrun_id_shift');
		$this->data['instansi']		= $this->m_sch_run->GetSchRunInstansi($schrun_id, $dept_id, $level)->result();
		$this->data['sch_run']	   = $this->db->get_where('sch_run',['id' => $schrun_id]);
		$this->data['schrun_shift'] = $this->m_sch_run->GetSchRunShift($dept_id, $level);
		$this->load->view('schshift_pegawai/v_edit', $this->data);
	}

	public function PegawaiJson()
	{
		$this->output->unset_template();
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$schrun_id = decrypt_url($this->input->post('schrun_id'),'schrun_id_shift');
		$sch = $this->db->get_where('sch_run',['id' => $schrun_id])->row();
		$this->load->library('datatables');
        $this->datatables->select('DISTINCT ON (no_urut) a.id, a.nip, a.nama, a.dept_name, status_pegawai,gelar_dpn,gelar_blk,b.user_id as checked, usercek.user_id as disabled')
        	->from('v_users_all a')
        	->join('(SELECT id, unnest(user_id) as user_id, schrun_id FROM sch_run_users where schrun_id='.$schrun_id.') as b','a.id=b.user_id','left')
        	->join("(SELECT a.id, unnest(user_id) as user_id, schrun_id, start_date, end_date FROM sch_run_users a
				left join sch_run b on a.schrun_id=b.id
				where ((start_date >= '$sch->start_date' and start_date <= '$sch->end_date' and end_date >= '$sch->start_date' and end_date <= '$sch->end_date') or
				(start_date <= '$sch->start_date' and end_date >= '$sch->start_date'
				and start_date <= '$sch->end_date' and end_date >= '$sch->end_date')) and schrun_id != $schrun_id and type in(1,2)) as usercek",'a.id=usercek.user_id','left')
        	->where('key > 0')
        	->where('att_status',1)
        	->order_by('no_urut')
        	->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)')
        	->add_column('cekbox','<label class="pure-material-checkbox">
										<input type="checkbox"  name="user[]" $1>
										<span></span>
							        </label>','checked_sch_shift(id, checked, disabled)');
		        $this->datatables->where('a.dept_id', $instansi);
        	 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(nama)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(nip)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	         }
	       
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_rules('user[]', 'pegawai', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		$schrun_id = decrypt_url($this->input->post('id'), 'schrun_id');
		$instansi  = decrypt_url($this->input->post('instansi'), 'instansi');
		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');			
			if ($this->mod == "edit") {

				$cek = $this->db->select('id')->get_where('sch_run_users',['schrun_id' => decrypt_url($this->input->post('id'),'schrun_id_shift'), 'dept_id' => decrypt_url($this->input->post('instansi'),'instansi')])->row();

				$user_cek = $this->input->post('user');
				$user_cek_ = array();
				for ($i=0; $i < count($user_cek); $i++) {
					$user_dec = $user_cek[$i]; 
					$user_cek_[] = decrypt_url($user_dec,'user_id_schshiftrun_user');
				}
				$user = str_replace(['[', ']', '"'],['{', '}',''], json_encode($user_cek_));
				$data = array(
							  'schrun_id' 		=> decrypt_url($this->input->post('id'),'schrun_id_shift'),
							  'user_id' 		=> $user,
							  'dept_id' 		=> decrypt_url($this->input->post('instansi'),'instansi'),
				 );

				if ($cek) {
					 $data['updated_at'] = date('Y-m-d H:i:s');
					 $data['updated_by'] = $this->session->userdata('tpp_user_id');
					 $this->return = $this->db->update('sch_run_users',$data, ['id' => $cek->id]);

					 $data_id = array('id' => encrypt_url($cek->id,'sch_run_user_shift'), );

					 // cek user_id if update on deleted
					 $where_del = array(
							  'schrun_id' 		=> decrypt_url($this->input->post('id'),'schrun_id_shift'),
							   'dept_id' 		=> decrypt_url($this->input->post('instansi'),'instansi'),
					 );
					 $this->db->where($where_del);
					 $this->db->where_not_in('user_id', $user_cek_);
					 $this->db->delete('shift_run_users');

				}else {
					 $data['created_at'] = date('Y-m-d H:i:s');
					 $data['created_by'] = $this->session->userdata('tpp_user_id');
					 $this->return = $this->db->insert('sch_run_users',$data);
					 $data_id = array('id' => encrypt_url($this->db->insert_id(),'sch_run_user_shift'), );
				}

				if ($this->return) {
					 $this->result = array('status' => true,
				    			          'message' => 'Data berhasil disimpan',
				    			          'result' => $data_id);
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

	public function shift($id)
	{
		$this->data['sub_title'] 	= "Atur Shift Pegawai";
		$this->breadcrumbs->push('Atur Shift', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['sch_run'] 		= $this->m_sch_run->sch_run_user(decrypt_url($id,'sch_run_user_shift'))->row();
		$this->data['user']	  		= $this->m_sch_run->userAll_schRunUser(decrypt_url($id,'sch_run_user_shift'))->result();
		$this->load->view('schshift_pegawai/v_shift', $this->data);
	}

	public function cetak($id)
	{
		$this->output->unset_template();
		$this->data['sch_run'] 		= $this->m_sch_run->sch_run_user(decrypt_url($id,'sch_run_user_shift'))->row();
		$this->data['user']	  		= $this->m_sch_run->userAll_schRunUser(decrypt_url($id,'sch_run_user_shift'))->result();
		$this->load->library('Tpdf');
		$this->load->view('schshift_pegawai/v_cetak', $this->data);
			

	}


	public function usershift()
	{
		$schrun_id 	  				= decrypt_url($this->input->get('sch'),'schrun_id_shift');
		$user_id  					= decrypt_url($this->input->get('u'),'user_id_shift');
		$user_data 					= $this->m_sch_run->GetUserSchRun($user_id,$schrun_id)->row();
		$dept_id = $user_data->dept_id;
		$level = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
		$this->data['sub_title'] 	= "Atur Shift Pegawai";
		$this->breadcrumbs->push('Atur Shift', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['user']			= $user_data;
		$this->data['schrun_shift'] = $this->m_sch_run->GetSchRunShift($dept_id, $level);

		//shift cek
		$cek = $this->db->select('id, shiftrun_id, start_shift')->get_where('shift_run_users', ['user_id' => $user_id, 'schrun_id' => $schrun_id])->row();
		$this->data['shift_data']	= $cek;
		$this->load->view('schshift_pegawai/v_usershift', $this->data);
	}

	public function AjaxSaveShift()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('schrun_shift[]', 'instansi', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		$user_id 	= decrypt_url($this->input->post('user_id'), 'user_id_shift');
		$schrun_id  = decrypt_url($this->input->post('schrun_id'), 'schrun_id_shift');
		$dept_id  	= decrypt_url($this->input->post('dept_id'), 'dept_id_shift');
		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');			
			if ($this->mod == "shift") {

				$cek = $this->db->select('id')->get_where('shift_run_users', ['user_id' => $user_id, 'schrun_id' => $schrun_id])->row();

				$user_data  =  $this->m_sch_run->GetUserSchRun($user_id,$schrun_id)->row();
				$shift_cek = $this->input->post('schrun_shift');

				$data = array();
				for ($i=0; $i < count($shift_cek); $i++) {
					$xplode = explode(",",$shift_cek[$i]);
					$shiftrun_id = $xplode[0];
					$work_day    = $xplode[1];
					$start_shift = tgl_plus($user_data->start_date, $i);
					$end_shift   = tgl_plus($start_shift, $work_day-1);

					//
					$shiftrun_id_in[] = $shiftrun_id;
					$start_shift_in[] = $start_shift;
					$end_shift_in[]   = $end_shift;
				}
				$shiftrun_id_array = str_replace(['[', ']', '"'],['{', '}',''], json_encode($shiftrun_id_in));
				$start_shift_array = str_replace(['[', ']', '"'],['{', '}',''], json_encode($start_shift_in));
				$end_shift_array   = str_replace(['[', ']', '"'],['{', '}',''], json_encode($end_shift_in));
				$data = array(    'user_id'  		=> $user_id,
								  'dept_id'  		=> $dept_id,
								  'shiftrun_id' 	=> $shiftrun_id_array,
								  'schrun_id' 		=> $schrun_id,
								  'start_shift' 	=> $start_shift_array,
								  'end_shift' 		=> $end_shift_array,);

				if ($cek) {
					 $data['updated_at'] = date('Y-m-d H:i:s');
					 $data['updated_by'] = $this->session->userdata('tpp_user_id');
					 $this->return = $this->db->update('shift_run_users',$data, ['id' => $cek->id]);
				}else {
					 $data['created_at'] = date('Y-m-d H:i:s');
					 $data['created_by'] = $this->session->userdata('tpp_user_id');
					 $this->return = $this->db->insert('shift_run_users',$data);
				}

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
		$cek = $this->db->select('id')->get_where('shift_run_users',['schrun_id' => decrypt_url($this->input->get('id'),'schrun_id_shift'), 'dept_id' => decrypt_url($this->input->get('instansi'),'instansi')])->result();
		$cek_sch_run = $this->db->select('id')->get_where('sch_run_users',['schrun_id' => decrypt_url($this->input->get('id'),'schrun_id_shift'), 'dept_id' => decrypt_url($this->input->get('instansi'),'instansi')])->row();
		

		$data_id = array();
		foreach($cek as $row){
			array_push($data_id,$row->id);
		}
		if ($cek) {
			$del = $this->db->where_in('id',$data_id)->delete('shift_run_users');
		}
		if ($del) {
			$del2 = $this->db->delete('sch_run_users',['id' => $cek_sch_run->id]);
			if($del2){
				$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
			}else {
				$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Ada berberapa data tidak berhasil dihapus.']));
			}
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus.']));	
		}
	}	


}

/* End of file Schshift_pegawai.php */
/* Location: ./application/modules/Mngsch/controllers/Schshift_pegawai.php */