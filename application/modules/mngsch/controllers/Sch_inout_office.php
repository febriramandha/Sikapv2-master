<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
* Created By: Rian Reski A
* 2019
*/

class Sch_inout_office extends  App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jadwal Absen', 'mngsch/sch-inout-office');
		$this->data['title'] = "Jadwal Absen";
		$this->load->model('m_user');
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/material/global_assets/js/plugins/ui/moment/moment.min.js');
		$this->load->js('public/themes/material/global_assets/js/plugins/ui/fullcalendar/fullcalendar.min.js');
		$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Jadwal Absen di luar kantor";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['user']		  = $this->m_user->GetUserAllAktifDept($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('sch_inout_office/v_index', $this->data);
	}


	public function AjaxSave()
	{
		$this->output->unset_template();
		if ($this->input->post('mod') == "add") {
			$this->form_validation->set_rules('start', 'tanggal', 'required');
			$this->form_validation->set_rules('end', 'tanggal', 'required');
		}
		$this->form_validation->set_rules('user[]', 'pegawai', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');
			if ($this->mod == "add") {

				$user_ = array();
				foreach ($this->input->post('user') as $v) {
						$user_[] = decrypt_url($v,'user_id_office');
				}

				$data = array('start_date' 	 	=> $this->input->post('start'),
							  'end_date' 		=> tgl_minus($this->input->post('end'),1),
							  'dept_id' 		=> '{'.$this->session->userdata('tpp_dept_id').'}',
							  'user_id' 		=> to_pg_array($user_),
							  'berita_acara'	=> 'kantor',
							  'created_at'		=> date('Y-m-d H:i:s'),
							  'created_by'		=> $this->session->userdata('tpp_user_id'),
				 );
				$this->return = $this->db->insert('sch_inout_office',$data);
				

				if ($this->return) {
					 $this->result = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			    'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				// $data = array(
				// 			  'nama' 			=> $this->input->post('ket'),
				//  );
				// $this->return = $this->db->update('days_off', $data, ['id' => $this->input->post('id')]);

				// if ($this->return) {
				// 	 $this->result = array('status' => true,
				//     			   'message' => 'Data berhasil disimpan');
				// }else{
				// 	 $this->result = array('status' => false,
				//     			   'message' => 'Data gagal disimpan');
				// }
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

	public function ajaxDel()
	{
		$this->output->unset_template();
		$del = $this->db->delete('sch_inout_office',['id' => $this->input->get('id')]);

		if ($del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus.']));	
		}
	}	

	public function getEvents()
	{
		$this->output->unset_template();
		$start = $this->input->get('start');
		$end = $this->input->get('end');
		$dept_id = $this->session->userdata('tpp_dept_id');
		$this->db->select('*')
				 ->where('berita_acara','kantor')
				 ->where("$dept_id",'any(dept_id)', false)
				 ->where("start_date BETWEEN '$start' and '$end'", NULL, FALSE )
				 ->join("(select id as office_id, 
					json_build_object(
							'data_pegawai', json_agg((nip, nama, gelar_dpn, gelar_blk) ORDER BY no_urut)
					) as json_nama_nip
					from (select a.id, a.user_id, b.nip, b.nama, b.gelar_dpn, b.gelar_blk, b.no_urut 
					from (select id, 	unnest(user_id) as user_id from sch_inout_office) as a
					left join v_users_all b on a.user_id=b.id) as a
					GROUP BY 1) as b",'a.id=b.office_id');
		$data = $this->db->get('sch_inout_office a')->result();
        $kalendar = array();
		foreach ($data as $row) {

			$sub_data['id'] 		= $row->id;
			$sub_data['start'] 		= $row->start_date;
			$sub_data['end'] 		= tgl_plus($row->end_date,1);
			$sub_data['title'] 		= "Lihat Jadwal";
			$sub_data['ket'] 		= pegawai_expl($row->json_nama_nip);
			$kalendar[] = $sub_data;
		}
		echo json_encode($kalendar);
	}

	public function dragUpdateEvent()
	{
		$this->output->unset_template();
		$this->data = array('start_date' =>  $this->input->get('start'),
							'end_date' =>  tgl_minus($this->input->get('end'),1) );

		$res = $this->db->update('sch_inout_office',$this->data, ['id' => $this->input->get('id')]);
		if ($res) {
			echo true;
		}else echo false;
	}

}


/* End of file sch_inout_office.php */
/* Location: ./application/modules/mngsch/controllers/sch_inout_office.php */