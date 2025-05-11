<?php
require('fpdf/fpdf.php');

class tFPDF extends FPDF
{
    // Convert UTF-8 to ISO-8859-1 or Windows-1252
    // Override Write and Cell methods to support UTF-8
    function Write($h, $txt, $link = '')
    {
        parent::Write($h, $txt, $link);
    }

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }
}
?>
