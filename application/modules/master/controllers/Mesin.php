<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Mesin extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Mesin', 'master/mesin');
		$this->data['title'] = "Master";
		$this->load->model(['m_instansi','m_machine']);
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['sub_title'] = "Mesin Finger";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('mesin/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$instansi = $this->input->post('instansi');
		if ($instansi) {
			$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => decrypt_url($instansi,'user_instansi')])->row()->level;
		}
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.name, b.dept_name, b.dept_alias, b.path_info, a.ip, a.status, a.machine_number, a.ket')
        	->from('_mf_machines a')
        	->join('v_instansi_all b','b.id=a.dept_id')
        	->order_by('b.path_info,a.id')
        	->where('a.deleted', 1)
        	->add_column('status_mesin','$1','status_user(status)')
        	->add_column('action', '<a href="'.base_url('master/mesin/edit/').'$1">
        								<i class="icon-pencil5 text-info-400 mr-1"></i>
					                </a>
					              	<span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus akun" style="cursor:pointer;" id="$1">
					              		<i class="icon-bin"></i>
					              	</span>', 'encrypt_url(id,"machines_id")');
        	if ($instansi) {
	        		$this->datatables->where("b.path_id['".$level."']='".decrypt_url($instansi,'user_instansi')."'");
	        }
        return $this->output->set_output($this->datatables->generate());
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Data Mesin";
		$this->breadcrumbs->push('Tambah Mesin', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('mesin/v_add', $this->data);
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Data Mesin";
		$this->breadcrumbs->push('Edit Mesin', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->data['mesin']		= $this->db->get_where('_mf_machines', ['id' => decrypt_url($id, 'machines_id')])->row();
		$this->load->view('mesin/v_edit', $this->data);
	}

	
	public function AjaxSave()
	{
		$this->load->model('m_server_att');
		$this->output->unset_template();
		$this->form_validation->set_rules('nama', 'nama mesin', 'required')
								->set_rules('ip', 'Alamat IP', 'required')
								->set_rules('port', 'port', 'required|numeric')
								->set_rules('ket', 'keterangan', 'required')
								->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		$res ='';
		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');
			
			if ($this->mod == "add") {
				$number = $this->db->select('max(machine_number) as jum')->get('_mf_machines')->row();
				if ($number) {
					$number_plus = $number->jum+1;
				}else {
					$number_plus = 1;
				}

				if ($this->input->post('status')) {
					$status = 1;
				}else {
					$status = 0;
				}

				$data = array('dept_id' 	 	=> decrypt_url($this->input->post('instansi'),'instansi'),
							  'name' 		    => $this->input->post('nama'),
							  'machine_number' 	=> $number_plus,
							  'ip' 	 			=> $this->input->post('ip'),
							  'port' 	 		=> $this->input->post('port'),
							  'password' 	 	=> $this->input->post('pass'),
							  'ket' 	 		=> $this->input->post('ket'),
							  'status' 	 		=> $status
				 );
				$this->return = $this->db->insert('_mf_machines',$data);
				$id_new = $this->db->insert_id();

				if ($this->return) {
					if ($status == 1) {
						$data_att_dept = array( 'id' 		=> $id_new,
												'machinealias'  => $this->input->post('nama'),
												'ConnectType' 	=> 1,
												'ip' 			=> $this->input->post('ip'),
												'serialport' 	=> 1,
												'port' 			=> $this->input->post('port'),
												'baudrate' 		=> '115200',
												'machinenumber' => $number_plus,
												'enabled' 		=> 1,
												'commpassword' 	=> $this->input->post('pass'),
										);
						$this->m_server_att->Newmachines($data_att_dept);
					}
					
				}

				if ($this->return) {
					 $this->result = array('status' => true,
				    			   			'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			    		'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				$cek_status = $this->db->select('*')->get_where('_mf_machines', ['id' => decrypt_url($this->input->post('id'),'machines_id')])->row();
				if ($this->input->post('status')) {
					$status = 1;
				}else {
					$status = 0;
				}

				$data = array(
							  'name' 		    => $this->input->post('nama'),
							  'ip' 	 			=> $this->input->post('ip'),
							  'port' 	 		=> $this->input->post('port'),
							  'password' 	 	=> $this->input->post('pass'),
							  'ket' 	 		=> $this->input->post('ket'),
							  'status' 	 		=> $status
				);
				$this->return = $this->db->update('_mf_machines', $data, ['id' => decrypt_url($this->input->post('id'),'machines_id')]);

				if ($this->return) {
					if ($status == 1) {
						if ($cek_status->status == 1) {
							   $data_att_dept = array(
											'machinealias'  => $this->input->post('nama'),
											'ip' 			=> $this->input->post('ip'),
											'port' 			=> $this->input->post('port'),
											'commpassword' 	=> $this->input->post('pass'),
										);
							   $this->return = $this->m_server_att->Updatemachines($data_att_dept, ['id' => decrypt_url($this->input->post('id'),'machines_id')]);
						}else {
							$data_att_dept = array( 'id' 		    => $cek_status->id,
													'machinealias'  => $this->input->post('nama'),
													'ConnectType' 	=> 1,
													'ip' 			=> $this->input->post('ip'),
													'serialport' 	=> 1,
													'port' 			=> $this->input->post('port'),
													'baudrate' 		=> '115200',
													'machinenumber' => $cek_status->machine_number,
													'enabled' 		=> 1,
													'commpassword' 	=> $this->input->post('pass'),
											);
							$this->m_server_att->Newmachines($data_att_dept);
						}
					}else {
						if ($cek_status->status == 1) {
								$this->m_server_att->Delmachines(['id' => decrypt_url($this->input->post('id'),'machines_id')]);
						}
					}
					
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
		$this->load->model('m_server_att');
		$this->output->unset_template();

		$this->del = $this->db->update('_mf_machines',['deleted' => 0,'status' => 0],['id' => decrypt_url($this->input->get('id'),'machines_id')]);
		if ($this->del) {
			$this->m_server_att->Delmachines(['id' => decrypt_url($this->input->get('id'),'machines_id')]);
		}
		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}

	public function cetak()
	{
		$this->output->unset_template();
		$this->load->library('Tpdf');
		$this->data['mesin'] = $this->m_machine->GetAllMesin()->result();
		$this->load->view('mesin/v_cetak', $this->data);
	}

	public function dumpt()
	{
		$this->output->unset_template();
		$this->db2 = $this->load->database('sqlsrv',TRUE);
		$sql = $this->db2->get('machines')->result();

		foreach ($sql as $row) {
			 $data = array('id' 				=> $row->ID,
			 			   'name' 				=> $row->MachineAlias,
			 			   'ip' 				=> $row->IP, 
			 			   'port' 				=> $row->Port,
			 			   'machine_number' 	=> $row->MachineNumber,
			 			   'password' 			=> $row->CommPassword,
			 			   'sn' 				=> $row->sn,);
			 $this->db->insert('_mf_machines', $data);
		}
	}


	


}

/* End of file Mesin.php */
/* Location: ./application/modules/Master/controllers/Mesin.php */