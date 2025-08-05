<?php
if (preg_match("/\bindex.php\b/i", $_SERVER['REQUEST_URI'])) exit;

$db_result = mysqli_connect("localhost", "root", "", "simv2");

// Check database connection
if (!$db_result) {
    die("Connection failed: " . mysqli_connect_error());
}

switch ($act) {
    default:
        echo "<h3 class='box-title'>Data Inventarisasi Barang</h3>";
        echo "<a href=\"$link_back&act=input\" class=\"btn btn-primary\"><i class=\"fa fa-plus\"></i> Tambah Data</a><br><br>";
        
        $sqld = "SELECT b.*, j.nama_jenis FROM inventarisasi_barang b
                JOIN jenis_inventarisasi j ON j.id = b.id_jenis
                ORDER BY b.id DESC";
        $query = mysqli_query($db_result, $sqld);
        
        echo "<div class='panel panel-default'>
        <div class='panel-body'>
        <div class='table-responsive'>
        <table class='table table-bordered'>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Jenis</th>
                <th>Nama Perangkat</th>
                <th>Merek/Model</th>
                <th>Serial Number</th>
                <th>Spesifikasi</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead><tbody>";
        
        $no = 1;
        while ($r = mysqli_fetch_array($query)) {
            echo "<tr>
                <td>$no</td>
                <td>{$r['kode_barang']}</td>
                <td>{$r['nama_jenis']}</td>
                <td>{$r['nama_perangkat']}</td>
                <td>{$r['merek_model']}</td>
                <td>{$r['serial_number']}</td>
                <td>{$r['spesifikasi']}</td>
                <td>{$r['lokasi']}</td>
                <td>{$r['status']}</td>
                <td>
                    <a href=\"$link_back&act=input&gid={$r['id']}\" class=\"btn btn-xs btn-success\">Edit</a>
                    <a href=\"$link_back&act=hapus&gid={$r['id']}\" onclick=\"return confirm('Yakin hapus data ini?')\" class=\"btn btn-xs btn-danger\">Hapus</a>
                </td>
            </tr>";
            $no++;
        }
        echo "</tbody></table></div></div></div>";
        break;

    case "hapus":
        if (isset($_GET['gid']) && is_numeric($_GET['gid'])) {
            $gid = (int)$_GET['gid'];
            $sqld = "DELETE FROM inventarisasi_barang WHERE id = $gid";
            if (mysqli_query($db_result, $sqld)) {
                echo "<div class='alert alert-success'>Data berhasil dihapus</div>";
            } else {
                echo "<div class='alert alert-danger'>Gagal menghapus data: " . mysqli_error($db_result) . "</div>";
            }
            echo "<meta http-equiv='refresh' content='1;url=$link_back'>";
        }
        break;

    case "input":
        include "form_input.php";
        $data = [];
        if (isset($_GET['gid']) && is_numeric($_GET['gid'])) {
            $gid = (int)$_GET['gid'];
            $sqld = "SELECT * FROM inventarisasi_barang WHERE id = $gid";
            $result = mysqli_query($db_result, $sqld);
            if ($result && mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_array($result);
            }
        }
        break;

    case "proses":
        // Check if form was submitted via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "<div class='alert alert-danger'>Invalid request method</div>";
            break;
        }

        // Validate required fields
        if (empty($_POST['kode_barang']) || empty($_POST['id_jenis'])) {
            echo "<div class='alert alert-danger'>Kode barang dan jenis wajib diisi!</div>";
            echo "<meta http-equiv='refresh' content='2;url=$link_back&act=input'>";
            break;
        }

        // Sanitize data
        $data = [];
        $fields = [
            'id', 'kode_barang', 'id_jenis', 'nama_perangkat', 'merek_model', 
            'serial_number', 'spesifikasi', 'lokasi', 'tanggal_pembelian', 
            'kondisi', 'status', 'kepemilikan', 'keterangan', 'mouse_pad'
        ];

        foreach ($fields as $field) {
            $data[$field] = isset($_POST[$field]) ? mysqli_real_escape_string($db_result, trim($_POST[$field])) : '';
        }

        // Validate id_jenis is numeric
        if (!is_numeric($data['id_jenis'])) {
            echo "<div class='alert alert-danger'>ID Jenis harus berupa angka!</div>";
            break;
        }

        try {
            if (empty($data['id']) || !is_numeric($data['id'])) {
                // INSERT new record
                $sqld = "INSERT INTO inventarisasi_barang (
                            kode_barang, id_jenis, nama_perangkat, merek_model, serial_number,
                            spesifikasi, lokasi, tanggal_pembelian, kondisi, status,
                            kepemilikan, keterangan, mouse_pad, tgl_input, tgl_update
                        ) VALUES (
                            '{$data['kode_barang']}', '{$data['id_jenis']}', '{$data['nama_perangkat']}', 
                            '{$data['merek_model']}', '{$data['serial_number']}', '{$data['spesifikasi']}', 
                            '{$data['lokasi']}', " . (empty($data['tanggal_pembelian']) ? 'NULL' : "'{$data['tanggal_pembelian']}'") . ", 
                            '{$data['kondisi']}', '{$data['status']}', '{$data['kepemilikan']}', 
                            '{$data['keterangan']}', '{$data['mouse_pad']}', NOW(), NOW()
                        )";
            } else {
                // UPDATE existing record
                $id = (int)$data['id'];
                $sqld = "UPDATE inventarisasi_barang SET
                            kode_barang='{$data['kode_barang']}', id_jenis='{$data['id_jenis']}', 
                            nama_perangkat='{$data['nama_perangkat']}', merek_model='{$data['merek_model']}', 
                            serial_number='{$data['serial_number']}', spesifikasi='{$data['spesifikasi']}',
                            lokasi='{$data['lokasi']}', tanggal_pembelian=" . (empty($data['tanggal_pembelian']) ? 'NULL' : "'{$data['tanggal_pembelian']}'") . ",
                            kondisi='{$data['kondisi']}', status='{$data['status']}', 
                            kepemilikan='{$data['kepemilikan']}', keterangan='{$data['keterangan']}',
                            mouse_pad='{$data['mouse_pad']}', tgl_update=NOW()
                        WHERE id = $id";
            }

            // Execute query
            if (mysqli_query($db_result, $sqld)) {
                echo "<div class='alert alert-success'>Data berhasil disimpan</div>";
                echo "<meta http-equiv='refresh' content='1;url=$link_back'>";
            } else {
                throw new Exception(mysqli_error($db_result));
            }

        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Gagal menyimpan data: " . $e->getMessage() . "</div>";
            echo "<meta http-equiv='refresh' content='3;url=$link_back&act=input'>";
        }
        break;
}

// Get all jenis and kode for JavaScript
$jenis_q = mysqli_query($db_result, "SELECT id, kode FROM jenis_inventarisasi ORDER BY nama_jenis");
$kode_map = [];
if ($jenis_q) {
    while ($j = mysqli_fetch_array($jenis_q)) {
        $kode_map[$j['id']] = $j['kode'];
    }
}

mysqli_close($db_result);
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const jenisSelect = document.querySelector("select[name='id_jenis']");
    const kodeInput = document.querySelector("input[name='kode_barang']");
    const kodePrefix = <?php echo json_encode($kode_map); ?>;
    
    if (jenisSelect && kodeInput) {
        jenisSelect.addEventListener('change', function () {
            const selectedId = this.value;
            const prefix = kodePrefix[selectedId] || '';
            
            if (prefix && selectedId) {
                fetch('get_kode_urut.php?id_jenis=' + selectedId)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(urut => {
                        const finalKode = prefix + urut.padStart(3, '0');
                        kodeInput.value = finalKode;
                    })
                    .catch(error => {
                        console.error('Error fetching kode urut:', error);
                    });
            } else {
                kodeInput.value = '';
            }
        });
        
        // Trigger change event on page load if there's a selected value
        if (jenisSelect.value) {
            jenisSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>
