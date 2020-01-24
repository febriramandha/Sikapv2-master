<?php
    $pdf = new Tpdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetFont('times', '', 10, '', false);
    $pdf->setHeaderFont(Array('times', 'sikap', 10));
    $pdf->SetTitle('Laporan kerja harian');
    //$pdf->SetTopMargin(10);
    $pdf->setFooterMargin(20);
    $pdf->SetAutoPageBreak(true, 22);
    $pdf->SetAuthor('Author');
    $pdf->SetDisplayMode('real', 'default');
    $pdf->SetMargins(7, 10, 7);
    
    $pdf->AddPage('L','A4');
    $pdf->SetFont('times', 'B', 12);
    $pdf->SetY(20);
    $txt = <<<EOD
            LAPORAN ABSENSI PEGAWAI
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(30);
    $pdf->SetFont('times', '', 6, '', false);
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
        
        <table cellpadding="3" border="1" width="100%" >
            <tr align="center"> 
                  <td width="3%" rowspan="3"><br><br><b>No</b></td>
                  <th width="12%" rowspan="3" ><br><br><b>Nama(NIP)</b></th>
                  <th width="85%" rowspan="1" colspan="31"><b>'.$priode.'</b></th>
            </tr>
            <tr align="center">';
            for ($i=0; $i < 32; $i++) { 
                  $tbl .='<th width="2.655%"><b>'.tanggal_format(tgl_plus($rank1, $i),'d').'</b></th>';
            }
     $tbl .='</tr>
     <tr align="center">';
            for ($i=0; $i < 32; $i++) { 
                  $tbl .='<th width="2.655%"><b>'.substr(hari_tgl(tgl_plus($rank1, $i)), 0,1).'</b></th>';
            }
     $tbl .='</tr>
            <thead cellpadding="2">
                <tr align="center" >
                    <td width="3%">1</td>
                    <td width="12%">2</td>';
            $noa = 3; 
            for ($i=0; $i < 32; $i++) {        
             $tbl .='<td width="2.655%">'.$noa++.'</td>';
            }
        $tbl .='</tr>
            </thead>';

        $tbl .='<tr>
                <td align="center">1</td> 
                <td>Fauzan Helmy Hutasuhut, AP, S.Sos, MAP (12312324324234)</td>';
            for ($i=0; $i < 32; $i++) {  
         $tbl .='
                <td>TL<br>07:16<br>16:16</td>'; 
            }      
     $tbl .='</tr>';

          
    $tbl .='</table><br><br>';
    $pdf->writeHTML($tbl, true, false, true, false, '');
  

    $pdf->Output('LaporanAbsensi_'.$priode.'.pdf', 'I');

?>