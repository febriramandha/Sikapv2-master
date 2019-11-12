<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instansi extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Instansi', 'master/instansi');
		$this->data['title'] = "Master";
		$this->load->model('m_instansi');
	}

	private function _init()
	{
		$this->output->set_template('app');
	}

	public function index()
	{
		$this->data['sub_title'] = "Instansi";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('instansi/v_index', $this->data);
	}

	public function Getjson()
	{
		$this->output->unset_template();
		//header('Content-Type: application/json');
		$this->load->library('datatables');
        $this->datatables->select('id, dept_name, dept_alias, parent_id, level, path_info, position_order, status_instansi')
        	->from('v_instansi_all_master')
        	->add_column('dept_alias', '$1$2', 'level_instansi(level, path_info), dept_alias')
        	->add_column('instansi_status', '$1', 'status_user(status_instansi)')
        	->add_column('action', '$2
        							<span class="cek list-icons-item text-primary-600 ml-2 msclick" onClick="edit($1)">
        									<i class="icon-pencil5" ></i>
        							</span>
        							<span class="read list-icons-item text-success ml-2 msclick"  id="$1">
        									<i class="icon-file-eye" ></i>
        							</span>
        							$3
        							<span class="confirm-aksi list-icons-item text-danger-600 ml-2 msclick"  id="$1" msg="yakin ingin menghapus data ini">
        									<i class="icon-bin" ></i>
        							</span>', 'id, ec_add_sub(id, level, dept_name), cekAksiAktifInstansi(status_instansi, id)');
        	 if ($this->input->post('search[value]')) {
	        	$this->datatables->like('lower(dept_name)', strtolower($this->input->post('search[value]')));
	        	$this->datatables->or_like('lower(dept_alias)', strtolower($this->input->post('search[value]')));
	        	$this->datatables->or_like('(path_info)::text', $this->input->post('search[value]'));

	        }
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxSave()
	{
		$this->db2 = $this->load->database('sqlsrv',TRUE);
		$this->output->unset_template();
		$this->form_validation->set_rules('nama', 'nama instansi', 'required')
								->set_rules('alias', 'nama singkatan', 'required')
								->set_rules('alamat', 'alamat', 'required')
								->set_rules('order', 'urutan', 'required|numeric');
		$this->form_validation->set_error_delimiters('<div>', '</div>');
		$res ='';
		if ($this->form_validation->run() == TRUE) {
			$mod = $this->input->post('mod');
			if ($mod == "add") {
				$data = array('dept_name' 	 	=> $this->input->post('nama'),
							  'dept_alias' 		=> $this->input->post('alias'),
							  'alamat' 			=> $this->input->post('alamat'),
							  'position_order' 	=> $this->input->post('order'),
							  'parent_id' 	 	=> $this->input->post('parent'),
							  'created_at' 		=> date('Y-m-d H:i:s'),
							  'created_by' 	 	=> $this->session->userdata('tpp_user_id'),
				 );
				$res_ = $this->db->insert('mf_departments',$data);
				$id_new = $this->db->insert_id();

				if ($res_) {
					$path = $this->db->select('path_info')->get_where('v_instansi_all', ['id' => $id_new])->row();
					$new_path = attConverPathNumber($path->path_info);
					$this->db2->query("SET IDENTITY_INSERT departments ON");
					$data_att_dept = array( 'deptid' 	=> $id_new,
											'deptname'  => $new_path.'_'.$this->input->post('alias'),
											'supdeptid' => $this->input->post('parent'),
											 );
					$res = $this->db2->insert('departments', $data_att_dept);
				}

				if ($res) {
					 $data_ = array('status' => true,
				    			    'alert' => 'Data berhasil disimpan');
				}else{
					 $data_ = array('status' => false,
				    			    'alert' => 'Data gagal disimpan');
					 $this->db->delete('mf_departments', ['id' => $id_new]);
				}
			}elseif ($mod == "edit") {
				$data = array('dept_name' 	 	=> $this->input->post('nama'),
							  'dept_alias' 		=> $this->input->post('alias'),
							  'alamat' 			=> $this->input->post('alamat'),
							  'position_order' 	=> $this->input->post('order'),
							  'updated_at' 		=> date('Y-m-d H:i:s'),
							  'updated_by' 	 	=> $this->session->userdata('tpp_user_id'),
				 );
				$res = $this->db->update('mf_departments', $data, ['id' => $this->input->post('id')]);

				$path = $this->db->select('path_info')->get_where('v_instansi_all', ['id' => $this->input->post('id')])->row();

				$new_path = attConverPathNumber($path->path_info);

				$data_att_dept = array('deptname' => $new_path.'_'.$this->input->post('alias'), );
				$res_ = $this->db2->update('departments', $data_att_dept, ['deptid' => $this->input->post('id')]);

				if ($res) {
					 $data_ = array('status' => true,
				    			   'alert' => 'Data berhasil disimpan');
				}else{
					 $data_ = array('status' => false,
				    			   'alert' => 'Data gagal disimpan');
				}
			}

		}else {
			$validasi =  form_error('nama').
						 form_error('alias').
						 form_error('alamat').
						 form_error('order');
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
		if ($this->mod == "get_posititon") {
				$position = $this->db->select('max(position_order)')->get_where('mf_departments', ['parent_id' => $this->input->get('parent')])->row();
				if ($position) {
					$position_ = $position->max+1;
				}else {
				 	$position_ =1;
				}

				//$data = array('instansi' => , );
				$this->output->set_output(json_encode(['position'=> $position_]));
		}elseif ($this->mod =="get_instansi") {
			$instansi = $this->db->get_where('mf_departments', ['id'=> $this->input->get('id')])->row();
			if ($instansi) {
				$this->output->set_output(json_encode(['status'=>TRUE, 'msg'=> 'Berhasil mengambil data.', 'data'=> $instansi]));
			} else{
				$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']));	
			}
		}elseif ($this->mod == "non_aktif") {
			$this->db2 = $this->load->database('sqlsrv',TRUE);
			$res = $this->db->update('mf_departments',['status' => 0], ['id'=> $this->input->get('id')]);
			if ($res) {
				  $res_ = $this->db2->update('departments', ['supdeptid' => -1 ], ['deptid' => $this->input->get('id')]);
			}

			if ($res) {
				$this->output->set_output(json_encode(['status'=>TRUE, 'msg'=> 'Berhasil diperbarui.']));
			} else{
				$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']));	
			}
		}elseif ($this->mod == "aktif") {
			$this->db2 = $this->load->database('sqlsrv',TRUE);
			$res = $this->db->update('mf_departments',['status' => 1], ['id'=> $this->input->get('id')]);
			if ($res) {
				  $parent = $this->db->select('parent_id')->get_where('mf_departments',  ['id'=> $this->input->get('id')])->row()->parent_id;
				  $res_ = $this->db2->update('departments', ['supdeptid' => $parent ], ['deptid' => $this->input->get('id')]);
			}

			if ($res) {
				$this->output->set_output(json_encode(['status'=>TRUE, 'msg'=> 'Berhasil diperbarui.']));
			} else{
				$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal mengambil data.']));	
			}
		}
	}

	public function read($id)
	{
		$this->data['sub_title'] 	= "Instansi";
		$this->breadcrumbs->push('Read', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstansi($id)->row();
		$this->load->view('instansi/v_read', $this->data);
	}

	public function AjaxDel()
	{
		$this->db2 = $this->load->database('sqlsrv',TRUE);
		$this->output->unset_template();
		$del ='';
		$cek = $this->db->select('id')->get_where('mf_departments', ['parent_id' => $this->input->get('id')])->row();
		if (!$cek) {
			$del = $this->db->delete('mf_departments',['id' => $this->input->get('id')]);
			if ($del) {
				$this->db2->delete('departments',['deptid' => $this->input->get('id')]);
			}
		}
		if ($del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'msg'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'msg'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}

	public function dump_path()
	{
		$this->db2 = $this->load->database('sqlsrv',TRUE);
		$this->output->unset_template();

		$data_dept = $this->db->select('id, path_info, dept_alias')->get_where('v_instansi_all')->result();

		foreach ($data_dept as $row) {
			$new_path = attConverPathNumber($row->path_info);

			$data_att_dept = array('deptname' => $new_path.'_'.$row->dept_alias, );
			$res_ = $this->db2->update('departments', $data_att_dept, ['deptid' => $row->id]);
		}

		
	}


}

/* End of file Instansi.php */
/* Location: ./application/modules/Master/controllers/Instansi.php */