<?php
if (preg_match("/\bindex.php\b/i", $_SERVER['REQUEST_URI'])) exit;

$db_result = mysqli_connect("localhost", "root", "", "simv2");
$data = [];
$id_jenis = '';

if (!empty($_GET['gid'])) {
    $gid = mysqli_real_escape_string($db_result, $_GET['gid']);
    $sql = "SELECT * FROM inventarisasi_barang WHERE id='$gid'";
    $q = mysqli_query($db_result, $sql);
    $data = mysqli_fetch_assoc($q);
    $id_jenis = $data['id_jenis'] ?? '';
}

$jenis = mysqli_query($db_result, "SELECT * FROM jenis_inventarisasi WHERE status='Aktif'");
$jenis_kode_map = [];
while ($j = mysqli_fetch_array($jenis)) {
    $jenis_kode_map[$j['id']] = $j['kode'];
}
?>

<div class='panel panel-default'>
  <div class='panel-heading'><h3 class='panel-title'>Form Inventarisasi Barang</h3></div>
  <div class='panel-body'>
    <form method='POST' action='modul/inventarisasi_barang/index.php?act=proses'>
      <input type='hidden' name='id' value='<?php echo $data['id'] ?? ''; ?>'>

      <div class='form-group'>
        <label>Kode Barang</label>
        <input type='text' name='kode_barang' id='kode_barang' value='<?php echo $data['kode_barang'] ?? ''; ?>' class='form-control' required readonly>
      </div>

      <div class='form-group'>
        <label>Jenis Inventaris</label>
        <select name='id_jenis' class='form-control' id='id_jenis' required>
          <option value=''>- Pilih Jenis -</option>
          <?php
          foreach ($jenis_kode_map as $id => $kode) {
              $res = mysqli_query($db_result, "SELECT nama_jenis FROM jenis_inventarisasi WHERE id='$id'");
              $nama = mysqli_fetch_array($res)['nama_jenis'];
              $sel = ($id_jenis == $id) ? "selected" : "";
              echo "<option value='$id' $sel>$nama</option>";
          }
          ?>
        </select>
      </div>

      <div class='form-group'>
        <label>Nama Perangkat</label>
        <input type='text' name='nama_perangkat' value='<?php echo $data['nama_perangkat'] ?? ''; ?>' class='form-control'>
      </div>

      <div class='form-group'>
        <label>Merek / Model</label>
        <input type='text' name='merek_model' value='<?php echo $data['merek_model'] ?? ''; ?>' class='form-control'>
      </div>

      <div class='form-group'>
        <label>Serial Number</label>
        <input type='text' name='serial_number' value='<?php echo $data['serial_number'] ?? ''; ?>' class='form-control'>
      </div>

      <div class='form-group'>
        <label>Spesifikasi</label>
        <textarea name='spesifikasi' class='form-control'><?php echo $data['spesifikasi'] ?? ''; ?></textarea>
      </div>

      <div class='form-group'>
        <label>Lokasi</label>
        <input type='text' name='lokasi' value='<?php echo $data['lokasi'] ?? ''; ?>' class='form-control'>
      </div>

      <div class='form-group'>
        <label>Tanggal Pembelian</label>
        <input type='date' name='tanggal_pembelian' value='<?php echo $data['tanggal_pembelian'] ?? ''; ?>' class='form-control'>
      </div>

      <div class='form-group'>
        <label>Kondisi</label>
        <select name='kondisi' class='form-control'>
          <option <?php echo (($data['kondisi'] ?? '') == 'Baik') ? 'selected' : ''; ?>>Baik</option>
          <option <?php echo (($data['kondisi'] ?? '') == 'Cukup') ? 'selected' : ''; ?>>Cukup</option>
          <option <?php echo (($data['kondisi'] ?? '') == 'Rusak') ? 'selected' : ''; ?>>Rusak</option>
        </select>
      </div>

      <div class='form-group'>
        <label>Status</label>
        <select name='status' class='form-control'>
          <option <?php echo (($data['status'] ?? '') == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
          <option <?php echo (($data['status'] ?? '') == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
        </select>
      </div>

      <div class='form-group'>
        <label>Kepemilikan</label>
        <input type='text' name='kepemilikan' value='<?php echo $data['kepemilikan'] ?? ''; ?>' class='form-control'>
      </div>

      <div class='form-group'>
        <label>Keterangan</label>
        <textarea name='keterangan' class='form-control'><?php echo $data['keterangan'] ?? ''; ?></textarea>
      </div>

      <div class='form-group'>
        <label>Mouse/Pad</label>
        <select name='mouse_pad' class='form-control'>
          <option <?php echo (($data['mouse_pad'] ?? '') == 'Ada') ? 'selected' : ''; ?>>Ada</option>
          <option <?php echo (($data['mouse_pad'] ?? '') == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
        </select>
      </div>

      <button type='submit' class='btn btn-success'>Simpan</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const jenisSelect = document.getElementById("id_jenis");
    const kodeInput = document.getElementById("kode_barang");
    const kodePrefix = <?php echo json_encode($jenis_kode_map); ?>;

    jenisSelect.addEventListener('change', function () {
        const selectedId = this.value;
        const prefix = kodePrefix[selectedId] || '';

        if (prefix) {
            fetch('modul/inventarisasi_barang/get_kode_urut.php?id_jenis=' + selectedId)
                .then(response => response.text())
                .then(urut => {
                    const finalKode = prefix + urut.padStart(3, '0');
                    kodeInput.value = finalKode;
                });
        } else {
            kodeInput.value = '';
        }
    });

    jenisSelect.dispatchEvent(new Event('change')); // auto generate saat edit
});
</script>
