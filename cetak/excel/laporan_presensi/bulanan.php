<?php
if(preg_match("/\bbulanan.php\b/i", $_SERVER['REQUEST_URI'])){
	exit;
}else{

$nama_file="Laporan Presensi";
$gtahun=$gid;
$gbulan=$gid2;

$sbln=SingkatanBulan($gbulan);
$nama_bulan=$sbln[1];

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1", "Data Presensi $nama_bulan $gtahun");

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'No. Urut')
            ->setCellValue('C3', 'NIP')
            ->setCellValue('D3', 'Nama Pegawai')
            ->setCellValue('E3', 'Unit Kerja')
            ->setCellValue('F3', 'Subunit Kerja')
            ->setCellValue('G3', 'Jabatan')
            ->setCellValue('H3', 'Terlambat')
            ->setCellValue('I3', 'Tidak Absen')
            ->setCellValue('J3', 'Durasi (jam)');


/*jumlah hari libur*/
$varjam=3600;
$twbulan=sprintf("%02d", $gbulan);
$bsnum="$gtahun-$twbulan-01";
$numDays=date("t", strtotime("$gtahun-$twbulan-01"));
$snum=sprintf("%'.02d", $numDays);
$snumdate="$gtahun-$twbulan-$snum";
$no=3;

$idaktif="";
$selaktif=SelKeaktifan("1");
if(count($selaktif)>0){
	foreach($selaktif as $selaktif1){
		$idaktif.="'$selaktif1[id]',";
	}
	
	$idaktif=substr($idaktif,0,-1);
	
	$wsa="and status_aktif in ($idaktif)";
	$wsa2="and a.status_aktif in ($idaktif)";
}

