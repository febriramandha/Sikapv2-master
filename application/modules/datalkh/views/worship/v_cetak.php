<?php
    $pdf = new Tpdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetFont('times', '', 10, '', false);
    $pdf->setHeaderFont(Array('times', 'sikap', 10));
    $pdf->SetTitle('Laporan ibadah');
    //$pdf->SetTopMargin(10);
    $pdf->setFooterMargin(20);
    $pdf->SetAutoPageBreak(true, 22);
    $pdf->SetAuthor('Author');
    $pdf->SetDisplayMode('real', 'default');
    $pdf->SetMargins(7, 10, 7);
    
    $pdf->AddPage();
    $pdf->SetFont('arial', 'B', 12);
    $pdf->SetY(15);
    $txt = <<<EOD
            LAPORAN IBADAH PER PERIODE
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(25);
    $pdf->SetFont('arial', '', 8, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='<table align="left" width="100%">
                <tr>
                    <td width="8%"><b>PERIODE</b></td>
                    <td width="2%">:</td>
                    <td>'.$priode.'</td>
                </tr>
            </table><br><br>';
    $pdf->writeHTML($html, true, false, true, false, '');
     // tabel 
    $tbl ='
        
        <table cellpadding="3" border="1" width="100%" >
            <tr align="center"> 
                  <td width="5%" rowspan="2"><br><br><b>No</b></td>
                  <th width="15%" rowspan="2" ><br><br><b>Tanggal</b></th>
                  <th width="80%" rowspan="1" colspan="2"><b>Sholat Berjamaah</b></th>
            </tr>
            <tr align="center">
                  <th width="40%"><b>Zhuhur</b></th>
                  <th width="40%"><b>Ashar</b></th>
            </tr>';


            $no=1;
          foreach ($data_ibadah->result() as $row):
        $tbl .='<tr nobr="true">
                <td>'.$no++.'</td> 
                <td>'.tglInd_hrtabel($row->tgl_ibadah).'</td> 
                <td>'.$row->t_zuhur.'</td> 
                <td>'.$row->t_ashar.'</td> 
            </tr>';
            endforeach; 

          
    $tbl .='</table><br><br>';
    $pdf->writeHTML($tbl, true, false, true, false, '');
  
    // ttd
     $ttd ='<div align="center">
            <table width="100%" nobr="true">
                <tr>
                    <td width="55%"></td> 
                    <td width="45%"><b>'.$instansi->kecamatan.', '.tgl_ind_bulan(date('Y-m-d')).'</b><br></td>
                </tr>
                <tr>
                    <td width="50%"><b>Disahkan Oleh:</b></td> 
                    <td width="5%"></td>
                    <td width="45%"><b>Yang Membuat Laporan:</b></td>
                </tr>';
      $ttd .='<tr>
                    <td width="50%">'.$ttd_data->ver_jabatan.'<br><br><br><br></td> 
                    <td width="5%"></td>
                    <td width="45%">'.$ttd_data->jabatan.'<br><br><br><br></td>
              </tr>';

      $ttd .= '<tr>
                    <td width="50%"><b><u>'.nama_gelar($ttd_data->ver_nama, $ttd_data->ver_gelar_dpn, $ttd_data->ver_gelar_blk).'</u>
                            </b><br>
                            <b>'.$ttd_data->ver_pangkat.'</b><br>
                            <b>NIP. '.konversi_nip($ttd_data->ver_nip).'</b>
                    </td> 
                    <td width="5%"></td>
                    <td width="45%"><b><u>'.nama_gelar($ttd_data->nama, $ttd_data->gelar_dpn, $ttd_data->gelar_blk).'</u>
                        </b><br>
                        <b>'.$ttd_data->pangkat.'</b><br>
                        <b>NIP. '.konversi_nip($ttd_data->nip).'</b>
                    </td>
                </tr>';              
    $ttd .='</table>';
    $pdf->writeHTML($ttd, true, false, true, false, '');
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
         <a class="btn btn-sm btn-info m-2" href="data:application/pdf;base64,<?php echo $pdfBase64 ?>" download="FileLaporanKehadiranPeriode_<?php echo $priode ?>">Download</a>
    </div>
    <embed width="100%" height="100%" src="data:application/pdf;base64,<?php echo $pdfBase64 ?>" type="application/pdf" />
</body>
</html>