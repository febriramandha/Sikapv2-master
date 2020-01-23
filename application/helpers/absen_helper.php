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
