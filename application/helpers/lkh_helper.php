<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

function tgl_minus_lkh($tgl='', $nilai='', $hari_id=array())
  {
      $taggal_minus = tgl_minus($tgl, $nilai);

      //$jum = jumlah_hari_rank($taggal_minus, $tgl);

      $for_hari = json_decode($hari_id, true);

      $i=0;
      foreach ($for_hari as $value) {
            $hari_id_[] = $for_hari[$i++]['f1'];
      }

      $tanggl_array = cek_tgl_jum($tgl, $hari_id_);

      $data_tgl = array();
      for ($i=0; $i < $nilai; $i++) { 
           $data_tgl[] = $tanggl_array[$i];
      }

      return $data_tgl;

  }

    function cek_tgl_jum($tgl, $hari_id=array())
    {
          $tgl_count = array();
          $jum_cek = array();
          for ($i=0; $i < 100; $i++) {
                foreach ($hari_id as $r_value) {
                      $tgl_N = tanggal_format(tgl_minus($tgl, $i),'N');
                      $tgl_f = tgl_minus($tgl, $i);
                      if ($tgl_N == $r_value) {
                            $jum_cek[] = $tgl_f;
                      }
                 } 
          }
          return $jum_cek;
    }

    function status_lkh_tabel($status='', $comment='')
    {
        if($status==0) {
            $a = '<span class="text-warning" tooltip="Menunggu verifikasi" flow="left" style="cursor:pointer;"><i class="icon-hour-glass"></i></span>';
        }elseif ($status==1) {
           $a = '<span class="text-success" tooltip="Telah diverifikasi" flow="left" style="cursor:pointer;"><i class="icon-checkmark-circle2"></i></span>';
        }elseif ($status==2) {
           $a = '<span class="badge badge-danger">Laporan ditolak</span>
                <div class="border-1 border-danger p-1" style="margin-top: 5px;">
                  '.$comment.'
                </div>';
        }elseif ($status==3) {
           $a = '<span class="badge badge-danger mb-1">Laporan ditolak</span><br>
                <span class="badge badge-warning mb-1">Telah diperbaiki</span>
                <div class="border-1 border-danger p-1">
                  '.$comment.'
                </div>';
        }elseif ($status==4) {
          $a = '<span class="text-success" tooltip="perubahan laporan" flow="left" style="cursor:pointer;"><i class="icon-checkmark-circle2"></i></span><br>
                <span class="text-warning" tooltip="Menunggu verifikasi" flow="left" style="cursor:pointer;"><i class="icon-hour-glass"></i></span>';
        }

        return $a;
    }

    function aksi_status_lkh($id='', $status='', $tgl_allow='', $tgl_lkh='')
    {
        $allow_ya = '';
        if ($tgl_allow) {
            $tgl_allow_ = explode("+",$tgl_allow); 
            foreach ($tgl_allow_ as $v ) {
                    if ($tgl_lkh == $v) {
                         $allow_ya = 1;
                    }
            }   
        }
          
        $a ='';
        if($status==0 && $allow_ya) {
            $datalkh_id_edit = encrypt_url($id,'datalkh_id_edit');
            $a = '<a href="'.base_url('datalkh/lkh/edit/'.$datalkh_id_edit).'" class="edit list-icons-item text-info-400"  tooltip="ubah data" flow="left" style="cursor:pointer;"><i class="icon-pencil5"></i>
                </a>
              <span class="confirm-aksi list-icons-item text-warning-600" tooltip="hapus data" flow="left" msg="Benar ingin hapus data ini?" title="hapus akun" style="cursor:pointer;" id="'.$datalkh_id_edit.'"><i class="icon-bin"></i>
              </span>';
        }elseif ($status==1) {
           $a = '<span class="text-success" tooltip="Selesai" flow="left" style="cursor:pointer;"><i class="icon-checkmark-circle2"></i></span>';
        }elseif ($status==2 && $allow_ya) {
           $datalkh_id_update = encrypt_url($id,'datalkh_id_update');
           $a = '<a href="'.base_url('datalkh/lkh/update/'.$datalkh_id_update).'" class="update list-icons-item text-info-400"  tooltip="perbaiki laporan ditolak" flow="left" style="cursor:pointer;"><i class="icon-pencil5"></i>
                    </a>';
        }elseif ($status==3) {
           $a = '<span class="text-warning" tooltip="telah diperbaiki" flow="left" style="cursor:pointer;"><i class="icon-checkmark-circle2"></i></span>';
        }elseif ($status==4 && $allow_ya) {
           $datalkh_id_edit = encrypt_url($id,'datalkh_id_edit');
           $a = '<a href="'.base_url('datalkh/lkh/edit/'.$datalkh_id_edit).'" class="edit list-icons-item text-info-400"  tooltip="ubah data" flow="left" style="cursor:pointer;" id="'.$id.'"><i class="icon-pencil5"></i>
                </a>';
        }

        return $a;
    }

    function pejabat_ptabel($id='', $nama='', $d='', $b='', $status='')
    {
          $a = '<span tooltip="Verifikasi Otomatis" flow="left"><i class="icon-git-commit msclick cekpejabat" id=""></i></span>';
          if ($id) {
              $a = '<span tooltip="'.nama_gelar($nama, $d, $b).'" flow="left"><i class="icon-user-tie msclick cekpejabat"></i></span>';
          }elseif (!$status || $status == 4) {
              $a = '';
          }

          return $a;
    }

    function cek_dltabel($dl)
    {
          $a = '';
          if ($dl == 3) {
                $a = '<hr class="m-0"><span class="badge badge-info">Dinas Luar</span>';
          }

          return $a;
    }


    function aksi_status_ibadah($id='', $tgl_allow='', $tgl_ibdh='')
    {
        $allow_ya = '';
        if ($tgl_allow) {
            $tgl_allow_ = explode("+",$tgl_allow); 
            foreach ($tgl_allow_ as $v ) {
                    if ($tgl_ibdh == $v) {
                         $allow_ya = 1;
                    }
            }   
        }
          
        $a ='';
        if ($allow_ya) {
               $ibadahmus_id_edit = encrypt_url($id,'ibadahmus_id_edit');
               $a = '<a href="'.base_url('datalkh/worship/edit/'.$ibadahmus_id_edit).'" class="edit list-icons-item text-info-400"  tooltip="ubah data" flow="left" style="cursor:pointer;"><i class="icon-pencil5"></i>
                </a>
              <span class="confirm-aksi list-icons-item text-warning-600" tooltip="hapus data" flow="left" msg="Benar ingin hapus data ini?" title="hapus akun" style="cursor:pointer;" id="'.$ibadahmus_id_edit.'"><i class="icon-bin"></i>
              </span>';
        }
       
        return $a;
    }





 ?>