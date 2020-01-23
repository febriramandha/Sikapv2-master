<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Cuti extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Data Cuti Pegawai', 'kepegawaian/cuti');
		$this->data['title'] = "Kepegawaian";
		$this->load->model(['m_instansi','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/plugin/autocomplete/dist/latest/bootstrap-autocomplete.js');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Data Cuti Pegawai";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('cuti/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		if ($instansi) {
			$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $instansi])->row()->level;
		}
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.nama, a.dept_name,gelar_dpn,gelar_blk, b.jum')
        	->from('v_users_all a')
        	->join('(select user_id, count(*) as jum from data_cuti where deleted=1 group by user_id) as b','a.id=b.user_id','left')
        	->where('key > 0')
        	->where('att_status',1)
        	->order_by('no_urut')
        	->add_column('id','$1','encrypt_url(id,"user_id_cuti")')
        	->add_column('jum','$1','jum')
        	->add_column('nama_nip','$1','nama_icon_pegawai(nama,gelar_dpn,gelar_blk,nip,"kepegawaian/cuti/view",id)');
        	 if ($instansi) {
		        $this->datatables->where("path_id['".$level."']='".$instansi."'");
		     }
        	 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(nama)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(nip)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
	       
        return $this->output->set_output($this->datatables->generate());
	}


	public function view($id)
	{
		$this->data['sub_title'] 	= "Cuti Pegawai";
		$this->breadcrumbs->push('Cuti Pegawai', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['user']			= $this->m_user->GetDetailBiodata(decrypt_url($id, 'user_id_cuti'))->row();
		$this->data['cuti']			= $this->db->order_by('id')->get('_cuti')->result();
		$this->load->view('cuti/v_view', $this->data);
	}

	public function CutiJson($id)
	{
		$this->output->unset_template();
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.start_date, a.end_date, c.kode, c.nama, a.start_date as libur_start_date, a.end_date as libur_end_date,')
        	->from('data_cuti a')
        	->join('days_off b','(a.start_date <= b.start_date and a.end_date >= b.start_date) and
								 (a.start_date <= b.end_date and a.end_date >= b.end_date)','left')
        	->join('_cuti c','a.cuti_id=c.id','left')
        	->order_by('a.start_date','desc')
        	->where('a.deleted',1)
        	->where('a.user_id', decrypt_url($id,'user_id_cuti'))
        	->add_column('start_date','$1 - $2','format_tgl_ind(start_date),format_tgl_ind(end_date)')
        	->add_column('cuti_nama','$1($2)','kode,nama')
        	->add_column('tot_cuti','$1','total_cuti_cek(start_date, end_date, libur_start_date,libur_end_date)')
        	->add_column('action','<a href="#" class="aksi_edit" id="$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>
        							<span class="confirm-aksi list-icons-item text-danger-600 msclick"  id="$1" msg="yakin ingin menghapus data ini">
        									<i class="icon-bin" ></i>
        							</span>', 'encrypt_url(id,"datacuti_id")');
        	 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(c.nama)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(c.kode)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
	       
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('modul');
		if ($this->mod == "listuser") {
			$instansi = decrypt_url($this->input->get('id'), 'instansi');
			$list_pegawai = $this->m_user->GetUserAllLimitQry($instansi, 5)->result();
			$data_user = array();
			foreach ($list_pegawai as $row ) {
				$sub_array['id'] 		= encrypt_url($row->id,'user_id_cuti');
				$sub_array['nama'] 		= $row->nama;
				$sub_array['nip'] 		= $row->nip;
				$sub_array['icon'] 		= base_url('uploads/avatar/thumb/'.$row->avatar);
				$data_user[] 			= $sub_array; 
			}
			if ($list_pegawai) {
				$this->output->set_output(json_encode(['results'=> $data_user ]));
			}else {
				$this->output->set_output(json_encode(['status'=>false, 'message'=> 'Gagal mengambil data.', 'results'=>[]]));
			}
		}elseif ($this->mod == "dataEdit") {
			$cuti = $this->db->get_where('data_cuti', ['id'=> decrypt_url($this->input->get('id'),'datacuti_id')])->row();
			if ($cuti) {
				$data_cuti = array('id' 		=> encrypt_url($cuti->id,'datacuti_id'),
								   'start_date' => format_tgl_ind($cuti->start_date),
								   'end_date' 	=> format_tgl_ind($cuti->end_date),
								   'cuti_id' 	=> $cuti->cuti_id, );
				$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Berhasil mengambil data.', 'data'=> $data_cuti]));
			} else{
				$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));	
			}
		}else {
			$this->form_validation->set_rules('instansi', 'instansi', 'required')
								  ->set_rules('user', 'pegawai', 'required');		
			$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

			if ($this->form_validation->run() == TRUE) {
				   $this->result = array('status'   => true,
			    	     		  		 'message'  => 'Data berhasil disimpan',
			    	     		  		 'user' 	=> $this->input->post('user'),);
			}else {
				$this->result = array('status'   => false,
					    	     	  'message' => validation_errors(),);
			}
			if ($this->result) {
				$this->output->set_output(json_encode($this->result));	
			}else {
				$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
			}
		}
			
	}

	public function AjaxSave()
	{
		$this->output->unset_template();

		$this->form_validation->set_rules('rank1', 'tanggal mulai', 'required')
							  ->set_rules('rank2', 'tanggal berakhir', 'required')
							  ->set_rules('jenis', 'jenis cuti', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));

		if ($rank1 >  $rank2) {
			$this->form_validation->set_rules('rank1', 'tanggal mulai dan tanggal berakhir', 'tidak sesuai');
		}

		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');
			if ($this->mod == "add") {
				$data = array('user_id' 		=> decrypt_url($this->input->post('user_id'),"user_id_cuti"),
							  'dept_id' 		=> decrypt_url($this->input->post('dept_id'),"dept_id"),
							  'start_date' 	 	=> $rank1,
							  'end_date' 		=> $rank2,
							  'cuti_id' 		=> $this->input->post('jenis'),
							  'created_at' 		=> date('Y-m-d H:i:s'),
							  'created_by' 	 	=> $this->session->userdata('tpp_user_id'),
				 );
				$this->return = $this->db->insert('data_cuti',$data);

				if ($this->return) {
					 $this->result = array('status' => true,
				    			    		'message' => 'Data berhasil disimpan');
				}else{
					 $this->result = array('status' => false,
				    			    		'message' => 'Data gagal disimpan');
				}
			}elseif ($this->mod == "edit") {
				$data = array(
							  'start_date' 	 	=> $rank2,
							  'end_date' 		=> $rank2,
							  'cuti_id' 		=> $this->input->post('jenis'),
							  'updated_at' 		=> date('Y-m-d H:i:s'),
							  'updated_by' 	 	=> $this->session->userdata('tpp_user_id'),
				 );
				$this->return = $this->db->update('data_cuti', $data, ['id' => decrypt_url($this->input->post('id'),"datacuti_id")]);

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
			$this->output->set_output(json_encode(['message'=>FALSE, 'msg'=> 'Gagal mengambil data.']));
		}
	}

	public function AjaxDel()
	{
		$this->output->unset_template();
		$this->del = $this->db->update('data_cuti',['deleted' => 0],['id' => decrypt_url($this->input->get('id'),"datacuti_id")]);

		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}



}

/* End of file Cuti.php */
/* Location: ./application/modules/Kepegawaian/controllers/Cuti.php */