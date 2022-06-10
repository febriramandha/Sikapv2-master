<?php

	// update hitung tpp by handika putra 
	// 2021

     function sisa_waktu_mnt_tpp($schedule_in_out,$check_in_out,$type)
    {
        // $awal  = strtotime('2017-08-10 10:05:25');
        // $akhir = strtotime('2017-08-11 11:07:33');
        // $diff  = $akhir - $awal;
        
        $schedule_in_out = strtotime($schedule_in_out);
        $check_in_out = strtotime($check_in_out);
        if($type === "TM")
        {
        $result = $check_in_out-$schedule_in_out;

        }else if($type === "PC")
        {
        $result = $schedule_in_out-$check_in_out;
        }

        $menit = $result * (60);

        return  floor( $result / 60);
    }

	function selisih_jam_terlambar($json_data)
    {
    	$pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_absen'];

    	 $count = count($json_absen);

    	 $hari_terlambat = array();

         for ($i=0; $i < $count; $i++) { 
						//jam masuk
					$jam_masuk          = $json_absen[$i]['f7'];
					$jam_masuk_shift    = $json_absen[$i]['f12'];
					$status_in          = $json_absen[$i]['f17'];
					$start_time_notfixed= $json_absen[$i]['f20'];
					$jam_masuk_notfixed = $json_absen[$i]['f22'];

					//jam pulang
					$jam_pulang         = $json_absen[$i]['f8'];
					$jam_pulang_shift   = $json_absen[$i]['f13'];
					$status_out         = $json_absen[$i]['f18'];
					$end_time_notfixed  = $json_absen[$i]['f21'];
					$jam_pulang_notfixed= $json_absen[$i]['f23'];

					// keterangan 
					$daysoff_id       = $json_absen[$i]['f19'];
					$lkhdl_id         = $json_absen[$i]['f15'];
					$dinasmanual_id   = $json_absen[$i]['f16'];
					$kode_cuti        = $json_absen[$i]['f14'];
					$rentan_tanggal   = $json_absen[$i]['f1'];

					$start_time   = $json_absen[$i]['f5'];
					$end_time     = $json_absen[$i]['f6'];

					$start_time_shift  = $json_absen[$i]['f10'];
					$end_time_shift    = $json_absen[$i]['f11'];
					$cek = absen_ket_tabel($daysoff_id, $jam_masuk, $jam_pulang,$jam_masuk_shift, $jam_pulang_shift, $lkhdl_id, $dinasmanual_id, $kode_cuti, $rentan_tanggal, $start_time, $start_time_shift, $status_in, $status_out,$end_time,$end_time_shift, $start_time_notfixed, $jam_masuk_notfixed, $end_time_notfixed, $jam_pulang_notfixed);

					if ($cek == "TM" || $cek == "TMM" || $cek == "TC" || $cek == "TCM") {
						if(empty($jam_masuk)){
							$jam_msk = date("H:i:s",strtotime("11:59:59"));
						}else {
							$jam_msk = $jam_masuk;
						}
						$selisih = sisa_waktu_mnt_tpp($start_time,$jam_msk,$type="TM");
						if($selisih > 0 && $selisih <= 30)
						{
							$potongan = 0.5;
						}else if($selisih > 30 && $selisih <= 60)
						{
							$potongan = 1;
						}else if($selisih > 60 && $selisih <= 90)
						{
							$potongan = 1.25;
						}else if($selisih > 90)
						{
							$potongan = 1.5;
						}else {	
							$potongan = 0;
						}

						$hari_terlambat[] = $potongan;
					}	
			}
			$jumlah_persen_terlambat = array_sum($hari_terlambat);
			return $jumlah_persen_terlambat;
    }

	function jum_tidak_apel($json_data)
	{
    	$pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_absen'];

    	 $count = count($json_absen);

    	 $hari_kerja = array(0);
         for ($i=0; $i < $count; $i++) { 
	
				 $jumtidak_apel = $json_absen[$i]['f27'];
                 if ($jumtidak_apel) {
                 		$hari_kerja[] = $jumtidak_apel;
                 }
         		
         }

         $jumlah_hari_kerja = array_sum($hari_kerja);

         return  $jumlah_hari_kerja;
    }
	
	function persen_tidak_apel($json_data)
	{
		$hasil = jum_tidak_apel($json_data);
		return $hasil*2;
	}

	function selisih_jam_cepat_pulang($json_data)
    {
    	$pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_absen'];

    	 $count = count($json_absen);

    	 $hari_cepat_pulang = array();

         for ($i=0; $i < $count; $i++) { 
						//jam masuk
					$jam_masuk          = $json_absen[$i]['f7'];
					$jam_masuk_shift    = $json_absen[$i]['f12'];
					$status_in          = $json_absen[$i]['f17'];
					$start_time_notfixed= $json_absen[$i]['f20'];
					$jam_masuk_notfixed = $json_absen[$i]['f22'];

					//jam pulang
					$jam_pulang         = $json_absen[$i]['f8'];
					$jam_pulang_shift   = $json_absen[$i]['f13'];
					$status_out         = $json_absen[$i]['f18'];
					$end_time_notfixed  = $json_absen[$i]['f21'];
					$jam_pulang_notfixed= $json_absen[$i]['f23'];

					// keterangan 
					$daysoff_id       = $json_absen[$i]['f19'];
					$lkhdl_id         = $json_absen[$i]['f15'];
					$dinasmanual_id   = $json_absen[$i]['f16'];
					$kode_cuti        = $json_absen[$i]['f14'];
					$rentan_tanggal   = $json_absen[$i]['f1'];

					$start_time   = $json_absen[$i]['f5'];
					$end_time     = $json_absen[$i]['f6'];

					$start_time_shift  = $json_absen[$i]['f10'];
					$end_time_shift    = $json_absen[$i]['f11'];
					$cek = absen_ket_tabel($daysoff_id, $jam_masuk, $jam_pulang,$jam_masuk_shift, $jam_pulang_shift, $lkhdl_id, $dinasmanual_id, $kode_cuti, $rentan_tanggal, $start_time, $start_time_shift, $status_in, $status_out,$end_time,$end_time_shift, $start_time_notfixed, $jam_masuk_notfixed, $end_time_notfixed, $jam_pulang_notfixed);

					if ($cek == "PC" || $cek == "PCM" || $cek == "TC" || $cek == "TCM") {
						//cek pulang cepat hari ini
					$hari_ini = date('Y-m-d');
					$jam_ini = date('H:i:s');
						if ($rentan_tanggal == $hari_ini && $jam_ini < $end_time) {
							# code...
						}else {
								if(empty($jam_pulang)){
									$jam_plg = date("H:i:s",strtotime("12:01:00"));
								}else {
									$jam_plg = $jam_pulang;
								}

								$selisih = sisa_waktu_mnt_tpp($end_time,$jam_plg,$type="PC");
								if($selisih > 0 && $selisih <= 30)
								{
									$potongan = 0.5;
								}else if($selisih > 30 && $selisih <= 60)
								{
									$potongan = 1;
								}else if($selisih > 60 && $selisih <= 90)
								{
									$potongan = 1.25;
								}else if($selisih > 90)
								{
									$potongan = 1.55;
								}else {	
									$potongan = 0;
								}
								$hari_cepat_pulang[] = $potongan;
						}
					}
				}
			$jumlah_persen_cepat_pulang = array_sum($hari_cepat_pulang);
			return $jumlah_persen_cepat_pulang;
    }

	function persen_tk($json_data)
	{
		$hasil = jum_tk_rekap($json_data);
		return $hasil*3;
	}

	function persen_tidak_upacara($json_data)
	{
		$hasil = jum_tidak_upacara_rekap($json_data);
		return $hasil*2;
	}

	function total_persen_aspek_disiplin($json_data)
	{
		return selisih_jam_terlambar($json_data)+persen_tidak_apel($json_data)+selisih_jam_cepat_pulang($json_data)+persen_tk($json_data)+persen_tidak_upacara($json_data);
	}

	function jum_tidak_buat_lkh($json_data,$jumlah_laporan='')
	{
		$jum_hari_kerja_lkh = jum_hari_kerja_rekap_lkh($json_data);
		$data_harian_lkh = jum_data_kerja_rekap_lkh($json_data,$jumlah_laporan);

		return $jum_hari_kerja_lkh - $data_harian_lkh;
	}

	function persen_tidak_buat_lkh($json_data, $jumlah_laporan ='')
	{
		
		$jum_hari_kerja_lkh = jum_hari_kerja_rekap_lkh($json_data);
		$data_harian_lkh = jum_data_kerja_rekap_lkh($json_data,$jumlah_laporan);

		if($data_harian_lkh > $jum_hari_kerja_lkh)
		{
			$total = '<span>Total LKH harian melebihi total hari kerja</span>';	
		}else {
			if($jum_hari_kerja_lkh != 0)
			{
				$parse = (($jum_hari_kerja_lkh-$data_harian_lkh)/$jum_hari_kerja_lkh) * 100;
			}else{
				$parse = 0;
			} 
			$total = number_format((float)$parse, 2, '.', ''); 
		}
		return $total;
	}

	

?>