<?php
function Variable(){
	include"kon.php";
	
	$data_array=array();
	$sqld="select * from db_setting";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		while($fdata=mysqli_fetch_assoc($data)){
			extract($fdata);
			$data_array["$fvar"]="$fket";
		}
	}

	return $data_array;
}

function Menu($idmenu){
	include"kon.php";
	$list=array();

	if(!empty($idmenu)){
		$sqld="select * from db_menu where id in ($idmenu) and sub_id=\"0\" and status=\"1\" and hapus=\"0\" order by urutan asc";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				$id1=$fdata["id"];
				$nama_menu1=$fdata["nama_menu"];
				$page1=$fdata["link_page"];
				$modul1=$fdata["modul"];
				$faicon1=$fdata["faicon"];
				$list3=array();

				$sqld2="select * from db_menu where id in ($idmenu) and sub_id=\"$id1\" and status=\"1\" and hapus=\"0\" order by urutan asc";
				$data2=mysqli_query($db_result, $sqld2);
				$ndata2=mysqli_num_rows($data2);
				if($ndata2>0){
					while($fdata2=mysqli_fetch_assoc($data2)){
						$id2=$fdata2["id"];
						$nama_menu2=$fdata2["nama_menu"];
						$page2=$fdata2["link_page"];
						$modul2=$fdata2["modul"];
						$faicon2=$fdata2["faicon"];

						$list3[]=array($id2, $nama_menu2, $faicon2, $page2, $modul2);
					}
				}

				$list2[]=array($id1, $nama_menu1, $faicon1, $page1, $modul1, $list3);
			}
		}
	}

	$list=array();
	$list["menu"]=$list2;

	return $list;
}

function MenuAkses(){
	include"kon.php";

	$sqld="select * from db_menu where sub_id=\"0\" and status=\"1\" and hapus=\"0\" order by urutan asc";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		while($fdata=mysqli_fetch_assoc($data)){
			$id1=$fdata["id"];
			$nama_menu1=$fdata["nama_menu"];
			$page1=$fdata["link_page"];
			$modul1=$fdata["modul"];
			$faicon1=$fdata["faicon"];
			$list3=array();

			$sqld2="select * from db_menu where sub_id=\"$id1\" and status=\"1\" and hapus=\"0\" order by urutan asc";
			$data2=mysqli_query($db_result, $sqld2);
			$ndata2=mysqli_num_rows($data2);
			if($ndata2>0){
				while($fdata2=mysqli_fetch_assoc($data2)){
					$id2=$fdata2["id"];
					$nama_menu2=$fdata2["nama_menu"];
					$page2=$fdata2["link_page"];
					$modul2=$fdata2["modul"];
					$faicon2=$fdata2["faicon"];

					$list3[]=array($id2, $nama_menu2, $faicon2, $page2, $modul2);
				}
			}

			$list2[]=array($id1, $nama_menu1, $faicon1, $page1, $modul1, $list3);
		}
	}

	$list=array();
	$list["menu"]=$list2;

	return $list;
}

function User($ss_id){
	include"kon.php";
	$sqld="select * from db_user where id=\"$ss_id\" and status=\"1\" and hapus=\"0\"";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		$fdata=mysqli_fetch_assoc($data);
		$nama_user=$fdata["nama_user"];
		$level_user=$fdata["level_user"];
		$id_pegawai=$fdata["id_pegawai"];
	}

	$list=array();
	$list["nama_user"]="$nama_user";
	$list["level_user"]="$level_user";
	$list["id_pegawai"]="$id_pegawai";

	return $list;
}

function LevelUser($lv=""){
	include"kon.php";

	if(!empty($lv)){
		$wh="and id=\"$lv\"";
	}

	$sqld="select * from db_user_level where status=\"1\" and hapus=\"0\" ";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		while($fdata=mysqli_fetch_assoc($data)){
			$id=$fdata["id"];
			$list[$id]=$fdata;
		}
	}

	return $list;
}

function AksesMenu($lv){
	include"kon.php";
	$list=array();

	$sqld="select * from db_user_akses where id_level=\"$lv\" and status=\"1\" and hapus=\"0\"";
	$data=mysqli_query($db_result, $sqld);
	$ndata=mysqli_num_rows($data);
	if($ndata>0){
		while($fdata=mysqli_fetch_assoc($data)){
			extract($fdata);

			$list[$id_menu]=array($id_menu, $hk_add, $hk_edit, $hk_delete);
		}
	}

	return $list;
}

function SingkatanBulan($gid){
	$gid=(int)$gid;

	switch($gid){
		case"1": $list=array("JAN", "Januari", "JAN"); break;
		case"2": $list=array("FEB", "Februari", "FEB"); break;
		case"3": $list=array("MAR", "Maret", "MAR"); break;
		case"4": $list=array("APR", "April", "APR"); break;
		case"5": $list=array("MEI", "Mei", "MAY"); break;
		case"6": $list=array("JUN", "Juni", "JUN"); break;
		case"7": $list=array("JUL", "Juli", "JUL"); break;
		case"8": $list=array("AGU", "Agustus", "AUG"); break;
		case"9": $list=array("SEP", "September", "SEP"); break;
		case"10": $list=array("OKT", "Oktober", "OCT"); break;
		case"11": $list=array("NOV", "November", "NOV"); break;
		case"12": $list=array("DES", "Desember", "DEC"); break;
	}

	return $list;
}

