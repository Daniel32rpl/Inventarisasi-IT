<?php
if(preg_match("/\bindex.php\b/i", $_SERVER['REQUEST_URI'])){
    exit;
}else{

switch($act){
    default:
        $td_table="";
        $no=0;
        
        $where_clause = "";
        if (!empty($gname)) {
            $gname_escaped = mysqli_real_escape_string($db_result, $gname);
            $where_clause = "WHERE nama_jenis LIKE '%$gname_escaped%' OR keterangan LIKE '%$gname_escaped%'";
        }
        
        $sqld = "SELECT * FROM jenis_inventarisasi $where_clause ORDER BY nama_jenis";
        $data = mysqli_query($db_result, $sqld);
        $ndata = mysqli_num_rows($data);
        
        if($ndata > 0){
            while($fdata = mysqli_fetch_assoc($data)){
                extract($fdata);
                $no++;
                
                if($lmn[$idsmenu][2]==1){
                    $link_edit = "$link_back&act=input&gket=edit&gid=$id";
                    $btn_edit = "<a href=\"$link_edit\" class=\"btn btn-xs btn-success\">Edit</a>";
                    
                    $nama_warna = ($status == 1) ? "bg-navy" : "default disable";
                    $link_status = "$link_back&act=status&gket=$status&gid=$id";
                    $btn_status = "<a href=\"$link_status\" class=\"btn btn-xs $nama_warna\">" . (($status == 1) ? 'Aktif' : 'Tidak Aktif') . "</a>";
                }else{
                    $btn_status = ($status == 1) ? 'Aktif' : 'Tidak Aktif';
                    $btn_edit = "";
                }
                
                if($lmn[$idsmenu][3]==1){
                    $link_hapus = "$link_back&act=hapus&gid=$id";
                    $btn_hapus = "<a href=\"$link_hapus\" onclick=\"return confirm('Apakah Anda Yakin Menghapus Data ini?');\" class=\"btn btn-xs btn-danger\">Hapus</a>";
                }else{
                    $btn_hapus = "";
                }
                
                $td_table .= "<tr>
                    <td>$no</td>
                    <td>$nama_jenis</td>
                    <td>$kode</td>
                    <td>$keterangan</td>
                    <td>$btn_status</td>
                    <td>$btn_edit $btn_hapus</td>
                </tr>";
            }
        }else{
            $search_message = !empty($gname) ? "untuk pencarian '<strong>" . htmlspecialchars($gname) . "</strong>'" : "";
            $td_table = "<tr><td colspan='6' class='text-center text-muted'>Tidak ada data yang ditemukan $search_message</td></tr>";
        }
        
        if($lmn[$idsmenu][1]==1){
            $link_add = "$link_back&act=input&gket=tambah";
            $btn_add = "<a href=\"$link_add\" class=\"btn btn-primary\"><i class=\"fa fa-plus\"></i> Tambah Data</a>";
        }else{
            $btn_add = "";
        }

        echo "<div class=\"row\">
            <div class=\"col-md-12\">
                
                <div class=\"box\">
                    <div class=\"box-header border-bottom1\">
                        <h3 class=\"box-title\">Pencarian</h3>
                    </div>

                    <div class=\"box-body\">
                        <form role=\"form\" class=\"form-horizontal\" method=\"get\">
                            <input name=\"pages\" type=\"hidden\" value=\"$pages\" />

                            <div class=\"form-group\">
                                <label class=\"col-sm-2 control-label text-left\">Nama Jenis</label>
                                <div class=\"col-sm-3\"><input type=\"text\" class=\"form-control\" name=\"gname\" value=\"" . htmlspecialchars($gname) . "\"></div>
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
                        $btn_add
                        <div class=\"clearfix\"></div><br />

                        <div class=\"table-responsive\">
                            <table id=\"dttable\" class=\"table table-bordered table-hover\">
                                <thead>
                                    <tr>
                                        <th width=\"50\">No</th>
                                        <th>Nama Jenis</th>
                                        <th>Kode</th>
                                        <th>Keterangan</th>
                                        <th width=\"80\">Status</th>
                                        <th width=\"150\">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    $td_table
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
    break;

    case "input":
        if($gket=="tambah" and $lmn[$idsmenu][1]==1){
            $nama_jenis = "";
            $keterangan = "";
            $status = "1";
            $ndata = 1;
            $judul = "Tambah Data";
        }

        if($gket=="edit" and $lmn[$idsmenu][2]==1){
            $judul = "Edit Data";

            $sqld = "SELECT * FROM jenis_inventarisasi WHERE id=\"$gid\"";
            $data = mysqli_query($db_result, $sqld);
            $ndata = mysqli_num_rows($data);
            if($ndata > 0){
                $fdata = mysqli_fetch_assoc($data);
                extract($fdata);

                $edit = "<input name=\"pid\" type=\"hidden\" value=\"$id\" />";
            }
        }

        if($ndata > 0){
            $opt_status = "";
            $status_options = array(
                array("1", "Aktif"),
                array("0", "Tidak Aktif")
            );
            
            foreach($status_options as $status_option){
                $sel1 = ($status_option[0] == $status) ? "selected=\"selected\"" : "";
                $opt_status .= "<option value=\"$status_option[0]\" $sel1>$status_option[1]</option>";
            }

            echo "<div class=\"row\">
                <div class=\"col-md-12\">

                    <div class=\"box\">
                        <div class=\"box-header border-bottom1\">
                            Data &raquo; $judul
                        </div>

                        <div class=\"box-body\">
                            <div class=\"row\">
                                <div class=\"col-md-12\">
                                    <form role=\"form\" class=\"form-horizontal\" method=\"post\" action=\"$link_back&act=proses&gket=$gket\">
                                        <div class=\"form-group\">
                                            <label class=\"col-sm-2 text-left\"><b>Nama Jenis</b></label>
                                            <div class=\"col-sm-5\">
                                                <input type=\"text\" class=\"form-control\" name=\"nama_jenis\" value=\"$nama_jenis\" required />
                                            </div>
                                        </div>
                                        <div class=\"form-group\">
                                            <label class=\"col-sm-2 text-left\"><b>Keterangan</b></label>
                                            <div class=\"col-sm-8\">
                                                <input type=\"text\" class=\"form-control\" name=\"keterangan\" value=\"$keterangan\" required />
                                            </div>
                                        </div>
                                        <div class=\"form-group\">
                                            <label class=\"col-sm-2 text-left\"><b>Status</b></label>
                                            <div class=\"col-sm-3\">
                                                <select name=\"status\" class=\"form-control\" required>
                                                    <option value=\"\">- Pilih -</option>
                                                    $opt_status
                                                </select>
                                            </div>
                                        </div>
                                        <div class=\"form-group\">
                                            <div class=\"col-sm-offset-1 col-sm-5\">
                                                $edit
                                                <a href=\"$link_back\" class=\"btn bg-navy\"><i class=\"fa fa-caret-left\"></i> Kembali</a>
                                                <button type=\"submit\" class=\"btn btn-success\"><i class=\"fa fa-save\"></i> Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>";
        }else{
            echo "<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
            echo "<meta http-equiv=\"refresh\" content=\"2;url=$link_back\">";
        }
    break;

    case "proses":
        if(count($_POST) > 0){
            foreach($_POST as $pkey => $pvalue){
                $post1 = mysqli_escape_string($db_result, $pvalue);
                $post1 = preg_replace('/\s+/', ' ', $post1);
                $post1 = trim($post1);

                $arpost[$pkey] = "$post1";
            }

            extract($arpost);
        }

        $error = "";
        $error .= ($nama_jenis == "") ? "&bull; Nama Jenis Tidak Boleh Kosong<br />" : "";
        $error .= ($keterangan == "") ? "&bull; Keterangan Tidak Boleh Kosong<br />" : "";
        $error .= ($status == "") ? "&bull; Status Tidak Boleh Kosong<br />" : "";

        if(empty($error)){
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

            if($gket=="tambah" and $lmn[$idsmenu][1]==1){
                $n = 1;

                $vdata = "nama_jenis, kode, keterangan, status, tgl_insert, tgl_update, user_update";
                $vvalues = "\"$nama_jenis\", \"$kode\", \"$keterangan\", \"$status\", \"$ndatetime\", \"$ndatetime\", \"$ss_id\"";
                
                $inp = "INSERT INTO jenis_inventarisasi ($vdata) VALUES ($vvalues)";
                $upd = mysqli_query($db_result, $inp);
                if($upd == 1){
                    $nket = "<div class=\"alert alert-success\">Data Berhasil Ditambah</div>";
                }else{
                    $nket = "<div class=\"alert alert-danger\">Data Gagal Ditambah</div>";
                }
            }

            if($gket=="edit" and $lmn[$idsmenu][2]==1){
                $n = 1;

                $sqld = "SELECT * FROM jenis_inventarisasi WHERE id=\"$pid\"";
                $data = mysqli_query($db_result, $sqld);
                $ndata = mysqli_num_rows($data);
                if($ndata > 0){
                    $fdata = mysqli_fetch_assoc($data);
                    $user_update = "$ss_id," . $fdata["user_update"];
                    $id1 = $fdata["id"];

                    $vdata = "nama_jenis=\"$nama_jenis\", kode=\"$kode\", keterangan=\"$keterangan\", status=\"$status\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
                    $vvalues = "id=\"$id1\"";

                    $inp = "UPDATE jenis_inventarisasi SET $vdata WHERE $vvalues";
                    $upd = mysqli_query($db_result, $inp);
                    if($upd == 1){
                        $nket = "<div class=\"alert alert-success\">Data Berhasil Dirubah</div>";
                    }else{
                        $nket = "<div class=\"alert alert-danger\">Data Gagal Dirubah</div>";
                    }
                }else{
                    $nket = "<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
                }
            }

            if($n == 1){
                echo "$nket";
            }else{
                echo "<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
            }
        }else{
            echo "<div class=\"alert alert-warning\">$error</div>";
        }

        echo "<meta http-equiv=\"refresh\" content=\"2;url=$link_back\">";
    break;

    case "hapus":
        if($lmn[$idsmenu][3]==1){
            $sqld = "SELECT * FROM jenis_inventarisasi WHERE id=\"$gid\"";
            $data = mysqli_query($db_result, $sqld);
            $ndata = mysqli_num_rows($data);
            if($ndata > 0){
                $fdata = mysqli_fetch_assoc($data);
                $pid = $fdata["id"];
                $user_update = "$ss_id," . $fdata["user_update"];

                // Delete related records first
                mysqli_query($db_result, "DELETE FROM inventarisasi_barang WHERE id_jenis = '$pid'");
                
                $vdata = "hapus=\"1\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
                $vvalues = "id=\"$pid\"";

                $inp = "UPDATE jenis_inventarisasi SET $vdata WHERE $vvalues";
                $upd = mysqli_query($db_result, $inp);
                if($upd == 1){
                    $nket = "<div class=\"alert alert-success\">Data Berhasil Dihapus</div>";
                }else{
                    $nket = "<div class=\"alert alert-danger\">Data Gagal Dihapus</div>";
                }
            }else{
                $nket = "<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
            }
        }else{
            $nket = "<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
        }

        echo "$nket
        <meta http-equiv=\"refresh\" content=\"2;url=$link_back\">";
    break;

    case "status":
        if($lmn[$idsmenu][2]==1){
            $sqld = "SELECT * FROM jenis_inventarisasi WHERE id=\"$gid\"";
            $data = mysqli_query($db_result, $sqld);
            $ndata = mysqli_num_rows($data);
            if($ndata > 0){
                $fdata = mysqli_fetch_assoc($data);
                $pid = $fdata["id"];
                $user_update = "$ss_id," . $fdata["user_update"];
                $new_status = ($gket == 1) ? "0" : "1";
                
                $vdata = "status=\"$new_status\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
                $vvalues = "id=\"$pid\"";
                
                $inp = "UPDATE jenis_inventarisasi SET $vdata WHERE $vvalues";
                $upd = mysqli_query($db_result, $inp);
                if($upd == 1){
                    $nket = "<div class=\"alert alert-success\">Data Berhasil Dirubah</div>";
                }else{
                    $nket = "<div class=\"alert alert-danger\">Data Gagal Dirubah</div>";
                }
            }else{
                $nket = "<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
            }
        }else{
            $nket = "<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
        }

        echo "$nket
        <meta http-equiv=\"refresh\" content=\"2;url=$link_back\">";
    break;
}
}
?>
