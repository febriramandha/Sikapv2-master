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
    
    $pdf->AddPage('P','A4');
    $pdf->SetFont('arial', '', 12);
    $pdf->SetY(15);
    $txt = <<<EOD
            LAPORAN KEHADIRAN PEGAWAI
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(25);
    $pdf->SetFont('arial', '', 8, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');
    $html ='<table width="100%">
                <tr>
                    <td width="10%"><b>NAMA</b></td>
                    <td width="2%">:</td>
                    <td width="80%">'.nama_gelar($user->nama, $user->gelar_dpn, $user->gelar_blk).'</td>
                </tr>
                <tr>
                    <td width="10%"><b>NIP</b></td>
                    <td width="2%">:</td>
                    <td width="80%">'.$user->nip.'</td>
                </tr>
                <tr>
                    <td width="10%"><b>INSTANSI</b></td>
                    <td width="2%">:</td>
                    <td width="80%">'.$user->dept_name.'</td>
                </tr>
                <tr>
                    <td width="10%"><b>PERIODE</b></td>
                    <td width="2%">:</td>
                    <td width="80%">'.$priode.'</td>
                </tr>
            </table><br><br>';
    $pdf->writeHTML($html, true, false, true, false, '');
     // tabel 
    $tbl ='  
        <table cellpadding="2.5" border="1" width="100%" >
                <tr align="center"> 
                    <th width="5%" rowspan="2"><b>No</b></th>
                    <th width="15%" rowspan="2"><b>Tanggal</b></th>
                    <th width="25%" colspan="3"><b>Masuk</b></th>
                    <th width="25%" colspan="3"><b>Pulang</b></th>
                    <th width="10%" rowspan="2"><b>DL</b></th>
                    <th width="10%" rowspan="2"><b>Cuti</b></th>
                    <th width="10%" rowspan="2"><b>Ket</b></th>
                </tr>
                <tr>
                    <th width="8.33%" align="center"><b>Jam Masuk</b></th>
                    <th width="8.33%" align="center"><b>Masuk Kerja</b></th>
                    <th width="8.33%"><b>Terlambat</b></th>

                    <th width="8.33%" align="center"><b>Jam Pulang</b></th>
                    <th width="8.33%" align="center"><b>Pulang Kerja</b></th>
                    <th width="8.33%" align="center"><b>Pulang Cepat</b></th>
                </tr>';
 $no =1; foreach ($data_absen as $row) {
            $tbl .='<tr nobr="true">
                        <td>'.$no++.'</td> 
                        <td>'.tglInd_hrtabel($row->rentan_tanggal).'</td> 
                        <td align="center">'.start_time_tabel($row->start_time, $row->start_time_shift, $row->start_time_notfixed).'</td> 
                        <td align="center">'.jam_masuk_tabel($row->jam_masuk, $row->jam_masuk_shift, $row->status_in, $row->start_time_notfixed, $row->jam_masuk_notfixed).'</td> 
                        <td align="center">'.terlambat_tabel($row->start_time, $row->start_time_shift, $row->jam_masuk, $row->jam_masuk_shift, $row->status_in, $row->start_time_notfixed, $row->jam_masuk_notfixed).'</td> 

                        <td align="center">'.start_time_tabel($row->end_time, $row->end_time_shift, $row->end_time_notfixed).'</td>
                        <td align="center">'.jam_pulang_tabel($row->jam_pulang, $row->jam_pulang_shift, $row->status_out, $row->end_time_notfixed, $row->jam_pulang_notfixed).'</td> 
                        <td align="center">'.pulang_cepat_tabel($row->end_time, $row->end_time_shift, $row->jam_pulang, $row->jam_pulang_shift, $row->status_out, $row->end_time_notfixed, $row->jam_pulang_notfixed).'</td> 
                        <td align="center">'.dinas_luar_tabel($row->lkhdl_id, $row->dinasmanual_id).'</td>
                        <td align="center">'.$row->kode_cuti.'</td> 
                        <td align="center">'.absen_ket_tabel($row->daysoff_id, $row->jam_masuk, $row->jam_pulang, $row->jam_masuk_shift, $row->jam_pulang_shift, $row->lkhdl_id, $row->dinasmanual_id, $row->kode_cuti, $row->rentan_tanggal, $row->start_time, $row->start_time_shift, $row->status_in, $row->status_out,$row->end_time, $row->end_time_shift, $row->start_time_notfixed, $row->jam_masuk_notfixed, $row->end_time_notfixed, $row->jam_pulang_notfixed).'</td>   
                    </tr>';
        }

          
    $tbl .='</table><br><br>';
    $pdf->writeHTML($tbl, true, false, true, false, '');

     $ttd ='<div align="center">
            <table width="100%">
                <tr nobr="true">
                    <td width="70%" align="left"><b>Ket :</b>  <br>- H : Hadir Normal - TM : Telat Masuk - PC : Pulang Cepat - TC : Telat Masuk Pulang Cepat - C* : Cuti
                    <br>- DL : Dinas Luar
                    <br>- *M : * Manual - F : Jadwal Tidak Tetap
                    <br>- TK : Tanpa Keterangan
                    <br>- L : Hari Libur Kerja              
                    </td> 
                    <td width="30%">
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
         <a class="btn btn-sm btn-info m-2" href="data:application/pdf;base64,<?php echo $pdfBase64 ?>" download="FileLaporanKehadiranPeriode_<?php echo $priode ?>.pdf">Download</a>
    </div>
    <embed width="100%" height="100%" src="data:application/pdf;base64,<?php echo $pdfBase64 ?>" type="application/pdf" />
</body>
</html>