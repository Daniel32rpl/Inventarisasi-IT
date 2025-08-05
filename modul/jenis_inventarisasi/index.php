<?php
if (preg_match("/\bindex.php\b/i", $_SERVER['REQUEST_URI'])) exit;

switch ($act) {
    default:
        echo "<h3 class='box-title'>Data Jenis Inventarisasi</h3>";
        echo "<a href=\"$link_back&act=input\" class=\"btn btn-primary\"><i class=\"fa fa-plus\"></i> Tambah Jenis</a><br><br>";

        $sql = "SELECT * FROM jenis_inventarisasi ORDER BY id DESC";
        $query = mysqli_query($db_result, $sql);

        echo "<div class='panel panel-default'>
        <div class='panel-body'>
        <div class='table-responsive'>
        <table class='table table-bordered'>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Jenis</th>
                <th>Kode</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead><tbody>";

        $no = 1;
        while ($r = mysqli_fetch_array($query)) {
            echo "<tr>
                <td>$no</td>
                <td>$r[nama_jenis]</td>
                <td>$r[kode]</td>
                <td>$r[keterangan]</td>
                <td>$r[status]</td>
                <td>
                    <a href=\"$link_back&act=input&gid=$r[id]\" class=\"btn btn-xs btn-success\">Edit</a>
                    <a href=\"$link_back&act=hapus&gid=$r[id]\" onclick=\"return confirm('Hapus data ini?')\" class=\"btn btn-xs btn-danger\">Hapus</a>
                </td>
            </tr>";
            $no++;
        }
        echo "</tbody></table></div></div></div>";
        break;

    case "input":
        if (!empty($_GET['gid'])) {
            $sql = "SELECT * FROM jenis_inventarisasi WHERE id='$_GET[gid]'";
            $q = mysqli_query($db_result, $sql);
            $data = mysqli_fetch_assoc($q);
        }

        echo "<div class='row'>
        <div class='col-md-12'>
            <div class='panel panel-default'>
                <div class='panel-heading'>
                    <h3 class='panel-title'>Form Jenis Inventarisasi</h3>
                </div>
                <div class='panel-body'>
                    <form method='post' action='$link_back&act=proses' class='form-horizontal'>
                        <input type='hidden' name='id' value='" . ($_GET['gid'] ?? '') . "'>

                        <div class='form-group'>
                            <label class='col-sm-2 control-label'>Nama Jenis</label>
                            <div class='col-sm-5'>
                                <input type='text' name='nama_jenis' value='" . ($data['nama_jenis'] ?? '') . "' class='form-control' required>
                            </div>
                        </div>

                        <div class='form-group'>
                            <label class='col-sm-2 control-label'>Keterangan</label>
                            <div class='col-sm-8'>
                                <input type='text' name='keterangan' value='" . ($data['keterangan'] ?? '') . "' class='form-control' required>
                            </div>
                        </div>

                        <div class='form-group'>
                            <label class='col-sm-2 control-label'>Status</label>
                            <div class='col-sm-3'>
                                <select name='status' class='form-control' required>
                                    <option value='Aktif' " . (($data['status'] ?? '') == 'Aktif' ? 'selected' : '') . ">Aktif</option>
                                    <option value='Tidak Aktif' " . (($data['status'] ?? '') == 'Tidak Aktif' ? 'selected' : '') . ">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class='col-sm-offset-2 col-sm-5'>
                                <button type='submit' class='btn btn-success'><i class='fa fa-save'></i> Simpan</button>
                                <a href='$link_back' class='btn btn-default'><i class='fa fa-caret-left'></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>";
        break;

    case "proses":
        $id = $_POST['id'];
        $nama_jenis = trim($_POST['nama_jenis']);
        $keterangan = trim($_POST['keterangan']);
        $status = $_POST['status'];

        // Menentukan kode berdasarkan nama jenis
        switch (strtolower($nama_jenis)) {
            case 'perangkat komputer': $kode = 'PC'; break;
            case 'perangkat input/output': $kode = 'IO'; break;
            case 'perangkat penyimpanan data': $kode = 'ST'; break;
            case 'perangkat jaringan dan komunikasi': $kode = 'NW'; break;
            case 'perangkat mobile': $kode = 'MB'; break;
            case 'perangkat keamanan dan pengawasan': $kode = 'SEC'; break;
            case 'perangkat pendukung': $kode = 'SUP'; break;
            case 'perangkat audio visual': $kode = 'AV'; break;
            case 'perangkat lunak dan lisensi': $kode = 'SW'; break;
            default: $kode = strtoupper(substr($nama_jenis, 0, 3)); break;
        }

        if (!empty($id)) {
            $sql = "UPDATE jenis_inventarisasi SET 
                    nama_jenis='$nama_jenis',
                    kode='$kode',
                    keterangan='$keterangan',
                    status='$status'
                    WHERE id='$id'";
        } else {
            $sql = "INSERT INTO jenis_inventarisasi (nama_jenis, kode, keterangan, status)
                    VALUES ('$nama_jenis', '$kode', '$keterangan', '$status')";
        }

        if (mysqli_query($db_result, $sql)) {
            echo "<div class='alert alert-success'>Data berhasil disimpan</div>";
        } else {
            echo "<div class='alert alert-danger'>Gagal menyimpan data</div>";
        }

        echo "<meta http-equiv='refresh' content='1;url=$link_back'>";
        break;

    case "hapus":
        $gid = $_GET['gid'];

        mysqli_query($db_result, "DELETE FROM inventarisasi_barang WHERE id_jenis = '$gid'");
        $sql = "DELETE FROM jenis_inventarisasi WHERE id = '$gid'";
        if (mysqli_query($db_result, $sql)) {
            echo "<div class='alert alert-success'>Data berhasil dihapus</div>";
        } else {
            echo "<div class='alert alert-danger'>Gagal menghapus data</div>";
        }

        echo "<meta http-equiv='refresh' content='1;url=$link_back'>";
        break;
}
?>
