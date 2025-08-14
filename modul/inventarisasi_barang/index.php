<?php
if (preg_match("/\bindex.php\b/i", $_SERVER['REQUEST_URI'])) exit;

$judul = "Inventarisasi Barang";
$current_script = basename($_SERVER['PHP_SELF']);
$query_parts = $_GET;

$gname = $_GET['gname'] ?? '';
$gjenis = $_GET['gjenis'] ?? '';
$glokasi = $_GET['glokasi'] ?? '';
$gkode = $_GET['gkode'] ?? '';
$gmerek = $_GET['gmerek'] ?? '';
$gserial = $_GET['gserial'] ?? '';
$entries_per_page = (int)($_GET['entries'] ?? 10);
$current_page = (int)($_GET['page'] ?? 1);
$offset = ($current_page - 1) * $entries_per_page;

switch ($act) {
    default:
        $td_table = "";
        $no = 0;
        
        $where_conditions = [];
        if (!empty($gname)) {
            $gname_escaped = mysqli_real_escape_string($db_result, $gname);
            $where_conditions[] = "b.nama_perangkat LIKE '%$gname_escaped%'";
        }
        if (!empty($gjenis)) {
            $gjenis_escaped = mysqli_real_escape_string($db_result, $gjenis);
            $where_conditions[] = "j.nama_jenis LIKE '%$gjenis_escaped%'";
        }
        if (!empty($glokasi)) {
            $glokasi_escaped = mysqli_real_escape_string($db_result, $glokasi);
            $where_conditions[] = "b.lokasi LIKE '%$glokasi_escaped%'";
        }
        if (!empty($gkode)) {
            $gkode_escaped = mysqli_real_escape_string($db_result, $gkode);
            $where_conditions[] = "b.kode_barang LIKE '%$gkode_escaped%'";
        }
        if (!empty($gmerek)) {
            $gmerek_escaped = mysqli_real_escape_string($db_result, $gmerek);
            $where_conditions[] = "b.merek_model LIKE '%$gmerek_escaped%'";
        }
        if (!empty($gserial)) {
            $gserial_escaped = mysqli_real_escape_string($db_result, $gserial);
            $where_conditions[] = "b.serial_number LIKE '%$gserial_escaped%'";
        }
        
        $where_clause = "";
        if (!empty($where_conditions)) {
            $where_clause = "WHERE " . implode(" AND ", $where_conditions);
        }
        
        // Count total records for pagination
        $count_sql = "SELECT COUNT(*) as total FROM inventarisasi_barang b
                     JOIN jenis_inventarisasi j ON j.id = b.id_jenis $where_clause";
        $count_result = mysqli_query($db_result, $count_sql);
        $total_records = mysqli_fetch_assoc($count_result)['total'];
        $total_pages = ceil($total_records / $entries_per_page);
        
        $sqld = "SELECT b.*, j.nama_jenis FROM inventarisasi_barang b
                 JOIN jenis_inventarisasi j ON j.id = b.id_jenis
                 $where_clause
                 ORDER BY b.id DESC LIMIT $entries_per_page OFFSET $offset";
        $data = mysqli_query($db_result, $sqld);
        $ndata = mysqli_num_rows($data);
        
        if ($ndata > 0) {
            $start_no = $offset + 1;
            while ($fdata = mysqli_fetch_assoc($data)) {
                extract($fdata);
                
                $status_text = ($status == 1 || $status == '1') ? 'Aktif' : 'Tidak Aktif';
                $nama_warna = ($status == 1 || $status == '1') ? "bg-navy" : "default disable";
                $btn_status = "<a href=\"$link_back&act=status&gket=$status&gid=$id\" class=\"btn btn-xs $nama_warna\">$status_text</a>";
                
                $btn_edit = "<a href=\"$link_back&act=input&gid=$id\" class=\"btn btn-xs btn-success\">Edit</a>";
                $btn_hapus = "<a href=\"$link_back&act=hapus&gid=$id\" onclick=\"return confirm('Apakah Anda Yakin Menghapus Data ini?');\" class=\"btn btn-xs btn-danger\">Hapus</a>";
                
                $td_table .= "<tr>
                    <td>$start_no</td>
                    <td>" . htmlspecialchars($kode_barang) . "</td>
                    <td>" . htmlspecialchars($nama_jenis) . "</td>
                    <td>" . htmlspecialchars($nama_perangkat) . "</td>
                    <td>" . htmlspecialchars($merek_model) . "</td>
                    <td>" . htmlspecialchars($serial_number) . "</td>
                    <td>" . htmlspecialchars($spesifikasi) . "</td>
                    <td>" . htmlspecialchars($lokasi) . "</td>
                    <td>$btn_status</td>
                    <td>$btn_edit $btn_hapus</td>
                </tr>";
                $start_no++;
            }
        } else {
            $search_fields = array_filter([$gname, $gjenis, $glokasi, $gkode, $gmerek, $gserial]);
            $search_message = !empty($search_fields) ? "untuk pencarian yang ditentukan" : "";
            $td_table = "<tr><td colspan='10' class='text-center text-muted'>Tidak ada data yang ditemukan $search_message</td></tr>";
        }

        echo "<div class=\"row\">
            <div class=\"col-md-12\">
                
                <div class=\"box\">
                    <div class=\"box-header border-bottom1\">
                        <h3 class=\"box-title\">Pencarian</h3>
                    </div>

                    <div class=\"box-body\">
                        <form role=\"form\" class=\"form-horizontal\" method=\"get\">
                            <input name=\"pages\" type=\"hidden\" value=\"" . ($_GET['pages'] ?? '') . "\" />

                            <div class=\"form-group\">
                                <label class=\"col-sm-2 control-label text-left\">Nama Perangkat</label>
                                <div class=\"col-sm-3\"><input type=\"text\" class=\"form-control\" name=\"gname\" value=\"" . htmlspecialchars($gname) . "\" placeholder=\"Cari nama perangkat...\"></div>
                                <label class=\"col-sm-2 control-label text-left\">Jenis</label>
                                <div class=\"col-sm-3\"><input type=\"text\" class=\"form-control\" name=\"gjenis\" value=\"" . htmlspecialchars($gjenis) . "\" placeholder=\"Cari jenis perangkat...\"></div>
                            </div>
                            
                            <div class=\"form-group\">
                                <label class=\"col-sm-2 control-label text-left\">Lokasi</label>
                                <div class=\"col-sm-3\"><input type=\"text\" class=\"form-control\" name=\"glokasi\" value=\"" . htmlspecialchars($glokasi) . "\" placeholder=\"Cari lokasi...\"></div>
                                <label class=\"col-sm-2 control-label text-left\">Kode</label>
                                <div class=\"col-sm-3\"><input type=\"text\" class=\"form-control\" name=\"gkode\" value=\"" . htmlspecialchars($gkode) . "\" placeholder=\"Cari kode barang...\"></div>
                            </div>
                            
                            <div class=\"form-group\">
                                <label class=\"col-sm-2 control-label text-left\">Merek/Model</label>
                                <div class=\"col-sm-3\"><input type=\"text\" class=\"form-control\" name=\"gmerek\" value=\"" . htmlspecialchars($gmerek) . "\" placeholder=\"Cari merek/model...\"></div>
                                <label class=\"col-sm-2 control-label text-left\">Serial Number</label>
                                <div class=\"col-sm-3\"><input type=\"text\" class=\"form-control\" name=\"gserial\" value=\"" . htmlspecialchars($gserial) . "\" placeholder=\"Cari serial number...\"></div>
                            </div>
                            
                            <div class=\"form-group\">
                                <div class=\"col-sm-offset-1 col-sm-5\">
                                    <button type=\"submit\" class=\"btn bg-maroon\"><i class=\"fa fa-search\"></i> Cari</button>
                                    <a href=\"$link_back\" class=\"btn btn-info\"><i class=\"fa fa-refresh\"></i> Refresh</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class=\"row\">
            <div class=\"col-md-12\">
                <div class=\"box\">
                    <div class=\"box-header border-bottom1\">
                        <h3 class=\"box-title\">Data</h3>
                    </div>

                    <div class=\"box-body\">
                        <div class=\"row\">
                            <div class=\"col-sm-6\">
                                <a href=\"$link_back&act=input\" class=\"btn btn-primary\"><i class=\"fa fa-plus\"></i> Tambah Data</a>
                            </div>
                        </div>
                        <div class=\"clearfix\"></div><br />

                        <div class=\"table-responsive\">
                            <table id=\"dttable\" class=\"table table-bordered table-hover\">
                                <thead>
                                    <tr>
                                        <th width=\"50\">No</th>
                                        <th>Kode</th>
                                        <th>Jenis</th>
                                        <th>Nama Perangkat</th>
                                        <th>Merek/Model</th>
                                        <th>Serial Number</th>
                                        <th>Spesifikasi</th>
                                        <th>Lokasi</th>
                                        <th width=\"80\">Status</th>
                                        <th width=\"150\">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    $td_table
                                </tbody>
                            </table>
                        </div>";

        if ($total_pages > 1) {
            $search_params = http_build_query([
                'gname' => $gname,
                'gjenis' => $gjenis,
                'glokasi' => $glokasi,
                'gkode' => $gkode,
                'gmerek' => $gmerek,
                'gserial' => $gserial
            ]);
            
            echo "<div class=\"row\">
                <div class=\"col-sm-6\">
                    <div class=\"dataTables_info\">
                        Showing " . ($offset + 1) . " to " . min($offset + $entries_per_page, $total_records) . " of $total_records entries
                    </div>
                </div>
                <div class=\"col-sm-6\">
                    <div class=\"dataTables_paginate paging_simple_numbers\" style=\"text-align: right;\">
                        <ul class=\"pagination\">";
            
            if ($current_page > 1) {
                $prev_page = $current_page - 1;
                echo "<li><a href=\"$link_back&entries=$entries_per_page&page=$prev_page&$search_params\">Previous</a></li>";
            }
            
            for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++) {
                $active = ($i == $current_page) ? ' class="active"' : '';
                echo "<li$active><a href=\"$link_back&entries=$entries_per_page&page=$i&$search_params\">$i</a></li>";
            }
            
            if ($current_page < $total_pages) {
                $next_page = $current_page + 1;
                echo "<li><a href=\"$link_back&entries=$entries_per_page&page=$next_page&$search_params\">Next</a></li>";
            }
            
            echo "</ul>
                    </div>
                </div>
            </div>";
        }

        echo "    </div>
                </div>
            </div>
        </div>";
        break;

    case "input":
        include "form_input.php";
        break;

    case "hapus":
        $gid = $_GET['gid'];
        $sql = "DELETE FROM inventarisasi_barang WHERE id = '$gid'";

        if (mysqli_query($db_result, $sql)) {
            echo "<div class='alert alert-success'>Data berhasil dihapus</div>";
        } else {
            echo "<div class='alert alert-danger'>Gagal menghapus data</div>";
        }

        echo "<meta http-equiv='refresh' content='1;url=$link_back'>";
        break;

    case "status":
        $sqld = "SELECT * FROM inventarisasi_barang WHERE id=\"$gid\"";
        $data = mysqli_query($db_result, $sqld);
        $ndata = mysqli_num_rows($data);
        if ($ndata > 0) {
            $fdata = mysqli_fetch_assoc($data);
            $pid = $fdata["id"];
            $new_status = ($gket == 1 || $gket == '1') ? 0 : 1;
            
            $inp = "UPDATE inventarisasi_barang SET status=\"$new_status\" WHERE id=\"$pid\"";
            $upd = mysqli_query($db_result, $inp);
            if ($upd == 1) {
                $nket = "<div class=\"alert alert-success\">Data Berhasil Dirubah</div>";
            } else {
                $nket = "<div class=\"alert alert-danger\">Data Gagal Dirubah</div>";
            }
        } else {
            $nket = "<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
        }

        echo "$nket
        <meta http-equiv=\"refresh\" content=\"2;url=$link_back\">";
        break;

     case "proses":
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "<div class='alert alert-danger'>Invalid request method</div>";
            break;
        }
        if (empty($_POST['kode_barang']) || empty($_POST['id_jenis'])) {
            echo "<div class='alert alert-danger'>Kode barang dan jenis wajib diisi!</div>";
            echo "<meta http-equiv='refresh' content='2;url=$link_back&act=input'>";
            break;
        }
        $data = [];
        $fields = [
            'id', 'kode_barang', 'id_jenis', 'nama_perangkat', 'merek_model', 
            'serial_number', 'spesifikasi', 'lokasi', 'tanggal_pembelian', 
            'kondisi', 'status', 'kepemilikan', 'keterangan', 'mouse_pad'
        ];
        foreach ($fields as $field) {
            $data[$field] = isset($_POST[$field]) ? mysqli_real_escape_string($db_result, trim($_POST[$field])) : '';
        }
        if ($data['status'] === 'Aktif') {
            $data['status'] = 1;
        } elseif ($data['status'] === 'Tidak Aktif') {
            $data['status'] = 0;
        }
        if (!is_numeric($data['id_jenis'])) {
            echo "<div class='alert alert-danger'>ID Jenis harus berupa angka!</div>";
            break;
        }
        try {
            if (empty($data['id']) || !is_numeric($data['id'])) {
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

?>
