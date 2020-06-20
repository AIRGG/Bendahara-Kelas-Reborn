<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1, width=device-width, viewport-fit=cover">
	<title>Bendahara</title>
	<link rel="stylesheet" href="dist/mdb/css/bootstrap.min.css">
	<link rel="stylesheet" href="dist/mdb/css/mdb.min.css">
	<link rel="stylesheet" href="dist/mdb/css/addons/datatables.min.css">
	<link rel="stylesheet" href="dist/font/css/all.min.css">
</head>


<body class="container">
	<div id="FrmLogin">
		<h5><b>Form Login</b></h5>
		<label for="">Insert NIS</label>
		<label id="msg" class="text-danger d-block ml-0" style="display: none;"></label>
		<input id="nis" type="text" maxlength="11" autofocus="true"><br/>
		<button class="btn btn-primary btn-sm" id="login" style="margin-left: 0px;">Login</button>
	</div>
	<div id="contentNya" style="display: none;">
		<h5 id="head" style="font-weight: bolder;">Bendahara Kelas | User: ?</h5>
		<!-- <div class="navNya"> -->
			<ul class="nav nav-pills" style="margin-left: 0px; font-size: 12px;">
			 	<li class="nav-item">
			    	<a id="navSiswa" class="nav-link" href="#">Siswa</a>
			 	</li>
			 	<li class="nav-item">
			    	<a id="navMasuk" class="nav-link" href="#">Masuk</a>
			 	</li>
			 	<li class="nav-item">
			    	<a id="navKeluar" class="nav-link" href="#">Keluar</a>
			 	</li>
			 	<li class="nav-item">
			    	<a id="navCatatan" class="nav-link" href="#">Catatan</a>
			 	</li>
			 	<li class="nav-item">
			    	<a id="navLogout" class="nav-link btn btn-danger btn-sm" href="#" style="padding: 4px 4px 4px 4px;">Logout</a>
			 	</li>
			</ul>
			<!-- <a id="navSiswa" href="#" class="mr-2" style="display: inline;">Siswa</a>
			<a id="navMasuk" href="#" class="mr-2" style="display: inline;">Masuk</a>
			<a id="navKeluar" href="#" class="mr-2"style="display: inline;">Keluar</a>
			<a id="navCatatan" href="#" class="mr-2"style="display: inline;">Catatan</a>
			<a id="navLogout" href="#" class="mr-2" style="background-color: white; color: #e63d2e; display: inline;">Logout</a> -->
		<!-- </div> -->
		
		
		<div id="seeSiswa" style="display: none; margin-top: 30px;">
			Search: <input id="sSiswa" class="mb-2" type='text'>
		</div>
		<div id="seeMonth" style="display: none; margin-top: 30px;">
			Lihat pada Bulan:
			<select name="" id="bulanNya">
				<?php
				$bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli","Agustus","September","Oktober","November","Desember"];
				$no = 1;
				foreach ($bulan as $k) {
					?>
					<option value="<?php echo strtolower($no); ?>"><?php echo $k; ?></option>
					<?php
					$no++;
				}
				?>
			</select>
			<br/><br/>
		</div>
		<div id="seeOut" style="display: none;">
			<input type="hidden" id="idOut">
			<label for="">Tgl</label><br/>
			<input type="date" id="tglOut"><br/><br/>
			<label for="">Jumlah</label><br/>
			<input type="number" id="jmlOut"><br/><br/>
			<label for="">Event</label><br/>
			<textarea name="" id="eventOut" cols="30" rows="3"></textarea><br/>
			<button class="btn btn-primary btn-sm ml-0" id="sendOut">Kirim</button>
			<button class="btn btn-primary btn-sm ml-0" id="edit" style="display: none;">Edit</button>&nbsp;
			<button class="btn btn-warning btn-sm ml-0" id="batal" style="display: none;">Batal</button>
		</div>
		<div id="seeCatatan" style="display: none;">
			<input type="hidden" id="idCttn">
			<label for="">Isi</label><br/>
			<textarea name="" id="isiCttn" cols="30" rows="3"></textarea><br/>
			<button class="btn btn-primary btn-sm ml-0" id="sendCttn">Kirim</button>
			<button class="btn btn-primary btn-sm ml-0" id="editCttn" style="display: none;">Edit</button>
			<button class="btn btn-warning btn-sm ml-0" id="batalCttn" style="display: none;">Batal</button>
		</div>
		<div id="tablenya">Silahkan Pilih Menu</div>
		
	</div>
	<script src="dist/mdb/js/jquery-3.3.1.min.js"></script>
	<script src="dist/mdb/js/bootstrap.min.js"></script>
	<script src="dist/mdb/js/mdb.min.js"></script>
	<script src="dist/mdb/js/popper.min.js"></script>
	<script src="dist/mdb/js/addons/datatables.min.js"></script>
	<script src="dist/font/js/all.min.js"></script>
	<!-- <script src="dist/datatables.min.js"></script> -->
	<script type="text/javascript">
		$(document).ready(function(){
			$("#nis").focus();
			$("#nis").keypress(function(e){
				if (e.which == 13) {
					$("#login").trigger("click");
				}
			});
		});
		function navNya(navgan){
			var nav = "#navSiswa,#navMasuk,#navKeluar,#navCatatan";
			var isi = nav.split(",");
			for (var i = 0; i <= nav.length; i++) {
				if (isi[i] == navgan) {
					$(navgan).addClass("active");
					continue
				}
				$(isi[i]).removeClass("active");
			}
		}
		$("#navSiswa").click(function(){
			$("#seeMonth, #seeCatatan, #seeOut").hide();
			$("#tablenya").css("display", "none");
			navNya("#navSiswa");

			$.post("empty.php", "siswa", function(){

			}).done(function(data){
				$("#tablenya").html(data);
				$("#tablenya, #seeSiswa").fadeIn(430);
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		});
		$("#navMasuk").click(function(){
			$("#tablenya").css("display", "none");
			$("#seeSiswa, #seeOut, #seeCatatan").hide();
			$("#seeMonth").fadeIn(430);
			navNya("#navMasuk");

			$.post("empty.php", "masuk&bulan="+1, function(){

			}).done(function(data){
				$("#tablenya").html(data);
				$("#tablenya").fadeIn(430);
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		});
		$("#navKeluar").click(function(){
			$("#seeSiswa, #seeMonth, #seeCatatan").hide();
			$("#seeOut").fadeIn(430);
			$("#tablenya").css("display", "none");
			$("#jmlOut").focus();
			navNya("#navKeluar");

			$.post("empty.php", "keluar", function(){

			}).done(function(data){
				$("#tablenya").html(data);
				$("#tablenya").fadeIn(430);
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		});
		$("#navCatatan").click(function(){
			$("#seeSiswa, #seeMonth, #seeOut").hide();
			$("#seeCatatan").fadeIn(430);
			$("#tablenya").css("display", "none");
			$("#isiCttn").focus();
			navNya("#navCatatan");

			$.post("empty.php", "catatan", function(){

			}).done(function(data){
				$("#tablenya").html(data);
				$("#tablenya").fadeIn(430);
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		});
		$("#sendOut").click(function(){
			var tgl = $("#tglOut").val(), jml = $("#jmlOut").val(), event = $("#eventOut").val();
			if (tgl == "" || jml == "" || event == "") {
				alert("Harapp isi semua data");
			}else{
				var jsn = {
					"tgl":tgl,
					"jml":jml,
					"event":event
				}
				$.post("empty.php", "addOut&data="+JSON.stringify(jsn), function(){

				}).done(function(data){
					// alert(data);
					// console.log(data);
					$("#tablenya").html(data);
					$("#tglOut, #jmlOut, #eventOut").val("");
				}).fail(function(xhr, msg, txt){
					alert(xhr+msg+txt)
				});
			}
		});
		$("#edit").click(function(){
			var tgl = $("#tglOut").val(), jml = $("#jmlOut").val(), event = $("#eventOut").val(), id = $("#idOut").val();
			if (tgl == "" || jml == "" || event == "") {
				alert("Harapp isi semua data");
			}else{
				var jsn = {
					"id":id,
					"tgl":tgl,
					"jml":jml,
					"event":event
				}
				$.post("empty.php", "editOut&data="+JSON.stringify(jsn), function(){

				}).done(function(data){
					// alert(data);
					// console.log(data);
					$("#tablenya").html(data);
					$("#tglOut, #jmlOut, #eventOut").val("");
					$("#batal").trigger("click");
				}).fail(function(xhr, msg, txt){
					alert(xhr+msg+txt)
				});
			}
		});
		$("#batal").click(function(){
			$("#sendOut").show();
			$("#batal, #edit").hide();
			$("#tglOut, #jmlOut, #eventOut").val("");
		});
		$("#bulanNya").change(function(){
			var bulan = $(this).val();
			
			$.post("empty.php", "masuk&bulan="+bulan, function(){

			}).done(function(data){
				$("#tablenya").html(data);
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		});
		$("#sendCttn").click(function(){
			var isi = $("#isiCttn").val();
			if (isi == "") {
				alert("Harap isi semua data");
			}else{
				$.post("empty.php", "addCttn&data="+isi, function(){

				}).done(function(data){
					$("#tablenya").html(data);
					$("#isiCttn").val("");
				}).fail(function(xhr, msg, txt){
					alert(xhr+msg+txt)
				});
			}
		});
		$("#editCttn").click(function(){
			var isi = $("#isiCttn").val(), id = $("#idCttn").val();
			if (isi == "") {
				alert("Harap isi semua data")
			}else{
				var jsn = {
					"id":id,
					"isi":isi
				}
				$.post("empty.php", "editCttn&data="+JSON.stringify(jsn), function(){

				}).done(function(data){
					$("#tablenya").html(data);
					$("#isiCttn, #idCttn").val("");
					$("#batalCttn").trigger("click");
				}).fail(function(xhr, msg, txt){
					alert(xhr+msg+txt)
				});
			}
		})
		$("#batalCttn").click(function(){
			$("#sendCttn").show();
			$("#editCttn, #batalCttn").hide();
			$("#isiCttn, #idCttn").val("");
		});
		$("#sSiswa").on("input", function(){
			var isi = $(this).val();
			var s;
			if (isi == "") {
				s = "siswa";
			}else{
				s = "searchSiswa&search="+isi;
			}
			$.post("empty.php", s, function(){

			}).done(function(data){
				$("#tablenya").html(data);
				$("#sSiswa").val($("#sSiswa").val());
				// $("#sSiswa").focus();
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		});
		$("#login").click(function(){
			var nis = $("#nis").val();
			if (nis == "") {
				$("#msg").css("display","none");
				$("#msg").text("Silahkan Masukkan NIS");
				$("#msg").fadeIn("slow");
				$("#msg").append("<br/>");
				$("#nis").focus();
			}else{
				$.post("empty.php", "login&nis="+nis, function(){

				}).done(function(sts){
					var usr = sts.split("_");
					if (usr[0] == "Berhasil") {
						$("#FrmLogin, #seeSiswa, #seeMonth, #seeOut, #seeCatatan").hide();
						$("#contentNya").fadeIn("slow");
						$("#nis").val("");
						$("#msg").text("");
						$("#tablenya").html("Silahakan Pilih Menu");
						$("#head").text("Bendahara Kelas | User: "+usr[1]);
					}else{
						$("#msg").css("display","none");
						$("#msg").text("Gagal Login! NIS Salah!!");
						$("#msg").fadeIn("slow");
						$("#msg").append("<br/>");
						$("#nis").focus();
					}
				}).fail(function(xhr, msg, txt){
					alert(xhr+msg+txt)
				});
			}
		});
		$("#navLogout").click(function(){
			$("#contentNya").hide();
			$("#FrmLogin").fadeIn("slow");
			$("#nis").focus();
		});
		// function datable(){
		// 	$("table").dataTable({
		// 		"paging":false
		// 	})
		// }
		function masuk(id){
			alert("minggu"+id);
		}
		function addMasuk(id){
			$.post("empty.php", "addMasuk&id="+id, function(){

			}).done(function(data){
				$("#tablenya").html(data);
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		}
		function delMasuk(id){
			var id = id.split("_");
			$.post("empty.php", "delMasuk&id="+id[0]+"&bulan="+id[1], function(){

			}).done(function(data){
				$("#tablenya").html(data);
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		}
		function getEditOut(id){
			$("#sendOut").hide();
			$("#batal, #edit").show();
			$.post("empty.php", "getEdit&id="+id, function(){

			}).done(function(data){
				var jsn = data.split("_");
				var tgl = jsn[1].split("-");

				$("#idOut").val(jsn[0]);
				$("#tglOut").val(jsn[1]);
				$("#eventOut").val(jsn[2]);
				$("#jmlOut").val(jsn[3]);
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		}
		function delOut(id){
			$.post("empty.php", "delOut&id="+id, function(){

			}).done(function(data){
				$("#tablenya").html(data);
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		}
		function getEditCttn(id){
			$("#editCttn, #batalCttn").show();
			$("#sendCttn").hide();
			$.post("empty.php", "getEditCttn&id="+id, function(){

			}).done(function(data){
				var jsn = data.split("_");

				$("#idCttn").val(jsn[0]);
				$("#isiCttn").val(jsn[1]);
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		}
		function changeSts(id){
			$.post("empty.php", "changeSts&id="+id, function(){

			}).done(function(data){
				$("#tablenya").html(data);
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		}
		function delCttn(id){
			$.post("empty.php", "delCttn&id="+id, function(){

			}).done(function(data){
				$("#tablenya").html(data);
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		}
		function ubahSts(isi){
			// alert(isi);
			$.post("empty.php", "filStsCttn&isi="+isi, function(){

			}).done(function(data){
				$("#tablenya").html(data);
			}).fail(function(xhr, msg, txt){
				alert(xhr+msg+txt)
			});
		}
		// function searchSiswa(s){
		// 	// alert(s);
		// 	var sch;
		// 	if (s == "") {
		// 		$sch = "siswa";
		// 	}else{
		// 		$sch = "searchSiswa&search="+s;
		// 	}
		// 	$.post("empty.php", $sch, function(){

		// 	}).done(function(data){
		// 		$("#tablenya").html(data);
		// 		$("#sSiswa").val($("#sSiswa").val());
		// 		$("#sSiswa").focus();

		// 	}).fail(function(xhr, msg, txt){
		// 		alert(xhr+msg+txt)
		// 	});
		// }
	</script>
</body>
</html>