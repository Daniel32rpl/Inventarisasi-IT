<?php
include"setting/kon.php";
include"setting/function.php";
include"setting/variable.php";

$pilih="$gid";
$kode="$gid2";
$kode3="$gid3";

if($pilih=="propinsi"){
	if($kode!=""){
		$spropinsi=MasterWilayah2("", "2", "$kode");
		if(count($spropinsi)>0){
			foreach($spropinsi as $spropinsi1){
				$sel1=($kode3==$spropinsi1["id"])? "selected=\"selected\"" : "";
				$list_propinsi.="<option value=\"$spropinsi1[id]\" $sel1>$spropinsi1[kategori_wilayah] $spropinsi1[nama]</option>";
			}
		}

		echo"<option value=\"\">Pilih</option>$list_propinsi";
	}
}

if($pilih=="kota") {
	if($kode!=""){
		$spropinsi=MasterWilayah2("", "3", "$kode");
		if(count($spropinsi)>0){
			foreach($spropinsi as $spropinsi1){
				$sel1=($kode3==$spropinsi1["id"])? "selected=\"selected\"" : "";
				$list_propinsi.="<option value=\"$spropinsi1[id]\" $sel1>$spropinsi1[kategori_wilayah] $spropinsi1[nama]</option>";
			}
		}

		echo"<option value=\"\">Pilih</option>$list_propinsi";
	}
}

if($pilih=="kec") {
	if($kode!=""){
		$spropinsi=MasterWilayah2("", "4", "$kode");
		if(count($spropinsi)>0){
			foreach($spropinsi as $spropinsi1){
				$sel1=($kode3==$spropinsi1["id"])? "selected=\"selected\"" : "";
				$list_propinsi.="<option value=\"$spropinsi1[id]\" $sel1>$spropinsi1[kategori_wilayah] $spropinsi1[nama]</option>";
			}
		}

		echo"<option value=\"\">Pilih</option>$list_propinsi";
	}
}

if($pilih=="terapi9"){
	if($kode!=""){
		$sqld="select * from ms_icd where hapus=\"0\" and jenis_icd=\"1\" and (nama_penyakit like \"%$kode%\" or kode_icd like \"%$kode%\" or singkatan like \"%$kode%\") order by nama_penyakit asc limit 10";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				extract($fdata);

				$resp[]="$nama_penyakit#$kode_icd#$id";
			}

			echo json_encode($resp);
		}
	}
}

if($pilih=="icd10"){
	if($kode!=""){
		$sqld="select * from ms_icd where hapus=\"0\" and jenis_icd=\"2\" and (nama_penyakit like \"%$kode%\" or kode_icd like \"%$kode%\" or singkatan like \"%$kode%\") order by nama_penyakit asc limit 10";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				extract($fdata);

				$resp[]="$nama_penyakit#$kode_icd#$id";
			}

			echo json_encode($resp);
		}
	}
}

/*tindakan poli*/
if($pilih=="tindakan-poli"){
	if($kode!=""){
		#ms_tindakan dihapus

		$sqld="select * from ms_layanan where hapus=\"0\" and status=\"1\" and nama like \"%$kode%\" order by nama asc limit 10";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				extract($fdata);

				$sqld2="select nominalPoli from ms_layanan_tarif where id_layanan=\"$id\" and status=\"1\" and tanggal_berlaku<=\"$ndate\"";
				$data2=mysqli_query($db_result, $sqld2);
				$fdata2=mysqli_fetch_assoc($data2);
				$tarif_layanan=$fdata2["nominalPoli"];

				$resp[]="$id#$nama#$tarif_layanan";
			}

			echo json_encode($resp);
		}
	}
}

