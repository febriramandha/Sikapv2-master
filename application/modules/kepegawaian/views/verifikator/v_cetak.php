<?php
    $pdf = new Tpdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetFont('arial', '', 10, '', false);
    $pdf->setHeaderFont(Array('times', 'sikap', 10));
    $pdf->SetTitle('Laporan Data Pegawai');
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
            LAPORAN DATA VERIFIKATOR PEGAWAI
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(25);
    $pdf->SetFont('arial', '', 8, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, ''); 

    $html ='<table align="left" width="100%">
                <tr>
                    <td width="8%"><b>INSTANSI</b></td>
                    <td width="2%">:</td>
                    <td width="80%">'.$instansi->dept_name.'</td>
                </tr>
            </table><br><br>';

    $html .='<table cellpadding="3" border="1" width="100%">
                <tr align="center"> 
                      <td width="5%" ><b>No</b></td>
                      <th width="47.5%"><b>Nama(NIP)</b></th>
                      <th width="47.5%"><b>Verifikator</b></th>
                </tr>';
    $no=1;
          foreach ($user as $row) {
                $html .='<tr nobr="true">
                        <td align="center">'.$no++.'</td> 
                        <td>'.nama_gelar($row->nama,$row->gelar_dpn,$row->gelar_blk).' ('.$row->nip.')</td> 
                        <td>'.nama_gelar($row->ver_nama,$row->ver_gelar_dpn,$row->ver_gelar_blk).' ('.$row->ver_nip.')</td> 
                    </tr>';
        }
    $html .='</table><br><br>';
  
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('Laporan_DataVerifikator_'.$instansi->dept_name.'.pdf', 'I');

?>