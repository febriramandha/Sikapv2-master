<?php
    $pdf = new Tpdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetFont('arial', '', 10, '', false);
    $pdf->setHeaderFont(Array('times', 'sikap', 10));
    $pdf->SetTitle('Laporan Data Pengguna');
    //$pdf->SetTopMargin(10);
    $pdf->setFooterMargin(20);
    $pdf->SetAutoPageBreak(true, 22);
    $pdf->SetAuthor('Author');
    $pdf->SetDisplayMode('real', 'default');
    $pdf->SetMargins(7, 10, 7);
    
    $pdf->AddPage();
    $pdf->SetFont('arial', '', 12);
    $pdf->SetY(15);
    $txt = <<<EOD
            LAPORAN KEHADIRAN UPACARA
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(25);
    $pdf->SetFont('arial', '', 8, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='<table align="left" width="100%">
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
                    <td width="10%"><b>UNIT KERJA</b></td>
                    <td width="2%">:</td>
                    <td width="80%">'.$user->dept_name.'</td>
                </tr>
                <tr>
                    <td width="10%"><b>PERIODE</b></td>
                    <td width="2%">:</td>
                    <td width="89%">'.$priode.'</td>
                </tr>
            </table><br><br>';

    $html .='<table cellpadding="3" border="1" width="100%">
                <tr align="center"> 
                      <td width="5%" rowspan="2"><b><br>No</b></td>
                      <th width="47%" rowspan="2"><b><br>Berita Acara</b></th>
                      <th width="18%" rowspan="2"><b><br>Tanggal (Jam)</b></th>
                      <th width="21%" colspan="3"><b>Absen</b></th>
                      <th width="9%" rowspan="2"><b><br>Ket</b></th>
                </tr>
                <tr>
                        <th  width="7%" >Hadir (H)</th>
                        <th  width="7%" >Tidak Hadir (A)</th>
                        <th  width="7%" >Cuti (C)</th>
                </tr>';
    $no=1;
          foreach ($data_upcara as $row) {
                $hadir = $row->hadir;
                $h = '';
                $t = '';
                $c = '';
                if ($hadir == 1) {
                    $h = '1';
                }elseif ($hadir == 2) {
                    $t = '1';
                }elseif ($hadir == 3) {
                    $c = '1';
                }

                $html .='<tr nobr="true">
                        <td align="center">'.$no++.'</td> 
                        <td>'.$row->ket.'</td> 
                        <td>'.tglInd_hrtabel($row->tanggal).'('.jm($row->jam_mulai).')</td> 
                        <td align="center">'.$h.'</td> 
                        <td align="center">'.$t.'</td> 
                        <td align="center">'.$c.'</td> 
                        <td align="center">'.upacara_ket($hadir).'</td> 
                    </tr>';
        }
    $html .='</table><br><br>';
  
    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->IncludeJS("print();");
    $pdf->lastPage();

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
         <a class="btn btn-sm btn-info m-2" href="data:application/pdf;base64,<?php echo $pdfBase64 ?>" download="FileLaporan_DataAbsenUpacara__<?php echo $user->nip ?>.pdf">Download</a>
    </div>
    <embed width="100%" height="100%" src="data:application/pdf;base64,<?php echo $pdfBase64 ?>" type="application/pdf" />
</body>
</html>