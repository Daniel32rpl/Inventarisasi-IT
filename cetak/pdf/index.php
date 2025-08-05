<?php
if(preg_match("/\bindex.php\b/i", $_SERVER['REQUEST_URI'])){
	exit;
}else{

include"../../setting/kon.php";
include"../../setting/function.php";
include"../../setting/variable.php";
require_once('../../plugins/tcpdf/tcpdf.php');

$pdf=new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

switch($pages) {
	case'sep': include"sep.php"; break;
	case'septtd': include"sep.php"; break;
	case'kartu': include"kartu.php"; break;
	case'tagihanbpjs': include"tagihan_bpjs.php"; break;
	case'casmix': include"casmix.php"; break;
	case'sepcsm': include"sepcsm.php"; break;
	case'sepcsmttd': include"sepcsm.php"; break;
	case'suratkontrol': include"surat_kontrol.php"; break;
	case'ketklinis': include"keterangan_klinis.php"; break;
	case'bukuvaksin': include"buku_vaksin.php"; break;
	case'keterangan_klinis': include"keterangan_klinis.php"; break;
	case'suratpesananobat': include"sp.php"; break;	
	case'covid1': include"covid1.php"; break;	
	case'covid2': include"covid2.php"; break;	
	case'triage': include"triage.php"; break;	
	case'triagepdf': include"triagepdf.php"; break;	
	case'triageskrining': include"triageskrining.php"; break;	
	case'triageskrining2': include"triageskrining2.php"; break;	
	case'igd': include"igd/igd.php"; break;
	case'karpeg': include"karpeg.php"; break;
	case'farmasi': include"farmasi/farmasi.php"; break;
	case'logbook': include"logbook/logbook.php"; break;
	case'diagnosa': include"diagnosa/diagnosa.php"; break;
}

if($sh==1){
	include"../../plugins/tcpdf/pdf.php";
}

}
?>
