<?php
if(preg_match("/\bindex.php\b/i", $_SERVER['REQUEST_URI'])){
    exit;
}else{

$jicd="2";

switch($act){
	default:
		$na_status=SelStatus();
		$posisi=CariPosisi($batas, $ghal);
		$wtab=(!empty($tab))? "and singkatan like \"%$tab%\"" : "";
		$td_table="";
		$sqld="select * from ms_icd where hapus=\"0\" and nama_penyakit like \"%$gname%\" and kode_icd like \"%$gup%\" and jenis_icd=\"$jicd\" $wtab order by nama_penyakit";
		$data=mysqli_query($db_result, "$sqld limit $posisi, $batas");
		$ndata=mysqli_num_rows($data);
		if($ndata>0){
			$no=$posisi+1;
			while($fdata=mysqli_fetch_assoc($data)){
				extract($fdata);
				$nama_status=$na_status[$status][1];
				$nama_jenisicd=$na_status[$jenis_icd][6];
				
				if($lmn[$idsmenu][2]==1){
					$link_edit="$link_back&act=input&gket=edit&gid=$id&ghal=$ghal";
					$btn_edit="<a href=\"$link_edit\" class=\"btn btn-xs btn-success\">Edit</a>";

					$nama_warna=($status==1)? "bg-navy" : "default disable";
					$link_status="$link_back&act=status&gket=$status&gid=$id&ghal=$ghal";
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
					<td>$nama_jenisicd</td>
					<td>$kode_icd</td>
					<td>$nama_penyakit</td>
					<td>$singkatan</td>
					<td>$btn_status</td>
					<td>$btn_edit $btn_hapus</td>
				</tr>";

				$no++;
			}
		}

		if($lmn[$idsmenu][1]==1){
			$link_add="$link_back&act=input&gket=tambah";
			$btn_add="<a href=\"$link_add\" class=\"btn btn-primary\"><i class=\"fa fa-plus\"></i> Tambah Data</a>";
		}

		$data2=mysqli_query($db_result, $sqld);
		$jmldata=mysqli_num_rows($data2);
		if($jmldata>$batas){
			$jh=JumlahHalaman($jmldata, $batas);
			$np=NavPage($ghal, $jh);
			
			$fghal=NumberFormat($ghal);
			$fjh=NumberFormat($jh);
			$fjmldata=NumberFormat($jmldata);
		
			$pg1="";
			foreach($np as $np1){
				$npclass="";
				$nplink="$link_back&gname=$gname&gup=$gup&tab=$tab&ghal=$np1[0]";

				if($np1[1]=="first"){
					$np0="First";
				}
				elseif($np1[1]=="back"){
					$np0="Previous";
				}
				elseif($np1[1]=="last"){
					$np0="Last";
				}
				elseif($np1[1]=="next"){
					$np0="Next";
				}
				elseif($np1[1]=="active"){
					$nplink="#";
					$np0="$np1[0]";
					$npclass="active";
				}
				else{
					$np0="$np1[0]";
				}

				$pg1.="<li class=\"paginate_button $npclass\"><a href=\"$nplink\">$np0</a></li>";
			}
			
			$list_pag="<div class=\"row\">
				<div class=\"col-sm-5\">
					<div class=\"dataTables_info\">Halaman ke $fghal dari $fjh Halaman - $fjmldata Data</div>
				</div>
				<div class=\"col-sm-7\">
					<div class=\"dataTables_paginate paging_simple_numbers\">
						<ul class=\"pagination\">
							$pg1
						</ul>
					</div>
				</div>
			</div>";
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
								<label class=\"col-sm-2 control-label text-left\">Kode ICD</label>
								<div class=\"col-sm-3\"><input type=\"text\" class=\"form-control\" name=\"gup\"></div>
							</div>
							<div class=\"form-group\">
								<label class=\"col-sm-2 control-label text-left\">Nama ICD/Penyakit</label>
								<div class=\"col-sm-3\"><input type=\"text\" class=\"form-control\" name=\"gname\"></div>
							</div>
							<div class=\"form-group\">
								<label class=\"col-sm-2 control-label text-left\">Nama Pendek</label>
								<div class=\"col-sm-3\"><input type=\"text\" class=\"form-control\" name=\"tab\"></div>
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
							<table class=\"table table-bordered table-hover\">
								<thead>
									<tr>
										<th width=\"50\">No</th>
										<th>ICD</th>
										<th>Kode ICD</th>
										<th>Nama ICD/Penyakit</th>
										<th>Nama Pendek</th>
										<th>Status</th>
										<th width=\"150\">Aksi</th>
									</tr>
								</thead>
								<tbody>
									$td_table
								</tbody>
							</table>
						</div>

						$list_pag
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

			$sqld="select * from ms_icd where id=\"$gid\" and hapus=\"0\" and jenis_icd=\"$jicd\"";
			$data=mysqli_query($db_result, $sqld);
			$ndata=mysqli_num_rows($data);
			if($ndata>0){
				$fdata=mysqli_fetch_assoc($data);
				extract($fdata);

				$edit="<input name=\"pid\" type=\"hidden\" value=\"$id\" />";
			}
		}

		if($ndata>0){
			$sstatus=SelStatus();
			$opt_status="";
			$opt_jenisicd="";
			foreach($sstatus as $sstatus1){
				$sel1=($sstatus1[0]==$status)? "selected=\"selected\"" : "";
				$opt_status.="<option value=\"$sstatus1[0]\" $sel1>$sstatus1[1]</option>";
				
				$sel2=($sstatus1[0]==$jenis_icd)? "selected=\"selected\"" : "";
				$opt_jenisicd.="<option value=\"$sstatus1[0]\" $sel2>$sstatus1[6]</option>";
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
		                    				<label class=\"col-sm-2\">Kode ICD</label>
		                    				<div class=\"col-sm-5\">
		                    					<input type=\"text\" name=\"kode_icd\" value=\"$kode_icd\" class=\"form-control\" required/>
		                    				</div>
										</div>
										<div class=\"form-group\">
		                    				<label class=\"col-sm-2\">Nama ICD/Penyakit</label>
		                    				<div class=\"col-sm-5\">
		                    					<input type=\"text\" name=\"nama_penyakit\" value=\"$nama_penyakit\" class=\"form-control\" required/>
		                    				</div>
										</div>
										<div class=\"form-group\">
		                    				<label class=\"col-sm-2\">Singkatan</label>
		                    				<div class=\"col-sm-5\">
		                    					<input type=\"text\" name=\"singkatan\" value=\"$singkatan\" class=\"form-control\"/>
												<small>nama singkatan jika lebih dari 1 dipisahkan dengan tanda koma (,)</small>
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
												<a href=\"$link_back&ghal=$ghal\" class=\"btn bg-navy\"><i class=\"fa fa-caret-left\"></i> Kembali</a>
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
			echo"<meta http-equiv=\"refresh\" content=\"2;url=$link_back&ghal=$ghal\">";
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
		$error.=(empty($kode_icd))? "&bull; Kode ICD Masih Kosong<br />" : "";
		$error.=(empty($nama_penyakit))? "&bull; Nama ICD/Penyakit Masih Kosong<br />" : "";
		$error.=($status=="")? "&bull; Status Tidak Boleh Kosong<br />" : "";

		if(empty($error)){
			if($gket=="tambah" and $lmn[$idsmenu][1]==1){
				$n=1;

				$sqld="select * from ms_icd where nama_penyakit=\"$nama_penyakit\" and hapus=\"0\" and jenis_icd=\"$jicd\"";
				$data=mysqli_query($db_result, $sqld);
				$ndata=mysqli_num_rows($data);
				if($ndata==0){
					$vdata="jenis_icd, kode_icd, nama_penyakit, singkatan, status, hapus, tgl_insert, tgl_update, user_update";
					$vvalues="\"$jicd\", \"$kode_icd\", \"$nama_penyakit\", \"$singkatan\", \"$status\", \"0\", \"$ndatetime\", \"$ndatetime\", \"$ss_id\"";
					
					$inp="insert into ms_icd ($vdata) values ($vvalues)";
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

				$sqld="select * from ms_icd where id=\"$pid\" and hapus=\"0\" and jenis_icd=\"$jicd\"";
				$data=mysqli_query($db_result, $sqld);
				$ndata=mysqli_num_rows($data);
				if($ndata>0){
					$fdata=mysqli_fetch_assoc($data);
					$fid=$fdata["id"];
					$user_update="$ss_id,".$fdata["user_update"];
					
					$vdata="kode_icd=\"$kode_icd\", nama_penyakit=\"$nama_penyakit\", singkatan=\"$singkatan\", status=\"$status\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
					$vvalues="id=\"$fid\"";

					$inp="update ms_icd set $vdata where $vvalues";
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

		echo"<meta http-equiv=\"refresh\" content=\"2;url=$link_back&ghal=$ghal\">";
	break;

	case"hapus":
		if($lmn[$idsmenu][3]==1){
			$sqld="select * from ms_icd where id=\"$gid\" and hapus=\"0\" and jenis_icd=\"$jicd\"";
			$data=mysqli_query($db_result, $sqld);
			$ndata=mysqli_num_rows($data);
			if($ndata>0){
				$fdata=mysqli_fetch_assoc($data);
				$pid=$fdata["id"];
				$user_update="$ss_id,".$fdata["user_update"];

				$vdata="hapus=\"1\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
				$vvalues="id=\"$pid\"";

				$inp="update ms_icd set $vdata where $vvalues";
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
		<meta http-equiv=\"refresh\" content=\"2;url=$link_back&ghal=$ghal\">";
	break;

	case"status":
		if($lmn[$idsmenu][2]==1){
			$sqld="select * from ms_icd where id=\"$gid\" and hapus=\"0\" and jenis_icd=\"$jicd\"";
			$data=mysqli_query($db_result, $sqld);
			$ndata=mysqli_num_rows($data);
			if($ndata>0){
				$fdata=mysqli_fetch_assoc($data);
				$pid=$fdata["id"];
				$user_update="$ss_id,".$fdata["user_update"];
				$status=($gket==1)? "2" : "1";
				
				$vdata="status=\"$status\", tgl_update=\"$ndatetime\", user_update=\"$user_update\"";
				$vvalues="id=\"$pid\"";
				
				$inp="update ms_icd set $vdata where $vvalues";
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
		<meta http-equiv=\"refresh\" content=\"2;url=$link_back&ghal=$ghal\">";
	break;
}
}
?>
