<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/

	function hitung_total_jam($data_jam='')
	{
		// $array_jam = array(
		//     array('masuk' => '07:00:00', 'keluar' => '08:30:00',),
		//     array('masuk' => '08:30:00', 'keluar' => '09:30:00'),
		//     array('masuk' => '09:30:00', 'keluar' => '10:30:00'),
		// );

		$total = 0;
		foreach ($data_jam as $item_jam) {
		    $keluar = strtotime($item_jam['keluar']);
		    $masuk = strtotime($item_jam['masuk']);
		    $total += $keluar - $masuk;
		}

		return gmdate('H.i', $total); 
		  
	}