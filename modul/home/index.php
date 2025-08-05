<?php
if(preg_match("/\bindex.php\b/i", $_SERVER['REQUEST_URI'])){
    exit;
}

switch($glevel){
	default:
		include"admin.php";
	break;
	
	case"level":
		$sqld21="select * from db_user_role where hapus=0 and id_user=\"$ss_id\" and id_level_user=\"$gid\"";
		$data21=mysqli_query($db_result, $sqld21);
		$ndata21=mysqli_num_rows($data21);
		if($ndata21>0){
			$fdata21=mysqli_fetch_assoc($data21);
			$id21=$fdata21["id_level_user"];
			
			$vdata="level_user=\"$id21\"";
			$vvalues="id=\"$ss_id\"";

			$inp="update db_user set $vdata where $vvalues";
			mysqli_query($db_result, $inp);
		}
		
		echo"<meta http-equiv=\"refresh\" content=\"0;url=?pages=home\">";
	break;
}
?>