if($pilih=="pegawai"){
	if($kode!=""){
		$sqld="select * from db_pegawai where hapus=\"0\" and status_aktif=\"1\" and (nama_pegawai like \"%$kode%\" or nip_pegawai like \"%$kode%\") order by nama_pegawai asc limit 10";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				extract($fdata);
				$nama_dokter=NamaGabung($nama_pegawai, $gelar_depan , $gelar_belakang);

				$resp[]="$id#$nama_dokter#$nip_pegawai";
			}

			echo json_encode($resp);
		}
	}
}

if($pilih=="diskon"){
	if($kode!=""){
		$sqld="select * from db_asuransi where hapus=\"0\" and status=\"1\" and id=\"$kode\"";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			$fdata=mysqli_fetch_assoc($data);
			echo json_encode($fdata);
		}
	}
}

if($pilih=="jasadokter"){
	if($kode!=""){
		if($gup==1){
			$jd=JadwalDokterIGD($gtanggal, $kode3, $kode);
		}else{
			$jd=JadwalDokter($gtanggal, $kode3, $kode);
		}
		$kategori=$jd[$kode3]["id_spesialis"];

		$bd=BiayaDokter($kategori, 1, $kode);
		$biaya_dokter=$bd["tarif"];
		$biaya_dokter=($biaya_dokter<=0)? "0" : "$biaya_dokter";

		$fdata=array(
			"biaya_dokter" => "$biaya_dokter"
		);

		echo json_encode($fdata);
	}
}

if($pilih=="bacaanrad"){
	if($gid4!=""){
		$sqld="select * from ms_tindakan_bacaan_dokter where id_jtbacaan=\"$gid4\" and id_pegawai=\"$kode3\" and hapus=\"0\"";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			$fdata=mysqli_fetch_assoc($data);
			extract($fdata);

			$list="$hasil_pemeriksaan####$hasil_kesimpulan";
		}

		echo $list;
	}
}

if($pilih=="jenis_bacaan"){
	if($kode!=""){
		$resp=array();

		$sqld="select * from ms_tindakan_bacaan_dokter where jenis_bacaan like \"%$kode%\" and jenis_bacaan!=\"\" and hapus=\"0\" group by jenis_bacaan order by jenis_bacaan asc";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				extract($fdata);

				$resp[]="$jenis_bacaan";
			}

			echo json_encode($resp);
		}
	}
}

if($pilih=="pekerjaan"){
	if($kode!=""){
		$resp=array();

		$sqld="select * from db_pasien where nama_pekerjaan like \"%$kode%\" and nama_pekerjaan!=\"\" and hapus=\"0\" group by nama_pekerjaan order by nama_pekerjaan asc";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				extract($fdata);

				$resp[]="$nama_pekerjaan";
			}

			echo json_encode($resp);
		}
	}
}

if($pilih=="ttd"){
	$result = array();
	$imagedata = base64_decode($_POST['img_data']);
	$filename = "$kode";
	$target_dir="files/ttd/pasien";

	$nd=date("Y");
	$target_dir="$target_dir/$nd";
	if(!is_dir("$target_dir")) {
		mkdir("$target_dir", 0755, true);
	}

	$nd=date("m");
	$target_dir="$target_dir/$nd";
	if(!is_dir("$target_dir")) {
		mkdir("$target_dir", 0755, true);
	}

	$nd=date("d");
	$target_dir="$target_dir/$nd";
	if(!is_dir("$target_dir")) {
		mkdir("$target_dir", 0755, true);
	}

	$lokasi="$target_dir";
	$file_name = "$lokasi/$filename.png";
	file_put_contents($file_name,$imagedata);
	$result['status'] = 1;
	$result['file_name'] = $file_name;
	echo json_encode($result);
}

