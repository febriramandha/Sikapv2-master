<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

	function checked($str='')
	{
		$checked ='';
		if ($str) {
			  $checked = 'checked';
		}

		return $checked;
	}

	function status_lock($status='')
	{
		if ($status == 1) {
				$icon = '<i class="icon-unlocked2 text-success"></i>';
		}else {
				$icon = '<i class="icon-lock2 text-warning"></i>';
		}

		return $icon;
	}

	function sch_name($str='',$str2='')
	{
		$a = '<span class="text-default font-weight-semibold letter-icon-title">
						'.$str.'</span>
					<div class="text-muted font-size-sm">
					<i class="icon-alarm font-size-sm mr-1"></i> 
			  '.$str2.'</div>';

		return $a;
	}

	function checked_sch_shift($id='', $checked='', $disabled='')
	{
		$id = encrypt_url($id,"user_id_schshiftrun_user");
		if ($checked) {
			  $checked = 'class="checkbox" checked value="'.$id.'"';
		}elseif ($disabled) {
			  $checked = 'disabled';
		}else {
			 $checked = 'class="checkbox" value="'.$id.'"';
		}

		return $checked;
	}

	function checked_sch($id='', $checked='', $disabled='')
	{
		$id = encrypt_url($id,"user_id_schrun_user");
		if ($checked) {
			  $checked = 'class="checkbox" checked value="'.$id.'"';
		}elseif ($disabled) {
			  $checked = 'disabled';
		}else {
			 $checked = 'class="checkbox" value="'.$id.'"';
		}

		return $checked;
	}

	function acktion_sch_status($id='',$status='')
	{
		if ($status == 1) {
				$a = '<a href="'.base_url('mngsch/sch-pegawai/edit/'.encrypt_url($id,"schrun_id")).'" class="btn btn-sm badge-info p-1"> <i class="icon-alarm-check"></i> Atur Jadwal</a>
					              </span>';
		}else {
				$a = '<a href="#" class="btn btn-sm badge-success p-1"> <i class="icon-lock2"></i></a>';
		}

		return $a;
	}

	function acktion_schshift_status($id='',$status='')
	{
		if ($status == 1) {
				$a = '<a href="'.base_url('mngsch/schshift-pegawai/edit/'.encrypt_url($id,"schrun_id_shift")).'" class="btn btn-sm badge-info p-1"> <i class="icon-alarm-check"></i> Atur Jadwal</a>
					              </span>';
		}else {
				$a = '<a href="#" class="btn btn-sm badge-success p-1"> <i class="icon-lock2"></i></a>';
		}

		return $a;
	}

	function acktion_schnotfixec_status($id='',$status='')
	{
		if ($status == 1) {
				$a = '<a href="'.base_url('mngsch/schnotfixed-pegawai/edit/'.encrypt_url($id,"schrun_id_notfixed")).'" class="btn btn-sm badge-info p-1"> <i class="icon-alarm-check"></i> Atur Jadwal</a>
					              </span>';
		}else {
				$a = '<a href="#" class="btn btn-sm badge-success p-1"> <i class="icon-lock2"></i></a>';
		}

		return $a;
	}


	