<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

	function cek_radio_disable($id='', $eselon='', $eselon_id='',$type='',$hadir='',$absenupacara_id='')
	{
		$a = '';
		$user_id = encrypt_url($id,'user_id_upacara');
		$tag = "<input type='radio' disabled>";
		$tag_hadir 	= '';
		$tag_user 	= '';
		$tag_absenupacara_id ='';
		
		$checked = '';
		$type_id = '';
		$name_checked = '';
		$checked = '';
		$hadir_id =0;
		
		if ($eselon !=0) {
			$data_eselon_ex = explode("+",$eselon);
			foreach ($data_eselon_ex as $value) {
				  if ($value == $eselon_id) {
				  			$a = 1;
				  }
				  if ($value == 11 && $eselon_id == '') {
				  			$a = 1;
				  }
			}
		}

		if ($type == "yahadir") {
			if ($hadir == 1) {
				$checked = 'checked';
			}

			if ($hadir) {
				$hadir_id = 1;
				$absenupacara_id = encrypt_url($absenupacara_id,'absenupacara_id');
				$tag_absenupacara_id = "<input type='hidden' name='absenupacara_id[$id]' value='$absenupacara_id'>";
			}else {
				$checked = 'checked';
			}

			$tag_hadir = "<input type='hidden' name='hadir[$id]' value='$hadir_id'> $tag_absenupacara_id";
			$type_id = encrypt_url(1,'type_upacara');
			$name_checked = "name='absen[$id]' value='$type_id' $checked";
			$tag_user = "<input type='hidden' name='user[]' value='$user_id'>";
		}else if ($type == "thadir") {
			if ($hadir == 2) {
				$checked = 'checked';
			}
			$type_id = encrypt_url(2,'type_upacara');
			$name_checked = "name='absen[$id]' value='$type_id' $checked";
		}else if ($type == "cuti") {
			if ($hadir == 3) {
				$checked = 'checked';
			}
			$type_id = encrypt_url(3,'type_upacara');
			$name_checked = "name='absen[$id]' value='$type_id' $checked";
		}

		if ($a) {
			$tag = "$tag_user <input type='radio' $name_checked > $tag_hadir";
		}
		
		return $tag;
	}

	function Cek_upacara_hadir($id='',$hadir='')
	{
		$a ='';
		if ($id == $hadir) {
			$a = '<span><i class="icon-checkmark-circle2"></i></span>';	
		}

		return $a;
	}

	function upacara_ket($ket='')
	{
		$a = '';
		if ($ket == 1) {
			$a = "H";
		}elseif ($ket == 2) {
			$a = "A";;
		}elseif ($ket == 3) {
			$a = "C";
		}

		return $a;
	}

	function action_upacara_id($id='', $eselon='', $eselon_id='',$hadir='')
	{
		$a = '';
		$tag_del = '';
		$id = encrypt_url($id,'absenupacara_id');
		
		if ($eselon !=0) {
			$data_eselon_ex = explode("+",$eselon);
			foreach ($data_eselon_ex as $value) {
				  if ($value == $eselon_id) {
				  			$a = 1;
				  }
				  if ($value == 11 && $eselon_id == '') {
				  			$a = 1;
				  }
			}
		}

		if ($a && $hadir) {
			$tag_del = '<span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus data" style="cursor:pointer;" id="'.$id.'">
					              <i class="icon-bin"></i>
					              </span>';
		}

		
		
		return $tag_del;
	}

	function start_time_tabel($start_time='',$start_time_shift='', $start_time_notfixed='')
	{
		 if ($start_time && !$start_time_shift && $start_time != "00:00:00") {
		 			$resul = jm($start_time);
		 }elseif (!$start_time && $start_time_shift) {
		 			$resul = jm($start_time_shift);
		 }elseif ($start_time && $start_time_shift) {
		 			$resul = jm($start_time);
		 }elseif ($start_time == "00:00:00" || $start_time_shift == "00:00:00") {
		 			$resul = 'Libur';
		 }else{
		 		$resul = '<span tooltip="Tidak ada jadwal" flow="left"><i class="icon-help msclick"></i></span>';
		 }

		 if ($start_time_notfixed) {
		 		$resul = jm($start_time).'F';
		 }elseif ($start_time_notfixed == "00:00:00") {
		 		$resul = 'Libur';
		 }

		 return $resul;
	}

	function jam_masuk_tabel($jam_masuk='', $jam_masuk_shift='', $status_in='', $start_time_notfixed='', $jam_masuk_notfixed='')
	{
		$resul ='';
		if ($jam_masuk && !$jam_masuk_shift) {
				$resul = jm($jam_masuk);
		}elseif (!$jam_masuk && $jam_masuk_shift) {
				$resul = jm($jam_masuk_shift);
		}else {
				$resul = jm($jam_masuk);
		}

		if ($start_time_notfixed) {
				$resul = jm($jam_masuk_notfixed);
		}

		if ($status_in) {
				if ($status_in == 1) {
						$resul = 'HM';
				}elseif ($status_in == 2) {
						$resul = 'TMM';
				}elseif ($status_in == 3) {
						$resul = 'TKM';
				}else {
					$resul = 'HM';
				}
		}

		return $resul;
	}

	function jam_pulang_tabel($jam_masuk='', $jam_masuk_shift='', $status_out='',$end_time_notfixed='', $jam_pulang_notfixed='')
	{
		if ($jam_masuk && !$jam_masuk_shift) {
				$resul = jm($jam_masuk);
		}elseif (!$jam_masuk && $jam_masuk_shift) {
				$resul = jm($jam_masuk_shift);
		}else {
				$resul = jm($jam_masuk);
		}

		if ($end_time_notfixed) {
				$resul = jm($jam_pulang_notfixed);
		}

		if ($status_out) {
				if ($status_out == 1) {
						$resul = 'HM';
				}elseif ($status_out == 2) {
						$resul = 'PCM';
				}elseif ($status_out == 3) {
						$resul = 'TKM';
				}else {
					$resul = 'HM';
				}
		}

		return $resul;
	}

	function terlambat_tabel($start_time='',$start_time_shift='', $jam_masuk='', $jam_masuk_shift='', $status_in='',$start_time_notfixed='', $jam_masuk_notfixed='', $jam_pulang='', $jam_pulang_shift='', $jam_pulang_notfixed='')
	{
		 $terlambat = '';
		 if ($start_time && $jam_masuk) {
		 		if(jm($jam_masuk) > jm($start_time)) {
                    $j_masuk = strtotime($jam_masuk);
                    $r_masuk = strtotime($start_time);
                    $terlambat = sisa_waktu($j_masuk-$r_masuk);
                }
		 }elseif ($jam_masuk =='' && $jam_pulang) {
                	$terlambat = 'TM';
         }

		 if ($start_time_shift && $jam_masuk_shift) {
		 		if(jm($jam_masuk_shift) > jm($start_time_shift)) {
                    $j_masuk = strtotime($jam_masuk_shift);
                    $r_masuk = strtotime($start_time_shift);
                    $terlambat = sisa_waktu($j_masuk-$r_masuk);
                }
		 }elseif ($jam_masuk_shift =='' && $jam_pulang_shift) {
                	$terlambat = 'TM';
         }

		 if ($start_time_notfixed && $jam_masuk_notfixed) {
		 		if(jm($jam_masuk_notfixed) > jm($start_time_notfixed)) {
                    $j_masuk = strtotime($jam_masuk_notfixed);
                    $r_masuk = strtotime($start_time_notfixed);
                    $terlambat = sisa_waktu($j_masuk-$r_masuk);
                }else $terlambat = '';
		 }elseif ($jam_masuk_notfixed =='' && $jam_pulang_notfixed) {
                	$terlambat = 'TM';
         }

		 if ($status_in == 2) {
		 		$terlambat = 'TMM';
		 }

		 return $terlambat;
	}

	 function pulang_cepat_tabel($end_time='',$end_time_shift='', $jam_pulang='', $jam_pulang_shift='', $status_out='',$end_time_notfixed='', $jam_pulang_notfixed='',$jam_masuk='', $jam_masuk_shift='', $jam_masuk_notfixed='')
	{
		$cepat = '';
		if ($end_time && $jam_pulang) {
			if(jm($jam_pulang) < jm($end_time)) {
	              $j_pulang = strtotime($jam_pulang);
	              $r_pulang = strtotime($end_time);
	              $cepat = sisa_waktu($r_pulang-$j_pulang);
	          }
	     }elseif ($jam_pulang =='' && $jam_masuk) {
                	$cepat = 'PC';
         }

	     if ($end_time_shift && $jam_pulang_shift) {
			if(jm($jam_pulang_shift) < jm($end_time_shift)) {
	              $j_pulang = strtotime($jam_pulang_shift);
	              $r_pulang = strtotime($end_time_shift);
	              $cepat = sisa_waktu($r_pulang-$j_pulang);
	          }
	     }elseif ($jam_pulang_shift =='' && $jam_masuk_shift) {
                	$cepat = 'PC';
         }

	     if ($end_time_notfixed && $jam_pulang_notfixed) {
		 		if(jm($jam_pulang_notfixed) < jm($end_time_notfixed)) {
	                  $j_pulang = strtotime($jam_pulang_notfixed);
		              $r_pulang = strtotime($end_time_notfixed);
		              $cepat 	= sisa_waktu($r_pulang-$j_pulang);
                }else $cepat = '';
		 }elseif ($jam_pulang_notfixed =='' && $jam_masuk_notfixed) {
                	$cepat = 'PC';
         }

	     if ($status_out == 2) {
		 		$terlambat = 'PCM';
		 }

	      return $cepat;
	}

	function dinas_luar_tabel($lkhdl_id='', $dinasmanual_id='')
	{
		$dl = '';
		if ($lkhdl_id) {
				$dl = 'DL';
		}elseif ($dinasmanual_id) {
				$dl = 'DLM';
		}

		return $dl;
	}

	function absen_ket_tabel($daysoff_id='', $jam_masuk='', $jam_pulang='',$jam_masuk_shift='', $jam_pulang_shift='', $lkhdl_id='', $dinasmanual_id='', $kode_cuti='', $rentan_tanggal='', $start_time='', $start_time_shift='', $status_in='', $status_out='',$end_time ='',$end_time_shift='', $start_time_notfixed='', $jam_masuk_notfixed='', $end_time_notfixed='', $jam_pulang_notfixed='')
	{
		$ket ='';
		$hari_ini = date('Y-m-d');

		

		if ($rentan_tanggal <= $hari_ini && !$daysoff_id && !$jam_masuk && !$jam_pulang && !$jam_masuk_shift && !$jam_pulang_shift && !$lkhdl_id && !$dinasmanual_id && !$kode_cuti ) {
			$ket = 'TK'; 
		}

		if ($jam_masuk || $jam_pulang || $jam_masuk_shift || $jam_pulang_shift || $jam_masuk_notfixed || $jam_pulang_notfixed || $status_in || $status_out) {

			$ket = 'H'; 
			$terlambat =  terlambat_tabel($start_time,$start_time_shift, $jam_masuk, $jam_masuk_shift, $status_in,$start_time_notfixed, $jam_masuk_notfixed, $jam_pulang, $jam_pulang_shift, $jam_pulang_notfixed);

			if ($terlambat) {
				$ket = 'TM';
			}

			$pulang_cepat = pulang_cepat_tabel($end_time,$end_time_shift, $jam_pulang, $jam_pulang_shift, $status_out,$end_time_notfixed, $jam_pulang_notfixed,$jam_masuk, $jam_masuk_shift, $jam_masuk_notfixed);

			if ($pulang_cepat) {
				$ket = 'PC';
			}

			if ($terlambat && $pulang_cepat) {
				$ket = 'TC';
			}

			if ($status_in) {
				if ($status_in == 1) {
							$ket = 'HM';
					}elseif ($status_in == 2) {
							$ket = 'TMM';
					}elseif ($status_in == 3) {
							$ket = 'TKM';
					}else {
						$ket = 'HM';
					}
			}

			if ($status_out) {
					if ($status_out == 1) {
							$ket = 'HM';
					}elseif ($status_out == 2) {
							$ket = 'PCM';
					}elseif ($status_out == 3) {
							$ket = 'TKM';
					}else {
						$ket = 'HM';
					}
			}

			if ($status_in == 2 && $status_out == 2) {
				  $ket = 'TCM';
			}elseif ($status_in == 1 && $status_out == 1) {
				  $ket = 'HM';
			}elseif ($status_in == 2 && $status_out == 1) {
				  $ket = 'TMM';
			}elseif ($status_in == 1 && $status_out == 2) {
				  $ket = 'PCM';
			}elseif ($status_in == 3 && $status_out == 3) {
				  $ket = 'TKM';
			}elseif ($status_in == 3 && $status_out == 1) {
				  $ket = 'TMM';
			}elseif ($status_in == 3 && $status_out == 2) {
				  $ket = 'TMM';
			}elseif ($status_in == 1 && $status_out == 3) {
				  $ket = 'PCM';
			}elseif ($status_in == 2 && $status_out == 3) {
				  $ket = 'PCM';
			}

			 
		}
		if ($lkhdl_id) {
			 $ket = 'DL'; 
		}

		if ($dinasmanual_id) {
			 $ket = 'DLM'; 
		}

		if ($kode_cuti) {
			 $ket = $kode_cuti; 
		}

		if ($start_time == "00:00:00"  || $start_time_shift == "00:00:00") {
			$ket = 'L';
		}

		if (!$start_time  && !$start_time_shift) {
			$ket = '?';
		}

		

		if ($daysoff_id) {
			 $ket = 'L'; 
		}

		return $ket;
	}

	function jumlah_lembur($jam_masuk='', $jam_pulang='', $start_time='', $end_time='', $daysoff_id='',$start_time_shift='', $end_time_shift='')
	{
		$jm_masuk  = strtotime($jam_masuk);
		$jm_pulang = strtotime($jam_pulang);

		if ($start_time_shift != "00:00:00" && !$daysoff_id && $start_time_shift) {
			$jm_masuk  = strtotime($end_time_shift);
		}

		if ($start_time != "00:00:00" && !$daysoff_id && $start_time) {
			$jm_masuk  = strtotime($end_time);
		}

		$jumlah = $jm_pulang-$jm_masuk;

		$sisa = sisa_waktu_lembur($jumlah);

		if ($jm_masuk >  $jm_pulang) {
			$sisa = '0m';
		}

		return $sisa;
	}

	function sisa_waktu_lembur($waktu='')
    {
      // $awal  = strtotime('2017-08-10 10:05:25');
      // $akhir = strtotime('2017-08-11 11:07:33');
      // $diff  = $akhir - $awal;

      $jam   = floor($waktu / (60 * 60));
      $menit = $waktu - $jam * (60 * 60);
      if ($jam==0) {
          return  floor( $menit / 60 ) . 'm';
        }else {
           return  $jam .  ':' . floor( $menit / 60 ) . 'm';
        }
     
    }

    function start_time_tabel_rekap($start_time='',$start_time_shift='', $start_time_notfixed='',$daysoff_id='', $kode_cuti='')
	{
		 $resul = 2;
		 if ($start_time && !$start_time_shift && $start_time != "00:00:00") {
		 			$resul = jm($start_time);
		 }elseif (!$start_time && $start_time_shift) {
		 			$resul = jm($start_time_shift);
		 }elseif ($start_time && $start_time_shift) {
		 			$resul = jm($start_time);
		 }elseif ($start_time == "00:00:00" || $start_time_shift == "00:00:00") {
		 			// $resul = 'Libur';
		 			$resul = 2;
		 }else{
		 		// $resul = '<span tooltip="Tidak ada jadwal" flow="left"><i class="icon-help msclick"></i></span>';
		 		$resul = 2;
		 }

		 if ($start_time_notfixed) {
		 		$resul = jm($start_time).'F';
		 }elseif ($start_time_notfixed == "00:00:00") {
		 		// $resul = 'Libur';
		 		$resul = 2;
		 }

		 if ($daysoff_id) {
		 		$resul = 2;
		 }

		 if ($kode_cuti) {
		 		$resul = 2;
		 }

		 return $resul;
	}

    function jum_hari_kerja_rekap($json_data)
    {
    	 $pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_absen'];

    	 $count = count($json_absen);

    	 $hari_kerja = array();
         for ($i=0; $i < $count; $i++) { 
         		 $start_time   = $json_absen[$i]['f5'];
         		 $start_time_shift  = $json_absen[$i]['f10'];
                 $start_time_notfixed= $json_absen[$i]['f20'];
                 $daysoff_id       = $json_absen[$i]['f19'];
                 $cek = start_time_tabel_rekap($start_time, $start_time_shift,$start_time_notfixed,$daysoff_id);
                 if ($cek != 2) {
                 	 $hari_kerja[] = 1;
                 }
         		
         }

         $jumlah_hari_kerja = count($hari_kerja);

         return  $jumlah_hari_kerja;
    }


    function jum_hadir_kerja_rekap($json_data)
    {
    	$pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_absen'];

    	 $count = count($json_absen);

    	 $hari_kerja = array();
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
                 
                 $cek = cek_hadir_kerja($daysoff_id, $jam_masuk, $jam_pulang,$jam_masuk_shift, $jam_pulang_shift, $lkhdl_id, $dinasmanual_id, $kode_cuti, $rentan_tanggal, $start_time, $start_time_shift, $status_in, $status_out,$end_time,$end_time_shift, $start_time_notfixed, $jam_masuk_notfixed, $end_time_notfixed, $jam_pulang_notfixed);

                 if ($cek == 1) {
                 		$hari_kerja[] = 1;
                 }
         		
         }

         $jumlah_hari_kerja = count($hari_kerja);

         return  $jumlah_hari_kerja;
    }

        function cek_hadir_kerja($daysoff_id='', $jam_masuk='', $jam_pulang='',$jam_masuk_shift='', $jam_pulang_shift='', $lkhdl_id='', $dinasmanual_id='', $kode_cuti='', $rentan_tanggal='', $start_time='', $start_time_shift='', $status_in='', $status_out='',$end_time ='',$end_time_shift='', $start_time_notfixed='', $jam_masuk_notfixed='', $end_time_notfixed='', $jam_pulang_notfixed='')
    {
    	$ket = 2;
		$hari_ini = date('Y-m-d');

		if ($rentan_tanggal <= $hari_ini && !$daysoff_id && !$jam_masuk && !$jam_pulang && !$jam_masuk_shift && !$jam_pulang_shift && !$lkhdl_id && !$dinasmanual_id && !$kode_cuti ) {
			// $ket = 'TK'; 
			$ket = 2;
		}

		if ($jam_masuk || $jam_pulang || $jam_masuk_shift || $jam_pulang_shift || $jam_masuk_notfixed || $jam_pulang_notfixed) {
			 //$ket = 'H'; 
			 $ket = 1; 
		}
		if ($lkhdl_id) {
			 // $ket = 'DL'; 
			$ket = 1; 
		}

		if ($dinasmanual_id) {
			 // $ket = 'DLM'; 
			$ket = 1; 
		}

		

		if ($start_time == "00:00:00"  || $start_time_shift == "00:00:00") {
			// $ket = 'L';
			$ket = 2; 
		}

		if (!$start_time  && !$start_time_shift) {
			// $ket = '?';
			$ket = 2;
		}

		if ($status_in) {
				if ($status_in == 1) {
						// $ket = 'HM';
						$ket = 1;
				}elseif ($status_in == 2) {
						// $ket = 'TMM';
						$ket = 1;
				}elseif ($status_in == 3) {
						// $ket = 'TKM';
						$ket = 2;
				}else {
					// $ket = 'HM';
					$ket = 1;
				}
		}

		if ($status_out) {
				if ($status_out == 1) {
						// $ket = 'HM';
						$ket = 1;
				}elseif ($status_out == 2) {
						// $ket = 'PCM';
						$ket = 1;
				}elseif ($status_out == 3) {
						// $ket = 'TKM';
						$ket = 2;
				}else {
					// $ket = 'HM';
					$ket = 1;
				}
		}

		if ($daysoff_id) {
			 // $ket = 'L'; 
			$ket = 2;
		}

		if ($kode_cuti) {
			 // $ket = $kode_cuti; 
			 $ket = 2; 
		}

		return $ket;
    }

    function jum_terlambar_rekap($json_data)
    {
    	$pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_absen'];

    	 $count = count($json_absen);

    	 $hari_kerja = array();
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
                 		$hari_kerja[] = 1;
                 }
         		
         }

         $jumlah_hari_kerja = count($hari_kerja);

         return  $jumlah_hari_kerja;
    }

    function jum_pulang_cepat_rekap($json_data)
    {
    	$pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_absen'];

    	 $count = count($json_absen);

    	 $hari_kerja = array();
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
                 		$hari_kerja[] = 1;
                 }
         		
         }

         $jumlah_hari_kerja = count($hari_kerja);

         return  $jumlah_hari_kerja;
    }

    function jum_tk_rekap($json_data)
    {
    	$pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_absen'];

    	 $count = count($json_absen);

    	 $hari_kerja = array();
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

                 if ($cek == "TK" || $cek == "TKM") {
                 		$hari_kerja[] = 1;
                 }
         		
         }

         $jumlah_hari_kerja = count($hari_kerja);

         return  $jumlah_hari_kerja;
    }

    function jum_tidak_upacara_rekap($json_data)
    {
    	$pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_absen'];

    	 $count = count($json_absen);

    	 $hari_kerja = array(0);
         for ($i=0; $i < $count; $i++) { 
	
				 $jumtidak_upacara = $json_absen[$i]['f25'];

                 if ($jumtidak_upacara) {
                 		$hari_kerja[] = $jumtidak_upacara;
                 }
         		
         }

         $jumlah_hari_kerja = array_sum($hari_kerja);

         return  $jumlah_hari_kerja;
    }

    function jum_tidak_sholatza_rekap($json_data, $agama_id='',$user_id_pengecualian='')
    {
    	$pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_absen'];

    	 $count = count($json_absen);

    	 $hari_kerja = array(0);
         for ($i=0; $i < $count; $i++) { 
				 $rentan_tanggal   = $json_absen[$i]['f1'];
				 $ibadah_id        = $json_absen[$i]['f26'];
				 $daysoff_id       = $json_absen[$i]['f19'];
				 $kode_cuti        = $json_absen[$i]['f14'];

				 $start_time   = $json_absen[$i]['f5'];
				 $start_time_shift  = $json_absen[$i]['f10'];
				 $start_time_notfixed= $json_absen[$i]['f20'];

				 if ($rentan_tanggal >= '2020-03-26' && $user_id_pengecualian != 6) {
				 	 $hari_kerja[] = 0;
				 }else {
				 	 $cek = cek_jum_shalat_id($ibadah_id, $daysoff_id, $kode_cuti, $start_time, $start_time_shift,$start_time_notfixed);

					 if ($agama_id == 1 || $agama_id == '' || $agama_id == 0) {
					 	 $hari_kerja[] = $cek;
					 }
				 }
				

		
         		
         }

         $jumlah_hari_kerja = array_sum($hari_kerja);

         return  $jumlah_hari_kerja;
    }

    function cek_jum_shalat_id($ibadah_id ='',$daysoff_id='', $kode_cuti='', $start_time='', $start_time_shift='',$start_time_notfixed='')
    {
    	$result = 2;
    	if ($ibadah_id == 1) {
    			$result = 1;
    	}elseif ($ibadah_id == 2) {
    			$result = 1;
    	}elseif ($ibadah_id == 3 || $ibadah_id == 4 || $ibadah_id == 5) {
    			$result = 0;
    	}

    	if ($kode_cuti || $start_time == "00:00:00" || $start_time_shift == "00:00:00" || $start_time_notfixed == "00:00:00" || $daysoff_id) {
    			$result = 0;
    	}

    	return $result;

    }

    function jum_dinas_luar_rekap($json_data)
    {
    	$pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_absen'];

    	 $count = count($json_absen);

    	 $hari_kerja = array();
         for ($i=0; $i < $count; $i++) { 
	
				 $lkhdl_id         = $json_absen[$i]['f15'];
				 $dinasmanual_id   = $json_absen[$i]['f16'];
				 $daysoff_id       = $json_absen[$i]['f19'];
				 $kode_cuti        = $json_absen[$i]['f14'];

				 $start_time   = $json_absen[$i]['f5'];
				 $start_time_shift  = $json_absen[$i]['f10'];
				 $start_time_notfixed= $json_absen[$i]['f20'];

				 $cek = cek_dinas_luar_rekap($lkhdl_id , $dinasmanual_id,$daysoff_id,$kode_cuti, $start_time,$start_time_shift, $start_time_notfixed);

				 if ($cek == 1) {
				 	 $hari_kerja[] = $cek;
				 }
         		
         }

         $jumlah_hari_kerja = count($hari_kerja);

         return  $jumlah_hari_kerja;
    }

    function cek_dinas_luar_rekap($lkhdl_id='', $dinasmanual_id='',$daysoff_id='',$kode_cuti='', $start_time='',$start_time_shift='', $start_time_notfixed='')
    {
    	$result = 0;
    	if ($lkhdl_id || $dinasmanual_id) {
    			$result = 1;
    	}

    	if ($daysoff_id || $kode_cuti || $start_time == "00:00:00" || $start_time_shift == "00:00:00" || $start_time_notfixed == "00:00:00") {
    			$result = 0;
    	}

    	return $result;
    }

    function jum_cuti_rekap($json_data)
    {
    	$pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_absen'];

    	 $count = count($json_absen);

    	 $hari_kerja = array();
         for ($i=0; $i < $count; $i++) { 
	
				 $daysoff_id       = $json_absen[$i]['f19'];
				 $kode_cuti        = $json_absen[$i]['f14'];

				 $start_time   = $json_absen[$i]['f5'];
				 $start_time_shift  = $json_absen[$i]['f10'];
				 $start_time_notfixed= $json_absen[$i]['f20'];

				 $cek = cek_cuti_rekap($daysoff_id,$kode_cuti, $start_time,$start_time_shift, $start_time_notfixed);

				 if ($cek == 1) {
				 	 $hari_kerja[] = $cek;
				 }
         		
         }

         $jumlah_hari_kerja = count($hari_kerja);

         return  $jumlah_hari_kerja;
    }

    function cek_cuti_rekap($daysoff_id='',$kode_cuti='', $start_time='',$start_time_shift='', $start_time_notfixed='')
    {
    	$result = 0;
    	if ($kode_cuti) {
    			$result = 1;
    	}

    	if ($daysoff_id || $start_time == "00:00:00" || $start_time_shift == "00:00:00" || $start_time_notfixed == "00:00:00") {
    			$result = 0;
    	}

    	return $result;
    }

    function jum_hari_kerja_rekap_lkh($json_data)
    {
    	 $pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_jum_lkh'];

    	 $count = count($json_absen);

    	 $hari_kerja = array();
         for ($i=0; $i < $count; $i++) { 
         		 $start_time   = $json_absen[$i]['f2'];
         		 $start_time_shift  = $json_absen[$i]['f4'];
                 $start_time_notfixed= $json_absen[$i]['f6'];
                 $daysoff_id       = $json_absen[$i]['f8'];
                 $cek = start_time_tabel_rekap($start_time, $start_time_shift,$start_time_notfixed,$daysoff_id);
                 if ($cek != 2) {
                 	 $hari_kerja[] = 1;
                 }
         		
         }

         $jumlah_hari_kerja = count($hari_kerja);

         return  $jumlah_hari_kerja;
    }

    function jum_data_kerja_rekap_lkh($json_data, $jumlah_laporan='')
    {
    	 $pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_jum_lkh'];

    	 $count = count($json_absen);

    	 $hari_kerja = array();
         for ($i=0; $i < $count; $i++) { 
         		 $start_time   = $json_absen[$i]['f2'];
         		 $start_time_shift  = $json_absen[$i]['f4'];
                 $start_time_notfixed= $json_absen[$i]['f6'];
                 $daysoff_id       = $json_absen[$i]['f8'];
                 $daysoff_id       = $json_absen[$i]['f8'];
                 $jumlah_lkh       = $json_absen[$i]['f9'];
                 $kode_cuti       = $json_absen[$i]['f10'];
                 $cek = start_time_tabel_rekap($start_time, $start_time_shift,$start_time_notfixed,$daysoff_id, $kode_cuti);
                 if ($cek != 2) {
                 	 if ($jumlah_lkh) {
                 	 		 $hari_kerja[] = 1;
                 	 }
                 }
         		
         }

         $jumlah_hari_kerja = count($hari_kerja);

         if ($jumlah_laporan) {
         		 $jumlah_hari_kerja = $jumlah_laporan;
         }

         return  $jumlah_hari_kerja;
    }

    function total_jum_lkh_rekap($json_data, $total_laporan='')
    {
    	 $pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_jum_lkh'];

    	 $count = count($json_absen);

    	 $hari_kerja = array(0);
         for ($i=0; $i < $count; $i++) { 
         		 $start_time   = $json_absen[$i]['f2'];
         		 $start_time_shift  = $json_absen[$i]['f4'];
                 $start_time_notfixed= $json_absen[$i]['f6'];
                 $daysoff_id       = $json_absen[$i]['f8'];
                 $daysoff_id       = $json_absen[$i]['f8'];
                 $jumlah_lkh       = $json_absen[$i]['f9'];
                 $cek = start_time_tabel_rekap($start_time, $start_time_shift,$start_time_notfixed,$daysoff_id);
                 if ($cek != 2) {
                 	 if ($jumlah_lkh) {
                 	 		 $hari_kerja[] = $jumlah_lkh;
                 	 }
                 }
         		
         }

         $jumlah_hari_kerja = array_sum($hari_kerja);

          if ($total_laporan) {
         		 $jumlah_hari_kerja = $total_laporan;
         }

         return  $jumlah_hari_kerja;
    }

    function total_kerja_rekap_lkh_field($json_data, $field)
    {
    	 $pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_jum_lkh'];

    	 $count = count($json_absen);

    	 $hari_kerja = array();
         for ($i=0; $i < $count; $i++) { 
         		 $start_time   = $json_absen[$i]['f2'];
         		 $start_time_shift  = $json_absen[$i]['f4'];
                 $start_time_notfixed= $json_absen[$i]['f6'];
                 $daysoff_id       = $json_absen[$i]['f8'];
                 $daysoff_id       = $json_absen[$i]['f8'];
                 $jumlah_lkh       = $json_absen[$i][$field];
                 $cek = start_time_tabel_rekap($start_time, $start_time_shift,$start_time_notfixed,$daysoff_id);
                 if ($cek != 2) {
                 	 if ($jumlah_lkh) {
                 	 		 $hari_kerja[] = $jumlah_lkh;
                 	 }
                 }
         		
         }

         $jumlah_hari_kerja = array_sum($hari_kerja);

         return  $jumlah_hari_kerja;
    }

    function jum_data_kerja_rekap_lkh_field($json_data,$field)
    {
    	 $pgarray_data = json_decode($json_data, true);
    	 $json_absen  = $pgarray_data['data_jum_lkh'];

    	 $count = count($json_absen);

    	 $hari_kerja = array();
         for ($i=0; $i < $count; $i++) { 
         		 $start_time   = $json_absen[$i]['f2'];
         		 $start_time_shift  = $json_absen[$i]['f4'];
                 $start_time_notfixed= $json_absen[$i]['f6'];
                 $daysoff_id       = $json_absen[$i]['f8'];
                 $daysoff_id       = $json_absen[$i]['f8'];
                 $jumlah_lkh       = $json_absen[$i][$field];
                 $cek = start_time_tabel_rekap($start_time, $start_time_shift,$start_time_notfixed,$daysoff_id);
                 if ($cek != 2) {
                 	 if ($jumlah_lkh) {
                 	 		 $hari_kerja[] = 1;
                 	 }
                 }
         		
         }

         $jumlah_hari_kerja = count($hari_kerja);

         return  $jumlah_hari_kerja;
    }

    function start_time_tabel_pegawai($start_time='',$start_time_shift='', $start_time_notfixed='', $check_in_time1='', $check_in_time2='', $check_in_time1_shift='', $check_in_time2_shift='', $check_in_time1_notfixed='', $check_in_time2_notfixed='')
	{
		 $resul = '<span tooltip="Tidak ada jadwal" flow="left"><i class="icon-help msclick"></i></span>';
		 if ($start_time && $start_time != "00:00:00") {
		 			$resul = jm($start_time).'('.jm($check_in_time1).'-'.jm($check_in_time2).')';
		 }

		 if ($start_time_shift && $start_time_shift != "00:00:00") {
		 			$resul = jm($start_time_shift).'('.jm($check_in_time1_shift).'-'.jm($check_in_time2_shift).')';
		 }

		 if ($start_time_notfixed) {
		 		$resul = jm($start_time_notfixed).'('.jm($check_in_time1_notfixed).'-'.jm($check_in_time2_notfixed).')';
		 }

		 if ($start_time == "00:00:00" || $start_time_shift == "00:00:00" || $start_time_notfixed == "00:00:00") {
		 			$resul = 'Libur';
		 }

		 return $resul;
	}

	function start_end_tabel_pegawai($start_time='',$start_time_shift='', $start_time_notfixed='', $check_in_time1='', $check_in_time2='', $check_in_time1_shift='', $check_in_time2_shift='', $check_in_time1_notfixed='', $check_in_time2_notfixed='')
	{
		 $resul = '<span tooltip="Tidak ada jadwal" flow="left"><i class="icon-help msclick"></i></span>';
		 if ($start_time && $start_time != "00:00:00") {
		 			$resul = jm($start_time).'('.jm($check_in_time1).'-'.jm($check_in_time2).')';
		 }

		 if ($start_time_shift && $start_time_shift != "00:00:00") {
		 			$resul = jm($start_time_shift).'('.jm($check_in_time1_shift).'-'.jm($check_in_time2_shift).')';
		 }

		 if ($start_time_notfixed) {
		 		$resul = jm($start_time_notfixed).'('.jm($check_in_time1_notfixed).'-'.jm($check_in_time2_notfixed).')';
		 }

		 if ($start_time == "00:00:00" || $start_time_shift == "00:00:00" || $start_time_notfixed == "00:00:00") {
		 			$resul = 'Libur';
		 }

		 return $resul;
	}






