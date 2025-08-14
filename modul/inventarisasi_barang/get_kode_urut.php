<?php
$db_result = mysqli_connect("localhost", "root", "", "simv2");
if (!$db_result) {
    die("Connection failed: " . mysqli_connect_error());
}

$id_jenis = $_GET['id_jenis'] ?? '';
$lokasi = $_GET['lokasi'] ?? '';

if (empty($id_jenis) || !is_numeric($id_jenis) || empty($lokasi)) {
    echo "XX001"; // fallback default jika data tidak valid
    exit;
}

// Ambil kode jenis (prefix)
$q_prefix = mysqli_query($db_result, "SELECT kode FROM jenis_inventarisasi WHERE id = " . (int)$id_jenis);
if (!$q_prefix || mysqli_num_rows($q_prefix) == 0) {
    echo "XX001"; // fallback jika tidak ditemukan
    exit;
}
$row_prefix = mysqli_fetch_assoc($q_prefix);
$prefix = $row_prefix['kode']; // contoh: 'PC', 'IO', 'ST'

// Escape lokasi untuk keamanan
$lokasi_escaped = mysqli_real_escape_string($db_result, $lokasi);

$sql = "SELECT kode_barang FROM inventarisasi_barang 
        WHERE lokasi = '$lokasi_escaped'
        AND kode_barang IS NOT NULL 
        AND kode_barang != ''
        AND LENGTH(kode_barang) >= 4
        ORDER BY CAST(SUBSTRING(kode_barang, -3) AS UNSIGNED) DESC 
        LIMIT 1";

$result = mysqli_query($db_result, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $last_kode = $row['kode_barang']; // contoh: IO002, PC005, ST003
    $angka = (int)substr($last_kode, -3); // ambil 002, 005, 003
    $angka++; // naikkan jadi 003, 006, 004
    echo $prefix . str_pad($angka, 3, '0', STR_PAD_LEFT); // hasil: PC003, IO006, ST004
} else {
    // jika belum ada data di lokasi ini, mulai dari 001
    echo $prefix . "001";
}

mysqli_close($db_result);
?>
