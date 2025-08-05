<?php
if (!isset($_GET['id_jenis']) || !is_numeric($_GET['id_jenis'])) {
    echo "001";
    exit;
}

$db_result = mysqli_connect("localhost", "root", "", "simv2");
if (!$db_result) {
    echo "001";
    exit;
}

$id_jenis = (int)$_GET['id_jenis'];

// Get the prefix for this jenis
$prefix_query = mysqli_query($db_result, "SELECT kode FROM jenis_inventarisasi WHERE id = $id_jenis");
if (!$prefix_query || mysqli_num_rows($prefix_query) == 0) {
    echo "001";
    exit;
}

$prefix_data = mysqli_fetch_array($prefix_query);
$prefix = $prefix_data['kode'];

// Get the last number for this jenis
$last_query = mysqli_query($db_result, "
    SELECT kode_barang 
    FROM inventarisasi_barang 
    WHERE id_jenis = $id_jenis 
    AND kode_barang LIKE '$prefix%' 
    ORDER BY id DESC 
    LIMIT 1
");

$next_number = 1;
if ($last_query && mysqli_num_rows($last_query) > 0) {
    $last_data = mysqli_fetch_array($last_query);
    $last_kode = $last_data['kode_barang'];
    
    // Extract number from the end of the code
    $number_part = substr($last_kode, strlen($prefix));
    if (is_numeric($number_part)) {
        $next_number = (int)$number_part + 1;
    }
}

echo str_pad($next_number, 3, '0', STR_PAD_LEFT);

mysqli_close($db_result);
?>
