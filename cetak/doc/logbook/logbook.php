<?php
if(preg_match("/\blogbook.php\b/i", $_SERVER['REQUEST_URI'])){
	exit;
}else{

switch($gid){
	case"perawat": include"perawat.php"; break;
	default: break;
	
}

}
?>
