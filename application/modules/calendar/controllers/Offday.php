<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Offday extends  App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Kelender Libur', 'calendar/offday');
		$this->data['title'] = "Kalender";
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/material/global_assets/js/plugins/ui/moment/moment.min.js');
		$this->load->js('public/themes/material/global_assets/js/plugins/ui/fullcalendar/fullcalendar.min.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Kelender Libur";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->load->view('offday/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		//header('Content-Type: application/json');
		$this->load->library('datatables');
        $this->datatables->select('id, start_date, end_date, nama')
        	->from('days_off')
        	->order_by('start_date','desc')
        	->add_column('start_date', '$1', '_start_clus(start_date, end_date)');
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		if ($this->input->post('mod') == "add") {
			$this->form_validation->set_rules('start', 'tanggal', 'required');
			$this->form_validation->set_rules('end', 'tanggal', 'required');
		}
		$this->form_validation->set_rules('ket', 'nama libur', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');
			if ($this->mod == "add") {
				$data = array('start_date' 	 	=> $this->input->post('start'),
							  'end_date' 		=> tgl_minus($this->input->post('end'),1),
							  'nama' 			=> $this->input->post('ket'),
				 );
				$this->return = $this->db->insert('days_off',$data);

				if ($this->return) {
					 $this->result = array('status' => true,
				    			    'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			    'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				$data = array(
							  'nama' 			=> $this->input->post('ket'),
				 );
				$this->return = $this->db->update('days_off', $data, ['id' => $this->input->post('id')]);

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
		$del = $this->db->delete('days_off',['id' => $this->input->get('id')]);

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
				 ->where("start_date BETWEEN '$start' and '$end'", NULL, FALSE );
		$data = $this->db->get('days_off')->result();
        $kalendar = array();
		foreach ($data as $row) {
			$sub_data['id'] 		= $row->id;
			$sub_data['start'] 		= $row->start_date;
			$sub_data['end'] 		= tgl_plus($row->end_date,1);
			$sub_data['title'] 		= $row->nama;
			$sub_data['ket'] 		= $row->nama;
			$kalendar[] = $sub_data;
		}
		echo json_encode($kalendar);
	}

	public function dragUpdateEvent()
	{
		$this->output->unset_template();
		$this->data = array('start_date' =>  $this->input->get('start'),
							'end_date' =>  tgl_minus($this->input->get('end'),1) );

		$res = $this->db->update('days_off',$this->data, ['id' => $this->input->get('id')]);
		if ($res) {
			echo true;
		}else echo false;
	}

}

/* End of file Offday.php */
/* Location: ./application/modules/calendar/controllers/Offday.php */