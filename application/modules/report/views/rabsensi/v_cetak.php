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
            LAPORAN ABSENSI PEGAWAI
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(25);
    $pdf->SetFont('arial', '', 6, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='<table align="left" width="100%">
                <tr>
                    <td width="4%"><b>Instansi</b></td>
                    <td width="2%">:</td>
                    <td>D</td>
                </tr>
                <tr>
                    <td width="4%"><b>Priode</b></td>
                    <td width="2%">:</td>
                    <td>'.$priode.'</td>
                </tr>
            </table><br><br>';
    $pdf->writeHTML($html, true, false, true, false, '');
     // tabel 
    $tbl ='  
        <table cellpadding="2.5" border="1" width="100%" >
            <tr align="center"> 
                  <td width="2.5%" rowspan="2"><br><br><b>No</b></td>
                  <th width="12%" rowspan="2" ><br><br><b>Nama(NIP)</b></th>';
    for ($i=0; $i < 32; $i++) { 
          $tbl .='<th width="2.657%"><b>'.tanggal_format(tgl_plus($rank1, $i),'d').'/'.tanggal_format(tgl_plus($rank1, $i),'m').'</b></th>';
    }
     $tbl .='</tr>
     <tr align="center">';
            for ($i=0; $i < 32; $i++) { 
                  $tbl .='<th width="2.657%"><b>'.substr(hari_tgl(tgl_plus($rank1, $i)), 0,1).'</b></th>';
            }
        $tbl .='</tr>
            </thead>';

        $tbl .='<tr>
                <td align="center">1</td> 
                <td><b>Fauzan Helmy Hutasuhut, AP, S.Sos, MAP (12312324324234)</b></td>';
            for ($i=0; $i < 32; $i++) {  
         $tbl .='
                <td>TL<br>07:16<br>16:16</td>'; 
            }      
     $tbl .='</tr>';

          
    $tbl .='</table><br><br>';
    $pdf->writeHTML($tbl, true, false, true, false, '');
  

    //$pdf->Output('LaporanAbsensi_'.$priode.'.pdf', 'I');
     $pdfString = $pdf->Output('LaporanAbsensi_'.$priode.'.pdf', 'S');
     $pdfBase64 = base64_encode($pdfString);
?>
<html>
<body style="margin:0!important">
    <embed width="100%" height="100%" src="data:application/pdf;base64,<?php echo $pdfBase64 ?>" type="application/pdf" />
</body>
</html>