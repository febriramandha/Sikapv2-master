<?php
    $pdf = new Tpdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetFont('arial', '', 10, '', false);
    $pdf->setHeaderFont(Array('arial', 'sikap', 10));
    $pdf->SetTitle('Laporan Rekpitulasi Penerimaan TPP');
    //$pdf->SetTopMargin(10);
    $pdf->setFooterMargin(20);
    $pdf->SetAutoPageBreak(true, 22);
    $pdf->SetAuthor('Author');
    $pdf->SetDisplayMode('real', 'default');
    $pdf->SetMargins(7, 10, 9);
    
    $pdf->AddPage('L','A4');
    $pdf->SetFont('arial', '', 12);
    $pdf->SetY(15);
    $txt = <<<EOD
            REKAPITULASI PENERIMAAN TPP
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(25);
    $pdf->SetFont('arial', '', 6, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='<table align="left" width="100%">
                <tr>
                    <td width="8%"><b>UNIT KERJA</b></td>
                    <td width="2%">:</td>
                    <td width="88%">'.$instansi->dept_name.'</td>
                </tr>
                <tr>
                    <td width="8%"><b>BULAN / TAHUN</b></td>
                    <td width="2%">:</td>
                    <td width="88%">'.$priode.'</td>
                </tr>
            </table><br><br>';
    $pdf->writeHTML($html, true, false, true, false, '');
     // tabel 
    $tbl ='  
        <table cellpadding="2.5" border="1" width="100%" class="table">
           <thead>
            <tr align="center"> 
                  <td width="2%" rowspan="3" ><b><br>No</b></td>
                  <td width="9%" rowspan="3" ><b><br>Nama(NIP)</b></td>
                  <td width="6%" rowspan="3" ><b><br>Beban Kerja</b></td>
                  <td width="6%" rowspan="3" ><b><br>Kondisi Kerja</b></td>
                  <td width="6%" rowspan="3" ><b><br>Kelangkaan Profesi</b></td>
                  <td width="6%" rowspan="3" ><b><br>Total TPP Awal (3+4+5)</b></td>
                  <td colspan="2" width="12%"><b>Besaran TPP Sesuai Standar</b></td>
                  <td colspan="2" width="12%"><b>Besaran Pemotongan</b></td>
                  <td width="6%" rowspan="3" ><b><br>Jumlah TPP (7-9) + (8-10)</b></td>
                  <td width="6%" rowspan="3" ><b><br>Potongan PPH</b></td>
                  <td width="6%" rowspan="3" ><b><br>Jml Setelah Pemotongan PPH</b></td>
                  <td width="5%" rowspan="3" ><b><br>Potongan BPJS (1%)</b></td>
                  <td width="6%" rowspan="3" ><b><br>Setelah Pemotongan BPJS</b></td>
                  <td width="6%" rowspan="3" ><b><br>Potongan Zakat</b></td>
                  <td width="6%" rowspan="3" ><b><br>Jumlah Diterima</b></td>
                  
            </tr>
            <tr align="center">
                  <td colspan="1" width="6%"><b>Aspek Disiplin Kerja</b></td>
                  <td colspan="1" width="6%"><b>Aspek Produktivitas Kerja</b></td>
                  <td colspan="1" width="6%"><b>Aspek Disiplin Kerja</b></td>
                  <td colspan="1" width="6%"><b>Aspek Produktivitas Kerja</b></td>
            </tr>
            </thead>';
            // 8.14285714
            
$no=1;foreach ($data_tpp as $row) {
            $tbl .='<tr nobr="true">
                        <td width="2%">'.$no++.'</td>
                        <td width="9%">'.nama_gelar($row->nama).' '.cekNipValid($row->nip).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->bbebankerja).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->bkondisikerja).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->bkelangkaan).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->totaltpp).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->disiplin_kerja).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->produktivitas_kerja).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->potongan_disiplin).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->potongan_produktivitas).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->hasiltpp).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->potonganpph).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->setelahpotongpph).'</td>
                        <td width="5%" align="center">'.format_rupiah($row->potonganbpjs).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->jml_setelah_potongbpjs).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->potonganzakat).'</td>
                        <td width="6%" align="center">'.format_rupiah($row->jml_setelah_potongzakat).'</td>';               
            $tbl .='</tr>';
        }
          
    $tbl .='</table><br><br>';
    $pdf->writeHTML($tbl, true, false, true, false, '');

    // ttd
    $ttd ='<div align="center">
    <table width="100%" nobr="true">
        <tr>
            <td width="50%"></td> 
            <td width="50%"><b>'.$instansi->kecamatan.', '.tgl_ind_bulan(date('Y-m-d')).'</b><br></td>
        </tr>
        <tr>
            <td width="50%"><b>Disahkan Oleh:</b></td> 
            <td width="50%"><b>Yang Membuat Laporan:</b></td>
        </tr>';
$ttd .='<tr>
            <td width="50%">'.$datainstansi_kepala->jabatan.'<br><br><br><br></td> 
            <td width="50%">'.$ttd_data->jabatan.'<br><br><br><br></td>
      </tr>';

      $ttd .= '<tr>
                    <td width="50%"><b><u>'.nama_gelar($datainstansi_kepala->nama, $datainstansi_kepala->gelar_dpn, $datainstansi_kepala->gelar_blk).'</u>
                        </b><br>
                        <b>'.$datainstansi_kepala->pangkat.'</b><br>
                        <b>NIP. '.konversi_nip($datainstansi_kepala->nip).'</b>
                    </td> 
                    <td width="50%"><b><u>'.nama_gelar($ttd_data->nama, $ttd_data->gelar_dpn, $ttd_data->gelar_blk).'</u>
                        </b><br>
                        <b>'.$ttd_data->pangkat.'</b><br>
                        <b>NIP. '.konversi_nip($ttd_data->nip).'</b>
                    </td>
                </tr>';              
    $ttd .='</table>';
    $pdf->writeHTML($ttd, true, false, true, false, '');

    $pdf->Output('Rekapitulasi TPP '.$priode.'.pdf', 'I');
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
        <a class="btn btn-sm btn-info m-2" href="data:application/pdf;base64,<?php echo $pdfBase64 ?>"
            download="FileLaporanRekapitulasiKehadiranPeriode_<?php echo $priode ?>.pdf">Download</a>
    </div>
    <embed width="100%" height="100%" src="data:application/pdf;base64,<?php echo $pdfBase64 ?>"
        type="application/pdf" />
</body>

</html>