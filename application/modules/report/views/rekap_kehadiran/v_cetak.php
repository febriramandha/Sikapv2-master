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
            REKAPITULASI KEHADIRAN PEGAWAI
            EOD;
    // print a block of text using Write()
    $pdf->Write(0, $txt, '', 0, 'C', true, 1, false, false, 0);
    $pdf->SetY(25);
    $pdf->SetFont('arial', '', 8, '', false);
    $html ='<hr style="height: 2px;">';
    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='<table align="left" width="100%">
                <tr>
                    <td width="8%"><b>UNIT KERJA</b></td>
                    <td width="2%">:</td>
                    <td width="88%">'.$datainstansi->dept_name.'</td>
                </tr>
                <tr>
                    <td width="8%"><b>PERIODE</b></td>
                    <td width="2%">:</td>
                    <td width="88%">'.$priode.'</td>
                </tr>
            </table><br><br>';
    $pdf->writeHTML($html, true, false, true, false, '');
     // tabel 
    $tbl ='  
        <table cellpadding="2.5" border="1" width="100%" >
           <thead>
            <tr align="center"> 
                  <td width="3%" rowspan="2" ><b><br>No</b></td>
                  <td width="30%" rowspan="2" ><b><br>Nama(NIP)</b></td>
                  <td rowspan="2" width="5%"><b>Jumlah Hari Kerja 1 Bulan </b></td>
                  <td rowspan="2"  width="5%"><b>Jumlah Hari Hadir</b></td>
                  <td colspan="7" width="57%"><b>Perilaku</b></td>
            </tr>
            <tr align="center">
                  <td><b>Terlambat Masuk Kerja</b></td>
                  <td><b>Pulang Kerja Lebih Awal</b></td>
                  <td><b>Tidak Hadir Tanpa Keterangan</b></td>
                  <td><b>Tidak Mengikuti Upacara</b></td>
                  <td><b>Tidak Shalat Zuhur/Ashar</b></td>
                  <td><b><br>DL</b></td>
                  <td><b><br>Cuti</b></td>
            </tr>
            </thead>';
$no=1;foreach ($pegawai_absen as $row) {
            $tbl .='<tr nobr="true">
                        <td width="3%">'.$no++.'</td>
                        <td width="30%">'.nama_gelar($row->nama, $row->gelar_dpn, $row->gelar_blk).' '.cekNipValid($row->nip).'</td>
                        <td width="5%" align="center">'.jum_hari_kerja_rekap($row->json_absen).'</td>
                        <td width="5%" align="center">'.jum_hadir_kerja_rekap($row->json_absen).'</td>
                        <td width="8.14285714%" align="center">'.jum_terlambar_rekap($row->json_absen).'</td>
                        <td width="8.14285714%" align="center">'.jum_pulang_cepat_rekap($row->json_absen).'</td>
                        <td width="8.14285714%" align="center">'.jum_tk_rekap($row->json_absen).'</td>
                        <td width="8.14285714%" align="center">'.jum_tidak_upacara_rekap($row->json_absen).'</td>
                        <td width="8.14285714%" align="center">'.jum_tidak_sholatza_rekap($row->json_absen, $row->agama_id, $row->id).'</td>
                        <td width="8.14285714%" align="center">'.jum_dinas_luar_rekap($row->json_absen).'</td>
                        <td width="8.14285714%" align="center">'.jum_cuti_rekap($row->json_absen).'</td>';     
            $tbl .='</tr>';
        }
          
    $tbl .='</table><br><br>';
    $pdf->writeHTML($tbl, true, false, true, false, '');

    // ttd
     $ttd ='
            <table width="100%" nobr="true" align="center">
                <tr>
                    <td width="55%"></td> 
                    <td width="45%"><b>'.$datainstansi->kecamatan.', '.tgl_ind_bulan(date('Y-m-d')).'</b><br></td>
                </tr>';
      $ttd .='<tr>
                    <td width="45%">'.$datainstansi_kepala->jabatan.'<br><br><br><br></td> 
                    <td width="10%"></td>
                    <td width="45%">'.$datainstansi->jabatan.'<br><br><br><br></td>
              </tr>';

      $ttd .= '<tr>
                    <td width="45%"><b><u>'.nama_gelar($datainstansi_kepala->nama, $datainstansi_kepala->gelar_dpn, $datainstansi_kepala->gelar_blk).'</u>
                            </b><br>
                            <b>'.$datainstansi_kepala->pangkat.'</b><br>
                            <b>NIP. '.konversi_nip($datainstansi_kepala->nip).'</b>
                    </td> 
                    <td width="10%"></td>
                    <td width="45%"><b><u>'.nama_gelar($datainstansi->nama, $datainstansi->gelar_dpn, $datainstansi->gelar_blk).'</u>
                        </b><br>
                        <b>'.$datainstansi->pangkat.'</b><br>
                        <b>NIP. '.konversi_nip($datainstansi->nip).'</b>
                    </td>
                </tr>';              
    $ttd .='</table>';
    $pdf->writeHTML($ttd, true, false, true, false, '');
  

    //$pdf->Output('LaporanAbsensi_'.$priode.'.pdf', 'I');
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