if($pilih=="ttdhd"){
	$result = array();
	$imagedata = base64_decode($_POST['img_data']);
	$filename = "$gid3";
	$target_dir="files/ttd/hd";

	if(!is_dir("$target_dir/")) {
		mkdir("$target_dir", 0755, true);
	}

	$nd=date("Y");
	$target_dir="$target_dir/$nd";
	if(!is_dir("$target_dir")) {
		mkdir("$target_dir", 0755, true);
	}

	$nd=date("m");
	$target_dir="$target_dir/$nd";
	if(!is_dir("$target_dir")) {
		mkdir("$target_dir", 0755, true);
	}

	$nd=date("d");
	$target_dir="$target_dir/$nd";
	if(!is_dir("$target_dir")) {
		mkdir("$target_dir", 0755, true);
	}

	$lokasi="$target_dir";
	$file_name = "$lokasi/$filename.png";

	/*simpan tanda tangan*/
	$vdata="ttd_pegawai=\"$file_name\"";
	$vvalues="id=\"$gid3\"";
	$inp="update hd_pemeriksaan set $vdata where $vvalues";
	mysqli_query($db_result, $inp);

	file_put_contents($file_name,$imagedata);
	$result['status'] = 1;
	$result['file_name'] = $file_name;
	echo json_encode($result);
}

if($pilih=="ttdpasienigd"){
	$result = array();
	$imagedata = base64_decode($_POST['img_data']);
	$filename = "$gid4"."_".date("Ymdhis")."_".uniqid();
	$target_dir="files/ttd/pasien";

	if(!is_dir("$target_dir/")) {
		mkdir("$target_dir", 0755, true);
	}

	$nd=date("Y");
	$target_dir="$target_dir/$nd";
	if(!is_dir("$target_dir")) {
		mkdir("$target_dir", 0755, true);
	}

	$nd=date("m");
	$target_dir="$target_dir/$nd";
	if(!is_dir("$target_dir")) {
		mkdir("$target_dir", 0755, true);
	}

	$nd=date("d");
	$target_dir="$target_dir/$nd";
	if(!is_dir("$target_dir")) {
		mkdir("$target_dir", 0755, true);
	}

	$lokasi="$target_dir";
	$file_name = "$lokasi/$filename.png";

	/*simpan tanda tangan*/
	$vdata="ttd_pj_pasien=\"$file_name\"";
	$vvalues="id=\"$gid3\" and kode_register_igdv1=\"$gid2\"";
	$inp="update ps_pemeriksaan_selesai set $vdata where $vvalues";
	mysqli_query($db_result, $inp);

	file_put_contents($file_name,$imagedata);
	$result['status'] = 1;
	$result['file_name'] = $file_name;
	echo json_encode($result);
}

if($pilih=="ttdpegawai"){
	$result = array();
	$imagedata = base64_decode($_POST['img_data']);
	$filename = "$kode";
	$target_dir="files/ttd/pegawai";

	$lokasi="$target_dir";
	$file_name = "$lokasi/$filename.png";
	@unlink($file_name);
	
	file_put_contents($file_name,$imagedata);
	$result['status'] = 1;
	$result['file_name'] = $file_name;
	echo json_encode($result);
}

if($pilih=="unit"){
	if($kode!=""){
		#$sqld="select * from db_unit_kerja2 where hapus=\"0\" and status=\"1\" and (sub_id=\"$kode\" or id=\"$kode\") order by sub_id asc, nama_unit asc";
		$sqld="select * from db_unit_kerja2 where hapus=\"0\" and status=\"1\" and (sub_id=\"$kode\") order by sub_id asc, nama_unit asc";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				$sel1=($kode3==$fdata["id"])? "selected=\"selected\"" : "";
				$list_unit.="<option value=\"$fdata[id]\" $sel1>$fdata[nama_unit]</option>";
			}
		}else{
			$sqld="select * from db_unit_kerja2 where hapus=\"0\" and status=\"1\" and id=\"$kode\" order by nama_unit asc";
			$data=mysqli_query($db_result, $sqld);
			$fdata=mysqli_fetch_assoc($data);
			$sel1=($kode3==$fdata["id"])? "selected=\"selected\"" : "";
			$list_unit="<option value=\"$fdata[id]\" $sel1>$fdata[nama_unit]</option>";
		}
	}

	echo"<option value=\"\">Pilih</option>$list_unit";
}

