<?php
include"../../setting/kon.php";
include"../../setting/function.php";
include"../../setting/variable.php";
include"../../plugins/PHPExcel/Classes/PHPExcel.php";

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("RSU UMM")
							 ->setLastModifiedBy("RSU Universitas Muhammadiyah Malang");

switch($pages){
	case"presensiharian": include"laporan_presensi/harian.php"; break;
	case"presensifinger": include"laporan_presensi/finger.php"; break;
	case"presensibulanan": include"laporan_presensi/bulanan.php"; break;
	case"presensifingerunit": include"laporan_presensi/finger_unit.php"; break;
	case"presensidurasiunit": include"laporan_presensi/durasi_unit.php"; break;
	case"presensilebaran": include"laporan_presensi/lebaran.php"; break;
	
	case"operasipendaftaran": include"operasi/lap_daftar.php"; break;
	case"operasiselesai": include"operasi/lap_selesai.php"; break;
	case"operasibatal": include"operasi/lap_batal.php"; break;
	case"operasibelum": include"operasi/lap_belum.php"; break;
	
	case"pegkeldata": include"pegawai/kelengkapan_data.php"; break;
	case"pegkegiatan": include"pegawai/kegiatan.php"; break;
	
	case"farmasi": include"farmasi/farmasi.php"; break;
	
	case"igd": include"igd/igd.php"; break;
	
}

$gmtdate=gmdate("D, d M Y H:i:s");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment;filename=\"$nama_file.xlsx\"");
header("Cache-Control: max-age=0");
header("Cache-Control: max-age=1");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: $gmtdate GMT");
header("Cache-Control: cache, must-revalidate");
header("Pragma: public");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>