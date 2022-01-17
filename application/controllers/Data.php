<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {

	public function index()
	{
		// foreach ($this->impo() as $k => $v) {
		// 	$nama = $v[1];
		// 	$jabatan = $v[3];
		// 	$laki = $v[6];
		// 	$per = $v[7];
			
		// 	$this->insert_data($nama,108,1,$jabatan,$laki,$per);
		// }


		
	}

	public function tambah_user()
	{
		$this->load->model('m_server_att');
		// insert to server 2
		// $data_att = array('userid' 	 		=> '10698',
		// 				  'badgenumber' 	=> '1168',			
		// 				  'ssn' 	 		=> '198603092010011005',
		// 		  		  'name' 	 		=> 'HAMDANIF AIQ',
		// 		  		  'defaultdeptid' 	=> '1804');
		// $this->m_server_att->NewUserinfo($data_att);
	}

	public function insert_data($nama='',$instansi=108, $agama=1,$jabatan,$gender1='', $gender2='')
	{
		$this->load->model(['m_user_login','m_server_att']);
		$kategori = 2;
		$status_att = 1;
		$level = 3;

		if ($gender1) {
			$gender =1;
		}else {
			$gender =2;
		}

		// key
		$key = $this->db->select('max(key)')->get('mf_users')->row()->max+1;

		// nip user
		if ($kategori == 1) {
			$nip =  $this->input->post('nip'); 
		}else {
			$nip =  $key; 
		}
		// att status
		$defaultdeptid = '-1';
		$att_status = 0;
		if ($status_att) {
			$att_status = 1;
			// aktif userinfo
			$defaultdeptid = $instansi;
		}
		// tpp
		$tpp = 0;
		if ($this->input->post('tpp') && $this->input->post('ketegori')==1) {
			$tpp = 1;
		}

		$data = array('key' 		=> $key,
					  'nip' 		=> $nip,
					  'nama' 		=> $nama,
					  'dept_id' 	=> $instansi,
					  'pns' 		=> $kategori,
					  'att_status' 	=> $att_status,
					  'tpp'			=> $tpp,
					  'created_at' 	=> date('Y-m-d H:i:s'),);

		
		$this->db->insert('mf_users', $data);
		$user_id = $this->db->insert_id();

		// insert to server 2
		$data_att = array('userid' 	 		=> $user_id,
						  'badgenumber' 	=> $key,			
						  'ssn' 	 		=> $nip,
				  		  'name' 	 		=> $nama,
				  		  'defaultdeptid' 	=> $defaultdeptid);
		$this->m_server_att->NewUserinfo($data_att);
		// end
		$status = 0;
		if ($this->input->post('status_akun')) {
			$status = 1;
		}
		$data = array('user_id' 	=> $user_id,
					  'username' 	=> $key,
					  'password' 	=> $this->m_user_login->ghash($key),
					  'recovery' 	=> $this->encryption->encrypt($key),  
					  'level' 		=> $level,
					  'status' 		=> $status,
					  'created_at' 	=> date('Y-m-d H:i:s'), );
		$return = $this->db->insert('users_login', $data);

		// biodata pegawai
		$data_biodata = array('agama_id'   => 1,
							  'jabatan'    => $jabatan,
							  'user_id'    => $user_id,
							  'gender'     => $gender,
						);
		if ($this->input->post('ketegori') == 1) {
			$data_biodata['golongan_id']= $this->input->post('golongan');
			$data_biodata['eselon_id']  = $this->input->post('eselon');
		}
		$return = $this->db->insert('sp_pegawai',$data_biodata);
		
		if ($return) {
			$result = array('status'   => true,
    	     		  		'message' => 'Data berhasil ditambahkan',);
		}else{
			$result = array('status'   => false,
    	     		  		'message' => 'Data gagal ditambahkan',);
		}	

		// echo $result;
	}

	public function impo($value='')
	{
		return $user = array(
				0 => array(
					'1',
					'dr. Fradila Nora Syair',
					'Manggopoh, 23/01/1987',
					'Dokter Gigi',
					'8/5/2017',
					'S.1 Dokter Gigi',
					'',
					'V'
				),
				1 => array(
					'2',
					'Abdul Syahriandi, S. Farm. Apt',
					'Lubuk Basung, 14/12/1991',
					'Apoteker',
					'4/1/2013',
					'S.1 Apoteker',
					'V',
					''
				),
				2 => array(
					'3',
					'Deby Patricia, S. Farm,Apt',
					'Padang, 13/03/1993',
					'Apoteker',
					'9/5/2017',
					'S.1 Apoteker',
					'',
					'V'
				),
				3 => array(
					'4',
					'Wangi Qurratu Ayuni M, S.Farm. Apt',
					'Bikittinggi, 09/09/1982',
					'Apoteker',
					'11/22/2017',
					'S.1 Apoteker',
					'',
					'V'
				),
				4 => array(
					'5',
					'Ardi Akbar, Amd.Far',
					'Lubuk Basung, 14/04/1986',
					'Asisten Apoteker',
					'9/5/2017',
					'D.III Apoteker',
					'',
					'V'
				),
				5 => array(
					'7',
					'Budi Oktaviyarno',
					'Manggopoh, 22/01/1992',
					'Satpam',
					'5/18/2017',
					'SMA',
					'V',
					''
				),
				6 => array(
					'8',
					'Jafri, Amd RAD',
					'Lubuk Basung, 19/01/1986',
					'Radiografer',
					'1/5/2017',
					'D.III RADIOLOGI',
					'V',
					''
				),
				7 => array(
					'9',
					'Siska Alya Eka Putri, Amd PK',
					'Titisan Tunggang,12/11/1994',
					'Entri JKN',
					'1/5/2017',
					'D.III REKAM MEDIS',
					'',
					'V'
				),
				8 => array(
					'10',
					'Riki Firnando',
					'Lubuk Basung, 2/11/1981',
					'IPAL',
					'8/6/2017',
					'SMK',
					'V',
					''
				),
				9 => array(
					'12',
					'Yulina Sapitri',
					'Damar Gadang, 10/5/1992',
					'Admision',
					'3/7/2017',
					'SMA',
					'',
					'V'
				),
				10 => array(
					'13',
					'Febrian Tumenggung',
					'Manggopoh, 02-09-1990',
					'Tenaga IT',
					'2/4/2018',
					'S1 Informatika',
					'v',
					''
				),
				11 => array(
					'15',
					'Tezza Yuniliyasmi',
					'Lubuk Basung,11-6-1995',
					'Perawat',
					'8/1/2018',
					'D3 Keperawatan',
					'',
					'v'
				),
				12 => array(
					'16',
					'Faris Azhari',
					'Bukittinggi,25/5/1995',
					'Perawat',
					'8/1/2018',
					'D3 Keperawatan',
					'v',
					''
				),
				13 => array(
					'17',
					'Bhakti Aulia',
					'Lubuk Basung, 22/1/1992',
					'Perawat',
					'8/1/2018',
					'Ners',
					'v',
					''
				),
				14 => array(
					'18',
					'Lisa Achmanda Sari',
					'Lubuk Basung,0-10-1994',
					'Perawat',
					'8/1/2018',
					'D3 Keperawatan',
					'',
					'v'
				),
				15 => array(
					'19',
					'Darma Eka Putra',
					'Payakumbuh, 22/12/1988',
					'Perawat',
					'8/1/2018',
					'Ners',
					'v',
					''
				),
				16 => array(
					'20',
					'Rizki Gunawan',
					'Pariaman, 01-08-1995',
					'Asisten Apoteker',
					'8/1/2018',
					'D3 Farmasi',
					'v',
					''
				),
				17 => array(
					'21',
					'Eza Tri Utami',
					'Bukittinggi, 19-11-1996',
					'Asisten Apoteker',
					'8/1/2018',
					'D3 Farmasi',
					'',
					'v'
				),
				18 => array(
					'22',
					'Rabiatul Khairani',
					'Bukittinggi,04-09-1996',
					'Asisten Apoteker',
					'8/1/2018',
					'D3 Farmasi',
					'',
					'v'
				),
				19 => array(
					'23',
					'Zulfatmi',
					'Kampuang Balai, 05-08-1992',
					'Fisioterapi',
					'8/1/2018',
					'D3 Fisioterapi',
					'v',
					''
				),
				20 => array(
					'24',
					'Firmansyah Denata',
					'Padang, 16-03-1994',
					'Rekam Medis',
					'8/1/2018',
					'D3 Rekam Medis',
					'v',
					''
				),
				21 => array(
					'26',
					'Syetra Melya Arendda. Amd. Kep',
					'Bukittinggi,6-12-1992',
					'Perawat',
					'10/1/2018',
					'D3 Keperawatan',
					'',
					'v'
				),
				22 => array(
					'27',
					'Ns. Ayu Permata Sari, S.Kep',
					'Balai Selasa, 17-07-1995',
					'Perawat',
					'10/1/2018',
					'Ners',
					'',
					'v'
				),
				23 => array(
					'30',
					'Ns. Istiqamah, S.Kep',
					'Kubu Anau, 8-10-1989',
					'Perawat',
					'10/1/2018',
					'Ners',
					'',
					'v'
				),
				24 => array(
					'31',
					'Dira Betia Vella, Amd. Kep',
					'Padang,24-05-1996',
					'Perawat',
					'10/1/2018',
					'D3 Keperawatan',
					'',
					'v'
				),
				25 => array(
					'32',
					'Haris Wahyudi, Amd. Kep',
					'Padang,01-10-1993',
					'Perawat',
					'10/1/2018',
					'D3 Keperawatan',
					'v',
					''
				),
				26 => array(
					'33',
					'Suryani, Amd. AK',
					'Padang, 29/9/1990',
					'Analis Kesehatan',
					'10/1/2018',
					'D3 Analis',
					'',
					'v'
				),
				27 => array(
					'34',
					'Wella Wulandari, Amd.Kes',
					'Aceh, 26-11-1993',
					'Administrasi',
					'10/1/2018',
					'D3 Adm RS',
					'',
					'v'
				),
				28 => array(
					'35',
					'Melida Dela Fega',
					'Tanggerang, 24-05-1996',
					'Administrasi',
					'10/1/2018',
					'D3 Adm',
					'',
					'v'
				),
				29 => array(
					'36',
					'Hendri N',
					'Lubuk Basung, 27-03-1994',
					'Administrasi',
					'10/1/2018',
					'D3 Adm',
					'v',
					''
				),
				30 => array(
					'37',
					'Ahmad Tri Putra',
					'Lubuk Basung,12-07-1995',
					'Satpam',
					'10/1/2018',
					'D1 Komputer',
					'v',
					''
				),
				31 => array(
					'38',
					'Yozzi Wira Perkasa',
					'Bawan, 17-07-1999',
					'Satpam',
					'10/1/2018',
					'SMK',
					'v',
					''
				),
				32 => array(
					'39',
					'Khairul Saleh',
					'Lubuk Basung, 07-05-1989',
					'Petugas Kebersihan',
					'10/1/2018',
					'SMA',
					'v',
					''
				),
				33 => array(
					'40',
					'Mira Fidilawati',
					'Kampung Pinang, 03-07-1994',
					'Petugas Kebersihan',
					'10/1/2018',
					'SMA',
					'',
					'v'
				),
				34 => array(
					'41',
					'Firman Illahi',
					'Lubuk Basung,17/9/1997',
					'Petugas Kebersihan',
					'10/1/2018',
					'SMA',
					'v',
					''
				),
				35 => array(
					'43',
					'Shintia Farma, Amd Rad',
					'Maninjau, 15/5/1997',
					'Radiografer',
					'8/1/2019',
					'D3 Radiologi',
					'',
					'v'
				),
				36 => array(
					'44',
					'Puja Tri Wahyuni, Amd Rad',
					'Pariaman, 14/4/1997',
					'Radiografer',
					'8/1/2019',
					'D3 Radiologi',
					'',
					'v'
				),
				37 => array(
					'47',
					'Ekko Riswandi, Amd.Kep',
					'Bayur, 24/2/1990',
					'Perawat',
					'01/05/19',
					'D3 Keperawatan',
					'v',
					''
				),
				38 => array(
					'48',
					'Delvira Ulandari',
					'Lubuk Basung, 05/12/1999',
					'Kurir',
					'01/05/19',
					'SMA',
					'',
					'v'
				),
				39 => array(
					'49',
					'Firdaus',
					'Bawan, 25/8/1992',
					'Brankarmen',
					'01/05/19',
					'SMA',
					'',
					'v'
				),
				40 => array(
					'51',
					'Winda Gusmita Sari',
					'Lubuk Basung, 29/01/1994',
					'Admision',
					'01/05/19',
					'SMA',
					'',
					'v'
				),
				41 => array(
					'52',
					'Heru Paringga, Amd. RM',
					'Bancah Taleh, 29-12-1992',
					'Rekam Medis',
					'01/10/19',
					'D3 Rekam Medis',
					'v',
					''
				),
				42 => array(
					'54',
					'Sri Wahyuni',
					'Lubuk Basung, 23/01/1994',
					'Petugas Kebersihan',
					'01/10/19',
					'SMA',
					'',
					'v'
				),
				43 => array(
					'56',
					'Jeni Saputra Nanda',
					'Padang, 26-04-1987',
					'Sopir Direktur',
					'01/11/19',
					'S1 Ekonomi',
					'v',
					''
				),
				44 => array(
					'57',
					'dr. Rachmat Try Hendro, Sp. An',
					'Padang/ 14/11/1985',
					'Dokter Spesialis',
					'01/11/19',
					'Sp. Anestesi',
					'v',
					''
				),
				45 => array(
					'58',
					'Tika Erawati',
					'Lubuk Basung, 04-1-1997',
					'Petugas Kebersihan',
					'3/1/2020',
					'SMA',
					'v',
					''
				),
				46 => array(
					'59',
					'Anisa',
					'Jakarta, 4-12-1992',
					'Petugas Kebersihan',
					'3/1/2020',
					'SMA',
					'v',
					''
				),
				47 => array(
					'60',
					'Afrita Andhini',
					'Padang, 22-04-1998',
					'Petugas Kebersihan',
					'3/1/2020',
					'SMA',
					'v',
					''
				),
				48 => array(
					'62',
					'Refial Muslim',
					'Lubuk Basung, 12-12-1993',
					'Brankarmen',
					'3/1/2020',
					'SMA',
					'',
					'v'
				),
				49 => array(
					'64',
					'Irsyad Pebrianda',
					'Bukittinggi, 23-02-1990',
					'Asisten Apoteker',
					'3/1/2020',
					'D3 Farmasi',
					'',
					'v'
				),
				50 => array(
					'65',
					'Windi Puspita Sari',
					'Lubuk Basung, 04-10-1987',
					'Pramusaji',
					'3/1/2020',
					'SMK TATA BOGA',
					'v',
					''
				),
				51 => array(
					'66',
					'Chicha Akmal, S. Tr. Keb',
					'Kp. Pinang, 6 Juli 1994',
					'Bidan',
					'5/1/2020',
					'D4 Kebidanan',
					'',
					'v'
				),
				52 => array(
					'67',
					'Yulia Mellia, Amd. Keb',
					'Sungai Jariang, 13 Juli 1990',
					'Bidan',
					'5/1/2020',
					'D3 Kebidanan',
					'',
					'v'
				),
				53 => array(
					'68',
					'Lili Mulyani, Amd. Keb',
					'Pangkalan, 14 Januari 1994',
					'Bidan',
					'5/1/2020',
					'D3 Kebidanan',
					'',
					'v'
				),
				54 => array(
					'69',
					'Ns. Ira Wahyuni, S. Kep',
					'Lubuk Basung, 25 Desember 1989',
					'Perawat',
					'5/1/2020',
					'Ners',
					'',
					'v'
				),
				55 => array(
					'70',
					'Ns. Widia Wati, S. Kep',
					'Maninjau, 12 Mei 1986',
					'Perawat',
					'5/1/2020',
					'Ners',
					'',
					'v'
				),
				56 => array(
					'71',
					'Ns. Weli Darma Syafitri, S. Kep',
					'Manggopoh, 31 Juli 1993',
					'Perawat',
					'5/1/2020',
					'Ners',
					'',
					'v'
				),
				57 => array(
					'72',
					'Sri Rahmatul Azizah, Amd. Kep',
					'Cicawan, 20 januari 1998',
					'Perawat',
					'5/1/2020',
					'D3 Keperawatan',
					'',
					'v'
				),
				58 => array(
					'73',
					'M.Firman Syah',
					'Pariaman, 29 Sept 1996',
					'Satpam',
					'3/1/2021',
					'SMA',
					'v',
					''
				),
			);
	}

}

/* End of file Data.php */
/* Location: ./application/controllers/Data.php */