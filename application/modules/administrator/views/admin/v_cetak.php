<?php
    $pdf = new Tpdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetFont('times', '', 10, '', false);
    $pdf->setHeaderFont(Array('times', 'sikap', 10));
    $pdf->SetTitle('Laporan Data Admin');
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
            LAPORAN DATA ADMIN
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(25);
    $pdf->SetFont('arial', '', 7, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='<table align="left" width="100%">
                <tr>
                    <td width="10%"><b>UNIT KERJA</b></td>
                    <td width="2%">:</td>
                    <td width="80%">'.$instansi->dept_name.'</td>
                </tr>
            </table><br><br>';

    $html .='<table cellpadding="3" border="1" width="100%">
                <tr align="center"> 
                      <td width="5%" ><b>No</b></td>
                      <th width="5%"><b>ID</b></th>
                      <th width="20%"><b>Nama</b></th>
                      <th width="15%"><b>NIP</b></th>
                      <th width="15%"><b>Nama Pengguna</b></th>
                      <th width="15%"><b>Instansi</b></th>
                      <th width="10%"><b>Status</b></th>
                      <th width="15%"><b>Kewenangan</b></th>
                </tr>';
    $no=1;
          foreach ($user as $row) {
                $html .='<tr nobr="true">
                        <td>'.$no++.'</td> 
                        <td>'.$row->key.'</td> 
                        <td>'.name_degree(_name($row->nama),$row->gelar_dpn,$row->gelar_blk).'</td> 
                        <td>'.$row->nip.'</td>
                        <td>'.$row->username.'</td> 
                        <td>'.strtoupper($row->dept_alias).'</td> 
                        <td align="center">'.status_user($row->att_status).'</td> 
                        <td align="center">'.level_alias($row->level).'</td> 
                    </tr>';
        }
    $html .='</table><br><br>';
  
    $pdf->writeHTML($html, true, false, true, false, '');
    //$pdf->Output('Laporan_DataAdmin_'.$instansi->dept_name.'.pdf', 'I');

    $pdfString = $pdf->Output('', 'S');
    $pdfBase64 = base64_encode($pdfString);
?>
<html>
<body style="margin:0!important">
    <embed width="100%" height="100%" src="data:application/pdf;base64,<?php echo $pdfBase64 ?>" type="application/pdf" />
</body>
</html>