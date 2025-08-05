<?php
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('infokom');
$pdf->SetTitle($file_name);
$pdf->SetSubject($file_name);
$pdf->SetKeywords('');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins($mleft, $mtop, $mright);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, $mbottom);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont($font_page, '', 12);

// add a page
$pdf->AddPage($orientasi_page, $kertas, false, false);

// set some text to print
$txt = <<<EOD
$html
EOD;

// print a block of text using Write()
#$pdf->WriteHTML(0, $txt, '', 0, 'C', true, 0, false, false, 0);
$pdf->writeHTML($txt, true, false, true, false, '');

// ---------------------------------------------------------

//Close and output PDF document
ob_end_clean();
$pdf->Output($file_name.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>
