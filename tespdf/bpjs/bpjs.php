<?php
include"kon.php";
$vclaim_id="24440";
$vclaim_key="0bOD41EEBE";
$url_bpjs="https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest";

function CekVclaim($urlbpjs, $data, $secretKey){
	$ndate=date("Y-m-d");
	
	date_default_timezone_set('UTC');
	$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
	$signature = hash_hmac('sha256', $data."&".$tStamp, $secretKey, true);
	$encodedSignature = base64_encode($signature);

	$headers=array(
	    "X-cons-id: ".$data."",
	    "X-timestamp: ".$tStamp."",
	    "X-signature: ".$encodedSignature.""
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $urlbpjs);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$server_output = curl_exec ($ch);
	curl_close ($ch);

	$list=json_decode($server_output);
	return $list;
}


$sqld="select * from tbl_bpjs where STATUS=\"0\" limit 1";
$data=mysqli_query($db_result, $sqld);
$ndata=mysqli_num_rows($data);
if($ndata>0){
	$fdata=mysqli_fetch_assoc($data);
	extract($fdata);
	
	/*cari sep*/
	$urlbpjs="$url_bpjs/SEP/$NOSEP";
	$cb=CekVclaim($urlbpjs, $vclaim_id, $vclaim_key);
	
	/* echo"<pre>";
	print_r($cb);
	echo"</pre>"; */
	
	$kelamin=$cb->response->peserta->kelamin;
	$no_rujukan=$cb->response->noRujukan;

	$urlbpjs2="$url_bpjs/rujukan/$no_rujukan";
	$cb2=CekVclaim($urlbpjs2, $vclaim_id, $vclaim_key);
	$plonar=$cb2->response->rujukan->peserta->informasi->prolanisPRB;
	
	$vdata="KET=\"$plonar\", NO_RUJUKAN=\"$no_rujukan\", JK=\"$kelamin\", STATUS=\"1\"";
	$vvalues="ID=\"$ID\"";
	
	$inp="update tbl_bpjs set $vdata where $vvalues";
	mysqli_query($db_result, $inp);
}

echo"<meta http-equiv=\"refresh\" content=\"1;url=\">";
?>