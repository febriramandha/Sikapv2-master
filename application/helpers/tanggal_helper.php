<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

	// function total_cuti_cek($start_date='',$end_date='',$libur_start_date='',$libur_end_date='')
	// {
		 
	// }
	 function format_tgl_ind($tgl='')
    {
      $tgl_ = '';
      if ($tgl) {
        $tgl_ = date('d-m-Y', strtotime($tgl));
      }
      return $tgl_;
    }

    function format_waktu_ind($tgl='')
    {
      $tgl_ = '';
      if ($tgl) {
        $tgl_ = date('d-m-Y (H:i)', strtotime($tgl));
      }
      return $tgl_;
    }

    function tgl_awal($tgl='')
    {
      $tgl_pertama = date('01-m-Y', strtotime($tgl));
     

      return $tgl_pertama;
    }

    function tgl_akhir($tgl='')
    {
       $tgl_terakhir = date('t-m-Y', strtotime($tgl));
       return $tgl_terakhir;
    }

    function jm($jam='')
    {
      if ($jam) {
        $a = date('H:i', strtotime($jam));
      }else $a='';
      return $a;
    }

    function _start_clus($start,$end)
    {
         if ($start != $end) {
              $date = format_tgl_ind($start).' - '.format_tgl_ind($end);
         }else $date = $start;

         return $date;
    }

    function _umur($tgl='')
    {
        $umur ='-';
        if ($tgl) {
            // Tanggal Lahir
            $birthday = $tgl;
            
            // Convert Ke Date Time
            $biday = new DateTime($birthday);
            $today = new DateTime();
            
            $diff = $today->diff($biday);

            $umur =  $diff->y ." Tahun";
        }
        return $umur;
  
    }

	function tgl_plus($tgl='', $nilai='')
	{

	  $tgl1 = $tgl;// pendefinisian tanggal awal
	  $tgl2 = date('Y-m-d', strtotime('+'.$nilai.' days', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari
	  return  $tgl2; //print tanggal
	}

	function tgl_minus($tgl='', $nilai='')
	{

	  $tgl1 = $tgl;// pendefinisian tanggal awal
	  $tgl2 = date('Y-m-d', strtotime('-'.$nilai.' days', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari
	  return  $tgl2; //print tanggal
	}

   function bulan_plus($tgl='', $nilai='')
  {
     return  date('Y-m-d', strtotime('+'.$nilai.' month', strtotime($tgl)));
  }


	function format_tgl_eng($tgl='')
	{	
		$a ='';
		if ($tgl) {
			$a = date('Y-m-d', strtotime($tgl));
		}
		return $a;
	}

  function jumlah_tanggal_bulan($y='',$m='')
  {
        $tahun = date('Y'); //Mengambil tahun saat ini
        $bulan = date('m'); //Mengambil bulan saat ini
        $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        return $tanggal;
  }

  function menampilakan_tanggal_dalam_bulan($y='',$m='')
  {
      $tahun = date('Y'); //Mengambil tahun saat ini
      $bulan = date('m'); //Mengambil bulan saat ini
      $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

      for ($i=1; $i < $tanggal+1; $i++) { 
        echo $i . " ";
      }


  }

   function jumlah_tanggal_bulan_cos($y='',$m='')
  {
        $tahun = $y; //Mengambil tahun saat ini
        $bulan = $m; //Mengambil bulan saat ini
        $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        return $tanggal;
  }

  function jumlah_hari_rank($start='', $end='')
  {
      $start_date      = new DateTime($start);
      $end_date        = new DateTime($end);
      $interval        = $start_date->diff($end_date);
      
      return $interval->days;

  }

   function jumlah_bulan_rank($start='', $end='')
  {
      $start_date      = new DateTime($start);
      $end_date        = new DateTime($end);
      $diff =  $start_date->diff($end_date);

      $months = $diff->y * 12 + $diff->m + $diff->d / 30;

      return (int) round($months);

  }

  function tanggal_format($tgl='',$format='')
    {
      $tgl_ = '';
      if ($tgl) {
        $tgl_ = date($format, strtotime($tgl));
      }
      return $tgl_;
    }


  function hari_tgl($tanggal='')
    {
          $day = date('D', strtotime($tanggal));
          $dayList = array(
            'Sun' => 'Minggu',
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat',
            'Sat' => 'Sabtu'
          );
         return $dayList[$day];
    }


  function tgl_ind_hari($tanggal='')
  {
          $bulan = array (
          1 =>   'Januari',
          2 => 'Februari',
          3 => 'Maret',
          4 => 'April',
          5 => 'Mei',
          6 =>'Juni',
          7 => 'Juli',
          8 => 'Agustus',
          9 => 'September',
          10 =>'Oktober',
          11 =>'November',
          12 => 'Desember'
        );
        $pecahkan = explode('-', $tanggal);
        return hari_tgl($tanggal). '/'. $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
  }

  function format_tanggal($tgl='', $format='')
    {
      $tgl_ = '';
      if ($tgl) {
        $tgl_ = date($format, strtotime($tgl));
      }
      return $tgl_;
    }

    function tgl_ind_bulan($tanggal='')
  {
        $pecahkan = explode('-', $tanggal);
        return $pecahkan[2] . '-' . _str_limit(_bulan((int)$pecahkan[1]),3) . '-' . $pecahkan[0];
  }

   function _bulan($blnint)
  {
         $bulan = array (
          1 =>   'Januari',
          2 => 'Februari',
          3 => 'Maret',
          4 => 'April',
          5 => 'Mei',
          6 =>'Juni',
          7 => 'Juli',
          8 => 'Agustus',
          9 => 'September',
          10 =>'Oktober',
          11 =>'November',
          12 => 'Desember'
        );

        return $bulan[$blnint];
  }

  function tglInd_hrtabel($tanggal)
  {
        $pecahkan = explode('-', $tanggal);
        return _str_limit(hari_tgl($tanggal),3). '/'. $pecahkan[2] . '-' . _str_limit(_bulan((int)$pecahkan[1]),3) . '-' . $pecahkan[0];
  }

  function sisa_waktu($waktu='')
  {
    // $awal  = strtotime('2017-08-10 10:05:25');
    // $akhir = strtotime('2017-08-11 11:07:33');
    // $diff  = $akhir - $awal;

    $jam   = floor($waktu / (60 * 60));
    $menit = $waktu - $jam * (60 * 60);
    if ($jam==0) {
        return  floor( $menit / 60 ) . ' menit';
      }else {
         return  $jam .  ' jam, ' . floor( $menit / 60 ) . ' menit';
      }
   
  }

  function Cek_tanggalValid($tanggal='')
  {
      $pecah = explode("-", $tanggal);

      if (checkdate($pecah[1], $pecah[2], $pecah[0])) {
          return true;
      }else return false;
  }

  function tanggal_rentang_from_bulan($tgl)
  {
    # code...
  }

