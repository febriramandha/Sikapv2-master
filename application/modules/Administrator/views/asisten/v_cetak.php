<?php
    $pdf = new Tpdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetFont('times', '', 10, '', false);
    $pdf->setHeaderFont(Array('times', 'sikap', 10));
    $pdf->SetTitle('LAPORAN DATA PEJABAT ASISTEN DAERAH');
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
            LAPORAN DATA PEJABAT ASISTEN DAERAH
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
                      <th width="30%"><b>NAMA(NIP)</b></th>
                      <th width="35%"><b>JABATAN</b></th>
                      <th width="30%"><b>INSTANSI</b></th>
                </tr>';
        $arrayForTable = [];
        foreach ($user as $databaseValue) {
            $temp = [];
            $temp['nama']         = name_degree(_name($databaseValue->nama),$databaseValue->gelar_dpn,$databaseValue->gelar_blk);
            $temp['nip']          = $databaseValue->nip;
            $temp['jabatan']      = $databaseValue->jabatan;
            $temp['dept_name']    = $databaseValue->dept_name;


            if(!isset($arrayForTable[$databaseValue->nama])){
                $arrayForTable[$databaseValue->nama] = [];
            }
                $arrayForTable[$databaseValue->nama][] = $temp;

        }

        $no=1; foreach ($arrayForTable as $id=>$values) {
                foreach ($values as $key=> $value) {

                    $html .='<tr nobr="true">';
                    if($key == 0) :

                    $html .='<td align="center" rowspan="'.count($values).'">'.$no++.'</td>
                             <td rowspan="'.count($values).'">'.$value['nama'].'('.$value['nip'].')</td>
                             <td rowspan="'.count($values).'">'.strtoupper($value['jabatan']).'</td>';
                    endif;
                    $html .='
                            <td>'.strtoupper($value['dept_name']).'</td> 
                        </tr>';

                }

            }



        
    $html .='</table><br><br>';
  
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('Laporan_DataPejabatAsisten.pdf', 'I');

?>