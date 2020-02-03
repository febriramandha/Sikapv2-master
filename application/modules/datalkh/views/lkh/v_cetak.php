<?php
    $pdf = new Tpdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetFont('tahoma', '', 10, '', false);
    $pdf->setHeaderFont(Array('times', 'sikap', 10));
    $pdf->SetTitle('Laporan kerja harian');
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
            LAPORAN KERJA HARIAN PER PERIODE
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(25);
    $pdf->SetFont('arial    ', '', 8, '', false);
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
        
        <table cellpadding="2.5" border="1" width="100%" >
            <tr align="center"> 
                  <td width="5%" rowspan="2"><br><br><b>No</b></td>
                  <th width="13%" rowspan="2" ><br><br><b>Tanggal</b></th>
                  <th width="13%" rowspan="1" colspan="2"><b>Jam</b></th>
                  <th width="37%" rowspan="2" ><br><br><b>Uraian Kegiatan</b></th>
                  <th width="32%" rowspan="2" ><br><br><b>Hasil</b></th>
            </tr>
            <tr align="center">
                  <th width="6.5%"><b>Mulai</b></th>
                  <th width="6.5%"><b>Selesai</b></th>
            </tr>
            <thead cellpadding="2">
                <tr align="center" >
                    <td width="5%">1</td>
                    <td width="13%">2</td>
                    <td width="6.5%">3</td>
                    <td width="6.5%">4</td>
                    <td width="37%">5</td>
                    <td width="32%">6</td>
                </tr>
            </thead>';


            $arrayForTable = [];
            foreach ($datalkh->result() as $databaseValue) {
                $temp = [];
                $temp['jam_mulai']      = $databaseValue->jam_mulai;
                $temp['jam_selesai']    = $databaseValue->jam_selesai;
                $temp['kegiatan']       = $databaseValue->kegiatan;
                $temp['hasil']          = $databaseValue->hasil;

                if(!isset($arrayForTable[$databaseValue->tgl_lkh])){
                    $arrayForTable[$databaseValue->tgl_lkh] = [];
                }
                    $arrayForTable[$databaseValue->tgl_lkh][] = $temp;

            }


        $no=1; foreach ($arrayForTable as $id=>$values) :
                    foreach ($values as $key=> $value) :

        $tbl .='<tr nobr="true">';
                if($key == 0) :

        $tbl .='<td align="center" rowspan="'.count($values).'">'.$no++.'</td>
                <td rowspan="'.count($values).'">'.tglInd_hrtabel($id).'</td>';
              endif;
        $tbl .='
                <td align="center">'.substr($value['jam_mulai'],0,5).'</td> 
                <td align="center">'.substr($value['jam_selesai'],0,5).'</td> 
                <td>'.$value['kegiatan'].'</td> 
                <td>'.$value['hasil'].'</td> 
            </tr>';
                    endforeach;
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
    //$pdf->Output('LaporanKerjaHarian_'.$ttd_data->nip.'_'.$priode.'.pdf', 'I');

    $pdfString = $pdf->Output('LaporanKerjaHarian_'.$ttd_data->nip.'_'.$priode.'.pdf', 'S');
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