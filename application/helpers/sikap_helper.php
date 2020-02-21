<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

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
            // $a = '<span class="badge badge-success">Aktif</span>';
            $a = '<span class="badge bg-success badge-pill">aktif</span>';
        }else{
           // $a = '<span class="badge badge-danger">Non Aktif</span>';
            $a = '<span class="badge bg-danger badge-pill">non aktif</span>';
        }

        return $a;
    }

    function status_tree($id='')
    {
        if($id==1) {
            // $a = '<span class="badge badge-success">Aktif</span>';
            $a = '<a class="badge bg-success badge-pill" >aktif</a>';
        }else{
           // $a = '<span class="badge badge-danger">Non Aktif</span>';
            $a = '<a class="badge bg-danger badge-pill">non aktif</a>';
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
            $level = "User Eksekutif";
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

    function nama_gelar($name='', $gl_d='', $gl_b='') { 
        if ($gl_d) {
              $gl_d = $gl_d.'. ';
              $gl_d = str_replace([', ,', '. .'],[',', '.'], $gl_d);
        }
        if ($gl_b) {
            $gl_b = ', '.$gl_b;
            $gl_b = str_replace([", ,", ". ."],[',', '.'], $gl_b);
        }

        return $gl_d._name($name).$gl_b;
    }

    function _ucname($str){
      $string = ucwords(strtolower($str));
      foreach (array('-','\'','.') as $delimiter) {
          if (strpos($string, $delimiter) !== FALSE) {
              $string = implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
          }
      }
      return $string;
    }

    function _name($name='')
    {
        return ucwords(strtolower($name));
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

    

    

    function status_tpp($id='')
    {
        if($id==1) {
             $a = '<span class="badge bg-success badge-pill">TPP</span>';
        }else{
             $a = '<span class="badge bg-danger badge-pill">Non TPP</span>';
        }

        return $a;
    }

    function random_color()
    {
        // the array
        $arrX = array("bg-warning-400", 
                      "bg-pink-400",
                      "bg-success-400", 
                      "bg-blue",
                      "bg-brown-400",
                      "bg-teal-400",
                      "border-indigo-400",
                      "bg-grey-400",
                      "bg-primary-600");
         
        // get 2 random indexes from array $arrX
        $randIndex = array_rand($arrX, 2);
         
        /*
        * output the value for the first random index
        * you can access the first index with $randIndex[0]
        * (may be a bit confusing for programming beginners)
        */
        return $arrX[$randIndex[0]];
    }

    function color_abjad()
    {
        return $color = array('a' => "bg-primary-600",
                       'b' => "bg-primary-400",
                       'c' => "bg-danger-600",
                       'd' => "bg-danger-400",
                       'e' => "bg-success-600",
                       'f' => "bg-success-400",
                       'g' => "bg-warning-600", 
                       'h' => "bg-warning-400",
                       'i' => "bg-info-600",
                       'j' => "bg-info-400",
                       'k' => "bg-pink-600",
                       'l' => "bg-pink-400",
                       'm' => "bg-violet-600",
                       'n' => "bg-violet-400",
                       'o' => "bg-orange-600",
                       'p' => "bg-orange-400",
                       'q' => "bg-indigo-600",
                       'r' => "bg-indigo-400",
                       's' => "bg-blue-600",
                       't' => "bg-blue-400",
                       'u' => "bg-teal-600",
                       'v' => "bg-teal-400",
                       'w' => "bg-green-600",
                       'x' => "bg-green-400",
                       'y' => "bg-grey-600",
                       'z' => "bg-brown-400",
                       '?' => "bg-slate-800",
                       '1' => "bg-blue-400",
                       '2' => "bg-teal-600",
                       '3' => "bg-teal-400",
                       '4' => "bg-green-600",
                       '5' => "bg-green-400",
                       '6' => "bg-grey-600",
                       '7' => "bg-brown-400",
                       '8' => "bg-danger-400",
                       '9' => "bg-success-600",
                       '0' => "bg-info-400",
                       );
    }

    function nama_icon_nip($name='', $gl_d='', $gl_b='', $nip='',$link='',$id='',$str3='',$encry_user_id ='') { 

        if ($link) {
              $encrypt = "user_id";
              if ($encry_user_id) {
                    $encrypt = $encry_user_id;
              }
              $link = base_url($link."/").encrypt_url($id,$encrypt);
        }else {
              $link = "#";
        }

        $nama_gelar = nama_gelar($name, $gl_d, $gl_b);
        $awal       = _str_limit($name,1);
        $nama_icon = _nama_icon($nama_gelar,$nip,$awal,$link,$str3);
        return $nama_icon;
    }

    function nama_icon_pegawai($name='', $gl_d='', $gl_b='', $nip='',$link='',$id='',$str3='') { 

        if ($link) {
              $link = base_url($link."/").$id;
        }else {
              $link = "#";
        }

        $nama_gelar = nama_gelar($name, $gl_d, $gl_b);
        $awal       = _str_limit($name,1);
        $nama_icon = _nama_icon($nama_gelar,$nip,$awal,$link,$str3);
        return $nama_icon;
    }

    function nama_icon_nip_link($name='', $gl_d='', $gl_b='', $nip='',$link='') { 

        if ($link) {
              $link = base_url($link);
        }else {
              $link = "#";
        }

        $nama_gelar = nama_gelar($name, $gl_d, $gl_b);
        $awal       = _str_limit($name,1);
        $nama_icon = _nama_icon($nama_gelar,$nip,$awal,$link);
        return $nama_icon;
    }

    function _str_icon($str1='',$str2='',$str3='',$link='')
    {
          return _nama_icon($str1,$str2,_str_limit($str1,1),$link,$str3);
    }

    function _str_limit($str='',$limit='')
    {
        if ($str) {
            $str = substr($str,0,$limit);
        }else {
            $str = '?';
        }

        return $str;
    }

    function _nama_icon($str1='',$str2='',$alias='',$link='',$str3='')
    {
         if ($str3) {
              $str3 = '<div class="text-muted font-size-sm"><span class="badge badge-mark border-blue mr-1"></span> '.$str3.'</div>';
         }
         if ($str2) {
              $str2 = '<div class="text-muted font-size-sm"><span class="badge badge-mark border-blue mr-1"></span> '.$str2.'</div>';
         }

         $tag_c = 'span';
         if ($link) {
              $tag_c = 'a';
         }

         $color = color_abjad();
         $icon = '<div class="d-flex align-items-center">
                    <div class="mr-3">
                      <'.$tag_c.' href="'.$link.'" class="btn '.$color[strtolower($alias)].' rounded-round btn-icon btn-sm legitRipple">
                        <span class="letter-icon">'.$alias.'</span>
                      </'.$tag_c.'>
                    </div>
                    <div>
                      <'.$tag_c.' href="'.$link.'" class="text-default font-weight-semibold letter-icon-title">'.$str1.'</'.$tag_c.'>
                      '.$str2.'
                      '.$str3.'
                    </div>
                  </div>';
          return $icon;
    }

    function level_instansi_tabel($str1='',$str2='', $level='',$path='') {
          $level = level_instansiF($level, $path);
          $name = $level.$str1;
        return  _name_list($name,$str2);

    }

    function _name_list($str1='',$str2='',$link='')
    {
         $tag_c = 'span';
         if ($link) {
              $tag_c = 'a';
         }
         $icon = '
                    <div>
                      <'.$tag_c.' href="'.$link.'" class="text-default font-weight-semibold letter-icon-title">'.$str1.'</'.$tag_c.'>
                      <div class="text-muted font-size-sm"><span class="badge badge-mark border-blue mr-1"></span> '.$str2.'</div>
                    </div>';
          return $icon;
    }

    function instansi_expl_($str,$path,$level)
    {
        $pgarray_str = pg_to_array($str);
        //$pgarray_path = pg_to_array($path);
        $pgarray_level = pg_to_array($level);
        $a ='';
        for ($i=0; $i < count($pgarray_str); $i++) { 
            $name = str_replace('"',"",$pgarray_str[$i]);
            //$path_info = str_replace('"',"",$pgarray_path[$i]);
            $p_level = $pgarray_level[$i];
            $path_new = level_instansiF($p_level, '');
            $a .= $path_new.$name.'<br>';
        }

        return $a;
    }

    function instansi_expl($json)
    {
        $pgarray_str = json_decode($json, true);
        $a ='';
        $instansi = $pgarray_str['data_instansi'];
        for ($i=0; $i < count($instansi); $i++) { 
            $dept_name  = $instansi[$i]['f1'];
            $path_info  = $instansi[$i]['f2'];
            $level      = $instansi[$i]['f3'];
            $path_new   = level_instansiF($level, $path_info);
            $a .= $path_new.$dept_name.'<hr class="m-1">';
        }
        return $a;
    }

    function pegawai_expl($json)
    {
        $pgarray_nama = json_decode($json, true);
        $a ='';
        $nip  = $pgarray_nama['data_pegawai'];
        $no = 1;
        for ($i=0; $i < count($nip); $i++) { 
              $nip_  = $nip[$i]['f1'];
              $nama_ = $nip[$i]['f2'];
              $gelar_dpn_ = $nip[$i]['f3'];
              $gelar_blk_ = $nip[$i]['f4'];
              $a .= $no++.'.'.nama_gelar($nama_,$gelar_dpn_,$gelar_blk_).'('.$nip_.')<hr class="m-1">';
        }

        return $a;
    }

    function konversi_nip($nip, $batas = " ") { 
      $nip = trim($nip," ");
      $panjang = strlen($nip);

      if($panjang == 18) {
        $sub[] = substr($nip, 0, 8); // tanggal lahir
        $sub[] = substr($nip, 8, 6); // tanggal pengangkatan
        $sub[] = substr($nip, 14, 1); // jenis kelamin
        $sub[] = substr($nip, 15, 3); // nomor urut

        return $sub[0].$batas.$sub[1].$batas.$sub[2].$batas.$sub[3];
      } else {
        return $nip;
      }
    }

    function nama_icon_nip_key($name='', $gl_d='', $gl_b='', $nip='',$link='',$id='',$str3='',$key='') { 

        if ($link) {
              $link = base_url($link."/").encrypt_url($id,$key);
        }else {
              $link = "#";
        }

        $nama_gelar = nama_gelar($name, $gl_d, $gl_b);
        $awal       = _str_limit($name,1);
        $nama_icon = _nama_icon($nama_gelar,$nip,$awal,$link,$str3);
        return $nama_icon;
    }

    function kewenangan_tabelicon($level='')
    {
          $a ='';
          if ($level) {
              $a = '<span tooltip="'.level_alias($level).'" flow="left"><i class="icon-user-tie msclick cekpejabat '.color_level($level).'"></i></span>';
          }

          return $a;
    }

    function color_level($id='')
    {
          if($id == 1) {
            $level = "";
          }else if($id == 2) {
              $level = "text-info-600";
          }else if($id == 3) {
              $level = "text-success-600";
          }else if($id == 5) {
              $level = "text-brown-400";
          }else if($id == 4) {
              $level = "text-warning-600";
          }else{
              $level = "";
          }
          return $level;
    }




/* End of file sikap_helper.php */
/* Location: ./application/helpers/sikap_helper.php */