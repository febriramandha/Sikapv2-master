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
            REKAPITULASI LAPORAN KERJA HARIAN
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(25);
    $pdf->SetFont('arial', '', 8, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='<table align="left" width="100%">
                <tr>
                    <td width="10%"><b>UNIT KERJA</b></td>
                    <td width="2%">:</td>
                    <td width="88%">'.$datainstansi->dept_name.'</td>
                </tr>
                <tr>
                    <td width="10%"><b>PERIODE</b></td>
                    <td width="2%">:</td>
                    <td width="88%">'.$priode.'</td>
                </tr>
            </table><br><br>';
    $pdf->writeHTML($html, true, false, true, false, '');
     // tabel 
    $tbl ='  
        <table cellpadding="2.5" border="1" width="100%" >
           <thead>
            <tr align="center"> 
                  <td width="5%"  ><b>No</b></td>
                  <td width="50%" ><b>Nama(NIP)</b></td>
                  <td width="15%"><b>Jumlah Hari Kerja</b></td>
                  <td width="15%"><b>Jumlah Laporan Kerja Harian</b></td>
                  <td width="15%"><b>Total Laporan</b></td>
            </tr>
            </thead>';
$no=1;foreach ($pegawai_lkh as $row) {
            $tbl .='<tr nobr="true">
                        <td width="5%">'.$no++.'</td>
                        <td width="50%">'.nama_gelar($row->nama, $row->gelar_dpn, $row->gelar_blk).' ('.$row->nip.')</td>
                        <td width="15%" align="center">'.jum_hari_kerja_rekap_lkh($row->json_jadwal_lkh).'</td>
                        <td width="15%" align="center">'.jum_data_kerja_rekap_lkh($row->json_jadwal_lkh, $row->jumlah_laporan).'</td>
                        <td width="15%" align="center">'.total_jum_lkh_rekap($row->json_jadwal_lkh, $row->total_laporan).'</td>';     
            $tbl .='</tr>';
        }
          
    $tbl .='</table><br><br>';
    $pdf->writeHTML($tbl, true, false, true, false, '');

    // ttd
     $ttd ='
            <table width="100%" nobr="true" align="center">
                <tr>
                    <td width="55%"></td> 
                    <td width="45%"><b>'.$datainstansi->kecamatan.', '.tgl_ind_bulan(date('Y-m-d')).'</b><br></td>
                </tr>';
      $ttd .='<tr>
                    <td width="45%">'.$datainstansi_kepala->jabatan.'<br><br><br><br></td> 
                    <td width="10%"></td>
                    <td width="45%">'.$datainstansi->jabatan.'<br><br><br><br></td>
              </tr>';

      $ttd .= '<tr>
                    <td width="45%"><b><u>'.nama_gelar($datainstansi_kepala->nama, $datainstansi_kepala->gelar_dpn, $datainstansi_kepala->gelar_blk).'</u>
                            </b><br>
                            <b>'.$datainstansi_kepala->pangkat.'</b><br>
                            <b>NIP. '.konversi_nip($datainstansi_kepala->nip).'</b>
                    </td> 
                    <td width="10%"></td>
                    <td width="45%"><b><u>'.nama_gelar($datainstansi->nama, $datainstansi->gelar_dpn, $datainstansi->gelar_blk).'</u>
                        </b><br>
                        <b>'.$datainstansi->pangkat.'</b><br>
                        <b>NIP. '.konversi_nip($datainstansi->nip).'</b>
                    </td>
                </tr>';              
    $ttd .='</table>';
    $pdf->writeHTML($ttd, true, false, true, false, '');
  

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
         <a class="btn btn-sm btn-info m-2" href="data:application/pdf;base64,<?php echo $pdfBase64 ?>" download="FileLaporanLKHPeriode_<?php echo $priode ?>.pdf">Download</a>
    </div>
    <embed width="100%" height="100%" src="data:application/pdf;base64,<?php echo $pdfBase64 ?>" type="application/pdf" />
</body>
</html>