<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
* Created By: Rian Reski A
* 2019
*/

class Apel_pegawai extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Tidak Apel Pagi', 'mngsch/apel-pegawai');
		$this->data['title'] = "Manajemen Jadwal";
		$this->load->model(['m_instansi','m_user','m_apel']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Input Daftar Pegawai Tidak Ikut Apel";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		if ($this->session->userdata('tpp_level') == 1) {
			$not_in_eselon = '1,2';
		}else {
			$not_in_eselon = '1,2,3,4,5,6';
		}
		$this->data['eselon']		= $this->db->where_not_in('id',$not_in_eselon,false)->order_by('id')->get('_eselon')->result();
		$this->load->view('apel_pegawai/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		//header('Content-Type: application/json');
		$this->load->library('datatables');
		$this->datatables->select('a.id, a.tgl_apel, a.start_time, a.ket, b.name,a.status')
		->from('sch_apel a')
		->where('a.deleted',1)
		->join('_jenis_apel b','a.jenis_apel_id=b.id')
		->order_by('a.tgl_apel','desc')
		->add_column('tgl_apel', '$1 ($2)', 'tgl_ind_hari(tgl_apel),jm(start_time)')
		->add_column('sch_name','$1','sch_name(name, tgl_apel)')
		->add_column('action', '$1', 'acktion_schapel_status(id,status)');

		return $this->output->set_output($this->datatables->generate());acktion_sch_status();
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Apel Pegawai";
		$this->breadcrumbs->push('Absen Apel Pegawai', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$schid_apel = decrypt_url($id,"sch_id_apel");
		$this->data['sch_apel']	   = $this->m_apel->get_sch_apel($schid_apel);
		$this->load->view('apel_pegawai/v_edit', $this->data);
	}

	public function PegawaiJson()
	{
		$this->output->unset_template();
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$schapel_id = decrypt_url($this->input->post('schapel_id'),'schapel_id');
		//$sch = $this->db->get_where('sch_run',['id' => $schrun_id])->row();
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.nama, a.dept_name, a.status_pegawai,a.gelar_dpn,a.gelar_blk, a.dept_id, b.user_id as checked')
        	->from('v_users_all a')
        	->join('(SELECT id,dept_id, unnest(user_id) as user_id, sch_apel_id FROM sch_apel_users where sch_apel_id ='.$schapel_id.') as b','(a.id=b.user_id and a.dept_id = b.dept_id) ','left')
        	->where('a.key > 0')
			->add_column('cekbox','<label class="pure-material-checkbox">
										<input type="checkbox" name="user[]" $1>
										<span></span>
							        </label>','checked_sch_apel(id,checked)')
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
		$schapel_id = decrypt_url($this->input->post('id'), 'schapel_id');
		$instansi  = decrypt_url($this->input->post('instansi'), 'instansi');
		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');			
			if ($this->mod == "edit") {

				$cek = $this->db->select('id')->get_where('sch_apel_users',['sch_apel_id' => decrypt_url($this->input->post('id'),'schapel_id'), 'dept_id' => decrypt_url($this->input->post('instansi'),'instansi')])->row();

				$user_cek = $this->input->post('user');
				$user_cek_ = array();
				for ($i=0; $i < count($user_cek); $i++) {
					$user_dec = $user_cek[$i]; 
					$user_cek_[] = decrypt_url($user_dec,'user_id_schapel_user');
				}
				$user = str_replace(['[', ']', '"'],['{', '}',''], json_encode($user_cek_));
				$data = array(
							  'sch_apel_id' 		=> decrypt_url($this->input->post('id'),'schapel_id'),
							  'user_id' 		=> $user,
							  'dept_id' 		=> decrypt_url($this->input->post('instansi'),'instansi'),
				 );

				if ($cek) {
					 $data['updated_at'] = date('Y-m-d H:i:s');
					 $data['updated_by'] = $this->session->userdata('tpp_user_id');
					 $this->return = $this->db->update('sch_apel_users',$data, ['id' => $cek->id]);

					 $data_id = array('id' => encrypt_url($cek->id,'sch_users_apel'), );

					 // cek user_id if update on deleted
					 $where_del = array(
							  'sch_apel_id' 		=> decrypt_url($this->input->post('id'),'schapel_id'),
							   'dept_id' 		=> decrypt_url($this->input->post('instansi'),'instansi'),
					 );
					 $this->db->where($where_del);
					 $this->db->where_not_in('user_id', $user);
					 $this->db->delete('sch_apel_users');

				}else {
					 $data['created_at'] = date('Y-m-d H:i:s');
					 $data['created_by'] = $this->session->userdata('tpp_user_id');
					 $this->return = $this->db->insert('sch_apel_users',$data);
					 $data_id = array('id' => encrypt_url($this->db->insert_id(),'sch_apel_users'), );
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

}

/* End of file Absen_upacara.php */
/* Location: ./application/modules/Kepegawaian/controllers/Absen_upacara.php */