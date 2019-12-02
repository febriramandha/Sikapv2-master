<?php
defined('BASEPATH') OR exit('No direct script access allowed');



	 function level_instansi($level, $path_info){
        
        $spasi = 10;
        if ($level != 1) {
            $spasi = 20*$level;
        }
	 	
		// for ($i=1; $i < $level ; $i++) { 
		// 	$spasi .= '&nbsp;&emsp;';
		// }
        $div = '<span style="padding-left:'.$spasi.'px;"></span>';

		return $div.'<span class="badge badge-info" >'.filter_path($path_info).'</span> ';
	}

  function level_instansiF($level, $path_info)
  {
    return  '['.$level.']'.carakteX($level, '-','|').filter_path($path_info);
  }


	 function filter_path($string)
    {
      $new_str = str_replace(['{', '}', ','],[' ', ' ','.'], $string);
      // $new_str = preg_replace('~[{}]~', ' ', $string);
      return  $new_str;

    }

    function pg_to_array($path) {
    	return explode(',', trim($path, '{}'));
    }

    function ec_add_sub($id, $level, $name) {
    	$a ='';
    	if ($level < 4) {
    		$a = '<span class="plus list-icons-item text-warning-600 ml-2 msclick"  id="'.$id.'" data="'.$name.'">
        									<i class="icon-file-plus2" ></i>
        							</span>';
    	}

    	return $a;
    }

    function status_user($id='')
    {
        if($id==1) {
            $a = '<span class="badge badge-success">Aktif</span>';
        }else{
           $a = '<span class="badge badge-danger">Non Aktif</span>';
        }

        return $a;
    }

    function level_alias($id='')
    {
        if($id == 1) {
            $level = "Super Administrator";
        }else if($id == 2) {
            $level = "Admin Instansi";
        }else if($id == 3) {
            $level = "Pegawai";
        }else if($id == 5) {
            $level = "Piminan Instansi";
        }else if($id == 4) {
            $level = "Admin Monitoring";
        }else{
            $level = "Belum Ada Akses";
        }
        return $level;
    }

     function status_pegawai($id='')
    {
        if ($id) {
            $a = '<span style="font-size: 70%;">'.$id.'</span>';
        }else {
            $a = '<span style="font-size: 70%;">NON PNS</span>';
        }

        return $a;
    }


    function gender($id='')
    {
      $g = '-';
      if ($id==1) {
       $g="Laki-Laki";
      }elseif ($id==2) {
         $g="Perempuan";
      }

      return $g;
    }

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

    function status_kawin($id='')
    {
        if ($id == 1) {
            $a = "Belum Menikah";
        }elseif ($id==2) {
            $a= "Menikah";
        }elseif ($id == 3) {
           $a= "Janda";
        }else $a = "";

        return $a;
    }

    function name_degree($name='', $gl_d='', $gl_b='') { 
        if ($gl_d) {
              $gl_d = $gl_d.'. ';
        }
        if ($gl_b) {
            $gl_b = ', '.$gl_b;
        }

        return $gl_d.$name.$gl_b;
    }

    function _name($name='')
    {
        return ucwords(strtolower($name));
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

    function cek_cookie_login($cookie)
    {
        $cookie_browser = get_cookie('tpp_cookie');

        if ($cookie == $cookie_browser) {
            $cek = '<span class="badge badge-success">Perangkat utama</span>';
        }else  $cek = '<span class="badge badge-danger">Perangkat lain</span>
                      <span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus perangkat ini?" title="hapus perangkat" style="cursor:pointer;" id="'.$cookie.'"><i class="icon-cancel-circle2" ></i></span>';
        return $cek;
    }

    function _icon($class='')
    {
      return $icon = '<i class="icon-'.strtolower($class).'"></i>';
    }

    function akun_cek_aksi($status, $id)
    {
       if ($status==1) {
           $a = '<a href="javascript:;" class="non_aktif_akun dropdown-item" data="'.encrypt_url($id,"user").'"><i class="icon-lock2"></i> Non Aktifkan Akun</a>';
       }else {
           $a = '<a href="javascript:;" class="aktif_akun dropdown-item" data="'.encrypt_url($id,"user").'"><i class="icon-unlocked2"></i> Aktifkan Akun</a>';
       }

       return $a;
    }
    
    function att_status_cek_aksi($status, $id)
    {
       if ($status==1) {
           $a = '<a href="javascript:;" class="non_aktif_user dropdown-item" data="'.encrypt_url($id,"user").'"><i class="icon-cancel-square2"></i>Non Aktifkan User Att</a>';
       }else {
           $a = '<a href="javascript:;" class="aktif_user dropdown-item" data="'.encrypt_url($id,"user").'"><i class="icon-checkmark2"></i>Aktifkan User Att</a>';
       }

       return $a;
    }

    function toAlpha($data){
        $alphabet =   array('z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
        $alpha_flip = array_flip($alphabet);
        if($data <= 25){
          return $alphabet[$data];
        }
        elseif($data > 25){
          $dividend = ($data + 1);
          $alpha = '';
          $modulo;
          while ($dividend > 0){
            $modulo = ($dividend - 1) % 26;
            $alpha = $alphabet[$modulo] . $alpha;
            $dividend = floor((($dividend - $modulo) / 26));
          } 
          return $alpha;
        }
    }

    function attConverPathNumber($path)
    {
        $path_new = pg_to_array($path);
        $count_ = count($path_new);
        $data_num_new = [];
        for ($i=0; $i < $count_; $i++) { 
          $cek_num = $path_new[$i];

          if ($cek_num < 10) {
             $num_new = '00'.$cek_num;
          }elseif ($cek_num < 100) {
            $num_new = '0'.$cek_num;
          }else {
            $num_new = $cek_num;
          }

          $data_num_new[] =  $num_new;
        }

        $number_path = json_encode($data_num_new);
        $new_path = str_replace(['[', ']', ',','"'],['', '','.',''],$number_path);

        return $new_path;
    }

    function cekAksiAktifInstansi($status, $id)
    {
      if ($status==1) {
           $a = '<span class="non_aktif list-icons-item ml-2 msclick"  id="'.$id.'">
                          <i class="icon-folder-remove" ></i>
                  </span>';
       }else {
           $a = '<span class="aktif list-icons-item text-info ml-2 msclick"  id="'.$id.'">
                          <i class="icon-folder-check" ></i>
                 </span>';
       }

       return $a;
    }

    function cekAksiAktifMesin($status, $id)
    {
      if ($status==1) {
           $a = '<a href="javascript:;" class="non_aktif dropdown-item" data="'.$id.'"><i class="icon-cancel-square2"></i> Non Aktif Mesin</a>';
       }else {
           $a = ' <a href="javascript:;" class="aktif dropdown-item" data="'.$id.'"><i class="icon-checkmark2"></i> Aktifkan Mesin</a>';
       }

       return $a;
    }

    function carakteX($jum,$string,$end)
    {
        $s ='';
        for ($i=0; $i < $jum*3; $i++) { 
            $s .= $string;
        }

        return $s.$end;
    }

    function rupiah($angka){
  
      $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
      return $hasil_rupiah;
     
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


/* End of file sikap_helper.php */
/* Location: ./application/helpers/sikap_helper.php */