$sqld="select * from db_pegawai where hapus=\"0\" and id_urutan>0 and tgl_keluar='0000-00-00' $wsa order by id_urutan asc";
$data=mysqli_query($db_result, $sqld);
$ndata=mysqli_num_rows($data);
if($ndata>0){
	while($fdata=mysqli_fetch_assoc($data)){
		$id=$fdata["id"];
		$idf=$fdata["idf"];
		$id_urutan=$fdata["id_urutan"];
		
		$arrdata[$id_urutan]=$fdata;
		$list_id.="'$idf',";
		$list_idp.="'$id',";
	}
	
	$sunit=LayananDaftar();
	$sjabatan=SelJabatan();
	
	$list_idp=substr($list_idp,0,-1);
	$arshift=array();
	
	$sqld="select a.*, b.jam_masuk_ef, b.jam_pulang_ef, b.jam_masuk_pu, b.jam_pulang_pu, d.waktu_tk, c.nama_shift from ms_shift_pegawai_jam as a inner join ms_shift_jam as b on a.id_shift_jam=b.id inner join ms_shift_nama as c on b.id_shift_nama=c.id inner join ms_shift_unit as d on c.id_shift_unit=d.id where a.hapus=0 and b.hapus=0 and a.id_pegawai in ($list_idp) and a.tgl_shift>=\"$bsnum\" and a.tgl_shift<=\"$snumdate\"";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		while($fdata=mysqli_fetch_assoc($data)){
			$id_pegawai=$fdata["id_pegawai"];
			$tgl_shift=$fdata["tgl_shift"];
			
			$arshift[$id_pegawai][$tgl_shift][]=$fdata;
		}
	}
	
	/*ijin terlambat*/
	$arter=array();
	$sqld="select id_pegawai, tgl_ijin_mulai from peg_absensi_ijin where hapus=\"0\" and id_pegawai in ($list_idp) and (tgl_ijin_mulai like \"$gtahun-$gbulan-%\" or tgl_ijin_selesai like \"$gtahun-$gbulan-%\") and id_status_ijin in (3)";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		while($fdata=mysqli_fetch_assoc($data)){
			$id_pegawai=$fdata["id_pegawai"];
			$tgl_ijin_mulai=$fdata["tgl_ijin_mulai"];
			$arter[$id_pegawai][$tgl_ijin_mulai]=$fdata;
		}
	}
	
	$arter2=array();
	$sqld="select id_pegawai, tgl_ijin_mulai from peg_absensi_ijin where hapus=\"0\" and id_pegawai in ($list_idp) and (tgl_ijin_mulai like \"$gtahun-$gbulan-%\" or tgl_ijin_selesai like \"$gtahun-$gbulan-%\") and id_status_ijin in (7)";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		while($fdata=mysqli_fetch_assoc($data)){
			$id_pegawai=$fdata["id_pegawai"];
			$tgl_ijin_mulai=$fdata["tgl_ijin_mulai"];
			$arter2[$id_pegawai][$tgl_ijin_mulai]=$fdata;
		}
	}
	
	/*ijin*/
	$arijin=array();
	$sqld="select * from peg_absensi_ijin where id_pegawai in ($list_idp) and hapus=0 and (tgl_ijin_mulai like \"$gtahun-$twbulan-%\" or tgl_ijin_selesai like \"$gtahun-$twbulan-%\") and id_status_ijin in (1,2,4,5,6)";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		while($fdata=mysqli_fetch_assoc($data)){
			$id_pegawai=$fdata["id_pegawai"];
			$tgl_ijin_mulai=$fdata["tgl_ijin_mulai"];
			$tgl_ijin_selesai=$fdata["tgl_ijin_selesai"];
			
			if($tgl_ijin_mulai==$tgl_ijin_selesai){
				$tgli1=date("Y-m-d", strtotime($tgl_ijin_mulai));
				$arijin[$id_pegawai][$tgli1]=$fdata;
			}else{
				
				if($tgl_ijin_selesai>$snumdate and $tgl_ijin_mulai>=$bsnum){
					$counthari=HitungTanggal($snumdate, $tgl_ijin_mulai);
					$days_total=$counthari["days_total"];
					
					for($ch=0;$ch<=$days_total;$ch++){
						$tgli1=date("Y-m-d", strtotime("$tgl_ijin_mulai +$ch days"));
						$arijin[$id_pegawai][$tgli1]=$fdata;
					}
				}
				elseif($tgl_ijin_selesai<=$snumdate and $tgl_ijin_mulai<$bsnum){
					$counthari=HitungTanggal($tgl_ijin_selesai, $bsnum);
					$days_total=$counthari["days_total"];
					
					for($ch=0;$ch<=$days_total;$ch++){
						$tgli1=date("Y-m-d", strtotime("$bsnum +$ch days"));
						$arijin[$id_pegawai][$tgli1]=$fdata;
					}
				}
				else{
					$counthari=HitungTanggal($tgl_ijin_selesai, $tgl_ijin_mulai);
					$days_total=$counthari["days_total"];
					
					for($ch=0;$ch<=$days_total;$ch++){
						$tgli1=date("Y-m-d", strtotime("$tgl_ijin_mulai +$ch days"));
						$arijin[$id_pegawai][$tgli1]=$fdata;
					}
				}
			}
			
		}
	}
	
	/*tanggal libur*/
	$arlibur=array();
	$sqld="select * from ms_tgl_libur where tgl_libur like \"$gtahun-$gbulan-%\" and hapus=0";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		while($fdata=mysqli_fetch_assoc($data)){
			$tgl_libur=$fdata["tgl_libur"];
			$arlibur[$tgl_libur]=$fdata;
		}
	}
	
	/*ambil shift manajemen*/
	$arsman=array();
	$sqld="select * from ms_shift_unit where hapus=0 and id_unit_kerja=0 and status=1 order by id desc limit 1";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		$fdata=mysqli_fetch_assoc($data);
		$idman=$fdata["id"];
		
		$sqld="select b.* from ms_shift_nama as a inner join ms_shift_jam as b on a.id=b.id_shift_nama where a.hapus=0 and a.status=1 and b.hapus=0 and b.status=1 and a.id_shift_unit=\"$idman\"";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				$idhari=$fdata["id_hari"];
				$arsman[$idhari]=$fdata;
			}
		}
	}
	
	$arcekclock=array();
	$sqld="select *, substr(clockin,1,10) as tglw from peg_absensi where hapus=0 and id_pegawai in ($list_idp)";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		while($fdata=mysqli_fetch_assoc($data)){
			$id_pegawai=$fdata["id_pegawai"];
			$tglw=$fdata["tglw"];
			
			$tglw=trim($tglw);
			
			$arcekclock[$id_pegawai][$tglw]=$fdata;
		}
	}
	
	for($jk=1;$jk<=2000;$jk++){
		$peg1=$arrdata[$jk];
		$no++;
		
		if(!empty($peg1)){
			extract($peg1);
			
			$nama_gab=NamaGabung($nama_pegawai, $gelar_depan, $gelar_belakang);
			$nip_peg=($nip_pegawai1='')? "$nip_pegawai" : "$no_reg";
			
			$unit_induk=$sunit[$id_unit_induk]["nama_unit"];
			$nama_unit=$sunit[$id_unit_kerja]["nama_unit"];
			
			if($id_jabatan<=0){
				$nama_jabatan="Staf";
			}else{
				$nama_jabatan=$sjabatan[$id_jabatan]["nama_jabatan"];
			}
			
			$jml_ter=0;
			$jml_absen=0;
			$ndate1="$gtahun-$gbulan-01";
			
			for($i=0;$i<$numDays;$i++){
				$nd=date("Y-m-d", strtotime("$ndate1 +$i days"));
				$idn=date("N", strtotime("$nd"));
				
				/*tanggal puasa*/
				$sqldp="select * from ms_tgl_puasa where tgl_puasa_awal<=\"$nd\" and tgl_puasa_akhir>=\"$nd\" and hapus=0 limit 1";
				$datap=mysqli_query($db_result, $sqldp);
				$adapuasa=mysqli_num_rows($datap);
				
				if(!empty($arshift[$id])){
					$cn1=count((array)$arshift[$id][$nd]);
				
					if($cn1<=1){
						if($adapuasa==1){
							$jam_masuk=$arshift[$id][$nd][0]["jam_masuk_pu"];
							$jam_pulang=$arshift[$id][$nd][0]["jam_pulang_pu"];
						}else{
							$jam_masuk=$arshift[$id][$nd][0]["jam_masuk_ef"];
							$jam_pulang=$arshift[$id][$nd][0]["jam_pulang_ef"];
						}
						
						$nama_shift=$arshift[$id][$nd][0]["nama_shift"];
					}else{
						$arjammin=array();
						$arjamp=array();
						$nama_shift="";
						
						for($cni=0;$cni<$cn1;$cni++){
							if($adapuasa==1){
								$jml_cn_masuk=$arshift[$id][$nd][$cni]["jam_masuk_pu"];
								$jml_cn_pulang=$arshift[$id][$nd][$cni]["jam_pulang_pu"];
							}else{
								$jml_cn_masuk=$arshift[$id][$nd][$cni]["jam_masuk_ef"];
								$jml_cn_pulang=$arshift[$id][$nd][$cni]["jam_pulang_ef"];
							}
							
							$arjammin[]=$jml_cn_masuk;
							$disnamashift=$arshift[$id][$nd][$cni]["nama_shift"];
							
							/* if($disnamashift=="Malam"){
								$jam_pulang_malam=$arshift[$id][$nd][$cni]["jam_pulang_ef"];
							}else{
								$arjamp[]=$arshift[$id][$nd][$cni]["jam_pulang_ef"];
							} */
							
							if($disnamashift=="Malam"){
								$jam_pulang_malam=$jml_cn_pulang;
							}else{
								$arjamp[]=$jml_cn_pulang;
							}
							
							$nama_shift.="$disnamashift-";
						}
						
						$jam_masuk=min((array)$arjammin);
						$jam_masuk_puasa=$jam_masuk;
						
						if($jam_pulang_malam<="00:00:00"){
							$jam_pulang=max((array)$arjamp);
						}else{
							$jam_pulang=$jam_pulang_malam;
						}
						
						$jam_pulang_puasa=$jam_pulang;
						$nama_shift=substr($nama_shift,0,-1);
					}
					
					/* $jam_masuk=$arshift[$id][$nd]["jam_masuk_ef"];
					$jam_pulang=$arshift[$id][$nd]["jam_pulang_ef"];
					$jam_masuk_puasa=$arshift[$id][$nd]["jam_masuk_pu"];
					$jam_pulang_puasa=$arshift[$id][$nd]["jam_pulang_pu"]; */
					
					$waktu_tk=$arshift[$id][$nd][0]["waktu_tk"];
					$waktu_tk=($waktu_tk>0)? $waktu_tk : 0;
					$waktu_tkp1=$waktu_tk+1;
					
					$jam_abs_masuk=$arcekclock[$id][$nd]["clockin"];
					$jam_abs_pulang=$arcekclock[$id][$nd]["clockout"];
					
					$jam_masuk1=date("Y-m-d H:i:s", strtotime("$nd $jam_masuk +$waktu_tkp1 minutes"));
					
					if($jam_masuk>$jam_pulang){
						$jam_pulang1=date("Y-m-d H:i:s", strtotime("$nd $jam_pulang +1 day"));
					}else{
						$jam_pulang1=date("Y-m-d H:i:s", strtotime("$nd $jam_pulang"));
					}
					
					$cekijin=$arijin[$id][$nd];
					if(empty($cekijin)){
						if($jam_masuk>"00:00:00"){
							if(($jam_masuk1<$jam_abs_masuk or $jam_abs_masuk<="0000-00-00 00:00:00") and empty($arter[$id][$nd])){
								$jml_ter++;
							}
							
							if(($jam_pulang1>$jam_abs_pulang or $jam_abs_pulang<="0000-00-00 00:00:00") and empty($arter2[$id][$nd])){
								$jml_ter++;
							}
							
							if($jam_abs_masuk<="0000-00-00 00:00:00" and empty($arijin[$id][$nd])){
								$jml_absen++;
							}
							
							if($jam_abs_pulang<="0000-00-00 00:00:00" and (empty($arijin[$id][$nd]) or empty($arter2[$id][$nd]))){
								$jml_absen++;
							}
						}
					}
					
				}else{
					if($adapuasa==1){
						$jam_masuk=$arsman[$idn]["jam_masuk_pu"];
						$jam_pulang=$arsman[$idn]["jam_pulang_pu"];
					}else{
						$jam_masuk=$arsman[$idn]["jam_masuk_ef"];
						$jam_pulang=$arsman[$idn]["jam_pulang_ef"];
					}
					
					$waktu_tkp1=16;
					
					$jam_abs_masuk=$arcekclock[$id][$nd]["clockin"];
					$jam_abs_pulang=$arcekclock[$id][$nd]["clockout"];
					
					$jam_masuk1=date("Y-m-d H:i:s", strtotime("$nd $jam_masuk +$waktu_tkp1 minutes"));
					
					if($jam_masuk>$jam_pulang){
						$jam_pulang1=date("Y-m-d H:i:s", strtotime("$nd $jam_pulang +1 day"));
					}else{
						$jam_pulang1=date("Y-m-d H:i:s", strtotime("$nd $jam_pulang"));
					}
					
					$cekijin=$arijin[$id][$nd];
					$tgllibur=$arlibur[$nd];
					
					if(empty($tgllibur)){
						if(empty($cekijin)){
							if($jam_masuk>"00:00:00"){
								if(($jam_masuk1<$jam_abs_masuk or $jam_abs_masuk<="0000-00-00 00:00:00") and empty($arter[$id][$nd])){
									$jml_ter++;
								}
								
								if(($jam_pulang1>$jam_abs_pulang or $jam_abs_pulang<="0000-00-00 00:00:00") and empty($arter2[$id][$nd])){
									$jml_ter++;
								}
								
								if($jam_abs_masuk<="0000-00-00 00:00:00" and empty($arijin[$id][$nd])){
									$jml_absen++;
								}
								
								if($jam_abs_pulang<="0000-00-00 00:00:00" and (empty($arijin[$id][$nd]) or empty($arter2[$id][$nd]))){
									$jml_absen++;
								}
							}
						}
					}
				}
			}
			
			$cekijin=$arijin[$id];
			$cnijin=count((array)$cekijin);
			if($cnijin>=13){
				$jml_ter=13;
			}else{
				$jml_ter=$jml_ter;
			}
		}else{
			$id_urutan="$jk";
			$nip_peg="";
			$nama_gab="";
			$unit_induk="";
			$nama_unit="";
			$nama_jabatan="";
			$jml_ter="0";
			$jml_absen="0";
		}
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A$no", "$no2")
					->setCellValue("B$no", "$id_urutan")
					->setCellValue("C$no", "$nip_peg")
					->setCellValue("D$no", "$nama_gab")
					->setCellValue("E$no", "$unit_induk")
					->setCellValue("F$no", "$nama_unit")
					->setCellValue("G$no", "$nama_jabatan")
					->setCellValue("H$no", "$jml_ter")
					->setCellValue("I$no", "$jml_absen")
					->setCellValue("J$no", "");
	}
}

$objPHPExcel->getActiveSheet()->setTitle("Laporan Presensi");
$objPHPExcel->setActiveSheetIndex(0);
}
?>