if($pilih=="unitpegawai"){
	if($kode!=""){
		$sqld="select * from db_unit_kerja2 where hapus=\"0\" and status=\"1\" and sub_id=\"$kode\" order by nama_unit asc";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				$sel1=($kode3==$fdata["id"])? "selected=\"selected\"" : "";
				$list_unit.="<option value=\"$fdata[id]\" $sel1>$fdata[nama_unit]</option>";
			}
		}
	}

	echo"<option value=\"\">Pilih</option>$list_unit";
}

if($pilih=="obat"){
	if($kode!=""){
		$sqld="select * from db_obat where hapus=\"0\" and stok_opname>\"0\" and nama_obat like \"%$kode%\" order by nama_obat asc limit 10";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				extract($fdata);

				/*satuan obat*/
				$so=SelSatuanObat($id_satuan_obat);
				$nama_satuan=$so[$id_satuan_obat]["satuan_obat"];

				$resp[]="$nama_obat#$nama_satuan#$harga_jual#$id";
			}

			echo json_encode($resp);
		}
	}
}
if($pilih=="obatSemua"){
	if($kode!=""){
		$sqld="select * from db_obat where hapus=\"0\" and nama_obat like \"%$kode%\" order by nama_obat asc limit 10";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				extract($fdata);

				/*satuan obat*/
				$so=SelSatuanObat($id_satuan_obat);
				$nama_satuan=$so[$id_satuan_obat]["satuan_obat"];

				$resp[]="$nama_obat#$nama_satuan#$id";
			}

			echo json_encode($resp);
		}
	}
}

if($pilih=="propinsi_kll"){
	$urlbpjs="$url_bpjs/referensi/propinsi";
	$cb=CekVclaim($urlbpjs, $vclaim_id, $vclaim_key);
	$spropinsi=$cb->response;

	if(count($spropinsi)>0){
		foreach($spropinsi as $spropinsi1){
			foreach($spropinsi1 as $spropinsi12){
				$kode_kll=$spropinsi12->kode;
				$nama_kll=$spropinsi12->nama;

				$list_propinsi.="<option value=\"$kode_kll\">$nama_kll</option>";
			}
		}
	}

	echo"<option value=\"\">Pilih</option>$list_propinsi";
}

if($pilih=="kota_kll"){
	if($kode!=""){
		$urlbpjs="$url_bpjs/referensi/kabupaten/propinsi/$kode";
		$cb=CekVclaim($urlbpjs, $vclaim_id, $vclaim_key);
		$spropinsi=$cb->response;

		if(count($spropinsi)>0){
			foreach($spropinsi as $spropinsi1){
				foreach($spropinsi1 as $spropinsi12){
					$kode_kll=$spropinsi12->kode;
					$nama_kll=$spropinsi12->nama;

					$list_propinsi.="<option value=\"$kode_kll\">$nama_kll</option>";
				}
			}
		}
	}

	echo"<option value=\"\">Pilih</option>$list_propinsi";
}

if($pilih=="kec_kll"){
	if($kode!=""){
		$urlbpjs="$url_bpjs/referensi/kecamatan/kabupaten/$kode";
		$cb=CekVclaim($urlbpjs, $vclaim_id, $vclaim_key);
		$spropinsi=$cb->response;

		if(count($spropinsi)>0){
			foreach($spropinsi as $spropinsi1){
				foreach($spropinsi1 as $spropinsi12){
					$kode_kll=$spropinsi12->kode;
					$nama_kll=$spropinsi12->nama;

					$list_propinsi.="<option value=\"$kode_kll\">$nama_kll</option>";
				}
			}
		}
	}

	echo"<option value=\"\">Pilih</option>$list_propinsi";
}

