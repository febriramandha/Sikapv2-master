<?php
    $pdf = new Tpdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetFont('arial', '', 10, '', false);
    $pdf->setHeaderFont(Array('arial', 'sikap', 10));
    $pdf->SetTitle('Laporan kerja harian');
    //$pdf->SetTopMargin(10);
    $pdf->setFooterMargin(20);
    $pdf->SetAutoPageBreak(true, 22);
    $pdf->SetAuthor('Author');
    $pdf->SetDisplayMode('real', 'default');
    $pdf->SetMargins(7, 10, 7);
    
    $pdf->AddPage('L','A4');
    $pdf->SetFont('arial', '', 12);
    $pdf->SetY(15);
    $txt = <<<EOD
            LAPORAN KEHADIRAN PEGAWAI PER PERIODE
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(25);
    $pdf->SetFont('arial', '', 6, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='<table align="left" width="100%">
                <tr>
                    <td width="6%"><b>UNIT KERJA</b></td>
                    <td width="2%">:</td>
                    <td>'.$datainstansi->dept_name.'</td>
                </tr>
                <tr>
                    <td width="6%"><b>PERIODE</b></td>
                    <td width="2%">:</td>
                    <td>'.$priode.'</td>
                </tr>
            </table><br><br>';
    $pdf->writeHTML($html, true, false, true, false, '');
     // tabel 
    $tbl ='  
        <table cellpadding="2.5" border="1" width="100%" >
           <thead>
            <tr align="center"> 
                  <td width="2.5%" rowspan="2"><br><br><b>No</b></td>
                  <th width="15%" rowspan="2" ><br><br><b>Nama(NIP)</b></th>';
    for ($i=0; $i < 31; $i++) { 
          $tbl .='<th width="2.657%"><b>'.tanggal_format(tgl_plus($rank1, $i),'d').'/'.tanggal_format(tgl_plus($rank1, $i),'m').'</b></th>';
    }
     $tbl .='</tr>
     <tr align="center">';
            for ($i=0; $i < 31; $i++) { 
                  $tbl .='<th width="2.657%"><b>'.substr(hari_tgl(tgl_plus($rank1, $i)), 0,1).'</b></th>';
            }
        $tbl .='</tr>
            </thead>';
        $no = 1;
        // var_dump($pegawai_absen);
        foreach ($pegawai_absen as $row) {
            $pgarray_data = json_decode($row->json_absen, true);
            $json_absen  = $pgarray_data['data_absen'];
            $tbl .='<tr nobr="true">
                        <td width="2.5%" align="center">'.$no++.'</td> 
                        <td width="15%"><b>'.nama_gelar($row->nama, $row->gelar_dpn, $row->gelar_blk).' '.cekNipValid($row->nip).'</b></td>';
                $count = count($json_absen);
                for ($i=0; $i < 31; $i++) { 
                    
                    $jam_masuk_tabel    = '-';
                    $jam_pulang_tabel   = '-';
                    $absen_ket_tabel    = '-';
                    $absen_ket_apel     = "-";
                    if ($i < $jum_hari+1) {
                        //jam masuk
                        $jam_masuk          = $json_absen[$i]['f7'];
                        $jam_masuk_shift    = $json_absen[$i]['f12'];
                        $status_in          = $json_absen[$i]['f17'];
                        $start_time_notfixed= $json_absen[$i]['f20'];
                        $jam_masuk_notfixed = $json_absen[$i]['f22'];
                        
                        $jam_masuk_tabel = jam_masuk_tabel($jam_masuk, $jam_masuk_shift,$status_in,$start_time_notfixed, $jam_masuk_notfixed);

                        //jam pulang
                        $jam_pulang         = $json_absen[$i]['f8'];
                        $jam_pulang_shift   = $json_absen[$i]['f13'];
                        $status_out         = $json_absen[$i]['f18'];
                        $end_time_notfixed  = $json_absen[$i]['f21'];
                        $jam_pulang_notfixed= $json_absen[$i]['f23'];
                        $jam_pulang_tabel = jam_pulang_tabel($jam_pulang, $jam_pulang_shift, $status_out);

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

                        $hadir_apel = $json_absen[$i]['f24'];
                        $dept_apel = array_to_pg($json_absen[$i]['f25']);
                        $dept_id_users = $json_absen[$i]['f26'];
                        $users_id_piket_apel = array_to_pg($json_absen[$i]['f27']);
                        $jam_absen_apel = $json_absen[$i]['f29'];

                        
                        $absen_ket_tabel = absen_ket_tabel($daysoff_id, $jam_masuk, $jam_pulang, $jam_masuk_shift, $jam_pulang_shift, $lkhdl_id, $dinasmanual_id, $kode_cuti, $rentan_tanggal, $start_time, $start_time_shift, $status_in, $status_out, $end_time, $end_time_shift,$start_time_notfixed, $jam_masuk_notfixed, $end_time_notfixed, $jam_pulang_notfixed);
                        
                        $absen_ket_apel = ket_apel($hadir_apel,$dept_apel,$dept_id_users,$users_id_piket_apel,$row->id,$jam_absen_apel);
                    }
                    

                 $tbl .='<td width="2.657%">'.$absen_ket_tabel.'<br>'.$jam_masuk_tabel.'<br>'.$jam_pulang_tabel.'</td>'; 
                    }      
             $tbl .='</tr>';
        }

          
    $tbl .='</table><br><br>';
    $pdf->writeHTML($tbl, true, false, true, false, '');

    $ttd ='<div align="center">
            <table width="100%">
                <tr nobr="true">
                    <td width="70%" align="left"><b>Ket :</b>  <br><br>- H : Hadir Normal - TM : Telat Masuk - PC : Pulang Cepat - TC : Telat Masuk Pulang Cepat - C* : Cuti - DL : Dinas Luar - A* : Apel - TA* : Tidak Apel
                    <br>- *M : * Manual
                    <br>- TK : Tanpa Keterangan
                    <br>- L : Hari Libur Kerja       
                    <br>- P : Piket, Tidak Mengikuti Apel               
                    </td> 
                    <td width="30%"><b>'.$datainstansi->kecamatan.', '.tgl_ind_bulan(date('Y-m-d')).'</b><br><br>
                        '.$datainstansi->jabatan.'
                        <br><br><br><br>
                        <br><b><u>'.nama_gelar($datainstansi->nama, $datainstansi->gelar_dpn, $datainstansi->gelar_blk).'</u></b>
                        <br><b>'.$datainstansi->pangkat.'</b>
                        <br><b>NIP. '.konversi_nip($datainstansi->nip).'</b>
                        </td>
                </tr>';
              
                  
    $ttd .='</table>';
    $pdf->writeHTML($ttd, true, false, false, false, '');
  

    //$pdf->Output('LaporanAbsensi_'.$priode.'.pdf', 'I');
     $pdfString = $pdf->Output('', 'S');
     $pdfBase64 = base64_encode($pdfString);
?>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="<?php echo base_url() ?>public/themes/material/css/bootstrap.css" rel="stylesheet" type="text/css">
</head>

<body style="margin:0!important">
    <div class="d-lg-none">
        <a class="btn btn-sm btn-info m-2" href="data:application/pdf;base64,<?php echo $pdfBase64 ?>"
            download="FileLaporanKehadiranPeriode_<?php echo $priode ?>.pdf">Download</a>
    </div>
    <embed width="100%" height="100%" src="data:application/pdf;base64,<?php echo $pdfBase64 ?>"
        type="application/pdf" />
</body>

</html>