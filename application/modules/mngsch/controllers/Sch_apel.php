<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Sch_apel extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jadwal Apel', 'mngsch/sch-apel');
		$this->data['title'] = "Manajemen Jadwal";
		$this->load->model(['m_instansi']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
		$this->load->css('public/themes/plugin/clock/dist/bootstrap-clockpicker.min.css');
		$this->load->js('public/themes/plugin/clock/dist/bootstrap-clockpicker.min.js');

		$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');

		$this->load->js('public/themes/material/global_assets/js/plugins/ui/moment/moment.min.js');
		$this->load->js('public/themes/material/global_assets/js/plugins/ui/fullcalendar/fullcalendar.min.js');
	
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
	}

	private function validation_cek()
	{
		$this->form_validation->set_rules('jenis_apel_id', 'Jenis Apel', 'required')
										->set_rules('pengambilan_apel', 'Pengambilan Apel', 'required')
										->set_rules('komandan_apel', 'Komandan Apel', 'required')
										->set_rules('kategori', 'Ketegori Pengguna', 'required')
										->set_rules('instansi[]', 'Instansi', 'required')
										->set_rules('keterangan', 'Keterangan', 'required')
										->set_rules('kondisi_cuaca', 'Kondisi Cuaca', 'required');
	}

	public function index()
	{
		$this->data['sub_title']  = "Jadwal Apel";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['jenis_apel'] = $this->db->get('_jenis_apel')->result();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptIDCountParent($this->session->userdata('tpp_dept_id'))->result();

		$this->load->view('sch_apel/v_index', $this->data);
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Jadwal Apel";
		$this->breadcrumbs->push('Tambah Jadwal Apel', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['day']		  	= $this->db->order_by('id')->get('days')->result();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptIDCountParent($this->session->userdata('tpp_dept_id'))->result();
		$this->data['jenis_apel'] = $this->db->get('_jenis_apel')->result();

		$this->load->view('sch_apel/v_add', $this->data);
	}


	public function indexJson()
	{
		$this->output->unset_template();
		//header('Content-Type: application/json');
		$this->load->library('datatables');
        $this->datatables->select('sch_apel.id,sch_apel.tgl_apel,_jenis_apel.name,sch_apel.pengambilan_apel,sch_apel.komandan_apel,sch_apel.ket')
        	->from('sch_apel')
			->where('sch_apel.deleted',1)
			->join('_jenis_apel','sch_apel.jenis_apel_id = _jenis_apel.id','left')
        	->order_by('id','asc')
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
		$this->validation_cek();
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
			$instansi = str_replace(['[', ']', '"'],['{', '}',''], json_encode($this->input->post('instansi')));
			$this->mod = $this->input->post('mod');	
			if ($this->mod == "add") {
				$data = array(
							  'jenis_apel_id' 	 => decrypt_url($this->input->post('jenis_apel_id'),'jenis_apel'),
							  'pengambilan_apel' => $this->input->post('pengambilan_apel'),
							  'komandan_apel' 		 => $this->input->post('komandan_apel'),
							  'start_time' 		 => $this->input->post('start_time'),
							  'end_time' 		 => $this->input->post('end_time'),
							  'dept_id' 		 => $instansi,
							  'tgl_apel' 	 => $this->input->post('start'),
							  'type_pegawai' 	=> $this->input->post('kategori'),
							  'ket' => $this->input->post('keterangan'),
							  'created_at'		=> date('Y-m-d H:i:s'),
							  'created_by'		=> $this->session->userdata('tpp_user_id'),
							  'kondisi_cuaca'	=> $this->input->post('kondisi_cuaca'),
							  'deleted'	 => 1,
				 );
				
				 $cek_apel = $this->db->select('sch_apel.id')
				  ->join('_jenis_apel','sch_apel.jenis_apel_id = _jenis_apel.id','left')
				 ->where('tgl_apel', $this->input->post('start'))
				 ->where('jenis_apel_id', decrypt_url($this->input->post('jenis_apel_id'),'jenis_apel'))
				 ->where('sch_apel.dept_id &&',"'$instansi'",false)
				 ->where('deleted', 1)
				 ->get('sch_apel')->result();
				 
				 if($cek_apel == NULL)
				 {
					$this->return = $this->db->insert('sch_apel',$data);
				 }

				if ($this->return) {
					 $this->result = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					if($cek_apel != NULL)
					{
						 $this->result = array('status' => false,
				    			    'message' => 'Data gagal disimpan, instansi yang dipilih telah terdaftar di jenis apel pada hari yang dipilih!');
					}else {
						$this->result = array('status' => false,
										'message' => 'Data gagal disimpan');		
					}
				}
			}elseif ($this->mod == "edit") {
				$data = array(
							  'jenis_apel_id' 	 => decrypt_url($this->input->post('jenis_apel_id'),'jenis_apel'),
							  'pengambilan_apel' => $this->input->post('pengambilan_apel'),
							  'komandan_apel' 		 => $this->input->post('komandan_apel'),
							  'dept_id' 		 => $instansi,
							  'type_pegawai' 	=> $this->input->post('kategori'),
							  'ket' => $this->input->post('keterangan'),
							  'start_time' 		 => $this->input->post('start_time'),
							  'end_time' 		 => $this->input->post('end_time'),
							  'created_at'		=> date('Y-m-d H:i:s'),
							  'created_by'		=> $this->session->userdata('tpp_user_id'),
							  'kondisi_cuaca'	=> $this->input->post('kondisi_cuaca')
				 );
				 
				$this->return = $this->db->update('sch_apel', $data, ['id' => decrypt_url($this->input->post('id'),'sch_apel')]);
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

	public function getEvents()
	{
		$this->output->unset_template();
		$start = $this->input->get('start');
		$end = $this->input->get('end');
		$this->db->select('sch_apel.id as sch_apel_id, sch_apel.*,_jenis_apel.*,sch_apel.ket as ket_apel')
				 ->join('_jenis_apel','sch_apel.jenis_apel_id = _jenis_apel.id','left')
				 ->where("tgl_apel BETWEEN '$start' and '$end'", NULL, FALSE )
				 ->where('sch_apel.deleted',1);
		$data = $this->db->get('sch_apel')->result();
        $kalendar = array();
		$dept_id = [];
		foreach ($data as $row) {
			$sub_data['id'] 		= encrypt_url($row->sch_apel_id,'sch_apel');
			$sub_data['start'] 		= $row->tgl_apel;
			$sub_data['jenis_apel_id'] = encrypt_url($row->jenis_apel_id,'jenis_apel');
			$sub_data['end'] 		= $row->tgl_apel;
			$sub_data['title'] 		= $row->name;
			$sub_data['kategori'] 		= $row->type_pegawai;
			$sub_data['pengambilan_apel'] 		= $row->pengambilan_apel;
			$sub_data['komandan_apel'] 		= $row->komandan_apel;
			$sub_data['dept_id'] 		= pg_to_array($row->dept_id);
			$sub_data['ket'] 		= $row->ket_apel;
			$sub_data['start_time'] 		= jm($row->start_time);
			$sub_data['end_time'] 		= jm($row->end_time);
			$kalendar[] = $sub_data;
		}
		echo json_encode($kalendar);
	}
	public function dragUpdateEvent()
	{
		$this->output->unset_template();
		$this->data = array('tgl_apel' =>  $this->input->get('start') );
		
		$res = $this->db->update('sch_apel',$this->data, ['id' => decrypt_url($this->input->get('id'),'sch_apel')]);
		if ($res) {
			echo true;
		}else echo false;
	}		

	public function ajaxDel()
	{
		$this->output->unset_template();
		$del = $this->db->update('sch_apel',['deleted' => 0],['id' => decrypt_url($this->input->get('id'),"sch_apel")]);

		if ($del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus.']));	
		}
	}	

	public function AjaxSaveOption()
	{
		$this->output->unset_template();
		$this->validation_cek();
		$this->form_validation->set_rules('ceklis[]', 'ceklis hari', 'required');
		
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

			$ceklis = $this->input->post('ceklis');
			$h = $this->input->post('h');
			foreach ($ceklis as $value) {
				    $day_id[]   =  $value;
				    $class_id[]	= $h[$value];
			}

			$class_id_array = str_replace(['[', ']', '"'],['{', '}',''], json_encode($class_id));
			$day_id_array = str_replace(['[', ']', '"'],['{', '}',''], json_encode($day_id));
			
		if ($this->form_validation->run() == TRUE) {
		

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

/* End of file Sch_apel.php */
/* Location: ./application/modules/Mngsch/controllers/Sch_apel.php */