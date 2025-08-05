<?php
if(preg_match("/\bperawat.php\b/i", $_SERVER['REQUEST_URI'])){
	exit;
}

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
	$tmpt_lahir1=$fdata["tmpt_lahir"];
	$tgl_lahir1=$fdata["tgl_lahir"];
	$alamat=$fdata["alamat"];
	$id_jenis_pegawai1=$fdata["id_jenis_pegawai"];
	$id_spesialis1=$fdata["id_spesialis"];
	$hp1=$fdata["hp"];
	$email1=$fdata["email"];
	$id_pendidikan1=$fdata["id_pendidikan"];
	$foto=$fdata["foto"];
	$id_pk=$fdata["id_pk"];
	
	$selpk=SelPK();
	$nama_pk=$selpk[$id_pk][1];
	
	$tmpt_lahir1=strtoupper($tmpt_lahir1);
	$ftgl_lahir1=TglFormat4($tgl_lahir1);
	$file_name.=" $nama_pegawai1";
	
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
	
	/*tgl awal masuk*/
	$sqld="select * from peg_riw_status_pegawai where hapus=0 and id_pegawai=\"$idpegawai\" and tgl_awal>\"0000-00-00\" order by tgl_awal asc";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		$fdata=mysqli_fetch_assoc($data);
		$tgl_awal=$fdata["tgl_awal"];
		
		$tgl_masuk=TglFormat4($tgl_awal);
	}else{
		$tgl_masuk="00 00 0000";
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
	
	/*halaman 1*/
	$phpWord->setDefaultFontName('Times New Roman');
	$phpWord->setDefaultFontSize(12);
	$phpWord->setDefaultParagraphStyle([
		'spaceBefore' => 0,
		'spaceAfter'  => 0
	]);

	$section = $phpWord->addSection();
	$section->addText('SURAT KETERANGAN', ['bold' => true, 'size' => 14, 'underline'=>'single'], ['alignment' => 'center']);
	$section->addText('Nomor :', [], ['alignment' => 'center']);
	$section->addTextBreak(1);

	// Identitas Penandatangan
	$section->addText('Yang bertanda tangan di bawah ini :');
	$table = $section->addTable();
	$table->addRow();
	$table->addCell(3100)->addText('Nama');
	$table->addCell(200)->addText(':');
	$table->addCell(6000)->addText('Prof. Dr. dr. Djoni Djunaedi, Sp.PD, KPTI');
	$table->addRow();
	$table->addCell(3100)->addText('Jabatan');
	$table->addCell(200)->addText(':');
	
	$cell = $table->addCell(6000);
	$cell->addText("Direktur Rumah Sakit Umum");
	$cell->addText("Universitas Muhammadiyah Malang");
	
	$table->addRow();
	$table->addCell(3100)->addText('Alamat');
	$table->addCell(200)->addText(':');
	$table->addCell(6000)->addText('Jl. Raya Tlogomas No. 45 Malang');
	$section->addTextBreak(1);
	
	$section->addText('Menerangkan bahwa :');
	$table = $section->addTable();
	$table->addRow();
	$table->addCell(3100)->addText('Nama');
	$table->addCell(200)->addText(':');
	$table->addCell(6000)->addText($nama_gab);
	$table->addRow();
	$table->addCell(3100)->addText('Tempat, Tanggal Lahir');
	$table->addCell(200)->addText(':');
	$table->addCell(6000)->addText("$tmpt_lahir1, $ftgl_lahir1");
	$table->addRow();
	$table->addCell(3100)->addText('Alamat');
	$table->addCell(200)->addText(':');
	$table->addCell(6000)->addText("$alamat");
	$section->addTextBreak(1);
	
	$section->addText("Adalah benar-benar Tenaga Keperawatan yang telah bekerja sejak $tgl_masuk sampai sekarang di Rumah Sakit Umum Universitas Muhammadiyah Malang dan akan pengurusan perpanjangan SIP. Adapun jumlah pelayanan keperawatan mulai Januari s/d Desember $gtahun sebagai berikut:");
	$section->addTextBreak(1);
	$section->addText('Demikian surat keterangan ini dibuat dengan sebenarnya dan untuk dipergunakan sebagaimana mestinya.');
	$section->addTextBreak(1);
	
	$table = $section->addTable();
	$table->addRow();
	$table->addCell(4500)->addText("");
	$table->addCell(4500)->addText("Malang, $ftgl1");
	$table->addRow();
	$table->addCell(4500)->addText("");
	$table->addCell(4500)->addText("Direktur,");
	$table->addRow(1000);
	$table->addCell(4500)->addText("");
	$table->addCell(4500)->addText("");
	$table->addRow();
	$table->addCell(4500)->addText("");
	$table->addCell(4500)->addText("Prof. Dr. dr. Djoni Djunaedi, Sp.PD, KPTI");
	
	$section->addPageBreak();
	$section->addText('Lampiran nomor surat:', ['bold' => true]);
	$section->addTextBreak(1);
	
	$table2 = $section->addTable([
		'borderSize' => 6,
		'alignment' => 'center',
		'cellMargin' => 50
	]);

	$table2->addRow();
	$table2->addCell(500, ['vMerge' => 'restart'])->addText('No', ['bold' => true, 'size'=> 10], ['alignment'=>'center']);
	$table2->addCell(3600, ['vMerge' => 'restart'])->addText('Kewenangan Klinik/ Clinical Privilege', ['bold' => true, 'size'=> 10], ['alignment'=>'center']);
	$table2->addCell(3600, ['gridSpan' => 12, 'alignment' => 'center'])->addText('Bulan', ['bold' => true, 'size'=> 10], ['alignment'=>'center']);
	$table2->addCell(500, ['vMerge' => 'restart'])->addText('Total', ['bold' => true, 'size'=> 10], ['alignment'=>'center']);
	
	$table2->addRow();
	$table2->addCell(500, ['vMerge' => 'continue']);
	$table2->addCell(3000, ['vMerge' => 'continue']);
	
	for($k=1;$k<=12;$k++){
		$nabulan=SingkatanBulan($k);
		$nama_bulan=ucfirst(strtolower($nabulan[2]));
		$table2->addCell(300)->addText($nama_bulan, ['bold' => true, 'size' => 8], ['alignment'=>'center']);
	}
	
	$table2->addCell(500, ['vMerge' => 'continue']);
	
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
			
			$table2->addRow();
			$table2->addCell(null, ['gridSpan' => 15])->addText($pk1_nama_jenis_pk, ['bold' => true]);
			
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
						
						$table2->addRow();
						$table2->addCell(500)->addText($no);
						$table2->addCell(7700, array('gridSpan' => 14))->addText($pk2_nama_jenis_pk, ['size' => 9]);
						
						while($fdata3=mysqli_fetch_assoc($data3)){
							$pk3_id=$fdata3["id"];
							$pk3_nama_jenis_pk=$fdata3["nama_jenis_pk"];
							
							$no++;
							$thasil=0;
							$table2->addRow();
							$table2->addCell(500)->addText("\u{25CF}", ['size' => 9], ['alignment'=>'center']);
							$table2->addCell(3000)->addText($pk3_nama_jenis_pk, ['size' => 9]);
							
							for($k=1;$k<=12;$k++){
								$cnhasil=$ardata[$k][$pk3_id]["jmlpk"];
								$fcnhasil=NumberFormat($cnhasil);
								$thasil+=(int)$cnhasil;
								
								$table2->addCell(300)->addText($fcnhasil, ['size' => 9], ['alignment'=>'center']);
							}
							
							$fthasil=NumberFormat($thasil);
							$table2->addCell(500)->addText($fthasil, ['size' => 9], ['alignment'=>'center']);
						}
						
					}else{
						
						$thasil=0;
						
						$table2->addRow();
						$table2->addCell(500)->addText($no, ['size' => 9], ['alignment'=>'center']);
						$table2->addCell(3000)->addText($pk2_nama_jenis_pk, ['size' => 9]);
						
						for($k=1;$k<=12;$k++){
							$cnhasil=$ardata[$k][$pk2_id]["jmlpk"];
							$fcnhasil=NumberFormat($cnhasil);
							$thasil+=(int)$cnhasil;
							
							$table2->addCell(300)->addText($fcnhasil, ['size' => 9], ['alignment'=>'center']);
						}
						
						$fthasil=NumberFormat($thasil);
						$table2->addCell(500)->addText($fthasil, ['size' => 9], ['alignment'=>'center']);
					}
				}
			}
		}
	}
}
?>
