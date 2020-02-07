<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Schpiket_manual extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Jadwal Piket Manual', 'mngabsenmanual/schabsen-manualin');
		$this->data['title'] = "Piket Manual";
		$this->load->model(['m_instansi']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Jadwal Piket Manual";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('schpiket_manual/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$this->load->library('datatables');
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $instansi])->row()->level;
        $this->datatables->select('a.id, a.name, a.start_date, a.end_date, json_nama_nip as nama_nip, c.dept_alias,a.absen_in,a.absen_out')
        	->from('schabsen_manual a')
        	->join("(select id as schabsenmanual_id, 
					json_build_object(
							'data_pegawai', json_agg((nip, nama, gelar_dpn, gelar_blk) ORDER BY no_urut)
					) as json_nama_nip
					from (select a.id, a.user_id, b.nip, b.nama, b.gelar_dpn, b.gelar_blk, b.no_urut 
					from (select id, 	unnest(user_id) as user_id from schabsen_manual) as a
					left join v_users_all b on a.user_id=b.id) as a
					GROUP BY 1) as b",'a.id=b.schabsenmanual_id')
        	->join('v_instansi_all c','a.dept_id=c.id','left')
        	->order_by('a.id','desc')
        	->where('a.type_absen','piket')
        	->add_column('start_date','$1 - $2','format_tgl_ind(start_date), format_tgl_ind(end_date)')
        	->add_column('sch_name','$1','sch_name(name, start_date)')
        	->add_column('pegawai','<div class="m-0 p-1 panel-geser" style="max-height: 100px;max-width: 300px">$1</div>','pegawai_expl(nama_nip)')
        	->add_column('akses','$1$2','span_label(absen_in,"Masuk","success"),span_label(absen_out,"Pulang","info")')
        	->add_column('action', '<a href="'.base_url('mngabsenmanual/schpiket-manual/edit/').'$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>
					              <span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus data" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'encrypt_url(id,"schabsenmanual_id")')
        	->where("path_id['".$level."']='".$instansi."'");
        return $this->output->set_output($this->datatables->generate());
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Jadwal";
		$this->breadcrumbs->push('Tambah Jadwal', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptIDCountParent($this->session->userdata('tpp_dept_id'))->result();
		$this->data['hari']			= $this->db->order_by('id')->get('days');
		$this->load->view('schpiket_manual/v_add', $this->data);
	}

	public function PegawaiJson()
	{
		$this->output->unset_template();
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.nama, a.dept_name, status_pegawai,gelar_dpn,gelar_blk')
        	->from('v_users_all a')
        	->where('key > 0')
        	->where('att_status',1)
        	->order_by('no_urut')
        	->add_column('cekbox','<label class="pure-material-checkbox">
										<input type="checkbox" name="user[]" value="$1" class="checkbox">
										<span></span>
							        </label>','id')
        	->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)');
		     $this->datatables->where('a.dept_id', $instansi);
		     
        	 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(nama)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(nip)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	         }
	       
        return $this->output->set_output($this->datatables->generate());
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_rules('nama', 'nama jadwal', 'required');
		if (!$this->input->post('cekin') && !$this->input->post('cekout')) {
			$this->form_validation->set_rules('cekout1', 'akses', 'required');
		}
		$this->form_validation->set_rules('hari[]', 'hari', 'required');
		$this->form_validation->set_rules('user[]', 'pegawai', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		$instansi  = decrypt_url($this->input->post('instansi'), 'instansi');

		$rank1 = format_tgl_eng($this->input->post('rank1'));
		$rank2 = format_tgl_eng($this->input->post('rank2'));

		if (jumlah_hari_rank($rank1,$rank2) > 365) {
			$this->form_validation->set_rules('rank1', 'jumlah tanggal maksimal 31 hari', 'tidak sesuai');
		}
		
		if ($rank1 >  $rank2) {
			$this->form_validation->set_rules('rank1', 'tanggal mulai dan tanggal berakhir', 'tidak sesuai');
		}
		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');	
			$absen_in = null;
			if ($this->input->post('cekin')) {
					$absen_in = 1;
			}

			$absen_out = null;
			if ($this->input->post('cekout')) {
					$absen_out = 1;
			}	

			$user_cek = $this->input->post('user');
			$user_cek_ = array();
			for ($i=0; $i < count($user_cek); $i++) {
				$user_dec[]= $user_cek[$i];
			}
			$user = str_replace(['[', ']', '"'],['{', '}',''], json_encode($user_dec));

			$hari = $this->input->post('hari');
			foreach ($hari as $value) {
				$hari_id_[]= $value;
			}

			$hari_id = str_replace(['[', ']', '"'],['{', '}',''], json_encode($hari_id_));

			if ($this->mod == "add") {
				$data = array(
							  'user_id' 		=> $user,
							  'hari_id' 		=> $hari_id,
							  'dept_id' 		=> decrypt_url($this->input->post('instansi'),'instansi'),
							  'name' 		 	=> $this->input->post('nama'),
							  'start_date' 		=> $rank1,
							  'end_date' 		=> $rank2,
							  'absen_in' 		=> $absen_in,
							  'absen_out' 		=> $absen_out,
							  'created_at' 		=> date('Y-m-d H:i:s'),
							  'created_by' 		=> $this->session->userdata('tpp_user_id'),
							  'type_absen' 		=> 'piket',
				 );
				$this->return = $this->db->insert('schabsen_manual',$data);

				
			}elseif ($this->mod == "edit") {
				$data = array(
							  'user_id' 		=> $user,
							  'hari_id' 		=> $hari_id,
							  'name' 		 	=> $this->input->post('nama'),
							  'absen_in' 		=> $absen_in,
							  'absen_out' 		=> $absen_out,
							  'updated_at' 		=> date('Y-m-d H:i:s'),
							  'updated_by' 		=> $this->session->userdata('tpp_user_id'),
				 );
				$this->return = $this->db->update('schabsen_manual',$data,['id' => decrypt_url($this->input->post('id'),'schabsenmanual_id')]);
			}

			if ($this->return) {
				 $this->result = array('status' => true,
			    			    'message' => 'Data berhasil disimpan');
			}else{
				 $this->result = array('status' => false,
			    			    'message' => 'Data gagal disimpan');
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

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Jadwal";
		$this->breadcrumbs->push('Edit Jadwal', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$schabsenmanual = $this->db->get_where('schabsen_manual',['id' => decrypt_url($id,"schabsenmanual_id")])->row();
		$this->data['schabsenmanual']	= $schabsenmanual;
		$this->data['instansi']		= $this->m_instansi->GetInstansi($schabsenmanual->dept_id)->row();
		$this->data['hari']			= $this->db->order_by('id')->get('days');
		$this->load->view('schpiket_manual/v_edit', $this->data);
	}	

	public function PegawaiJsonEdit($id)
	{
		$this->output->unset_template();
		$schabsenmanual_id = decrypt_url($id,'schabsenmanual_id');
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.nama,gelar_dpn,gelar_blk,b.user_id')
        	->from('v_users_all a')
        	->join("(select id, unnest(user_id) as user_id from schabsen_manual where id=$schabsenmanual_id) as b",'a.id=b.user_id','left')
        	->where('key > 0')
        	->where('att_status',1)
        	->order_by('no_urut')
        	->add_column('cekbox','<label class="pure-material-checkbox">
										<input type="checkbox" name="user[]" value="$1" $2 class="checkbox">
										<span></span>
							        </label>','id, checked(user_id)')
        	->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)');
        	$this->datatables->where('a.dept_id', $instansi);
        	 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(nama)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(nip)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	         }
	       
        return $this->output->set_output($this->datatables->generate());
	}	

	public function AjaxDel()
	{
		$this->output->unset_template();
		$this->del = $this->db->delete('schabsen_manual',['id' => decrypt_url($this->input->get('id'),"schabsenmanual_id")]);
		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}	
}

/* End of file Schpiket_manual.php */
/* Location: ./application/modules/mngabsenmanual/controllers/Schpiket_manual.php */