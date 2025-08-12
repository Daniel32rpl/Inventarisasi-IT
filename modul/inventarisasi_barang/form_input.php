<?php
if (preg_match("/\bindex.php\b/i", $_SERVER['REQUEST_URI'])) exit;

$db_result = mysqli_connect("localhost", "root", "", "simv2");

$data = [];
$id_jenis = $_GET['id_jenis'] ?? '';

if (!empty($_GET['gid'])) {
    $gid = mysqli_real_escape_string($db_result, $_GET['gid']);
    $sql = "SELECT * FROM inventarisasi_barang WHERE id='$gid'";
    $res = mysqli_query($db_result, $sql);
    $data = mysqli_fetch_assoc($res);
    $id_jenis = $data['id_jenis'] ?? '';
}

// Jenis Inventaris
$q = mysqli_query($db_result, "SELECT id, nama_jenis, kode FROM jenis_inventarisasi WHERE status='1' ORDER BY nama_jenis ASC");
$jenis_kode_map = [];
while ($r = mysqli_fetch_array($q)) {
    $jenis_kode_map[$r['id']] = $r['kode'];
}

// Lokasi
$lokasi_options = [
    "Rekam Medis", "Dokumen RM", "CASEMIX", "Bidang 1 Pelayanan", "Kepegawaian",
    "Keuangan", "Hukum", "Humas", "Ruang Direktur", "Ruang Dewan Pengawas",
    "Ruang Wakil Direktur 3", "Ruang Rektor", "Ruang Sekretaris Direktur", "Ruang Pertemuan",
    "Forensik", "Kesling", "Ruang IT", "Binroh", "Ruang Komite PPI", "Gudang Logistik Non Medis",
    "Alkex", "Mechanical Electrical"
];

// URL Form Action
$current_script = $_SERVER['PHP_SELF'];
$query_string = $_SERVER['QUERY_STRING'];
parse_str($query_string, $query_parts);
unset($query_parts['act'], $query_parts['gid']);
$query_parts['act'] = 'proses';
$form_action = $current_script . '?' . http_build_query($query_parts);
?>

<div class="box">
  <div class="box-header border-bottom1">
    <h3 class="box-title">Form Inventarisasi Barang</h3>
  </div>
  <div class="box-body">
    <form method="POST" action="<?php echo $form_action; ?>">
      <input type="hidden" name="id" value="<?php echo $data['id'] ?? ''; ?>">

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Jenis Inventaris</label>
            <select name="id_jenis" class="form-control" id="id_jenis" required>
              <option value="">- Pilih Jenis -</option>
              <?php
              foreach ($jenis_kode_map as $id => $kode) {
                  $sel = ($id_jenis == $id) ? "selected" : "";
                  $nama = mysqli_fetch_assoc(mysqli_query($db_result, "SELECT nama_jenis FROM jenis_inventarisasi WHERE id='$id'"))['nama_jenis'];
                  echo "<option value='$id' $sel>$nama</option>";
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label>Lokasi</label>
            <select name="lokasi" class="form-control" id="lokasi" required>
              <option value="">- Pilih Lokasi -</option>
              <?php
              foreach ($lokasi_options as $lokasi) {
                  $sel = (($data['lokasi'] ?? '') == $lokasi) ? "selected" : "";
                  echo "<option value='$lokasi' $sel>$lokasi</option>";
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" name="kode_barang" id="kode_barang" 
              value="<?php echo htmlspecialchars($data['kode_barang'] ?? ''); ?>" 
              class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Nama Perangkat</label>
            <input type="text" name="nama_perangkat" value="<?php echo $data['nama_perangkat'] ?? ''; ?>" class="form-control">
          </div>

          <div class="form-group">
            <label>Merek / Model</label>
            <input type="text" name="merek_model" value="<?php echo $data['merek_model'] ?? ''; ?>" class="form-control">
          </div>

          <div class="form-group">
            <label>Serial Number</label>
            <input type="text" name="serial_number" value="<?php echo $data['serial_number'] ?? ''; ?>" class="form-control">
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label>Spesifikasi</label>
            <textarea name="spesifikasi" class="form-control"><?php echo $data['spesifikasi'] ?? ''; ?></textarea>
          </div>

          <div class="form-group">
            <label>Tanggal Pembelian</label>
            <input type="date" name="tanggal_pembelian" value="<?php echo $data['tanggal_pembelian'] ?? ''; ?>" class="form-control">
          </div>

          <div class="form-group">
            <label>Kondisi</label>
            <select name="kondisi" class="form-control">
              <option value="Baik" <?php echo (($data['kondisi'] ?? '') == 'Baik') ? 'selected' : ''; ?>>Baik</option>
              <option value="Cukup" <?php echo (($data['kondisi'] ?? '') == 'Cukup') ? 'selected' : ''; ?>>Cukup</option>
              <option value="Rusak" <?php echo (($data['kondisi'] ?? '') == 'Rusak') ? 'selected' : ''; ?>>Rusak</option>
            </select>
          </div>

          <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
              <option value="AKtif" <?php echo (($data['status'] ?? '') == '1') ? 'selected' : ''; ?>>Aktif</option>
              <option value="Tidak Aktif" <?php echo (($data['status'] ?? '') == '0') ? 'selected' : ''; ?>>Tidak Aktif</option>
            </select>
          </div>

          <div class="form-group">
            <label>Kepemilikan</label>
            <input type="text" name="kepemilikan" value="<?php echo $data['kepemilikan'] ?? ''; ?>" class="form-control">
          </div>

          <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"><?php echo $data['keterangan'] ?? ''; ?></textarea>
          </div>

          <div class="form-group">
            <label>Mouse/Pad</label>
            <select name="mouse_pad" class="form-control">
              <option value="Ada" <?php echo (($data['mouse_pad'] ?? '') == 'Ada') ? 'selected' : ''; ?>>Ada</option>
              <option value="Tidak" <?php echo (($data['mouse_pad'] ?? '') == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
            </select>
          </div>
        </div>
      </div>

      <div class="text-right">
        <a href="<?php echo $current_script . '?' . http_build_query(array_diff_key($query_parts, ['act' => ''])); ?>" class="btn bg-navy">
            <i class="fa fa-caret-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const jenisSelect = document.getElementById("id_jenis");
    const lokasiSelect = document.getElementById("lokasi");
    const kodeInput = document.getElementById("kode_barang");
    const kodePrefix = <?php echo json_encode($jenis_kode_map); ?>;

    function generateKodeBarang() {
        const selectedJenis = jenisSelect.value;
        const selectedLokasi = lokasiSelect.value;
        const prefix = kodePrefix[selectedJenis] || '';

        if (prefix && selectedLokasi) {
            fetch('modul/inventarisasi_barang/get_kode_urut.php?id_jenis=' + selectedJenis + '&lokasi=' + encodeURIComponent(selectedLokasi))
                .then(response => response.text())
                .then(urut => {
                    kodeInput.value = urut;
                })
                .catch(error => {
                    console.error('Error:', error);
                    kodeInput.value = prefix + '001';
                });
        } else {
            kodeInput.value = '';
        }
    }

    jenisSelect.addEventListener('change', generateKodeBarang);
    lokasiSelect.addEventListener('change', generateKodeBarang);

    if (jenisSelect.value && lokasiSelect.value && !kodeInput.value) {
        generateKodeBarang();
    }
});
</script>
