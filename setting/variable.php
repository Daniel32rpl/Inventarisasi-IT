<?php
$ndate=date("Y-m-d");
$ntime=date("H:i:s");
$ndatetime="$ndate $ntime";

$batas="25";
$pages      = isset($_GET["pages"]) ? $_GET["pages"] : '';
$act        = isset($_GET["act"]) ? $_GET["act"] : '';
$act2       = isset($_GET["act2"]) ? $_GET["act2"] : '';
$act3       = isset($_GET["act3"]) ? $_GET["act3"] : '';
$act4       = isset($_GET["act4"]) ? $_GET["act4"] : '';
$act5       = isset($_GET["act5"]) ? $_GET["act5"] : '';
$act6       = isset($_GET["act6"]) ? $_GET["act6"] : '';
$act7       = isset($_GET["act7"]) ? $_GET["act7"] : '';
$gid        = isset($_GET["gid"]) ? $_GET["gid"] : '';
$tab        = isset($_GET["tab"]) ? $_GET["tab"] : '';
$gid2       = isset($_GET["gid2"]) ? $_GET["gid2"] : '';
$gid3       = isset($_GET["gid3"]) ? $_GET["gid3"] : '';
$gid4       = isset($_GET["gid4"]) ? $_GET["gid4"] : '';
$gid5       = isset($_GET["gid5"]) ? $_GET["gid5"] : '';
$ghal       = isset($_GET["ghal"]) ? $_GET["ghal"] : '';
$gket       = isset($_GET["gket"]) ? $_GET["gket"] : '';
$gsub       = isset($_GET["gsub"]) ? $_GET["gsub"] : '';
$gup        = isset($_GET["gup"]) ? $_GET["gup"] : '';
$gm         = isset($_GET["gm"]) ? $_GET["gm"] : '';
$galamat    = isset($_GET["galamat"]) ? $_GET["galamat"] : '';
$glevel     = isset($_GET["glevel"]) ? $_GET["glevel"] : '';
$gtahun     = isset($_GET["gtahun"]) ? $_GET["gtahun"] : '';
$gbulan     = isset($_GET["gbulan"]) ? $_GET["gbulan"] : '';
$gtanggal   = isset($_GET["gtanggal"]) ? $_GET["gtanggal"] : '';
$gtanggal2  = isset($_GET["gtanggal2"]) ? $_GET["gtanggal2"] : '';
$gtanggal3  = isset($_GET["gtanggal3"]) ? $_GET["gtanggal3"] : '';
$gtanggal4  = isset($_GET["gtanggal4"]) ? $_GET["gtanggal4"] : '';
$gname      = isset($_GET["gname"]) ? $_GET["gname"] : '';
$gstatus    = isset($_GET["gstatus"]) ? $_GET["gstatus"] : '';

$pages=mysqli_real_escape_string($db_result, $pages);
$act=mysqli_real_escape_string($db_result, $act);
$act2=mysqli_real_escape_string($db_result, $act2);
$act3=mysqli_real_escape_string($db_result, $act3);
$act4=mysqli_real_escape_string($db_result, $act4);
$act5=mysqli_real_escape_string($db_result, $act5);
$act6=mysqli_real_escape_string($db_result, $act6);
$act7=mysqli_real_escape_string($db_result, $act7);
$tab=mysqli_real_escape_string($db_result, $tab);
$gname=mysqli_real_escape_string($db_result, $gname);
$gket=mysqli_real_escape_string($db_result, $gket);
$ghal=mysqli_real_escape_string($db_result, $ghal);
$gid=mysqli_real_escape_string($db_result, $gid);
$gid2=mysqli_real_escape_string($db_result, $gid2);
$gid3=mysqli_real_escape_string($db_result, $gid3);
$gid4=mysqli_real_escape_string($db_result, $gid4);
$gid5=mysqli_real_escape_string($db_result, $gid5);
$gsub=mysqli_real_escape_string($db_result, $gsub);
$gup=mysqli_real_escape_string($db_result, $gup);
$gm=mysqli_real_escape_string($db_result, $gm);
$gtahun=mysqli_real_escape_string($db_result, $gtahun);
$gbulan=mysqli_real_escape_string($db_result, $gbulan);
$gtanggal=mysqli_real_escape_string($db_result, $gtanggal);
$gtanggal2=mysqli_real_escape_string($db_result, $gtanggal2);
$gtanggal3=mysqli_real_escape_string($db_result, $gtanggal3);
$gtanggal4=mysqli_real_escape_string($db_result, $gtanggal4);
$gstatus=mysqli_real_escape_string($db_result, $gstatus);
$galamat=mysqli_real_escape_string($db_result, $galamat);
$glevel=mysqli_real_escape_string($db_result, $glevel);

$link_back="?pages=$pages";
$ghal=($ghal>=1)? "$ghal" : "1";

#$phost=$_SERVER["HTTP_HOST"];
$phost="localhost";
$phost="http://localhost/simv2";

$arvar=Variable();
foreach($arvar as $kk => $dt){$$kk=$dt;}
?>