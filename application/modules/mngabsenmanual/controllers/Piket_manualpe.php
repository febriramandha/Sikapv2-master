<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Piket_manualpe extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Piket Manual Pegawai', 'mngabsenmanual/piket-manualpe');
		$this->data['title'] = "Piket Manual";
		$this->load->model(['m_instansi','m_schabsen_manual']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Piket Manual Pegawai";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('piket_manualpe/v_index', $this->data);
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
        	->add_column('action', '<a href="'.base_url('mngabsenmanual/piket-manualpe/view/').'$1" class="badge bg-info">
        							<i class="icon-pencil5"></i> Input Piket
					                </a>
					              ', 'encrypt_url(id,"schabsenmanual_id_piket")')
        	->where("path_id['".$level."']='".$instansi."'");
        return $this->output->set_output($this->datatables->generate());
	}

	public function view($id)
	{
		$this->data['sub_title'] 	= "Input Piket Manual";
		$this->breadcrumbs->push('Input Piket Manual', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$user_data = $this->m_schabsen_manual->userAll_schpiket_manual(decrypt_url($id,'schabsenmanual_id_piket'));
		$this->data['user_data']	= $user_data;
		$this->data['instansi']		= $this->m_instansi->GetInstansi($user_data->row()->dept_id)->row();
		$this->load->view('piket_manualpe/v_view', $this->data);
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('u', 'pegawai', 'required');

		if (!$this->input->post('in') && !$this->input->post('out')) {
			$this->form_validation->set_rules('cekout1', 'jenis piket', 'required');
		}

		$tahun 	= decrypt_url($this->input->post('tahun'),'tahun_piket');
		$bulan 	=  decrypt_url($this->input->post('bulan'),'bulan_piket');
		$tanggal=  decrypt_url($this->input->post('tanggal'),'tanggal_piket');

		$tanggal_full = "$tahun-$bulan-$tanggal";

		$cek_valit_tanggal = Cek_tanggalValid($tanggal_full);

		if ($cek_valit_tanggal == false) {
			$this->form_validation->set_rules('tanggal_valid', 'tanggal valid', 'required');
		}

		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');	
			
			$schabsenmanual_id 	  = decrypt_url($this->input->post('sch'),'schabsmanual_id_piket');
			$user_id  			  = decrypt_url($this->input->post('u'),'user_id_absenmanual_piket');

			$in_c 		= $this->input->post('in');
			$out_c 		= $this->input->post('out');

			$check_in  	= decrypt_url($this->input->post('cekin'),'absen_in_piket');
			$check_out  = decrypt_url($this->input->post('cekout'),'absen_out_piket');

			$in_data = "{NULL}";
			if ($in_c && $check_in) {
				$in_data 	  = "{1}";
			}
			$out_data = "{NULL}";
			if ($out_c && $check_out) {
				$out_data 	  = "{1}";
			}

			$tanggal_data = "{{$tanggal_full}}";

			$cek_id = $this->db->select('id')->get_where('absenmanual_data',['user_id' => $user_id, 'schabsmanual_id' => $schabsenmanual_id, 'tanggal' => $tanggal_data ])->row();

			if (!$cek_id) {
				$data = array(
							  'user_id' 		=> $user_id,
							  'schabsmanual_id' => $schabsenmanual_id,
							  'tanggal' 		=> $tanggal_data,
							  'status_in' 		=> $in_data,
							  'status_out' 		=> $out_data,
							  'created_at' 		=> date('Y-m-d H:i:s'),
							  'created_by' 		=> $this->session->userdata('tpp_user_id'),
				 );
				$this->return = $this->db->insert('absenmanual_data',$data);
	
			}elseif ($cek_id) {
				$data = array(
							  'tanggal' 		=> $tanggal_data,
							  'status_in' 		=> $in_data,
							  'status_out' 		=> $out_data,
							  'created_at' 		=> date('Y-m-d H:i:s'),
							  'created_by' 		=> $this->session->userdata('tpp_user_id'),
				 );
				$this->return = $this->db->update('absenmanual_data',$data,['id' => $cek_id->id]);
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

	public function absenJson()
	{
		$this->output->unset_template();
		$this->load->library('datatables');
		$schabsenmanual_id = decrypt_url($this->input->post('sch'),'schabsmanual_id_piket');
        $this->datatables->select('a.id, unnest(tanggal) as tanggal, b.nama,unnest(status_in) as status_in, unnest(status_out) as status_out, nip')
        	->from('absenmanual_data a')
        	->join('mf_users b','a.user_id=b.id','left')
        	->order_by('a.id','desc')
        	->where('schabsmanual_id', $schabsenmanual_id)
        	->add_column('nama', '$1($2)','nama,nip')
        	->add_column('tanggal','$1','tglInd_hrtabel(tanggal)')
        	->add_column('action', '<span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus data" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'encrypt_url(id,"schabsenmanual_data_id")');
        return $this->output->set_output($this->datatables->generate());
	}	

	public function AjaxDel()
	{
		$this->output->unset_template();
		$this->del = $this->db->delete('absenmanual_data',['id' => decrypt_url($this->input->get('id'),"schabsenmanual_data_id")]);
		if ($this->del) {
			$this->output->set_output(json_encode(['status'=>TRUE, 'message'=> 'Data berhasil dihapus.']));
		} else{
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal dihapus atau data sedang digunakan.']));	
		}
	}	

}

/* End of file Piket_manualpe.php */
/* Location: ./application/modules/mngabsenmanual/controllers/Piket_manualpe.php */