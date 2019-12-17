<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Kupacara extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Kelender Upacara', 'calendar/kupacara');
		$this->data['title'] = "Kalender";
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/material/global_assets/js/plugins/ui/moment/moment.min.js');
		$this->load->js('public/themes/material/global_assets/js/plugins/ui/fullcalendar/fullcalendar.min.js');
		$this->load->css('public/themes/plugin/clock/dist/bootstrap-clockpicker.min.css');
		$this->load->js('public/themes/plugin/clock/dist/bootstrap-clockpicker.min.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Kelender Upacara";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('kupacara/v_index', $this->data);
	}


	public function indexJson()
	{
		$this->output->unset_template();
		//header('Content-Type: application/json');
		$this->load->library('datatables');
        $this->datatables->select('id, tanggal, jam_mulai, ket')
        	->from('sch_upacara')
        	->order_by('tanggal','desc')
        	->add_column('tanggal', '$1($2)', 'format_tgl_ind(tanggal),jm(jam_mulai)');
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		if ($this->input->post('mod') == "add") {
			$this->form_validation->set_rules('start', 'tanggal upacara', 'required');
		}
		$this->form_validation->set_rules('jam', 'jam upacara', 'required')
							  ->set_rules('ket', 'keterangan', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');
			if ($this->mod == "add") {
				$data = array('tanggal' 	 	=> $this->input->post('start'),
							  'jam_mulai' 		=> $this->input->post('jam'),
							  'ket' 			=> $this->input->post('ket'),
				 );
				$this->return = $this->db->insert('sch_upacara',$data);

				if ($this->return) {
					 $this->result = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			    'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				$data = array(
							  'jam_mulai' 		=> $this->input->post('jam'),
							  'ket' 			=> $this->input->post('ket'),
				 );
				$this->return = $this->db->update('sch_upacara', $data, ['id' => $this->input->post('id')]);

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

	public function ajaxDel()
	{
		$this->output->unset_template();
		$del = $this->db->delete('sch_upacara',['id' => $this->input->get('id')]);

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
		$this->db->select('*')
				 ->where("tanggal BETWEEN '$start' and '$end'", NULL, FALSE );
		$data = $this->db->get('sch_upacara')->result();
        $kalendar = array();
		foreach ($data as $row) {
			$sub_data['id'] 		= $row->id;
			$sub_data['start'] 		= $row->tanggal;
			$sub_data['end'] 		= $row->tanggal;
			$sub_data['title'] 		= jm($row->jam_mulai);
			$sub_data['jam'] 		= jm($row->jam_mulai);
			$sub_data['ket'] 		= $row->ket;
			$kalendar[] = $sub_data;
		}
		echo json_encode($kalendar);
	}

	public function dragUpdateEvent()
	{
		$this->output->unset_template();
		$this->data = array('tanggal' =>  $this->input->get('start') );

		$res = $this->db->update('sch_upacara',$this->data, ['id' => $this->input->get('id')]);
		if ($res) {
			echo true;
		}else echo false;
	}		

}

/* End of file Kupacara.php */
/* Location: ./application/modules/calendar/controllers/Kupacara.php */