<?php
include"../../setting/kon.php";
include"../../setting/function.php";
include"../../setting/variable.php";
require_once '../../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;

$phpWord = new PhpWord();

switch($pages){
	case'logbook': include"logbook/logbook.php"; break;
}

// Set header untuk langsung download
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=\"$file_name.docx\"");
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
header('Pragma: public');

// Simpan ke output
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('php://output');
exit;
?>