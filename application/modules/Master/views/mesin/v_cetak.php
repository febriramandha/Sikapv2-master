<?php
    $pdf = new Tpdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetFont('times', '', 10, '', false);
    $pdf->setHeaderFont(Array('times', 'sikap', 10));
    $pdf->SetTitle('Laporan Data Instansi');
    //$pdf->SetTopMargin(10);
    $pdf->setFooterMargin(20);
    $pdf->SetAutoPageBreak(true, 22);
    $pdf->SetAuthor('Author');
    $pdf->SetDisplayMode('real', 'default');
    $pdf->SetMargins(7, 10, 7);
    
    $pdf->AddPage();
    $pdf->SetFont('times', 'B', 12);
    $pdf->SetY(20);
    $txt = <<<EOD
            LAPORAN DATA MESIN
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(30);
    $pdf->SetFont('times', '', 8, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='';

    $html .='<table cellpadding="3" border="1" width="100%">
                <tr align="center"> 
                      <td width="5%" ><b>No</b></td>
                      <th width="20%"><b>NAMA MESIN</b></th>
                      <th width="10%"><b>NO MESIN</b></th>
                      <th width="15%"><b>IP</b></th>
                      <th width="50%"><b>INSTANSI</b></th>
                </tr>';
    $no=1;
          foreach ($mesin as $row) {
                $html .='<tr nobr="true">
                        <td align="center">'.$no++.'</td> 
                        <td>'.$row->name.'</td>
                        <td align="center">'.$row->machine_number.'</td>
                        <td>'.$row->ip.'</td>
                        <td>'.$row->dept_name.'</td>
                    </tr>';
        }
    $html .='</table><br><br>';
  
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('Laporan_DataMesin_.pdf', 'I');

?>