if($pilih=="tindakanradiologi"){
	if($kode!=""){
		$sqld="select a.*, b.jenis_tindakan from ms_tindakan as a inner join ms_tindakan_jenis as b on a.id_jenistindakan=b.id where a.hapus=\"0\" and a.status=\"1\" and a.nama_tindakan like \"%$kode%\" order by a.nama_tindakan asc limit 10";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				extract($fdata);

				$resp[]="$id#$nama_tindakan#$jenis_tindakan#$tarif_tindakan";
			}

			echo json_encode($resp);
		}
	}
}

if($pilih=="ppk1"){
	if($kode!=""){
		$urlbpjs="$url_bpjs/referensi/faskes/$kode/$kode3";
		$cb=CekVclaim($urlbpjs, $vclaim_id, $vclaim_key);
		$cekstatus=$cb->metaData->code;
		if($cekstatus==200){
			$faskes=$cb->response->faskes;

			foreach($faskes as $faske1){
				$ref_kode=$faske1->kode;
				$ref_nama=$faske1->nama;

				$resp[]="$ref_nama#$ref_kode";
			}

			echo json_encode($resp);
		}
	}
}

if($pilih=="bpjs"){
	if($kode!=""){
		/*banyak rujukan*/
		$urlbpjs="$url_bpjs/Rujukan/List/Peserta/$kode";
		$cb=CekVclaim($urlbpjs, $vclaim_id, $vclaim_key);
		$cekstatus=$cb->metaData->code;
		if($cekstatus==201){

			/*single rujukan*/
			$urlbpjs="$url_bpjs/rujukan/Peserta/$kode";
			$cb=CekVclaim($urlbpjs, $vclaim_id, $vclaim_key);
			$cekstatus=$cb->metaData->code;
			$cekmessage=$cb->metaData->message;

			if($cekstatus==200){
				$tglKunjungan=$cb->response->rujukan->tglKunjungan;
				$poli_tujuan=$cb->response->rujukan->poliRujukan->nama;
				$nomr=$cb->response->rujukan->peserta->mr->noMR;

				$list_poli="$poli_tujuan<br />";
				$tgl_berlaku=date("Y-m-d", strtotime("$tglKunjungan +90 days"));
				if($tgl_berlaku<=$ndate){
					echo"Nomor Rujukan Sudah Melebihi Batas Waktu 90 Hari";
				}else{
					echo"$list_poli";
				}
			}else{
				echo"$cekmessage";
			}
		}else{
			$cekmessage=$cb->metaData->message;

			$res1=$cb->response->rujukan;
			$cnres1=count($res1);

			if($cnres1>0){
				for($r1=0;$r1<$cnres1;$r1++){
					$nr++;

					$noKunjungan=$res1[$r1]->noKunjungan;
					$tglKunjungan=$res1[$r1]->tglKunjungan;
					$poli_tujuan=$res1[$r1]->poliRujukan->nama;
					$nomr=$res1[$r1]->peserta->mr->noMR;


					$tgl_berlaku=date("Y-m-d", strtotime("$tglKunjungan +90 days"));
					if($tgl_berlaku<=$ndate){
						$k=0;
						$list_poli.="$poli_tujuan (kadaluarsa)<br />";
					}else{
						$k=1;
						$list_poli.="$poli_tujuan (berlaku - $tgl_berlaku)<br />";
					}

					$k1=$k1+$k;
				}

				echo"$list_poli";
			}else{
				echo"$cekmessage";
			}
		}
	}
}

if($pilih=="pasien"){
	if($kode!=""){
		$sqld="select * from db_pasien where kode_rm=\"$kode\" and hapus=\"0\" limit 1";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			$fdata=mysqli_fetch_assoc($data);
			extract($fdata);
			$resp="$nama_pasien#$tgl_lahir";

			echo $resp;
		}
	}
}

