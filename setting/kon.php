<?php
$db_server="localhost";
$db_user="root";
$db_pwd="";

$db_db1="simv2";

$db_result=mysqli_connect($db_server,$db_user,$db_pwd);
if(!$db_result){
	echo"Database no Conection";
	exit;
}else{
	mysqli_select_db($db_result, $db_db1);
}
?>
