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
            JADWAL SHIFT PEGAWAI PER PERIODE
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
                    <td>'.$sch_run->dept_name.'</td>
                </tr>
                <tr>
                    <td width="6%"><b>PERIODE</b></td>
                    <td width="2%">:</td>
                    <td>'.format_tgl_ind($sch_run->start_date).'-'.format_tgl_ind($sch_run->end_date).'</td>
                </tr>
            </table><br><br>';
    $pdf->writeHTML($html, true, false, true, false, '');
     // tabel

    $jum_tanggal = jumlah_hari_rank($sch_run->start_date, $sch_run->end_date);
    $schrun_id = encrypt_url($sch_run->id,'schrun_id_shift');

    $tbl ='  
        <table cellpadding="2.5" border="1" width="100%" >
           <thead>
            <tr align="center"> 
                  <td width="2.5%" rowspan="2"><br><br><b>No</b></td>
                  <th width="15%" rowspan="2"><br><br><b>Nama(NIP)</b></th>';
           for ($i=0; $i < $jum_tanggal+1; $i++) { 
          $tbl .='<th width="2.658%"><b>'.tanggal_format(tgl_plus($sch_run->start_date, $i),'d').'/'.tanggal_format(tgl_plus($sch_run->start_date, $i),'m').'</b></th>';
            }
     $tbl .='</tr>
     <tr align="center">';
            for ($i=0; $i < $jum_tanggal+1; $i++) { 
                  $tbl .='<th width="2.657%"><b>'.substr(hari_tgl(tgl_plus($sch_run->start_date, $i)), 0,1).'</b></th>';
            }
        $tbl .='</tr>
            </thead>'; 
$ir=0; $no=1; foreach ($user as $row ) {
          $id = encrypt_url($row->id,"user_id_shift");
          $kd_shift = pg_to_array($row->kd_shift);
          $kode = $row->kd_shift;
         $tbl .='<tr nobr="true">
                        <td width="2.5%" align="center">'.$no++.'</td> 
                        <td width="15%"><b>'.nama_gelar($row->nama, $row->gelar_dpn, $row->gelar_blk).' ('.$row->nip.')</b></td>';
            for ($i=0; $i < $jum_tanggal+1; $i++) {
                if ($kode == null) {
                  $shift_kode=  '-';
                }else {
                  $shift_kode = $kd_shift[$i];
                }

                $tbl .='<td width="2.657%">'.$shift_kode.'</td>';

            }
            $tbl .='</tr>';
        }

    $tbl .='</table><br><br>';
    $pdf->writeHTML($tbl, true, false, true, false, '');
  

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
         <a class="btn btn-sm btn-info m-2" href="data:application/pdf;base64,<?php echo $pdfBase64 ?>" download="FileLaporanKehadiranPeriode_.pdf">Download</a>
    </div>
    <embed width="100%" height="100%" src="data:application/pdf;base64,<?php echo $pdfBase64 ?>" type="application/pdf" />
</body>
</html>