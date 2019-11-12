<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mesin extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Mesin', 'administrator/mesin');
		$this->data['title'] = "Master";
		$this->load->model('m_user');
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

	public function json()
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
        	->add_column('status_mesin','$1','status_user(status)')
        	->add_column('action', '<div class="list-icons">
										<div class="dropdown">
											<a href="#" class="list-icons-item" data-toggle="dropdown">
												<i class="icon-menu9"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												$2
												<a href="javascript:;" class="edit dropdown-item" data="$1"><i class="icon-pencil5"></i> Ubah Data</a>
											</div>
										</div>
									</div>', 'id, cekAksiAktifMesin(status,id)');
        	if ($instansi) {
	        		$this->datatables->where("b.path_id['".$level."']='".decrypt_url($instansi,'user_instansi')."'");
	        }
        return $this->output->set_output($this->datatables->generate());
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

	public function AjaxSave()
	{
		$this->db2 = $this->load->database('sqlsrv',TRUE);
		$this->output->unset_template();
		$this->form_validation->set_rules('ip', 'IP Adress', 'required')
								->set_rules('port', 'port', 'required|numeric')
								->set_rules('ket', 'keterangan', 'required');
		$this->form_validation->set_error_delimiters('<div>', '</div>');
		$res ='';
		if ($this->form_validation->run() == TRUE) {
			$mod = $this->input->post('mod');
			$dept = $this->db->select('dept_alias, path_info')->get_where('v_instansi_all', ['id' => decrypt_url($this->input->post('instansi_add'),'user_instansi')])->row();
			// $new_path = attConverPathNumber($dept->path_info);
			
			if ($mod == "add") {
				$number = $this->db->select('max(machine_number) as jum')->get('_mf_machines')->row();
				if ($number) {
					$number_plus = $number->jum+1;
				}else {
					$number_plus = 1;
				}

				$data = array('dept_id' 	 	=> decrypt_url($this->input->post('instansi_add'),'user_instansi'),
							  'name' 		    => $dept->dept_alias.'_'.$number_plus,
							  'machine_number' 	=> $number_plus,
							  'ip' 	 			=> $this->input->post('ip'),
							  'port' 	 		=> $this->input->post('port'),
							  'password' 	 	=> $this->input->post('password'),
							  'ket' 	 		=> $this->input->post('ket'),
							  'status' 	 		=> 1
				 );
				$res_ = $this->db->insert('_mf_machines',$data);
				$id_new = $this->db->insert_id();

				if ($res_) {
					$this->db2->query("SET IDENTITY_INSERT machines ON");
					$data_att_dept = array( 'id' 			=> $id_new,
											'machinealias'  => $dept->dept_alias.'_'.$number_plus,
											'ConnectType' 	=> 1,
											'ip' 			=> $this->input->post('ip'),
											'serialport' 	=> 1,
											'port' 			=> $this->input->post('port'),
											'baudrate' 		=> '115200',
											'machinenumber' => $number_plus,
											'enabled' 		=> 1,
											'commpassword' 	=> $this->input->post('password'),
										);
					$res = $this->db2->insert('machines', $data_att_dept);
				}

				if ($res) {
					 $data_ = array('status' => true,
				    			    'alert' => 'Data berhasil disimpan');
				}else{
					 $data_ = array('status' => false,
				    			    'alert' => 'Data gagal disimpan');
					 $this->db->delete('_mf_machines', ['id' => $id_new]);
				}
			}elseif ($mod == "edit") {
				$machine_number =$this->db->select('machine_number')->get_where('_mf_machines', ['id' => $this->input->post('id')])->row()->machine_number;
				$data = array(
							  'name' 		    => $dept->dept_alias.'_'.$machine_number,
							  'ip' 	 			=> $this->input->post('ip'),
							  'port' 	 		=> $this->input->post('port'),
							  'password' 	 	=> $this->input->post('password'),
							  'ket' 	 		=> $this->input->post('ket'),
				 );
				$res = $this->db->update('_mf_machines', $data, ['id' => $this->input->post('id')]);

				if ($res) {
					$data_att_dept = array(
											'machinealias'  => $dept->dept_alias.'_'.$machine_number,
											'ip' 			=> $this->input->post('ip'),
											'port' 			=> $this->input->post('port'),
											'commpassword' 	=> $this->input->post('password'),
										);
					$res = $this->db2->update('machines', $data_att_dept, ['id' => $this->input->post('id')]);
				}

				if ($res) {
					 $data_ = array('status' => true,
				    			   'alert' => 'Data berhasil disimpan');
				}else{
					 $data_ = array('status' => false,
				    			   'alert' => 'Data gagal disimpan');
				}
			}

		}else {
			$validasi =  form_error('ip').
						 form_error('port').
						 form_error('ket');
			$data_ = array('status' => false,
				    		'alert' => $validasi,);
		}

		if ($data_) {
			$this->output->set_output(json_encode($data_));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']));
		}
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('mod');

	   if ($this->mod == "get_edit") {
			$res = $this->db->get_where('_mf_machines', ['id' => $this->input->get('id')])->row();

			$data = array('id' 		=> $res->id,
						  'dept_id' => encrypt_url($res->dept_id,'user_instansi'),
						  'ip' 		=> $res->ip,
						  'port' 	=> $res->port,
						  'ket' 	=> $res->ket, );

			if ($res) {
				$this->output->set_output(json_encode(['status'=>true, 'data'=> $data]));
			}else{
				$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']));
			}
		}elseif ($this->mod == "non_aktif") {
			$this->db2 = $this->load->database('sqlsrv',TRUE);
			$update = $this->db->update('_mf_machines',['status' => 0], ['id' => $this->input->get('id')]);
			if ($update) {
					$this->db2->delete('machines', ['id' => $this->input->get('id')]);
			}

			if ($update) {
				$this->output->set_output(json_encode(['status'=>true, 'msg'=> 'mesin dinonaktifkan.']));
			}else{
				$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']));
			}
		}elseif ($this->mod == "aktif") {
			$this->db2 = $this->load->database('sqlsrv',TRUE);
			

			$update = $this->db->update('_mf_machines',['status' => 1], ['id' => $this->input->get('id')]);
			$res ='';
			if ($update) {
				$get_data = $this->db->select('name, machine_number, ip, port, password')->get_where('_mf_machines', ['id' => $this->input->get('id')])->row();

				$this->db2->query("SET IDENTITY_INSERT machines ON");
					$data_att_dept = array( 'id' 			=> $this->input->get('id'),
											'machinealias'  => $get_data->name,
											'ConnectType' 	=> 1,
											'ip' 			=> $get_data->ip, 
											'serialport' 	=> 1,
											'port' 			=> $get_data->port,
											'baudrate' 		=> '115200',
											'machinenumber' => $get_data->machine_number,
											'enabled' 		=> 1,
											'commpassword' 	=> $get_data->password,
										);
				$res = $this->db2->insert('machines', $data_att_dept);
			}

			if ($res) {
				$this->output->set_output(json_encode(['status'=>true, 'msg'=> 'mesin diaktifkan.']));
			}else{
				$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']));
			}
			
		}
	}


}

/* End of file Mesin.php */
/* Location: ./application/modules/Master/controllers/Mesin.php */