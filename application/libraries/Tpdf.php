<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
class Tpdf extends TCPDF{
    function __construct()
    {
        parent::__construct();
    }

       //Page header
    public function Header() {
        if ($this->page == 1) {
            // Logo
            $image_file = K_PATH_IMAGES.'agam.png';
            $this->Image($image_file, 10, 5, 15, '', 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);
            // Set font

            $this->SetFont('arial', 'B', 14);
            // Title
            $this->SetY(12);
            $this->Cell(0, 15, 'PEMERINTAH KABUPATEN AGAM', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }

    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Halaman '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */