<?php
ini_set('display_errors', 0);
session_start();
$ss_user=$_SESSION["ss_user"];
$ss_id=$_SESSION["ss_id"];
$ss_ket=$_SESSION["ss_ket"];

if(!empty($ss_user) and !empty($ss_id)){
	header("Location:home.php?pages=home");
}else{
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Rumah Sakit Universitas Muhammadiyah Malang</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta name="robots" content="noindex, nofollow">

	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="bootstrap/css/font-awesome.min.css">
	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="plugins/iCheck/square/blue.css">
	
	<style>
		html { 
			background: url(images/bglogin.png) no-repeat center center fixed; 
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}
		
		body{
			background: none !important;
		}
	</style>
</head>

<body style="padding:10px;">
<div class="row" style="margin-bottom:20px;">
	<div class="col-xs-6">
		<img src="images/logo_rsuumm.png" style="height:50px;" />
	</div>
	
	<div class="col-xs-6 text-right">
		<img src="images/logo_larsi_paripurna.png" style="height:50px;" />
	</div>
</div>

<div class="row">
	<div class="col-md-6 text-center">
		<img src="images/motors.png" style="width:80%;" />
	</div>
	
	<div class="col-md-6">
		<div class="row">
			<div class="col-md-12 text-center">
				<img src="images/logo_slogan_sim.png" style="width:35%;" />
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="login-box">
					<div class="login-box-body">
				
						<p class="login-box-msg"><?php echo"$ss_ket"; ?></p>
						<form action="signin.php" method="post">
							<div class="form-group has-feedback">
								<input type="text" name="username" class="form-control" placeholder="Username" required>
								<span class="fa fa-user form-control-feedback"></span>
							</div>
							<div class="form-group has-feedback">
								<input type="password" name="password" class="form-control" placeholder="Password" required>
								<span class="fa fa-lock form-control-feedback"></span>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- jQuery 2.1.4 -->
<script src="plugins/jQuery/jQuery-3.3.1.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
$(function () {
	$('input').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
		increaseArea: '20%'
	});
});
</script>
</body>
</html>
<?php
unset($_SESSION["ss_ket"]);
}
?>
