<?php
if(preg_match("/\bperawat.php\b/i", $_SERVER['REQUEST_URI'])){
	exit;
}else{

$file_name="Logbook Perawat";
$orientasi_page="P";
$kertas="LEGAL";
$font_page="Helvetica";

$html="";
$mbottom="30";
$mleft="25";
$mtop="40";
$mright="10";

$ftgl1=TglFormat4($ndate);
list($idpegawai, $idtahun)=explode("a", $gid2);
$gtahun=(empty($idtahun))? date("Y") : "$idtahun";

$st1="style=\"border-top:1px solid #000000;border-bottom:1px solid #000000;font-weight:bold;\"";
$border1="border:1px solid #000000;border-collapse:collapse;";

$sqld="select * from db_pegawai where hapus=\"0\" and id=\"$idpegawai\" and id_pk>0";
$data=mysqli_query($db_result, $sqld);
$ndata=mysqli_num_rows($data);
if($ndata>0){
	$fdata=mysqli_fetch_assoc($data);
	$nama_pegawai1=$fdata["nama_pegawai"];
	$gelar_depan1=$fdata["gelar_depan"];
	$gelar_belakang1=$fdata["gelar_belakang"];
	$nip_pegawai1=$fdata["nip_pegawai"];
	$jenis_kelamin1=$fdata["jenis_kelamin"];
	$id_jenis_pegawai1=$fdata["id_jenis_pegawai"];
	$id_spesialis1=$fdata["id_spesialis"];
	$hp1=$fdata["hp"];
	$email1=$fdata["email"];
	$id_pendidikan1=$fdata["id_pendidikan"];
	$foto=$fdata["foto"];
	$id_pk=$fdata["id_pk"];
	
	$selpk=SelPK();
	$nama_pk=$selpk[$id_pk][1];
	
	$foto=($foto!="")? "files/foto/$foto" : "";
	$nama_gab=NamaGabung($nama_pegawai1, $gelar_depan1, $gelar_belakang1);
	$spend=SelPendidikan($id_pendidikan1);
	$nama_pendidikan=$spend[$id_pendidikan1]["nama_pendidikan"];
	
	$sjp=SelJenisPegawai($id_jenis_pegawai1);
	$jenis_pegawai=$sjp[$id_jenis_pegawai1]["jenis_pegawai"];
	
	if($id_spesialis1>0){
		$sdoktersp=SelDokterSpesialis($id_spesialis1);
		$jenis_pegawai=$sdoktersp[$id_spesialis1]["spesialis"];
	}else{
		$jenis_pegawai="$jenis_pegawai";
	}
	
	$sjk=SelStatus();
	$nama_kelamin=$sjk[$jenis_kelamin1][3];
	
	$th="";
	for($k=1;$k<=12;$k++){
		$nabulan=SingkatanBulan($k);
		$nama_bulan=ucfirst(strtolower($nabulan[2]));
		$th1.="<th width=\"5%\" style=\"font-size:10px;\"><b>$nama_bulan</b></th>";
	}

	$ardata=array();
	$td_table="";
	$no=0;
	
	$sqld="select * from db_pk_perawat where id_pegawai=\"$idpegawai\" and tgl_berlaku<=\"$ndate\" and tgl_akhir>=\"$ndate\" and hapus=0 limit 1";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		$fdata=mysqli_fetch_assoc($data);
		$id_pk_unit_kuis=$fdata["id_pk_unit"];
	}else{
		$id_pk_unit_kuis=0;
	}
	
	$sqld="select substr(a.tgl_kegiatan,1,7) as tglpk, b.id_pk_kuesioner, count(b.hasil_pk) as jmlpk from db_pk_catatan_harian as a inner join db_pk_catatan_harian_item as b on a.id=b.id_pk_catatan where a.tgl_kegiatan like \"$gtahun-%\" and a.hapus=0 and a.id_pk_unit_kuis=\"$id_pk_unit_kuis\" and a.id_pk=\"$id_pk\" and b.hasil_pk=1 and b.hapus=0 and a.id_pegawai=\"$idpegawai\" group by b.id_pk_kuesioner, substr(a.tgl_kegiatan,1,7)";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		while($fdata=mysqli_fetch_assoc($data)){
			extract($fdata);
			
			list($exth, $exbulan)=explode("-", $tglpk);
			$exbulan=(int)$exbulan;
			
			$ardata[$exbulan][$id_pk_kuesioner]=$fdata;
		}
	}
	
	$sqld="select * from db_pk_kuesioner where sub_id=0 and status=1 and hapus=0 and id_pk_unit=\"$id_pk_unit_kuis\" and id_pk=\"$id_pk\" order by nama_jenis_pk asc";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		while($fdata=mysqli_fetch_assoc($data)){
			$pk1_id=$fdata["id"];
			$pk1_nama_jenis_pk=$fdata["nama_jenis_pk"];
			
			$td_table.="<tr>
				<td colspan=\"15\"><b>$pk1_nama_jenis_pk</b></td>
			</tr>";
			
			$sqld2="select * from db_pk_kuesioner where sub_id=\"$pk1_id\" and status=1 and hapus=0 and id_pk_unit=\"$id_pk_unit_kuis\" and id_pk=\"$id_pk\" order by nama_jenis_pk asc";
			$data2=mysqli_query($db_result, $sqld2);
			$ndata2=mysqli_num_rows($data2);
			if($ndata2>0){
				while($fdata2=mysqli_fetch_assoc($data2)){
					$pk2_id=$fdata2["id"];
					$pk2_nama_jenis_pk=$fdata2["nama_jenis_pk"];
					
					$no++;
					
					$sqld3="select * from db_pk_kuesioner where sub_id=\"$pk2_id\" and status=1 and hapus=0 and id_pk_unit=\"$id_pk_unit_kuis\" and id_pk=\"$id_pk\" order by nama_jenis_pk asc";
					$data3=mysqli_query($db_result, $sqld3);
					$ndata3=mysqli_num_rows($data3);
					if($ndata3>0){
						
						$td_table.="<tr>
							<td>$no</td>
							<td colspan=\"14\">$pk2_nama_jenis_pk</td>
						</tr>";
						
						while($fdata3=mysqli_fetch_assoc($data3)){
							$pk3_id=$fdata3["id"];
							$pk3_nama_jenis_pk=$fdata3["nama_jenis_pk"];
							
							$td1="";
							$thasil=0;
							
							for($k=1;$k<=12;$k++){
								$cnhasil=$ardata[$k][$pk3_id]["jmlpk"];
								$fcnhasil=NumberFormat($cnhasil);
								$thasil+=(int)$cnhasil;
								
								$td1.="<td align=\"center\">$fcnhasil</td>";
							}
							
							$fthasil=NumberFormat($thasil);
							
							$no++;
							$td_table.="<tr>
								<td align=\"right\" style=\"font-size:5px;\"><i class=\"fa fa-circle\"></i></td>
								<td>$pk3_nama_jenis_pk</td>
								$td1
								<td align=\"center\">$fthasil</td>
							</tr>";
						}
						
					}else{
						
						$td1="";
						$thasil=0;
						for($k=1;$k<=12;$k++){
							$cnhasil=$ardata[$k][$pk2_id]["jmlpk"];
							$fcnhasil=NumberFormat($cnhasil);
							$thasil+=(int)$cnhasil;
							
							$td1.="<td align=\"center\">$fcnhasil</td>";
						}
						
						$fthasil=NumberFormat($thasil);
						
						$td_table.="<tr>
							<td>$no</td>
							<td>$pk2_nama_jenis_pk</td>
							$td1
							<td align=\"center\">$fthasil</td>
						</tr>";
					}
				}
			}
		}
	}
	
	$html="<table width=\"100%\">
		<tr>
			<td width=\"100%\" align=\"center\" style=\"font-size:25px;\">
				<u><b>SURAT KETERANGAN</b></u>
			</td>
		</tr>
		<tr>
			<td width=\"100%\" align=\"center\">
				Nomor : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
	</table>
	<br /><br /><br />
	
	<table width=\"100%\">
		<tr>
			<td colspan=\"3\">Yang bertanda tangan di bawah ini :</td>
		</tr>
		<tr>
			<td width=\"30%\">Nama</td>
			<td width=\"2%\">:</td>
			<td width=\"68%\">Prof. Dr. dr. Djoni Djunaedi, Sp.PD, KPTI</td>
		</tr>
		<tr>
			<td>Jabatan</td>
			<td>:</td>
			<td>Direktur Rumah Sakit Umum<br />Universitas Muhammadiyah Malang</td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td>:</td>
			<td>Jl. Raya Tlogomas No. 45 Malang</td>
		</tr>
	</table>
	<br /><br />
	
	<table width=\"100%\">
		<tr>
			<td colspan=\"3\">Menerangkan bahwa :</td>
		</tr>
		<tr>
			<td width=\"30%\">Nama</td>
			<td width=\"2%\">:</td>
			<td width=\"68%\">$nama_gab</td>
		</tr>
		<tr>
			<td>Jabatan</td>
			<td>:</td>
			<td>$jenis_pegawai</td>
		</tr>
	</table>
	<br /><br />
	
	Telah melaksanakan pelayanan medis di Rumah Sakit Umum Universitas Muhammadiyah Malang pada Tahun $gtahun dengan data jumlah pasien terlampir.<br /><br />
	Demikian surat keterangan ini dibuat dengan sebenarnya dan untuk dipergunakan sebagaimana mestinya.<br /><br />
	
	<table width=\"100%\">
		<tr>
			<td width=\"50%\"></td>
			<td width=\"50%\">
				Malang, $ftgl1<br />
				Direktur,<br /><br /><br /><br />
				
				Prof. Dr. dr. Djoni Djunaedi, Sp.PD, KPTI
			</td>
		</tr>
	</table>";
	
	$html2="Lampiran nomor surat:
	<br /><br />
	<table width=\"100%\" cellpadding=\"5\" border=\"1\" style=\"border-collapse:collapse;border:1px solid #000000; font-size:12px;\">
		<thead>
			<tr>
				<th width=\"5%\" align=\"center\" rowspan=\"2\"><b>No</b></th>
				<th width=\"28%\" rowspan=\"2\"><b>Kewenangan Klinik/ Clinical Privilege</b></th>
				<th width=\"60%\"  align=\"center\" colspan=\"12\"><b>Bulan</b></th>
				<th width=\"7%\"  align=\"center\" rowspan=\"2\"><b>Total</b></th>
			</tr>
			<tr>
				$th1
			</tr>
		<thead>
		<tbody>
			$td_table
		</tbody>
	</table>";
}

#echo"$html";
#$sh=1;

// Include the main TCPDF library (search for installation path).
require_once('../../plugins/tcpdf/tcpdf_include.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = "/home/web/rsumm/v2/images/bgsurat_v1.jpg";
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RSU UMM');
$pdf->SetTitle($file_name);
$pdf->SetSubject('logbook');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins($mleft, $mtop, $mright);

$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

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

/*halaman 1*/
$pdf->SetFont('times', '', 12);
$pdf->AddPage();
$pdf->writeHTML($html, true, false, true, false, '');

/*halaman 2*/
$pdf->AddPage();
$pdf->writeHTML($html2, true, false, true, false, '');

// remove default header
$pdf->setPrintHeader(false);

//Close and output PDF document
$pdf->Output("$file_name.pdf", 'I');

//============================================================+
// END OF FILE
//============================================================+

}
?>