function NamaHari($gid){
	$gid=(int)$gid;

	switch($gid){
		case"1": $list="Senin"; break;
		case"2": $list="Selasa"; break;
		case"3": $list="Rabu"; break;
		case"4": $list="Kamis"; break;
		case"5": $list="Jumat"; break;
		case"6": $list="Sabtu"; break;
		case"7": $list="Minggu"; break;
	}

	return $list;
}

function TglFormat1($gid){
	$list=($gid<="0000-00-00")? "" : date("d-m-Y", strtotime("$gid"));
	return $list;
}

function TglFormat2($gid){
	if($gid>"0000-00-00"){
		list($th, $bl, $tg)=explode("-", $gid);
		$nama_bulan=SingkatanBulan($bl);
		$nama_bulan=$nama_bulan[0];

		$list="$tg $nama_bulan $th";
	}

	return $list;
}

function TglFormat3($gid){
	$list=($gid<="0000-00-00")? "" : date("d-m-Y H:i:s", strtotime("$gid"));
	return $list;
}

function TglFormat4($gid){
	if($gid>"0000-00-00"){
		list($gid, $gid1)=explode(" ", $gid);
		list($th, $bl, $tg)=explode("-", $gid);
		$nama_bulan=SingkatanBulan($bl);
		$nama_bulan=$nama_bulan[1];

		$list="$tg $nama_bulan $th";
	}

	return $list;
}

function TglFormat5($gid){
	if($gid>"0000-00-00"){
		list($gid, $gid1)=explode(" ", $gid);
		list($th, $bl, $tg)=explode("-", $gid);
		$nama_bulan=SingkatanBulan($bl);
		$nama_bulan=$nama_bulan[2];

		$list="$tg $nama_bulan $th";
	}

	return $list;
}

function KodeBooking($panjang){
	$karakter= '1234567890';
	$string = '';
	for ($i = 0; $i < $panjang; $i++) {
		$pos = rand(0, strlen($karakter)-1);
		$string .= $karakter[$pos];
	}
	return $string;
}

function SelStatus(){
	$list=array(
		"1" => array("1", "Aktif", "Digunakan", "Laki-Laki", "Belum", "Baru", "ICD 9-CM", "Ya", "Aktif", "Paket", "Faskes Tingkat 1", "Rawat Inap", "Bicarbonat", "+", "FORMULARIUM RS", "FORMULARIUM NASIONAL", "Masuk Presensi", "L", "BPJS", "Naik Pangkat", "Home", "Teratur"),
		"2" => array("2", "Tidak Aktif", "Tidak Digunakan", "Perempuan", "Selesai", "Lama", "ICD 10", "Tidak", "Meninggal", "Tdk. Paket", "Faskes Tingkat 2", "Rawat Jalan", "Acid", "-", "NON FORMULARIUM RS", "NON FORMULARIUM NASIONAL", "Tidak Masuk Presensi", "P", "Umum/Asuransi Lain", "Berkala", "Mitra", "Tidak Teratur")
	);

	return $list;
}

function SelKab(){
	$list=array(
		"KAB." => array("KAB."),
		"KOTA" => array("KOTA")
	);

	return $list;
}

function SelKel(){
	$list=array(
		"KEL." => array("KEL."),
		"DESA" => array("DESA")
	);

	return $list;
}

#halaman
function CariPosisi($batas, $hal){
	if(empty($hal)){$posisi=0; $hal=1;}
	else{$posisi = ($hal-1) * $batas;}
	return $posisi;
}

function JumlahHalaman($jmldata, $batas){
	$jmlhalaman = @ceil($jmldata/$batas);
	return $jmlhalaman;
}

function NavPage($h_aktif, $jmlhal){
	$link_hal=array();
	$h_aktif=($h_aktif<=0)? "1" : "$h_aktif";

	if($jmlhal>1){
		if($h_aktif > 1){
			$p=$h_aktif-1;
			$link_hal[]=array("1", "first");
			$link_hal[]=array("$p", "back");
		}

		for($i=$h_aktif-2;$i<$h_aktif;$i++){
			if ($i < 1) continue;
			$link_hal[]=array("$i", "$i");
		}

		$link_hal[]=array("$i", "active");

		for($i=$h_aktif+1;$i<($h_aktif+3);$i++){
			if ($i > $jmlhal) break;
			$link_hal[]=array("$i", "$i");
		}

		if($h_aktif<$jmlhal){
			$n=$h_aktif+1;
			$link_hal[]=array("$n", "next");
			$link_hal[]=array("$jmlhal", "last");
		}
	}

	return $link_hal;
}

function NumberFormat($number){
	$number=($number>0)? "$number" : "0";
	$number=@number_format($number, 0, ",", ".");
	return $number;
}
?>