if($pilih=="anatomi"){
	if(count($_POST)>0){
		foreach($_POST as $pkey => $pvalue){
			$post1=mysqli_escape_string($db_result, $pvalue);
			$post1=preg_replace('/\s+/', ' ', $post1);
			$post1=trim($post1);

			$arpost[$pkey]="$post1";
		}

		extract($arpost);

		$error="";
		$error.=(empty($pgid3))? "&bull; Kode Masih Kosong<br />" : "";
		$error.=(empty($posisix) or empty($posisiy))? "&bull; Posisi Belum Ditentukan<br />" : "";
		$error.=(empty($bagiantubuh))? "&bull; Bagian Tubuh Masih Kosong<br />" : "";
		$error.=(empty($keterangan))? "&bull; Keterangan Masih Kosong<br />" : "";

		if(empty($error)){
			$sqld="select * from ps_anatomi_tubuh where kode_register_igdv1=\"$pgid3\" and posisix=\"$posisix\" and posisiy=\"$posisiy\" and hapus=\"0\"";
			$data=mysqli_query($db_result, $sqld);
			$ndata=mysqli_num_rows($data);
			if($ndata==0){
				$vdata="kode_register_igdv1, posisix, posisiy, bagiantubuh, keterangan, hapus, tgl_insert, tgl_update, user_update";
				$vvalues="\"$pgid3\", \"$posisix\", \"$posisiy\", \"$bagiantubuh\", \"$keterangan\", \"0\", \"$ndatetime\", \"$ndatetime\", \"$ss_id\"";

				$inp="insert into ps_anatomi_tubuh ($vdata) values ($vvalues)";
				$upd=mysqli_query($db_result, $inp);
				if($upd==1){
					$id1=mysqli_insert_id($db_result);
					$link_hapus="";
					$resp="1|$id1|$bagiantubuh|$keterangan|$link_hapus";
				}else{
					$resp="2|Data Gagal Disimpan";
				}
			}else{
				$resp="2|Data Sudah Ada";
			}
		}else{
			$resp="2|Bagian Tubuh dan Keteragan Tidak Boleh Kosong";
		}

		echo $resp;
	}
}

if($pilih=="bpjsnik"){
	if($kode!=""){
		$urlbpjs="$url_bpjs/Peserta/nik/$kode/tglSEP/$ndate";
		$cb=CekVclaim($urlbpjs, $vclaim_id, $vclaim_key);
		$cekstatus=$cb->metaData->code;
		$cekmessage=$cb->metaData->message;
		if($cekstatus==200){
			$noKartu=$cb->response->peserta->noKartu;
			$sex=$cb->response->peserta->sex;
			$tglLahir=$cb->response->peserta->tglLahir;
			$nama=$cb->response->peserta->nama;
			$hakKelas=$cb->response->peserta->hakKelas->keterangan;
			$statusPeserta=$cb->response->peserta->statusPeserta->keterangan;

			$sex2=($sex=="L")? "1" : "2";
			$tglLahir=date("d-m-Y", strtotime($tglLahir));

			echo"$noKartu#$sex2#$tglLahir#$nama#$hakKelas#$statusPeserta";
		}
	}
}

if($pilih=="bpjsno"){
	if($kode!=""){
		$urlbpjs="$url_bpjs/Peserta/nokartu/$kode/tglSEP/$ndate";
		$cb=CekVclaim($urlbpjs, $vclaim_id, $vclaim_key);
		$cekstatus=$cb->metaData->code;
		$cekmessage=$cb->metaData->message;
		if($cekstatus==200){
			$noKartu=$cb->response->peserta->nik;
			$sex=$cb->response->peserta->sex;
			$tglLahir=$cb->response->peserta->tglLahir;
			$nama=$cb->response->peserta->nama;
			$hakKelas=$cb->response->peserta->hakKelas->keterangan;
			$statusPeserta=$cb->response->peserta->statusPeserta->keterangan;

			$sex2=($sex=="L")? "1" : "2";
			$tglLahir=date("d-m-Y", strtotime($tglLahir));

			echo"$noKartu#$sex2#$tglLahir#$nama#$hakKelas#$statusPeserta";
		}
	}
}

