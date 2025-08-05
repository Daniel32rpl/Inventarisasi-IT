<?php
session_start();
$ss_user=$_SESSION["ss_user"];
$ss_id=$_SESSION["ss_id"];

header("location:./");
session_destroy();
?>