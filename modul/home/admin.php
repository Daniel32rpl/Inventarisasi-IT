<?php
if(preg_match("/\badmin.php\b/i", $_SERVER['REQUEST_URI'])){
    exit;
}
?>