if($pilih=="rujukinternal"){
	if($kode!=""){
		$id_rehab=$kode;
		$jd=JadwalDokter($ndate, "", "$id_rehab");
		if(count($jd)>0){
			foreach ($jd as $jd1) {
				$nama_dokter=NamaGabung($jd1["nama_pegawai"], $jd1["gelar_depan"], $jd1["gelar_belakang"]);
				$idjadwal=$jd1["id"];
				$kode_dpjp=$jd1["kode_dpjp"];

				if($kode_dpjp>0){
					$jam_awal=substr($jd1["jam_awal"], 0, 5);
					$jam_akhir=substr($jd1["jam_akhir"], 0, 5);
					$pkuota=$jd1["kuota"];
					$pkuota=($pkuota>0)? "$pkuota" : "0";

					$sqld2="select * from ps_pendaftaran_online where id_instalasi=\"$id_rehab\" and hapus=\"0\" and tgl_daftar=\"$ndate\" and no_antri>\"0\" and id_jadwal_dokter=\"$idjadwal\"";
					$data2=mysqli_query($db_result, $sqld2);
					$ndata2=mysqli_num_rows($data2);

					$pilihjadwal=($pilihjadwal>0)? "$pilihjadwal" : "$gstatus";
					$psel1=($jd1["id"]=="$pilihjadwal")? "checked=\"checked\"" : "";
					$pilih="<input type=\"radio\" name=\"pilihjadwal\" class=\"pilihjadwal\" value=\"$jd1[id]\" $psel1 required/>";

					$td_rehab.="<tr class=\"rehab\">
						<td>$pilih</td>
						<td>$kode_dpjp</td>
						<td>$nama_dokter</td>
						<td>$jam_awal - $jam_akhir</td>
						<td>$pkuota</td>
						<td>$ndata2</td>
					</tr>";
				}
			}

			$sd=SelDokter("", "", "kode_dpjp!=''");
			if(count($sd)>0){
				foreach($sd as $sd1){
					$nama_dokter=NamaGabung($sd1["nama_pegawai"], $sd1["gelar_depan"], $sd1["gelar_belakang"]);
					$kode_dpjp=$sd1["kode_dpjp"];

					$list_dpjp.="<option value=\"$kode_dpjp\">$nama_dokter</option>";
				}
			}

			$td_rehab="<div class=\"form-group\">
				<label class=\"col-md-12\">Dokter DPJP Awal Rujukan</label>
				<div col-md-12>
					<select name=\"dokterdpjp\" class=\"form-control select2\">
						<option value=\"\">- Pilih -</option>
						$list_dpjp
					</select>
				</div>
			</div>

			<table class=\"table table-bordered table-hover\">
				<thead>
					<tr>
						<th>Pilih</th>
						<th>Kode DPJP</th>
						<th>Dokter</th>
						<th>Waktu (WIB)</th>
						<th>Kuota</th>
						<th>Pendaftar</th>
					</tr>
				</thead>
				<tbody>
					$td_rehab
				</tbody>
			</table>";
		}
	}

	echo"$td_rehab";
}

if($pilih=="ttdpasienfarmasi"){
	$result = array();
	$imagedata = base64_decode($_POST['img_data']);
	$filename = "$gid2"."_".date("Ymdhis")."_".uniqid();
	$target_dir="files/ttd/pasien";

	if(!is_dir("$target_dir/")) {
		mkdir("$target_dir", 0755, true);
	}

	$nd=date("Y");
	$target_dir="$target_dir/$nd";
	if(!is_dir("$target_dir")) {
		mkdir("$target_dir", 0755, true);
	}

	$nd=date("m");
	$target_dir="$target_dir/$nd";
	if(!is_dir("$target_dir")) {
		mkdir("$target_dir", 0755, true);
	}

	$nd=date("d");
	$target_dir="$target_dir/$nd";
	if(!is_dir("$target_dir")) {
		mkdir("$target_dir", 0755, true);
	}

	$lokasi="$target_dir";
	$file_name = "$lokasi/$filename.png";

	/*simpan tanda tangan*/
	$vdata="ttd_penerima=\"$file_name\"";
	$vvalues="id=\"$gid3\" and id_antrian_farmasi_v3=\"$gid2\"";
	$inp="update db_resep_verifikasi set $vdata where $vvalues";
	mysqli_query($db_result, $inp);

	file_put_contents($file_name,$imagedata);
	$result['status'] = 1;
	$result['file_name'] = $file_name;
	echo json_encode($result);
}

