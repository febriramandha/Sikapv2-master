<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Setsch extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jam Kerja', 'mnglkh/setsch');
		$this->data['title'] = "Manajemen Jadwal";
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
		$this->load->css('public/themes/plugin/clock/dist/bootstrap-clockpicker.min.css');
		$this->load->js('public/themes/plugin/clock/dist/bootstrap-clockpicker.min.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Jam Kerja";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('setsch/v_index', $this->data);
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Jam Kerja";
		$this->breadcrumbs->push('Tambah Jadwal Kehadiran', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->load->view('setsch/v_add', $this->data);
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Jam Kerja";
		$this->breadcrumbs->push('Edit Jadwal Kehadiran', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['jadwal']		= $this->db->get_where('sch_class', ['id' => $id])->row();
		$this->load->view('setsch/v_edit', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		//header('Content-Type: application/json');
		$this->load->library('datatables');
        $this->datatables->select('id, name, start_time, end_time, check_in_time1, check_in_time2, check_out_time1, check_out_time2, work_day, sch_type')
        	->from('sch_class')
        	->order_by('id','asc')
        	->where('deleted', 1)
        	->add_column('name', '$1','_str_icon(name)')
        	->add_column('start_time', '$1<hr class="m-0">$2 - $3', 'jm(start_time), jm(check_in_time1), jm(check_in_time2)')
        	->add_column('end_time', '$1<hr class="m-0">$2 - $3', 'jm(end_time), jm(check_out_time1), jm(check_out_time2)')
        	->add_column('action', '<a href="'.base_url('mngsch/setsch/edit/').'$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>
					              <span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus akun" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'id');
        	if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(name)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('nama', 'nama jam kerja', 'required')
								->set_rules('jam_masuk', 'jam masuk', 'required')
								->set_rules('jam_pulang', 'jam pulang', 'required')
								->set_rules('mulai_cin', 'jam mulai scan masuk', 'required')
								->set_rules('akhir_cin', 'jam akhir scan masuk', 'required')
								->set_rules('mulai_cout', 'jam mulai scan pulang', 'required')
								->set_rules('akhir_cout', 'jam akhir scan pulang', 'required')
								->set_rules('hari', 'hari', 'required|numeric')
								->set_rules('jenis', 'jenis jam kerja', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');	
			$cekin = '0';
			if ($this->input->post('cekin')) {
						$cekin = 1;
				}
			$cekout = '0';
			if ($this->input->post('cekout')) {
						$cekout = 1;
				}		
			if ($this->mod == "add") {
				
				$data = array(
							  'name' 		    => $this->input->post('nama'),
							  'start_time' 		=> $this->input->post('jam_masuk'),
							  'end_time' 		=> $this->input->post('jam_pulang'),
							  'check_in_time1' 	=> $this->input->post('mulai_cin'),
							  'check_in_time2' 	=> $this->input->post('akhir_cin'),
							  'check_out_time1' => $this->input->post('mulai_cout'),
							  'check_out_time2' => $this->input->post('akhir_cout'),
							  'work_day' 	 	=> $this->input->post('hari'),
							  'sch_type' 	 	=> $this->input->post('jenis'),
							  'required_in' 	=> $cekin,
							  'required_out' 	=> $cekout,
							  'created_at'		=> date('Y-m-d H:i:s'),
							  'created_by'		=> $this->session->userdata('tpp_user_id')
				 );
				$this->return = $this->db->insert('sch_class',$data);
				if ($this->return) {
					 $this->result = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			    'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				
				$data = array(
							  'name' 		    => $this->input->post('nama'),
							  'start_time' 		=> $this->input->post('jam_masuk'),
							  'end_time' 		=> $this->input->post('jam_pulang'),
							  'check_in_time1' 	=> $this->input->post('mulai_cin'),
							  'check_in_time2' 	=> $this->input->post('akhir_cin'),
							  'check_out_time1' => $this->input->post('mulai_cout'),
							  'check_out_time2' => $this->input->post('akhir_cout'),
							  'work_day' 	 	=> $this->input->post('hari'),
							  'sch_type' 	 	=> $this->input->post('jenis'),
							  'required_in' 	=> $cekin,
							  'required_out' 	=> $cekout,
							  'updated_at'		=> date('Y-m-d H:i:s'),
							  'updated_by'		=> $this->session->userdata('tpp_user_id')
				 );
				$this->return = $this->db->update('sch_class', $data, ['id' => $this->input->post('id')]);
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
		$del = $this->db->update('sch_class',['deleted' => 0],['id' => $this->input->get('id')]);

		if ($del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus.']));	
		}
	}	
}

/* End of file Setsch.php */
/* Location: ./application/modules/Mngsch/controllers/Setsch.php */