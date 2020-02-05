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
            LAPORAN LEMBUR PEGAWAI
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(25);
    $pdf->SetFont('arial', '', 9, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->SetMargins(20, 10, 20);
    $html ='';
    $pdf->writeHTML($html, true, false, true, false, '');
     // tabel 
    $pdf->SetMargins(20, 10, 20);
    $tbl ='<table width="100%">
                <tr>
                    <td width="12%"><b> UNIT KERJA</b></td>
                    <td width="2%">:</td>
                    <td width="80%"> '.$datainstansi->dept_name.'</td>
                </tr>
                <tr>
                    <td width="12%"><b> PERIODE</b></td>
                    <td width="2%">:</td>
                    <td width="80%"> '.$priode.'</td>
                </tr>
            </table><br><br>  
        <table cellpadding="2.5" border="1" width="100%" >
                <tr align="center"> 
                      <td width="5%"><b>No</b></td>
                      <th width="45%"><b>Nama (NIP)</b></th>
                      <th width="20%"><b>Tanggal</b></th>
                      <th width="10%"><b>Jam Masuk</b></th>
                      <th width="10%"><b>Jam Pulang</b></th>
                      <th width="10%"><b>Jumlah</b></th>
                </tr>';
        $arrayForTable = [];
        foreach ($pegawai_absen as $v) {
            $temp = [];
            $temp['tanggal']             = tglInd_hrtabel($v->tanggal);
            $temp['jam_masuk_tabel']     = jm($v->jam_masuk);
            $temp['jam_pulang_tabel']    = jm($v->jam_pulang);
            $temp['jumlah']              = jumlah_lembur($v->jam_masuk,$v->jam_pulang,$v->start_time, $v->end_time,$v->daysoff_id,$v->start_time_shift, $v->end_time_shift);

            if(!isset($arrayForTable[$v->nama])){
                $arrayForTable[$v->nama] = [];
            }
                $arrayForTable[nama_gelar($v->nama, $v->gelar_dpn, $v->gelar_blk).' ('.$v->nip.')'][] = $temp;

        }

         $no=1; foreach ($arrayForTable as $id => $values) {
            foreach ($values as $key=> $value) {

            $tbl .='<tr nobr="true">';
            if($key == 0) :
                $tbl .='<td align="center" rowspan="'.count($values).'">'.$no++.'</td>
                        <td rowspan="'.count($values).'">'.$id.'</td>';
              endif;
                     $tbl .='
                        <td>'.$value['tanggal'].'</td> 
                        <td align="center">'.$value['jam_masuk_tabel'].'</td> 
                        <td align="center">'.$value['jam_pulang_tabel'].'</td> 
                        <td align="center">'.$value['jumlah'].'</td>  
                    </tr>';
            }
        }

          
    $tbl .='</table><br><br>';
    $pdf->writeHTML($tbl, true, false, true, false, '');

    $ttd ='<div align="center">
            <table width="100%">
                <tr nobr="true">
                    <td width="60%" align="left">
                    </td> 
                    <td width="40%"><b>'.$datainstansi->kecamatan.', '.tgl_ind_bulan(date('Y-m-d')).'</b><br>
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
         <a class="btn btn-sm btn-info m-2" href="data:application/pdf;base64,<?php echo $pdfBase64 ?>" download="FileLaporanKehadiranPeriode_<?php echo $priode ?>">Download</a>
    </div>
    <embed width="100%" height="100%" src="data:application/pdf;base64,<?php echo $pdfBase64 ?>" type="application/pdf" />
</body>
</html>