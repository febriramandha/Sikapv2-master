<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Rekap_lkh extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Rekapitulasi LKH', 'report/rekap-lkh');
		$this->data['title'] = "Laporan Umum";
		$this->load->model(['m_instansi','m_pejabat_instansi','m_absen','m_sch_run']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->css('public/themes/plugin/datepicker/css/bootstrap-datepicker.css');
    	$this->load->js('public/themes/plugin/datepicker/js/bootstrap-datepicker.js');
    	$this->load->css('public/themes/plugin/chekbox/rrcheckbox.css');
    	$this->load->js('public/themes/material/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js');
    	$this->load->css('public/themes/plugin/toplipcss/rrtooltip/rrtooltip.css');
	}

	public function index()
	{
		$this->data['sub_title']  = "Rekapitulasi LKH";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->data['laporan_tahun'] = $this->m_sch_run->GetTahun()->result();
		$this->load->view('rekap_lkh/v_index', $this->data);
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('mod');
		if ($this->mod == "DataPegawai") {
			$dept_id = decrypt_url($this->input->get('id'),'instansi');
			$pns 	 = $this->input->get('pns');
			$tpp 	 = $this->input->get('tpp');
				$this->db->select('*')
						->from('v_users_all')
						->where('key > 0')
        				->where('att_status',1)
        				->where('dept_id', $dept_id);
        		if ($pns) {
        			$this->db->where('pns',$pns);
        		}
        		if ($tpp != 'false' && $tpp) {
        			$this->db->where('tpp',1);
        		}
        		$data_pegawai = $this->db->get()->result();

        		$res='<select class="form-control multiselect-clickable-groups" name="pegawai[]" multiple="multiple" data-fouc>';
        		$no = 1;
        		foreach ($data_pegawai as $row ){
		          		$res.= "<option value='".$row->id."'>($no) $row->nama/$row->nip</option>";
		          		$no++;
		          }
		        $res .='</select>
		        		<script type="text/javascript" language="javascript" > 
		        			$(".multiselect-clickable-groups").multiselect({
							    includeSelectAllOption: true,
							    enableFiltering: true,
							    enableCaseInsensitiveFiltering: true,
							    placeholder: "Pilih Pegawai",
							});
		        		</script>';

		        $this->output->set_output($res);
		}elseif ($this->mod == "a") {
			
			
		}
		
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$user_id  	= $this->input->post('pegawai');
		$user_id_in =array();
		if ($user_id) {
			foreach ($user_id as $r_v ) {
				$user_id_in[] = $r_v;
			}
		}
		
		$tahun  	= $this->input->post('tahun');
		$bulan  	= $this->input->post('bulan');

		if ($tahun && $bulan) {
			$hari_ini 		= "$tahun-$bulan-01";
 			$rank1 			= date('Y-m-01', strtotime($hari_ini));
 			$rank2 			= date('Y-m-t', strtotime($hari_ini));
			
		}else {
			$rank1  	   = date('Y-m-d');
			$rank2  	   = date('Y-m-d');
		}
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nama, a.nip, a.gelar_dpn, a.gelar_blk, json_jadwal_lkh')
        	->from("v_users_all a")
        	->order_by('no_urut');
        $this->db->join("(select a.id,
					json_build_object(
									'data_jum_lkh',json_agg(
									(	rentan_tanggal,
										start_time, 
										end_time,
										start_time_shift,
										end_time_shift,
										start_time_notfixed, 
										end_time_notfixed,
										daysoff_id,
										jumlah_lkh
									) ORDER BY rentan_tanggal)
							) as json_jadwal_lkh
						from mf_users a
						left join (
						select 
						a.id, 
						rentan_tanggal,
						b.start_time, 
						b.end_time,
						c.start_time as start_time_shift,
						c.end_time as end_time_shift,
						d.start_time as start_time_notfixed, 
						d.end_time as end_time_notfixed,
						e.tanggal as daysoff_id,
						f.jum as jumlah_lkh
						from 
						(select a.id, rentan_tanggal from mf_users a, (select * from rentan_tanggal('$rank1','$rank2')) as tanggal) as a
						left join v_jadwal_kerja_users b on ((rentan_tanggal >= b.start_date and rentan_tanggal <= b.end_date and extract('isodow' from a.rentan_tanggal) = b.s_day)and b.user_id=a.id)
						left join v_jadwal_kerja_users_shift c on (a.id = c.user_id and c.start_shift=a.rentan_tanggal)
						left join v_jadwal_kerja_users_notfixed d on ((rentan_tanggal >= d.start_date and rentan_tanggal <= d.end_date and extract('isodow' from a.rentan_tanggal) = d.day_id)and d.user_id=a.id)
						left join days_off e on (rentan_tanggal >= e.start_date and rentan_tanggal <= e.end_date)
						left join jumlah_lkh_users f on (a.id = f.user_id and rentan_tanggal = f.tgl_lkh)
						group by 1,2,3,4,5,6,7,8,9,10) as b on a.id=b.id
						group by 1) as b",'a.id=b.id','left',false);
        	$this->datatables->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)');
        	$this->datatables->add_column('jum_hari_kerja','$1','jum_hari_kerja_rekap_lkh(json_jadwal_lkh)');
        	$this->datatables->add_column('jum_hadir_kerja_rekap','$1','jum_data_kerja_rekap_lkh(json_jadwal_lkh)');
        	$this->datatables->add_column('total_jum_lkh_rekap','$1','total_jum_lkh_rekap(json_jadwal_lkh)');
        	 if ($user_id_in) {
        	 	if (!$tahun || !$bulan) {
			     		$this->datatables->where_in('a.id','0');
			     }else {
			     	$this->datatables->where_in('a.id', $user_id_in);
			     }
		     }else {
		     	 $this->datatables->where_in('a.id','0');
		     }
		     
        return $this->output->set_output($this->datatables->generate());
	}

	public function cetak()
	{
		$this->output->unset_template();
		$dept_id = decrypt_url($this->input->post('instansi'),'instansi');
		

		$this->form_validation->set_rules('instansi', 'nama instansi', 'required')
							  ->set_rules('pegawai[]', 'pegawai', 'required')
							  ->set_rules('tahun', 'tahun', 'required')
							  ->set_rules('bulan', 'bulan', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');
		if ($this->form_validation->run() == TRUE) {
			$tahun  	= $this->input->post('tahun');
			$bulan  	= $this->input->post('bulan');

			$hari_ini 		= "$tahun-$bulan-01";
 			$rank1 			= date('Y-m-01', strtotime($hari_ini));
 			$rank2 			= date('Y-m-t', strtotime($hari_ini));
			$jum_hari = jumlah_hari_rank($rank1, $rank2);
			if ($jum_hari > 31) {
				echo 'maksimat tanggal yang diizinkan 31 hari';
			}else{
				$user_id  	= $this->input->post('pegawai');
				$user_id_in =array();
				if ($user_id) {
					foreach ($user_id as $r_v ) {
						$user_id_in[] = $r_v;
					}
				}
				$this->data['jum_hari']	= $jum_hari;
				$this->data['rank1'] 	= $rank1;
				$this->data['pegawai_lkh'] = $this->m_absen->PegawaiAbsenQueryRekapitulasiLkh($user_id_in, $rank1, $rank2)->result();
				$this->data['priode']		 = tgl_ind_bulan($rank1).' s/d '.tgl_ind_bulan($rank2);
				$this->data['datainstansi']  		= $this->m_pejabat_instansi->GetPajabatByInstansi($dept_id, 7)->row();
				$this->data['datainstansi_kepala']  = $this->m_pejabat_instansi->GetPajabatByInstansi($dept_id, 3)->row();
				$this->load->library('Tpdf');
				$this->load->view('rekap_lkh/v_cetak', $this->data);
			}
		}else {
			echo  validation_errors();
		}
	}

}

/* End of file Rekap_lkh.php */
/* Location: ./application/modules/report/controllers/Rekap_lkh.php */