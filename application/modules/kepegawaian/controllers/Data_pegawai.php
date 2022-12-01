<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

class Data_pegawai extends App_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Data Pegawai', 'kepegawaian/data-pegawai');
		$this->data['title'] = "Kepegawaian";
		$this->load->model(['m_instansi','m_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/material/global_assets/js/plugins/forms/wizards/steps.min.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Data Pegawai";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('data_pegawai/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$instansi = decrypt_url($this->input->post('instansi'),'instansi');
		$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $instansi])->row()->level;
		$this->load->library('datatables');
        $this->datatables->select('a.id, a.nip, a.nama, a.dept_name, b.level, status, status_pegawai,gelar_dpn,gelar_blk,tpp')
        	->from('v_users_all a')
        	->where('key > 0')
        	->where('att_status',1)
        	->join('users_login b','a.id=b.user_id','left')
        	->order_by('no_urut')
        	->add_column('nama_nip','$1','nama_icon_nip(nama,gelar_dpn,gelar_blk,nip,"kepegawaian/data-pegawai/view",id)')
        	->add_column('status_user','$1','status_user(status)')
        	->add_column('pegawai_status','$1','status_pegawai(status_pegawai)')
        	->add_column('tpp','$1','status_tpp(tpp)')
        	->add_column('action','<a href="'.base_url('kepegawaian/data-pegawai/edit/').'$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>', 'encrypt_url(id,"user_id")');
		     $this->datatables->where("path_id['".$level."']='".$instansi."'");
        	 if ($this->input->post('search[value]')) {
        	 	$this->db->group_start();
		        	$this->datatables->like('lower(nama)', strtolower($this->input->post('search[value]')));
		        	$this->datatables->or_like('lower(nip)', strtolower($this->input->post('search[value]')));
	        	$this->db->group_end();
	        }
	       
        return $this->output->set_output($this->datatables->generate());
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Data Pegawai";
		$this->breadcrumbs->push('Edit Pegawai', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['user']			= $this->m_user->GetUser(decrypt_url($id, 'user_id'))->row();
		$this->data['simpeg_user']  = $this->m_user->getSimpegDeptUser($this->data['user']->dept_id);
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->data['instansi_cek']	= $this->m_instansi->GetInstansi($this->data['user']->dept_id)->row();
		$this->data['agama']		= $this->db->order_by('id')->get('_agama')->result();
		$this->data['eselon']		= $this->db->order_by('id')->get('_eselon')->result();
		$this->data['golongan']		= $this->db->order_by('id')->get('_golongan')->result();
		$this->data['status_peg']	= $this->db->order_by('id')->get('_statpeg')->result();
		$this->load->view('data_pegawai/v_edit', $this->data);
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$mod = $this->input->post('mod');
		// unik field 
		$username_cek = 'is_unique[users_login.username]';
		if ($mod == "edit") {
			$username_cek = 'edit_unique[users_login.username.'.decrypt_url($this->input->post('login_id'),'login_id').']';
		}

		$this->form_validation->set_rules('username', 'nama pengguna', 'required|trim|min_length[6]|'.$username_cek.'');

		if ($this->input->post('password') || $this->input->post('password_confirmation') || $mod == "add") {
			$this->form_validation->set_rules('password_confirmation', 'kata sandi', 'trim|required|min_length[6]|matches[password]');
			$this->form_validation->set_rules('password', 'ulangi kata sandi', 'trim|required');
		}
		$this->form_validation->set_rules('ketegori', 'kategori', 'required')
							//   ->set_rules('simpeg_pegawai_id','Simpeg Pegawai','required')
							  ->set_rules('nama', 'nama lengkap', 'required');		
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ','</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
				if ($mod == "edit") {
					// tpp
					$tpp = 0;
					if ($this->input->post('tpp') && $this->input->post('ketegori')==1) {
						$tpp = 1;
					}

					$absen_online_app = 0;
					if ($this->input->post('absen_online_app')) {
						$absen_online_app = 1;
					}

					$data = array('nama' 		=> $this->input->post('nama'),
								  'tpp'			=> $tpp,
								  'absen_online_app' => $absen_online_app,
								  'updated_at' 	=> date('Y-m-d H:i:s'),
								  'updated_by'  => $this->session->userdata('tpp_user_id'),
								  'simpeg_pegawai_id' => decrypt_url($this->input->post('simpeg_pegawai_id'),'simpeg_pegawai_id'), );

					$this->db->update('mf_users', $data, ['id' => decrypt_url($this->input->post('user_id'),'user_id')]);

					$status = 0;
					if ($this->input->post('status_akun')) {
						$status = 1;
					}
					$data = array(
								  'username' 	=> $this->input->post('username'),
								  'updated_at' 	=> date('Y-m-d H:i:s'),
								  'status'		=> $status,
								  'updated_by'  => $this->session->userdata('tpp_user_id'), );
					if ($this->input->post('password')) {
						$data['password'] = $this->m_user_login->ghash($this->input->post('password'));
						$data['recovery'] = $this->encryption->encrypt($this->input->post('password'));
					}
					$return = $this->db->update('users_login', $data, ['id' => decrypt_url($this->input->post('login_id'),'login_id')]);

					// biodata pegawai
					$biodata_cek = $this->db->get_where('sp_pegawai', ['user_id' => decrypt_url($this->input->post('user_id'),'user_id')])->row();

					$data_biodata = array('agama_id'   => $this->input->post('agama'),
										  'gelar_dpn'  => $this->input->post('gelar_dpn'),
										  'gelar_blk'  => $this->input->post('gelar_blk'),
										  'gender'     => $this->input->post('gender'),
										  'jabatan'    => $this->input->post('jabatan'),
									);
					if ($this->input->post('ketegori') == 1) {
						$data_biodata['golongan_id']= $this->input->post('golongan');
						$data_biodata['eselon_id']  = $this->input->post('eselon');
					}
					if ($biodata_cek) {
						$return = $this->db->update('sp_pegawai',$data_biodata, ['id' => $biodata_cek->id]);
					}else {
						$data_biodata['user_id']  = decrypt_url($this->input->post('user_id'),'user_id');
						$return = $this->db->insert('sp_pegawai',$data_biodata);
					}

					if ($this->input->post('reset_device')) {
						$this->db->update('device_users',['is_active' => '0'],['user_id' => decrypt_url($this->input->post('user_id'),'user_id')]);
					}


					if ($return) {
						$result = array('status'   => true,
			    	     		  		'message' => 'Data berhasil disimpan',);
					}else{
						$result = array('status'   => false,
			    	     		  		'message' => 'Data gagal disimpan',);
					}	
				}

		}else {
			$result = array('status'   => false,
				    	     'message' => validation_errors(),);
		}
		if ($result) {
			$this->output->set_output(json_encode($result));	
		}else {
			$this->output->set_output(json_encode(['status'=>FALSE, 'message'=> 'Gagal mengambil data.']));
		}
	}

	public function cetak($dept_id)
	{
		$this->output->unset_template();
		$this->load->library('Tpdf');
		$this->data['instansi'] = $this->m_instansi->GetInstansi(decrypt_url($dept_id, 'instansi'))->row();
		$this->data['user']		= $this->m_user->GetUserAllAktif(decrypt_url($dept_id, 'instansi'))->result();
		$this->load->view('data_pegawai/v_cetak', $this->data);
	}

	public function view($id)
	{
		$this->data['sub_title'] 	= "Data Pegawai";
		$this->breadcrumbs->push('Lihat Pegawai', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['user']  		= $this->m_user->GetDetailBiodata(decrypt_url($id,"user_id"))->row();
		$this->load->view('data_pegawai/v_view', $this->data);
	}

	public function export($dept_id)
	{
		$instansi = $this->m_instansi->GetInstansi(decrypt_url($dept_id, 'instansi'))->row();
		$user = $this->m_user->GetUserAllAktifExport(decrypt_url($dept_id, 'instansi'))->result();
	// Load plugin PHPExcel nya
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		
		// Panggil class PHPExcel nya
		$excel = new PHPExcel();
		// Settingan awal fil excel
		$excel->getProperties()->setCreator('My Notes Code')
					->setLastModifiedBy('My Notes Code')
					->setTitle($instansi->dept_name)
					->setSubject("Pegawai")
					->setTitle($instansi->dept_name)
					->setKeywords("Data Pegawai");
		// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
		$style_col = array(
		'font' => array('bold' => true), // Set font nya jadi bold
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
			'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
		),
		'borders' => array(
			'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
			'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
			'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
			'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
		)
		);
		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_row = array(
		'alignment' => array(
			'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
		),
		'borders' => array(
			'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
			'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
			'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
			'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
		)
		);
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA PEGAWAI"); // Set kolom A1 dengan tulisan "DATA SISWA"
		$excel->getActiveSheet()->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
		$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
		$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
		$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
		$excel->setActiveSheetIndex(0)->setCellValue('B3', "NAMA"); // Set kolom B3 dengan tulisan "NIS"
		$excel->setActiveSheetIndex(0)->setCellValue('C3', "GELAR DPN"); // Set kolom C3 dengan tulisan "NAMA"
		$excel->setActiveSheetIndex(0)->setCellValue('D3', "GELAR BLKNG"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
		$excel->setActiveSheetIndex(0)->setCellValue('E3', "JABATAN"); // Set kolom E3 dengan tulisan "ALAMAT"
		$excel->setActiveSheetIndex(0)->setCellValue('F3', "AGAMA"); // Set kolom E3 dengan tulisan "AGAMA"
		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
		// Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
		foreach($user as $data){ // Lakukan looping pada variabel siswa
		$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $data->id);
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data->nama);
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data->gelar_dpn);
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data->gelar_blk);
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $data->jabatan);
		$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $data->agama);
		
		// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
		$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
		
		$no++; // Tambah 1 setiap kali looping
		$numrow++; // Tambah 1 setiap kali looping
		}
		// Set width kolom
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15); // Set width kolom B
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); // Set width kolom C
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(30); // Set width kolom E
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(30); // Set width kolom E
		
		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle($instansi->dept_name);
		$excel->setActiveSheetIndex(0);
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Data Pegawai'.$instansi->dept_name.'.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

}

/* End of file Data_pegawai.php */
/* Location: ./application/modules/Kepegawaian/controllers/Data_pegawai.php */