if($pilih=="cgr"){
	$ck1 = $_POST['ck1'];
	
	$post1=mysqli_escape_string($db_result, $ck1);
	$post1=preg_replace('/\s+/', ' ', $post1);
	$post1=trim($post1);
	
	if($post1!=""){
		list($gtahun, $gbulan, $ik2, $id_pegawai, $arjam1)=explode("#", $post1);
		
		$tgl_gab="$gtahun-$gbulan-$ik2";
		
		if($arjam1=="L"){
			$sqld="select * from ms_shift_pegawai_jam where tgl_shift=\"$tgl_gab\" and hapus=\"0\" and id_pegawai=\"$id_pegawai\" and libur=\"1\"";
			$data=mysqli_query($db_result, $sqld);
			$ndata=mysqli_num_rows($data);
			if($ndata>0){
				$fdata=mysqli_fetch_assoc($data);
				$id1=$fdata["id"];
				
				$vdata="hapus=\"1\", tgl_update=\"$ndatetime\"";
				$vvalues="id=\"$id1\"";
				
				$inp="update ms_shift_pegawai_jam set $vdata where $vvalues";
				mysqli_query($db_result, $inp);
				
			}else{
				$vdata="tgl_shift, id_shift_jam, oncall, libur, id_pegawai, hapus, tgl_insert, tgl_update, user_update";
				$vvalues="\"$tgl_gab\", \"0\", \"0\", \"1\", \"$id_pegawai\", \"0\", \"$ndatetime\", \"$ndatetime\", \"$ss_id\"";
				
				$inp="insert into ms_shift_pegawai_jam ($vdata) values ($vvalues)";
				mysqli_query($db_result, $inp);
			}
		}else{
			$nday=date("N", strtotime($tgl_gab));
			
			$sqld="select * from ms_shift_jam where hapus=0 and status=1 and id_hari=\"$nday\" and id_shift_nama=\"$arjam1\"";
			$data=mysqli_query($db_result, $sqld);
			$ndata=mysqli_num_rows($data);
			if($ndata>0){
				$fdata=mysqli_fetch_assoc($data);
				$id_jam=$fdata["id"];
				
				$sqld="select * from ms_shift_pegawai_jam where tgl_shift=\"$tgl_gab\" and hapus=\"0\" and id_pegawai=\"$id_pegawai\" and id_shift_jam=\"$id_jam\"";
				$data=mysqli_query($db_result, $sqld);
				$ndata=mysqli_num_rows($data);
				if($ndata>0){
					$fdata=mysqli_fetch_assoc($data);
					$id1=$fdata["id"];
					
					$vdata="hapus=\"1\", tgl_update=\"$ndatetime\"";
					$vvalues="id=\"$id1\"";
					
					$inp="update ms_shift_pegawai_jam set $vdata where $vvalues";
					mysqli_query($db_result, $inp);
					
				}else{
					$vdata="tgl_shift, id_shift_jam, id_shift_nama, oncall, libur, id_pegawai, hapus, tgl_insert, tgl_update, user_update";
					$vvalues="\"$tgl_gab\", \"$id_jam\", \"$arjam1\", \"0\", \"0\", \"$id_pegawai\", \"0\", \"$ndatetime\", \"$ndatetime\", \"$ss_id\"";
					
					$inp="insert into ms_shift_pegawai_jam ($vdata) values ($vvalues)";
					mysqli_query($db_result, $inp);
				}
			}
		}
	}
}
?>
