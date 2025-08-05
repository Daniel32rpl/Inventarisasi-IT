<?php
if(preg_match("/\bindex.php\b/i", $_SERVER['REQUEST_URI'])){
    exit;
}else{

switch($act){
	default:
		$na_status=SelStatus();
		$na_level=LevelUser();
		$td_table="";
		$no=0;
		$sqld="select * from db_user where hapus=\"0\" and id!=\"1\" and nama_user like \"%$gname%\" order by nama_user";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				extract($fdata);
				$nama_status=$na_status[$status][1];
				$nama_level=$na_level[$level_user]["nama_level"];
				$no++;

				if($lmn[$idsmenu][2]==1){
					$link_edit="$link_back&act=input&gket=edit&gid=$id";
					$btn_edit="<a href=\"$link_edit\" class=\"btn btn-xs btn-success\">Edit</a>";

					$nama_warna=($status==1)? "bg-navy" : "default disable";
					$link_status="$link_back&act=status&gket=$status&gid=$id";
					$btn_status="<a href=\"$link_status\" class=\"btn btn-xs $nama_warna\">$nama_status</a>";
				}else{
					$btn_status="$nama_status";
					$btn_edit="";
				}

				if($lmn[$idsmenu][3]==1){
					$link_hapus="$link_back&act=hapus&gid=$id";
					$btn_hapus="<a href=\"$link_hapus\" onclick=\"return confirm('Apakah Anda Yakin Menghapus Data ini?');\" class=\"btn btn-xs btn-danger\">Hapus</a>";
				}else{
					$btn_hapus="";
				}

				$td_table.="<tr>
					<td>$no</td>
					<td>$nama_user</td>
					<td>$username</td>
					<td>$nama_level</td>
					<td>$btn_status</td>
					<td>$btn_edit $btn_hapus</td>
				</tr>";
			}
		}

		if($lmn[$idsmenu][1]==1){
			$link_add="$link_back&act=input&gket=tambah";
			$btn_add="<a href=\"$link_add\" class=\"btn btn-primary\"><i class=\"fa fa-plus\"></i> Tambah Data</a>";
		}

		echo"<div class=\"row\">
			<div class=\"col-md-12\">
				
				<div class=\"box\">
					<div class=\"box-header border-bottom1\">
						<h3 class=\"box-title\">Pencarian</h3>
					</div>

					<div class=\"box-body\">
						<form role=\"form\" class=\"form-horizontal\" method=\"get\">
							<input name=\"pages\" type=\"hidden\" value=\"$pages\" />

							<div class=\"form-group\">
								<label class=\"col-sm-2 control-label text-left\">Nama User</label>
								<div class=\"col-sm-3\"><input type=\"text\" class=\"form-control\" name=\"gname\"></div>
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
										<th>Nama User</th>
										<th>Username</th>
										<th>Nama Level</th>
										<th width=\"50\">Status</th>
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

	case"input":
		if($gket=="tambah" and $lmn[$idsmenu][1]==1){
			$username="";
			$status="1";
			$ndata=1;
			$judul="Tambah Data";
			$input_user="<input type=\"text\" class=\"form-control\" name=\"username\" value=\"$username\" required />";
			
		}

		if($gket=="edit" and $lmn[$idsmenu][2]==1){
			$judul="Edit Data";

			$sqld="select * from db_user where id=\"$gid\" and hapus=\"0\"";
			$data=mysqli_query($db_result, $sqld);
			$ndata=mysqli_num_rows($data);
			if($ndata>0){
				$fdata=mysqli_fetch_assoc($data);
				extract($fdata);

				$edit="<input name=\"pid\" type=\"hidden\" value=\"$id\" />
				<input type=\"hidden\" class=\"form-control\" name=\"username\" value=\"$username\" required />";
				
				$input_user="$username";
			}
		}

		if($ndata>0){
			$sstatus=SelStatus();
			$opt_status="";
			$opt_level="";
			$level_user="";
			$nama_user="";
			$password="";	
			$no_telp="";
			
			foreach($sstatus as $sstatus1){
				$sel1=($sstatus1[0]==$status)? "selected=\"selected\"" : "";
				$opt_status.="<option value=\"$sstatus1[0]\" $sel1>$sstatus1[1]</option>";
				$edit="";
			}

			$slevel=LevelUser();
			foreach($slevel as $slevel1){
				if($slevel1["id"]!="1"){
					$sel1=($slevel1["id"]==$level_user)? "selected=\"selected\"" : "";
					$opt_level.="<option value=\"$slevel1[id]\" $sel1>$slevel1[nama_level]</option>";
				}
			}

			echo"<div class=\"row\">
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
											<label class=\"col-sm-2 text-left\"><b>Nama User</b></label>
											<div class=\"col-sm-3\">
												<input type=\"text\" class=\"form-control\" name=\"nama_user\" value=\"$nama_user\" required />
											</div>
										</div>
										<div class=\"form-group\">
											<label class=\"col-sm-2 text-left\"><b>Username</b></label>
											<div class=\"col-sm-3\">
												$input_user
											</div>
										</div>
										<div class=\"form-group\">
											<label class=\"col-sm-2 text-left\"><b>Password</b></label>
											<div class=\"col-sm-3\">
												<input type=\"password\" class=\"form-control\" name=\"password\" value=\"$password\" required />
											</div>
										</div>
										<div class=\"form-group\">
											<label class=\"col-sm-2 text-left\"><b>No. Telp</b></label>
											<div class=\"col-sm-3\">
												<input type=\"text\" class=\"form-control\" name=\"no_telp\" value=\"$no_telp\" data-numeric=\"true\" data-mask=\"9999999999999\" />
											</div>
										</div>
										<div class=\"form-group\">
											<label class=\"col-sm-2 text-left\"><b>Level</b></label>
											<div class=\"col-sm-3\">
												<select name=\"level_user\" class=\"form-control\" required>
													<option value=\"\">- Pilih -</option>
													$opt_level
												</select>
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
			echo"<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
			echo"<meta http-equiv=\"refresh\" content=\"2;url=$link_back\">";
		}
	break;

	case"proses":
		if(count($_POST)>0){
			foreach($_POST as $pkey => $pvalue){
				$post1=mysqli_escape_string($db_result, $pvalue);
				$post1=preg_replace('/\s+/', ' ', $post1);
				$post1=trim($post1);

				$arpost[$pkey]="$post1";
			}

			extract($arpost);
			$no_telp=str_replace("_", "", $no_telp);
		}

		$error="";
		$error.=($nama_user=="")? "&bull; Nama User Tidak Boleh Kosong<br />" : "";
		$error.=($username=="")? "&bull; Username Tidak Boleh Kosong<br />" : "";
		$error.=($password=="")? "&bull; Password Tidak Boleh Kosong<br />" : "";
		$error.=($level_user=="")? "&bull; Level User Tidak Boleh Kosong<br />" : "";
		$error.=($status=="")? "&bull; Status Tidak Boleh Kosong<br />" : "";

		if(empty($error)){
			if($gket=="tambah" and $lmn[$idsmenu][1]==1){
				$n=1;

				$sqld="select * from db_user where username=\"$username\" and hapus=\"0\"";
				$data=mysqli_query($db_result, $sqld);
				$ndata=mysqli_num_rows($data);
				if($ndata==0){
					$vdata="`username`, no_telp, `password`, nama_user, id_pegawai, level_user, status, hapus, tgl_insert, tgl_update, user_update";
					$vvalues="\"$username\", \"$no_telp\", password(\"$password\"), \"$nama_user\", \"\", \"$level_user\", \"$status\", \"0\", \"$ndatetime\", \"$ndatetime\", \"$ss_id\"";
					
					$inp="insert into db_user ($vdata) values ($vvalues)";
					$upd=mysqli_query($db_result, $inp);
					if($upd==1){
						$nket="<div class=\"alert alert-success\">Data Berhasil Ditambah</div>";
					}else{
						$nket="<div class=\"alert alert-danger\">Data Gagal Ditambah</div>";
					}
				}else{
					$nket="<div class=\"alert alert-warning\">Data Sudah Ada</div>";
				}
			}

			if($gket=="edit" and $lmn[$idsmenu][2]==1){
				$n=1;

				$sqld="select * from db_user where id=\"$pid\" and hapus=\"0\"";
				$data=mysqli_query($db_result, $sqld);
				$ndata=mysqli_num_rows($data);
				if($ndata>0){
					$fdata=mysqli_fetch_assoc($data);
					$user_update="$ss_id,".$fdata["user_update"];
					$id1=$fdata["id"];
					$pw1=$fdata["password"];

					if($password!=$pw1){
						$dataw1="password=password(\"$password\"), ";
					}

					$vdata="$dataw1 no_telp=\"$no_telp\", nama_user=\"$nama_user \", level_user=\"$level_user\", status=\"$status\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
					$vvalues="id=\"$id1\"";

					$inp="update db_user set $vdata where $vvalues";
					$upd=mysqli_query($db_result, $inp);
					if($upd==1){
						$nket="<div class=\"alert alert-success\">Data Berhasil Dirubah</div>";
					}else{
						$nket="<div class=\"alert alert-danger\">Data Gagal Dirubah</div>";
					}
				}else{
					$nket="<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
				}
			}

			if($n==1){
				echo"$nket";
			}else{
				echo"<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
			}
		}else{
			echo"<div class=\"alert alert-warning\">$error</div>";
		}

		echo"<meta http-equiv=\"refresh\" content=\"2;url=$link_back\">";
	break;

	case"hapus":
		if($lmn[$idsmenu][3]==1){
			$sqld="select * from db_user where id=\"$gid\" and hapus=\"0\"";
			$data=mysqli_query($db_result, $sqld);
			$ndata=mysqli_num_rows($data);
			if($ndata>0){
				$fdata=mysqli_fetch_assoc($data);
				$pid=$fdata["id"];
				$user_update="$ss_id,".$fdata["user_update"];

				$vdata="hapus=\"1\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
				$vvalues="id=\"$pid\"";

				$inp="update db_user set $vdata where $vvalues";
				$upd=mysqli_query($db_result, $inp);
				if($upd==1){
					$nket="<div class=\"alert alert-success\">Data Berhasil Dihapus</div>";
				}else{
					$nket="<div class=\"alert alert-danger\">Data Gagal Dihapus</div>";
				}
			}else{
				$nket="<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
			}
		}else{
			$nket="<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
		}

		echo"$nket
		<meta http-equiv=\"refresh\" content=\"2;url=$link_back\">";
	break;

	case"status":
		if($lmn[$idsmenu][2]==1){
			$sqld="select * from db_user where id=\"$gid\" and hapus=\"0\"";
			$data=mysqli_query($db_result, $sqld);
			$ndata=mysqli_num_rows($data);
			if($ndata>0){
				$fdata=mysqli_fetch_assoc($data);
				$pid=$fdata["id"];
				$user_update="$ss_id,".$fdata["user_update"];
				$status=($gket==1)? "2" : "1";
				
				$vdata="status=\"$status\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
				$vvalues="id=\"$pid\"";
				
				$inp="update db_user set $vdata where $vvalues";
				$upd=mysqli_query($db_result, $inp);
				if($upd==1){
					$nket="<div class=\"alert alert-success\">Data Berhasil Dirubah</div>";
				}else{
					$nket="<div class=\"alert alert-danger\">Data Gagal Dirubah</div>";
				}
			}else{
				$nket="<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
			}
		}else{
			$nket="<div class=\"alert alert-warning\">Data Tidak Ditemukan</div>";
		}

		echo"$nket
		<meta http-equiv=\"refresh\" content=\"2;url=$link_back\">";
	break;
}
}
?>
