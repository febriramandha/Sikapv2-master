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
                    <td width="9%"><b>Unit Kerja</b></td>
                    <td width="2%">:</td>
                    <td width="89%">'.$instansi->dept_name.'</td>
                </tr>
                <tr>
                    <td width="9%"><b>Hari/Tanggal</b></td>
                    <td width="2%">:</td>
                    <td width="89%">'.tgl_ind_hari($jadwal->tanggal).' '.jm($jadwal->jam_mulai).'</td>
                </tr>
                <tr>
                    <td width="9%"><b>Acara</b></td>
                    <td width="2%">:</td>
                    <td width="89%">'.$jadwal->ket.'</td>
                </tr>
            </table><br><br>';

    $html .='<table cellpadding="3" border="1" width="100%">
                <tr align="center"> 
                      <td width="5%" rowspan="2"><b><br>No</b></td>
                      <th width="25%" rowspan="2"><b><br>Nama</b></th>
                      <th width="18%" rowspan="2"><b><br>NIP</b></th>
                      <th width="22%" rowspan="2"><b><br>Pangkat/Gol</b></th>
                      <th width="21%" colspan="3"><b>Absen</b></th>
                      <th width="9%" rowspan="2"><b><br>Ket</b></th>
                </tr>
                <tr>
                        <th  width="7%" >Hadir (H)</th>
                        <th  width="7%" >Tidak Hadir (A)</th>
                        <th  width="7%" >Cuti (C)</th>
                </tr>';
    $no=1;
          foreach ($user_upacara as $row) {
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
                        <td>'.nama_gelar($row->nama,$row->gelar_dpn,$row->gelar_blk).'</td> 
                        <td>'.$row->nip.'</td> 
                        <td>'.$row->pangkat.'/'.$row->golongan.'</td> 
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


    $pdf->Output('Laporan_DataAbsenUpacara_'.$instansi->dept_name.'.pdf', 'I');


?>