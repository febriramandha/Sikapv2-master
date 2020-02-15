<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Sch_notfixed extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jadwal Tidak Tetap', 'mngsch/sch-notfixed');
		$this->data['title'] = "Manajemen Jadwal";
		$this->load->model(['m_instansi','m_sch_run','m_sch_class']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Jadwal Tidak Tetap";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('sch_notfixed/v_index', $this->data);
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Jadwal Jam Kerja";
		$this->breadcrumbs->push('Tambah Jadwal Jam Kerja', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['day']		  	= $this->db->order_by('id')->get('days')->result();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptIDCountParent($this->session->userdata('tpp_dept_id'))->result();
		$this->data['sch_class']	= $this->m_sch_class->GetSchClassType('umum')->result();
		$this->load->view('sch_notfixed/v_add', $this->data);
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('mod');

		if ($this->mod == "cekhari") {
				$rank1 = format_tgl_eng($this->input->get('rank1'));
				$rank2 = format_tgl_eng($this->input->get('rank2'));
				$jum = jumlah_hari_rank($rank1, $rank2);
				if ($rank1 <= $rank2 && $rank1 && $rank2) {
					$tanggal = array();
					for ($i=0; $i < $jum+1; $i++) { 
							$tanggal[] = format_tanggal(tgl_plus($rank1, $i),'D');
					}
					$tgl_id = str_replace(['[', ']','"'],['', '',"'"],json_encode(array_unique($tanggal)));

					$this->db->select('a.id,b.day_eng')
							->from('days a')
							->join("(select * from days where day_eng in($tgl_id)) as b",'a.id=b.id','left');
					$data_hari = $this->db->get()->result();

					$this->return = array('hari_id' => $data_hari, );
				}
				

				if ($this->return) {
					echo json_encode($this->result = array('status' => true,
							    			          'message' => 'berhasil memanggil data',
							    			          'result' => $this->return));
				}else{
					echo json_encode($this->result = array('status' => false,
				    			    					   'message' => 'data error'));
				}

		}
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('nama', 'nama skedul', 'required');
		$this->form_validation->set_rules('rank1', 'tanggal mulai', 'required');
		$this->form_validation->set_rules('rank2', 'tanggal berakhir', 'required');
		$this->form_validation->set_rules('instansi[]', 'instansi', 'required');
		$this->form_validation->set_rules('h[]', 'hari', 'required');
		$this->form_validation->set_rules('ceklis[]', 'ceklis hari', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));

		if ($rank1 >  $rank2) {
			$this->form_validation->set_rules('rank1', 'tanggal mulai dan tanggal berakhir', 'tidak sesuai');
		}
		if ($this->form_validation->run() == TRUE) {
			
			$status = '0';
			if ($this->input->post('status')) {
				$status = 1;
			}

			$ceklis = $this->input->post('ceklis');
			$h = $this->input->post('h');
			foreach ($ceklis as $value) {
				    $day_id[]   =  $value;
				    $class_id[]	= $h[$value];
			}

			$class_id_array = str_replace(['[', ']', '"'],['{', '}',''], json_encode($class_id));
			$day_id_array = str_replace(['[', ']', '"'],['{', '}',''], json_encode($day_id));

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
							  'type' 			=> 3,
							  'schedule_status'	=> $status
				 );
				$this->return = $this->db->insert('sch_run',$data);
				$run_id = $this->db->insert_id();


				$data = array('run_id' 	 => $run_id,
							  'class_id' => $class_id_array,
							  'day_id' 	 => $day_id_array, );

				$this->return = $this->db->insert('schnotfixed_run_day', $data);


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
							  'updated_by'		=> $this->session->userdata('tpp_user_id'),
							  'schedule_status'	=> $status
				 );
				$this->return = $this->db->update('sch_run', $data, ['id' => decrypt_url($this->input->post('id'),'schrun_tidak_tetap')]);

				$data = array(
							  'class_id' => $class_id_array,
							  'day_id' 	 => $day_id_array, );

				$this->return = $this->db->update('schnotfixed_run_day', $data, ['run_id' => decrypt_url($this->input->post('id'),'schrun_tidak_tetap')]);

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
        $this->datatables->select('a.id, name, start_date, end_date,json_data_instansi, schedule_status')
        	->from('sch_run a')
        	->join("(select a.id as schrun_id, 
					json_build_object(
							'data_instansi', json_agg((dept_name, path_info::text, level) ORDER BY path_info)
					) as json_data_instansi
					from (SELECT id, unnest(dept_id) as dept_id FROM sch_run) as a
					join v_instansi_all b on a.dept_id=b.id
					GROUP BY 1) as instansi_all",'instansi_all.schrun_id=a.id','left')
        	->order_by('a.id','desc')
        	->where('type',3)
        	->add_column('start_date','$1 - $2','format_tgl_ind(start_date), format_tgl_ind(end_date)')
        	->add_column('sch_name','$1','sch_name(name, start_date)')
        	->add_column('dept_name','<div class="m-0 p-1 panel-geser">$1</div>','instansi_expl(json_data_instansi)')
        	->add_column('status','$1','status_lock(schedule_status)')
        	->add_column('action', '<a href="'.base_url('mngsch/sch-notfixed/edit/').'$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>
					              <span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus akun" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'encrypt_url(id,"schrun_tidak_tetap")');
        	if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(name)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
        return $this->output->set_output($this->datatables->generate());
	}

	public function edit($id)
	{
		$this->data['sub_title'] 			= "Tambah Jadwal Jam Kerja";
		$this->breadcrumbs->push('Tambah Jadwal Jam Kerja', '/');
		$this->data['breadcrumb'] 			= $this->breadcrumbs->show();
		$this->data['sch_run']				= $this->db->get_where('sch_run', ['id' => decrypt_url($id,'schrun_tidak_tetap')])->row();
		$this->data['instansi']				= $this->m_instansi->GetInstasiDeptIDCountParent($this->session->userdata('tpp_dept_id'))->result();
		$this->data['sch_class']			= $this->m_sch_class->GetSchClassType('umum')->result();
		$this->data['schnotfixed_run_day']	= $this->m_sch_class->Getschnotfixed_run_day(decrypt_url($id,'schrun_tidak_tetap'))->result();
		$this->load->view('sch_notfixed/v_edit', $this->data);
	}

	public function AjaxDel()
	{
		$this->output->unset_template();
		$id = decrypt_url($this->input->get('id'),'schrun_tidak_tetap');
		$cek = $this->db->get_where('sch_run_users',['schrun_id' => $id])->row();
		if (!$cek) {
			$this->del = $this->db->delete('sch_run_deil',['run_id' => $id]);
			$this->del = $this->db->delete('sch_run',['id' => $id]);
			$this->del = $this->db->delete('schnotfixed_run_day', ['run_id' => $id]);
		}
		
		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data telah dikunci.']));	
		}
	}	


}

/* End of file Sch_notfixed.php */
/* Location: ./application/modules/Mngsch/controllers/Sch_notfixed.php */