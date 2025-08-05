<?php
if(preg_match("/\bindex.php\b/i", $_SERVER['REQUEST_URI'])){
    exit;
}else{

switch($act){
	default:
		$na_status=SelStatus();
		$no=0;
		$td_table="";
		$sqld="select * from db_user_level where hapus=\"0\" and id!=\"1\" and nama_level like \"%$gname%\"";
		$data=mysqli_query($db_result, $sqld);
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			while($fdata=mysqli_fetch_assoc($data)){
				extract($fdata);
				$nama_status=$na_status[$status][1];
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
								<label class=\"col-sm-2 control-label text-left\">Nama Level</label>
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
			$ndata=1;
			$judul="Tambah Data";
			
		}

		if($gket=="edit" and $lmn[$idsmenu][2]==1){
			$judul="Edit Data";

			$sqld="select * from db_user_level where id=\"$gid\" and id!=\"1\" and hapus=\"0\"";
			$data=mysqli_query($db_result, $sqld);
			$ndata=mysqli_num_rows($data);
			if($ndata>0){
				$fdata=mysqli_fetch_assoc($data);
				extract($fdata);

				$edit="<input name=\"pid\" type=\"hidden\" value=\"$id\" />";

				$sqld2="select * from db_user_akses where id_level=\"$id\" and hapus=\"0\"";
				$data2=mysqli_query($db_result, $sqld2);
				$ndata2=mysqli_num_rows($data2);
				if($ndata2>0){
					while($fdata2=mysqli_fetch_assoc($data2)){
						$id_menu2=$fdata2["id_menu"];
						$hak1=$fdata2["hk_add"];
						$hak2=$fdata2["hk_edit"];
						$hak3=$fdata2["hk_delete"];
						$lm_akses[$id_menu2]=array($id_menu2, $hak1, $hak2, $hak3);
					}
				}
			}
		}

		if($ndata>0){
			$sstatus=SelStatus();
			foreach($sstatus as $sstatus1){
				$sel1=($sstatus1[0]==$status)? "selected=\"selected\"" : "";
				$opt_status.="<option value=\"$sstatus1[0]\" $sel1>$sstatus1[1]</option>";
			}

			$ma=MenuAkses();
			$mamenu=$ma["menu"];
			foreach($mamenu as $kv1){
				$idm1=$kv1[0];

				if(count($kv1[5])>0){
					$list_kv.="<tr>
						<td colapse=\"5\">$kv1[1]</td>
					</tr>";

					foreach($kv1[5] as $kv2){
						$idm12=$kv2[0];

						$cek1=($lm_akses[$idm12][0]>0)? "checked=\"checked\"" : "";
						$cek2=($lm_akses[$idm12][1]==1)? "checked=\"checked\"" : "";
						$cek3=($lm_akses[$idm12][2]==1)? "checked=\"checked\"" : "";
						$cek4=($lm_akses[$idm12][3]==1)? "checked=\"checked\"" : "";

						$list_kv.="<tr>
							<td>&nbsp;&nbsp; <i class=\"fa fa-caret-right\"></i> $kv2[1]</td>
							<td><input type=\"checkbox\" name=\"menusel$kv2[0]\" value=\"1\" $cek1/></td>
							<td><input type=\"checkbox\" name=\"menuadd$kv2[0]\" value=\"1\" $cek2/></td>
							<td><input type=\"checkbox\" name=\"menuupd$kv2[0]\" value=\"1\" $cek3/></td>
							<td><input type=\"checkbox\" name=\"menudel$kv2[0]\" value=\"1\" $cek4/></td>
						</tr>";
					}
				}else{
					if($idm1==1){
						$cek1="checked=\"checked\"";
						$cek2="checked=\"checked\"";
						$cek3="checked=\"checked\"";
						$cek4="checked=\"checked\"";

						$ds="onclick=\"return false;\"";
					}else{
						$cek1=($lm_akses[$idm1][0]>0)? "checked=\"checked\"" : "";
						$cek2=($lm_akses[$idm1][1]==1)? "checked=\"checked\"" : "";
						$cek3=($lm_akses[$idm1][2]==1)? "checked=\"checked\"" : "";
						$cek4=($lm_akses[$idm1][3]==1)? "checked=\"checked\"" : "";

						$ds="";
					}

					$list_kv.="<tr>
					<td>$kv1[1]</td>
						<td><input type=\"checkbox\" name=\"menusel$kv1[0]\" value=\"1\" $ds $cek1/></td>
						<td><input type=\"checkbox\" name=\"menuadd$kv1[0]\" value=\"1\" $ds $cek2/></td>
						<td><input type=\"checkbox\" name=\"menuupd$kv1[0]\" value=\"1\" $ds $cek3/></td>
						<td><input type=\"checkbox\" name=\"menudel$kv1[0]\" value=\"1\" $ds $cek4/></td>
					</tr>";
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
											<label class=\"col-sm-2 text-left\"><b>Nama Level</b></label>
											<div class=\"col-sm-3\">
												<input type=\"text\" class=\"form-control\" name=\"nama_level\" value=\"$nama_level\" required />
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
											<label class=\"col-sm-2 text-left\"><b>Menu Akses</b></label>
											<div class=\"col-sm-9\">
												<table class=\"table table-bordered responsive\">
													<thead>
														<tr>
															<th>Nama Menu</th>
															<th>Melihat</th>
															<th>Tambah</th>
															<th>Ubah</th>
															<th>Hapus</th>
														</tr>
													</thead>
													<tbody>
														$list_kv
													</tbody>
												</table>
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
		}

		$error="";
		$error.=($nama_level=="")? "&bull; Nama Level Tidak Boleh Kosong<br />" : "";
		$error.=($status=="")? "&bull; Status Tidak Boleh Kosong<br />" : "";

		if(empty($error)){
			if($gket=="tambah" and $lmn[$idsmenu][1]==1){
				$n=1;

				$sqld="select * from db_user_level where nama_level=\"$nama_level\" and hapus=\"0\"";
				$data=mysqli_query($db_result, $sqld);
				$ndata=mysqli_num_rows($data);
				if($ndata==0){
					$vdata="nama_level, status, hapus, tgl_insert, tgl_update, user_update";
					$vvalues="\"$nama_level\", \"$status\", \"0\", \"$ndatetime\", \"$ndatetime\", \"$ss_id\"";
					
					$inp="insert into db_user_level ($vdata) values ($vvalues)";
					$upd=mysqli_query($db_result, $inp);
					if($upd==1){
						$nket="<div class=\"alert alert-success\">Data Berhasil Ditambah</div>";
						$id1=mysqli_insert_id($db_result);

						$ma=MenuAkses();
						$mamenu=$ma["menu"];
						foreach($mamenu as $kv1){
							$nk=0;

							if(count($kv1[5])>0){
								$nk=0;
								foreach($kv1[5] as $kv2){
									/*sub menu*/
									
									$menu_add=$arpost["menuadd$kv2[0]"];
									$menu_upd=$arpost["menuupd$kv2[0]"];
									$menu_del=$arpost["menudel$kv2[0]"];
									$menu_sel=$arpost["menusel$kv2[0]"];

									if($menu_sel==1){
										$sqld2="select * from db_user_akses where id_menu=\"$kv2[0]\" and hapus=\"0\" and id_level=\"$id1\"";
										$data2=mysqli_query($db_result, $sqld2);
										$ndata2=mysqli_num_rows($data2);
										if($ndata2==0){
											$vdata2="id_level, id_menu, hk_add, hk_edit, hk_delete, status, hapus, tgl_insert, tgl_update, user_update";
											$vvalues2="\"$id1\", \"$kv2[0]\", \"$menu_add\", \"$menu_upd\", \"$menu_del\", \"1\", \"0\", \"$ndatetime\", \"$ndatetime\", \"$ss_id\"";

											$inp2="insert into db_user_akses ($vdata2) values ($vvalues2)";
											mysqli_query($db_result, $inp2);
										}else{
											$fdata2=mysqli_fetch_assoc($data2);
											$id2=$fdata2["id"];
											$user_update="$ss_id,$fdata2[user_update]";

											$vdata2="hk_add=\"$menu_add\", hk_edit=\"$menu_upd\", hk_delete=\"$menu_del\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
											$vvalues2="id=\"$id2\"";

											$inp2="update db_user_akses set $vdata2 where $vvalues2";
											mysqli_query($db_result, $inp2);
										}

										$nk=1;
									}else{
										$sqld2="select * from db_user_akses where id_menu=\"$kv2[0]\" and hapus=\"0\" and id_level=\"$id1\"";
										$data2=mysqli_query($db_result, $sqld2);
										$ndata2=mysqli_num_rows($data2);
										if($ndata2>0){
											$fdata2=mysqli_fetch_assoc($data2);
											$id2=$fdata2["id"];
											$user_update="$ss_id,$fdata2[user_update]";

											$vdata2="hapus=\"1\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
											$vvalues2="id=\"$id2\"";

											$inp2="update db_user_akses set $vdata2 where $vvalues2";
											mysqli_query($db_result, $inp2);
										}
									}
								}

								/*insert menu utama*/
								if($nk==1){
									$sqld2="select * from db_user_akses where id_menu=\"$kv1[0]\" and hapus=\"0\" and id_level=\"$id1\"";
									$data2=mysqli_query($db_result, $sqld2);
									$ndata2=mysqli_num_rows($data2);
									if($ndata2==0){
										$vdata2="id_level, id_menu, hk_add, hk_edit, hk_delete, status, hapus, tgl_insert, tgl_update, user_update";
										$vvalues2="\"$id1\", \"$kv1[0]\", \"$menu_add\", \"$menu_upd\", \"$menu_del\", \"1\", \"0\", \"$ndatetime\", \"$ndatetime\", \"$ss_id\"";

										$inp2="insert into db_user_akses ($vdata2) values ($vvalues2)";
										mysqli_query($db_result, $inp2);
									}else{
										$fdata2=mysqli_fetch_assoc($data2);
										$id2=$fdata2["id"];
										$user_update="$ss_id,$fdata2[user_update]";

										$vdata2="hk_add=\"$menu_add\", hk_edit=\"$menu_upd\", hk_delete=\"$menu_del\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
										$vvalues2="id=\"$id2\"";

										$inp2="update db_user_akses set $vdata2 where $vvalues2";
										mysqli_query($db_result, $inp2);
									}
								}else{
									$sqld2="select * from db_user_akses where id_menu=\"$kv1[0]\" and hapus=\"0\" and id_level=\"$id1\"";
									$data2=mysqli_query($db_result, $sqld2);
									$ndata2=mysqli_num_rows($data2);
									if($ndata2>0){
										$fdata2=mysqli_fetch_assoc($data2);
										$id2=$fdata2["id"];
										$user_update="$ss_id,$fdata2[user_update]";

										$vdata2="hapus=\"1\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
										$vvalues2="id=\"$id2\"";

										$inp2="update db_user_akses set $vdata2 where $vvalues2";
										mysqli_query($db_result, $inp2);
									}
								}

							}else{
								$menu_add=$arpost["menuadd$kv1[0]"];
								$menu_upd=$arpost["menuupd$kv1[0]"];
								$menu_del=$arpost["menudel$kv1[0]"];
								$menu_sel=$arpost["menusel$kv1[0]"];

								if($menu_sel==1){
									$sqld2="select * from db_user_akses where id_menu=\"$kv1[0]\" and hapus=\"0\" and id_level=\"$id1\"";
									$data2=mysqli_query($db_result, $sqld2);
									$ndata2=mysqli_num_rows($data2);
									if($ndata2==0){
										$vdata2="id_level, id_menu, hk_add, hk_edit, hk_delete, status, hapus, tgl_insert, tgl_update, user_update";
										$vvalues2="\"$id1\", \"$kv1[0]\", \"$menu_add\", \"$menu_upd\", \"$menu_del\", \"1\", \"0\", \"$ndatetime\", \"$ndatetime\", \"$ss_id\"";

										$inp2="insert into db_user_akses ($vdata2) values ($vvalues2)";
										mysqli_query($db_result, $inp2);
									}else{
										$fdata2=mysqli_fetch_assoc($data2);
										$id2=$fdata2["id"];
										$user_update="$ss_id,$fdata2[user_update]";

										$vdata2="hk_add=\"$menu_add\", hk_edit=\"$menu_upd\", hk_delete=\"$menu_del\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
										$vvalues2="id=\"$id2\"";

										$inp2="update db_user_akses set $vdata2 where $vvalues2";
										mysqli_query($db_result, $inp2);
									}
								}else{
									$sqld2="select * from db_user_akses where id_menu=\"$kv1[0]\" and hapus=\"0\" and id_level=\"$id1\"";
									$data2=mysqli_query($db_result, $sqld2);
									$ndata2=mysqli_num_rows($data2);
									if($ndata2>0){
										$fdata2=mysqli_fetch_assoc($data2);
										$id2=$fdata2["id"];
										$user_update="$ss_id,$fdata2[user_update]";

										$vdata2="hapus=\"1\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
										$vvalues2="id=\"$id2\"";

										$inp2="update db_user_akses set $vdata2 where $vvalues2";
										mysqli_query($db_result, $inp2);
									}
								}
							}
						}

					}else{
						$nket="<div class=\"alert alert-danger\">Data Gagal Ditambah</div>";
					}
				}else{
					$nket="<div class=\"alert alert-warning\">Data Sudah Ada</div>";
				}
			}

			if($gket=="edit" and $lmn[$idsmenu][2]==1){
				$n=1;

				$sqld="select * from db_user_level where id=\"$pid\" and hapus=\"0\" and id!=\"1\"";
				$data=mysqli_query($db_result, $sqld);
				$ndata=mysqli_num_rows($data);
				if($ndata>0){
					$fdata=mysqli_fetch_assoc($data);
					$user_update="$ss_id,".$fdata["user_update"];
					$id1=$fdata["id"];

					$vdata="nama_level=\"$nama_level\", status=\"$status\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
					$vvalues="id=\"$pid\"";
					
					$inp="insert into  ($vdata) values ($vvalues)";
				
					$inp="update db_user_level set $vdata where $vvalues";
					$upd=mysqli_query($db_result, $inp);
					if($upd==1){
						$nket="<div class=\"alert alert-success\">Data Berhasil Dirubah</div>";

						$ma=MenuAkses();
						$mamenu=$ma["menu"];

						foreach($mamenu as $kv1){
							$nk=0;

							if(count($kv1[5])>0){
								$nk=0;

								foreach($kv1[5] as $kv2){
									/*sub menu*/
									$menu_add=$arpost["menuadd$kv2[0]"];
									$menu_upd=$arpost["menuupd$kv2[0]"];
									$menu_del=$arpost["menudel$kv2[0]"];
									$menu_sel=$arpost["menusel$kv2[0]"];

									if($menu_sel==1){
										$sqld2="select * from db_user_akses where id_menu=\"$kv2[0]\" and hapus=\"0\" and id_level=\"$id1\"";
										$data2=mysqli_query($db_result, $sqld2);
										$ndata2=mysqli_num_rows($data2);
										if($ndata2==0){
											$vdata2="id_level, id_menu, hk_add, hk_edit, hk_delete, status, hapus, tgl_insert, tgl_update, user_update";
											$vvalues2="\"$id1\", \"$kv2[0]\", \"$menu_add\", \"$menu_upd\", \"$menu_del\", \"1\", \"0\", \"$ndatetime\", \"$ndatetime\", \"$ss_id\"";

											$inp2="insert into db_user_akses ($vdata2) values ($vvalues2)";
											mysqli_query($db_result, $inp2);
										}else{
											$fdata2=mysqli_fetch_assoc($data2);
											$id2=$fdata2["id"];
											$user_update="$ss_id,".$fdata2["user_update"];

											$vdata2="hk_add=\"$menu_add\", hk_edit=\"$menu_upd\", hk_delete=\"$menu_del\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
											$vvalues2="id=\"$id2\"";

											$inp2="update db_user_akses set $vdata2 where $vvalues2";
											mysqli_query($db_result, $inp2);
										}

										$nk=1;
									}else{
										$sqld2="select * from db_user_akses where id_menu=\"$kv2[0]\" and hapus=\"0\" and id_level=\"$id1\"";
										$data2=mysqli_query($db_result, $sqld2);
										$ndata2=mysqli_num_rows($data2);
										if($ndata2>0){
											$fdata2=mysqli_fetch_assoc($data2);
											$id2=$fdata2["id"];
											$user_update="$ss_id,".$fdata2["user_update"];

											$vdata2="hapus=\"1\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
											$vvalues2="id=\"$id2\"";

											$inp2="update db_user_akses set $vdata2 where $vvalues2";
											mysqli_query($db_result, $inp2);
										}
									}
								}

								if($nk==1){
									$sqld2="select * from db_user_akses where id_menu=\"$kv1[0]\" and hapus=\"0\" and id_level=\"$id1\"";
									$data2=mysqli_query($db_result, $sqld2);
									$ndata2=mysqli_num_rows($data2);
									if($ndata2==0){
										$vdata2="id_level, id_menu, hk_add, hk_edit, hk_delete, status, hapus, tgl_insert, tgl_update, user_update";
										$vvalues2="\"$id1\", \"$kv1[0]\", \"$menu_add\", \"$menu_upd\", \"$menu_del\", \"1\", \"0\", \"$ndatetime\", \"$ndatetime\", \"$ss_id\"";

										$inp2="insert into db_user_akses ($vdata2) values ($vvalues2)";
										mysqli_query($db_result, $inp2);
									}else{
										$fdata2=mysqli_fetch_assoc($data2);
										$id2=$fdata2["id"];
										$user_update="$ss_id,".$fdata2["user_update"];

										$vdata2="hk_add=\"$menu_add\", hk_edit=\"$menu_upd\", hk_delete=\"$menu_del\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
										$vvalues2="id=\"$id2\"";

										$inp2="update db_user_akses set $vdata2 where $vvalues2";
										mysqli_query($db_result, $inp2);
									}
								}else{
									$sqld2="select * from db_user_akses where id_menu=\"$kv1[0]\" and hapus=\"0\" and id_level=\"$id1\"";
									$data2=mysqli_query($db_result, $sqld2);
									$ndata2=mysqli_num_rows($data2);
									if($ndata2>0){
										$fdata2=mysqli_fetch_assoc($data2);
										$id2=$fdata2["id"];
										$user_update="$ss_id,".$fdata2["user_update"];

										$vdata2="hapus=\"1\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
										$vvalues2="id=\"$id2\"";

										$inp2="update db_user_akses set $vdata2 where $vvalues2";
										mysqli_query($db_result, $inp2);
									}
								}

							}else{
								$menu_add=$arpost["menuadd$kv1[0]"];
								$menu_upd=$arpost["menuupd$kv1[0]"];
								$menu_del=$arpost["menudel$kv1[0]"];
								$menu_sel=$arpost["menusel$kv1[0]"];

								if($menu_sel==1){
									$sqld2="select * from db_user_akses where id_menu=\"$kv1[0]\" and hapus=\"0\" and id_level=\"$id1\"";
									$data2=mysqli_query($db_result, $sqld2);
									$ndata2=mysqli_num_rows($data2);
									if($ndata2==0){
										$vdata2="id_level, id_menu, hk_add, hk_edit, hk_delete, status, hapus, tgl_insert, tgl_update, user_update";
										$vvalues2="\"$id1\", \"$kv1[0]\", \"$menu_add\", \"$menu_upd\", \"$menu_del\", \"1\", \"0\", \"$ndatetime\", \"$ndatetime\", \"$ss_id\"";

										$inp2="insert into db_user_akses ($vdata2) values ($vvalues2)";
										mysqli_query($db_result, $inp2);
									}else{
										$fdata2=mysqli_fetch_assoc($data2);
										$id2=$fdata2["id"];
										$user_update="$ss_id,".$fdata2["user_update"];

										$vdata2="hk_add=\"$menu_add\", hk_edit=\"$menu_upd\", hk_delete=\"$menu_del\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
										$vvalues2="id=\"$id2\"";

										$inp2="update db_user_akses set $vdata2 where $vvalues2";
										mysqli_query($db_result, $inp2);
									}
								}else{
									$sqld2="select * from db_user_akses where id_menu=\"$kv1[0]\" and hapus=\"0\" and id_level=\"$id1\"";
									$data2=mysqli_query($db_result, $sqld2);
									$ndata2=mysqli_num_rows($data2);
									if($ndata2>0){
										$fdata2=mysqli_fetch_assoc($data2);
										$id2=$fdata2["id"];
										$user_update="$ss_id,".$fdata2["user_update"];

										$vdata2="hapus=\"1\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
										$vvalues2="id=\"$id2\"";

										$inp2="update db_user_akses set $vdata2 where $vvalues2";
										mysqli_query($db_result, $inp2);
									}
								}
							}
						}

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
			$sqld="select * from db_user_level where id=\"$gid\" and hapus=\"0\" and id!=\"1\"";
			$data=mysqli_query($db_result, $sqld);
			$ndata=mysqli_num_rows($data);
			if($ndata>0){
				$fdata=mysqli_fetch_assoc($data);
				$pid=$fdata["id"];
				$user_update="$ss_id,".$fdata["user_update"];

				$vdata="hapus=\"1\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
				$vvalues="id=\"$pid\"";

				$inp="update db_user_level set $vdata where $vvalues";
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
			$sqld="select * from db_user_level where id=\"$gid\" and hapus=\"0\" and id!=\"1\"";
			$data=mysqli_query($db_result, $sqld);
			$ndata=mysqli_num_rows($data);
			if($ndata>0){
				$fdata=mysqli_fetch_assoc($data);
				$pid=$fdata["id"];
				$user_update="$ss_id,".$fdata["user_update"];
				$status=($gket==1)? "2" : "1";
				
				$vdata="status=\"$status\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
				$vvalues="id=\"$pid\"";
				
				$inp="update db_user_level set $vdata where $vvalues";
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
