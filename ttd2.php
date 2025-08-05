<!doctype html>
<html>
<head>
	<title>TTD Pasien</title>
</head>
<body>
<?php
include"setting/kon.php";
include"setting/function.php";
include"setting/variable.php";

$sqld="select * from ps_pendaftaran where id=\"$gid\" and hapus=\"0\"";
$data=mysqli_query($db_result, $sqld);
$ndata=mysqli_num_rows($data);
if($ndata>0){
	$fdata=mysqli_fetch_assoc($data);
	extract($fdata);
	
	echo"<script src=\"plugins/jQuery/jQuery-3.3.1.js\"></script>
	<script src=\"plugins/jSignature-master/libs/jSignature.min.js\"></script>
    <script src=\"plugins/jSignature-master/libs/modernizr.js\"></script>
	
	<!--[if lt IE 9]>
		<script type=\"text/javascript\" src=\"plugins/jSignature-master/libs/flashcanvas.js\"></script>
    <![endif]-->

	<!-- jSignature -->
	<style>
		#signature{
			margin-top:300px;
			width: 100%;
			height: auto;
			border: 20px solid #000000;
		}
	</style>

	<!-- Signature area -->
	<div id=\"signature\"></div><br />

	<center>
		<input type='button' id='click' value='simpan' style='font-size:50px;background:#000000;color:#ffffff;padding:20px;border:1px solid #000000;'> &nbsp; 
		<input type='button' onclick='window.location.reload()' value='ulangi' style='font-size:50px;padding:20px;border:1px solid #000000;'>
	</center>

	<!-- Script -->
	<script>
		$(document).ready(function() {
			// Initialize jSignature
			var \$sigdiv = $(\"#signature\").jSignature({
				'UndoButton':false,
				'color':\"#000000\",
				'lineWidth':15,
				'height': '700px',
				'width': '100%'
			});

			$('#click').click(function(){
				// Get response of type image
				var data1 = \$sigdiv.jSignature('getData', 'image');
				
				var data2 = \"data:\"+data1;
				var img_data = data2.replace(/^data:image\/(png|jpg);base64,/, \"\");
				
				$.ajax({
					url: 'load_data.php?gid=ttd&gid2=$kode_rm',
					data: { img_data:img_data },
					type: 'post',
					dataType: 'json',
					success: function (response) {
					   alert('Tanda Tangan Berhasil Disimpan');
					   window.close();
					}
				});
			});
		});
	</script>";
}
?>
</body>
</html>