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

    $html ='<table align="left" width="100%">
                <tr>
                    <td width="10%"><b>INSTANSI</b></td>
                    <td width="2%">:</td>
                    <td>'.$datainstansi->dept_name.'</td>
                </tr>
                <tr>
                    <td width="10%"><b>PERIODE</b></td>
                    <td width="2%">:</td>
                    <td>'.$priode.'</td>
                </tr>
            </table><br><br>';
    $pdf->writeHTML($html, true, false, true, false, '');
     // tabel 
    $tbl ='  
        <table cellpadding="2.5" border="1" width="100%" >
                <tr align="center"> 
                      <td width="5%"><b>No</b></td>
                      <th width="50%"><b>Nama(NIP)</b></th>
                      <th width="15%">Tanggal</th>
                      <th width="10%">Jam Masuk</th>
                      <th width="10%">Jam Pulang</th>
                      <th width="10%">Jumlah</th>
                </tr>';
        
        $tbl .='<tr nobr="true">
                    <td align="center">1</td> 
                    <td><b>Fauzan Helmy Hutasuhut, AP, S.Sos, MAP (12312324324234)</b></td>
                    <td>Rab, 07 jan 2020</td> 
                    <td>17:00</td>
                    <td>17:00</td>
                    <td>17:00</td>
                </tr>';

          
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
<body style="margin:0!important">
    <embed width="100%" height="100%" src="data:application/pdf;base64,<?php echo $pdfBase64 ?>" type="application/pdf" />
</body>
</html>