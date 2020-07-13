<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Schnotfixed_pegawai extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jadwal Tidak Tetap Pegawai', 'mngsch/schnotfixed-pegawai');
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
		$this->data['sub_title']  = "Jadwal Tidak Tetap Pegawai";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('schnotfixed_pegawai/v_index', $this->data);
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
        	->where('type',3)
        	->add_column('status','$1','status_lock(schedule_status)')
        	->add_column('start_date','$1 - $2','format_tgl_ind(start_date), format_tgl_ind(end_date)')
        	->add_column('sch_name','$1','sch_name(name, start_date)')
        	->add_column('action', '$1', 'acktion_schnotfixec_status(id,schedule_status)');
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
		$this->data['sub_title'] 	= "Atur Jam Tidak Tetap Pegawai";
		$this->breadcrumbs->push('Atur Tidak Tetap Pegawai', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$dept_id = $this->m_instansi->GetAdminDept($this->session->userdata('tpp_dept_id'));
		$level = $this->db->select('level')->get_where('v_instansi_all', ['id' => $dept_id])->row()->level;
		$schrun_id = decrypt_url($id, 'schrun_id_notfixed');
		$this->data['instansi']		= $this->m_sch_run->GetSchRunInstansi($schrun_id, $dept_id, $level)->result();
		$this->data['sch_run']	    = $this->m_sch_run->GetSchRunNotfixed($schrun_id);
		$this->load->view('schnotfixed_pegawai/v_edit', $this->data);
	}

	public function PegawaiJson()
	{
		$this->output->unset_template();
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$schrun_id = decrypt_url($this->input->post('schrun_id'),'schrun_id_notfixed');
		$sch = $this->db->get_where('sch_run',['id' => $schrun_id])->row();
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.nama, a.dept_name, status_pegawai,gelar_dpn,gelar_blk, b.user_id as checked, usercek.user_id as disabled')
        	->from('v_users_all a')
        	->join('(SELECT id, unnest(user_id) as user_id, schrun_id FROM sch_run_users where schrun_id='.$schrun_id.') as b','a.id=b.user_id','left')
        	->join("(SELECT a.id, unnest(user_id) as user_id, schrun_id, start_date, end_date FROM sch_run_users a
				left join sch_run b on a.schrun_id=b.id
				where ((start_date >= '$sch->start_date' and start_date <= '$sch->end_date' and end_date >= '$sch->start_date' and end_date <= '$sch->end_date') or
				(start_date <= '$sch->start_date' and end_date >= '$sch->start_date'
				and start_date <= '$sch->end_date' and end_date >= '$sch->end_date')) and schrun_id != $schrun_id and b.type=3) as usercek",'a.id=usercek.user_id','left')
        	->where('key > 0')
        	->where('att_status',1)
        	->order_by('no_urut')
        	->add_column('cekbox','<label class="pure-material-checkbox">
										<input type="checkbox" name="user[]" $1>
										<span></span>
							        </label>','checked_sch(id, checked, disabled)')
        	->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)');
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

		$schrun_id = decrypt_url($this->input->post('id'), 'schrun_id_notfixed');
		$instansi  = decrypt_url($this->input->post('instansi'), 'instansi');
	
		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');			
			if ($this->mod == "edit") {

				$cek = $this->db->select('id')->get_where('sch_run_users',['schrun_id' => decrypt_url($this->input->post('id'),'schrun_id_notfixed'), 'dept_id' => decrypt_url($this->input->post('instansi'),'instansi')])->row();

				$user_cek = $this->input->post('user');
				$user_cek_ = array();
				for ($i=0; $i < count($user_cek); $i++) {
					$user_dec = $user_cek[$i]; 
					$user_cek_[] = decrypt_url($user_dec,'user_id_schrun_user');
				}
				$user = str_replace(['[', ']', '"'],['{', '}',''], json_encode($user_cek_));
				$data = array(
							  'schrun_id' 		=> decrypt_url($this->input->post('id'),'schrun_id_notfixed'),
							  'user_id' 		=> $user,
							  'dept_id' 		=> decrypt_url($this->input->post('instansi'),'instansi'),
				 );

				if ($cek) {
					 $data['updated_at'] = date('Y-m-d H:i:s');
					 $data['updated_by'] = $this->session->userdata('tpp_user_id');
					 $this->return = $this->db->update('sch_run_users',$data, ['id' => $cek->id]);
				}else {
					 $data['created_at'] = date('Y-m-d H:i:s');
					 $data['created_by'] = $this->session->userdata('tpp_user_id');
					 $this->return = $this->db->insert('sch_run_users',$data);
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



}

/* End of file Schnotfixed_pegawai.php */
/* Location: ./application/modules/Mngsch/controllers/Schnotfixed_pegawai.php */