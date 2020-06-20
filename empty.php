<?php
include 'config/class.php';
$db = new excecution();


if (isset($_POST['siswa'])) {
	showTableSiswa();

}else if (isset($_POST['masuk'])) {
	$bulan = $_POST['bulan'];
	$tahun = $_POST['tahun'];
	showTableMasuk($bulan, $tahun);

}else if(isset($_POST['keluar'])){
	showTableKeluar();

}else if(isset($_POST['catatan'])){
	showTableCatatan();

}else if(isset($_POST['addMasuk'])){
	$id = $_POST['id'];
	$bulan = explode("_", $id);
	$db->addMasuk($id);
	showTableMasuk($bulan[1], $bulan[3]);

}else if (isset($_POST['delMasuk'])) {
	$id = $_POST['id'];
	$bulan = $_POST['bulan'];
	$tahun = $_POST['tahun'];
	$db->delMasuk($id);
	showTableMasuk($bulan, $tahun);

}elseif (isset($_POST['addOut'])) {
	$jsn = $_POST['data'];
	$db->addOut($jsn);
	showTableKeluar();

}else if (isset($_POST['getEdit'])) {
	$id = $_POST['id'];
	$db->getEditOut($id);

}else if (isset($_POST['delOut'])) {
	$id = $_POST['id'];
	$db->delOut($id);
	showTableKeluar();

}else if (isset($_POST['editOut'])) {
	$jsn = $_POST['data'];
	$db->editOut($jsn);
	showTableKeluar();

}else if (isset($_POST['addCttn'])) {
	$dt = $_POST['data'];
	$db->addCatatan($dt);
	showTableCatatan();

}else if(isset($_POST['getEditCttn'])){
	$id = $_POST['id'];
	$db->getEditCatatan($id);

}else if(isset($_POST['editCttn'])){
	$jsn = $_POST['data'];
	$db->editCatatan($jsn);
	showTableCatatan();

}else if(isset($_POST['changeSts'])){
	$id = $_POST['id'];
	$db->changeSts($id);
	showTableCatatan();

}else if (isset($_POST['delCttn'])) {
	$id = $_POST['id'];
	$db->delCatatan($id);
	showTableCatatan();
}else if(isset($_POST['filStsCttn'])){
	$isi = $_POST['isi'];
	showTableCatatan($isi);
}else if(isset($_POST['searchSiswa'])){
	$search = $_POST['search'];
	showTableSiswa($search);

}else if (isset($_POST['login'])) {
	$nis = $_POST['nis'];
	$db->login($nis);
}else{
	header("location:index.php");
}
// echo is_int($db->addMasuk("3_3_3"));

// $db->changeSts(2);
// echo $db->getLastId("catatan");
// $db->login("1017007631");

function showTableSiswa($sch=""){
	$db = new excecution();
	if ($sch == "") {
		$q = $db->act->prepare("SELECT *, (SELECT sum(b.nominal) FROM masuk b WHERE b.nis=a.nis) as jml FROM siswa a");
	}else{
		$q = $db->act->prepare("SELECT *, (SELECT sum(b.nominal) FROM masuk b WHERE b.nis=a.nis) as jml FROM siswa a WHERE a.nama LIKE ?");
		$s = "%".$sch."%";
		$q->bind_param("s", $s);
	}
	$q->execute();
	$sql = $q->get_result();
	// echo $sch;
	?>
	<table class="table table-responsive  table-bordered text-center" style="border-collapse: collapse;">
		<thead>
			<th>No</th>
			<th>NIS</th>
			<th>Nama</th>
			<th>Jumlah</th>
		</thead>
		<?php
		foreach ($sql as $key => $v) {
			$key+=1;
			?>
			<tr style="vertical-align: middle;">
				<td><?php echo $key; ?></td>
				<td><?php echo $v['nis']; ?></td>
				<td><?php echo $v['nama']; ?></td>
				<td style="white-space: nowrap;"><?php echo "Rp. ".number_format($v['jml'])." ,-"; ?></td>
			</tr>
		<?php } ?>
	</table>
	<?php
}

function showTableCatatan($isi = 2){
	$db = new excecution();
	if ($isi == 2) {
		$sql = $db->act->prepare("SELECT * FROM catatan");
		// $sqll->bind_param("i",$isi);
	}else{
		$sql = $db->act->prepare("SELECT * FROM catatan WHERE sts=?");
		$sql->bind_param("i",$isi);
	}
	$sql->execute();
	$hasil = $sql->get_result();
	$sql->close();

	

	$q = $db->act->prepare("SELECT sum(nominal) as jml FROM masuk");
	$q->execute();
	$q = $q->get_result();

	$jmlMsk;
	foreach ($q as $key => $v) {
		$jmlMsk = $v['jml'];
		echo "<br/>Total <b>Masuk</b>: Rp. ".number_format($v['jml'])." ,-";
	}
	$q->close();
	
	$q = $db->act->prepare("SELECT sum(nominal) as jml FROM keluar");
	$q->execute();
	$q = $q->get_result();
	$jmlKel;
	foreach ($q as $key => $v) {
		$jmlKel = $v['jml'];
		echo "<br/>Total <b>Keluar</b>: Rp. ".number_format($v['jml'])." ,-";
	}
	$q->close();

	$total = $jmlMsk-$jmlKel;
	echo "<br/><br/><b>Sisa Saldo</b>: Rp. ".number_format($total)." ,-";
	?>
	<br/><br/>
	<label for="">Filter: </label>
	<select onchange="ubahSts(this.value);" name="" id="filStsCttn">
		<option value="2" <?php echo ($isi == 2)? "selected":""; ?>>All</option>
		<option value="1" <?php echo ($isi == 1)? "selected":""; ?>>Finish</option>
		<option value="0" <?php echo ($isi == 0)? "selected":""; ?>>Waiting</option>
	</select>
	<table class="table table-responsive  table-bordered text-center mt-2">
		<thead>
			<th>No</th>
			<th>Tgl</th>
			<th>Isi</th>
			<th>Status</th>
			<th>Action</th>
		</thead>
		<?php
		foreach ($hasil as $key => $v) {
			$key += 1;
			?>
			<tr style="vertical-align: middle;">
				<td><?php echo $key; ?></td>
				<td><?php echo $v['tgl'] ?></td>
				<td class="tdgy"><?php echo $v['isi'] ?></td>
				<td><?php echo ($v['sts'] == 0)? "<label class='badge badge-warning'>Waiting</label>" : "<label class='badge badge-primary'>Finish</label>"; ?></td>
				<td>
					<select name="" id="" onchange="changeSts('<?php echo $v["id"]; ?>')" style="background-color: white;">
						<option value=""<?php echo ($v['sts'] == 0)? "selected='true'" : ""; ?>>Waiting</option>
						<option value=""<?php echo ($v['sts'] == 1)? "selected='true'" : ""; ?>>Finish</option>
					</select>
					<button class="btn btn-warning btn-sm" onclick="getEditCttn('<?php echo $v["id"]; ?>')"><i class="fas fa-edit"></i></button>
					<button class="btn btn-danger btn-sm" onclick="delCttn('<?php echo $v["id"]; ?>')"><i class="fas fa-times"></i></button>
				</td>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
}

function showTableKeluar(){
	$db = new excecution();
	$sql = $db->sql("SELECT * FROM keluar");
	$q = $db->act->prepare("SELECT sum(nominal) as jml FROM keluar");
	$q->execute();
	$q = $q->get_result();

	foreach ($q as $key => $v) {
		echo "<br/>Total Keluar: Rp. ".number_format($v['jml'])." ,-";
	}
	$q->close();
	?>
	<table class="table table-responsive table-bordered text-center mt-2">
		<thead>
			<th>No</th>
			<th>Tgl</th>
			<th>Event</th>
			<th>Nominal</th>
			<th>Action</th>
		</thead>
		<?php
		foreach ($sql as $key => $v) {
			$key +=1;
			?>
			<tr style="vertical-align: middle;">
				<td><?php echo $key ?></td>
				<td><?php echo $v['tgl'] ?></td>
				<td class="tdgy"><?php echo $v['event'] ?></td>
				<td><?php echo "Rp. ".number_format($v['nominal'])." ,-" ?></td>
				<td>
					<button class="btn btn-warning btn-sm" onclick="getEditOut('<?php echo $v["id"]; ?>')"><i class="fas fa-edit"></i></button>
					<button class="btn btn-danger btn-sm" onclick="delOut('<?php echo $v["id"]; ?>')"><i class="fas fa-times"></i></button>
				</td>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
}

function showTableMasuk($bulan, $tahun){
	$db = new excecution();
	$sql = $db->sql(sprintf("SELECT *, (SELECT count(ketmsk) FROM masuk b WHERE a.nis=b.nis AND substr(b.tgl, 3, 2) = %s AND substr(b.tgl, 5, 4) = %s LIMIT 1) as ket FROM siswa a", $bulan, $tahun));
	
	$sqll = $db->sql(sprintf("SELECT sum(nominal) as nominal FROM masuk WHERE substr(tgl, 3, 2) = %s AND substr(tgl, 5, 4) = %s", $bulan, $tahun));
	$sqlll = $db->sql(sprintf("SELECT sum(nominal) as nominal FROM masuk"));
	$mon = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli","Agustus","September","Oktober","November","Desember"];
	foreach ($sqlll as $key => $v) {
		echo "Total Seluruhnya: Rp. ".number_format($v['nominal'])." ,-<br/>";
	}
	foreach ($sqll as $key => $v) {
		echo "Total <b>".$mon[$bulan]."</b> <b>".$tahun."</b>: Rp. ".number_format($v['nominal'])." ,-";
	}
	
	?>

	<table class="table table-responsive  mt-2 text-center">
		<thead>
			<th>No</th>
			<th>Nama</th>
			<?php
			for ($i=1; $i <= 4; $i++) { 
				?>
				<th><?php echo $i; ?></th>
			<?php } ?>
			<th>Keterangan</th>
		</thead>
		<?php
		foreach ($sql as $key => $va) {
			$key += 1;

			$nama = explode(" ", $va['nama']);
			if ($nama[0][0] == "M") {
				$nama = $nama[1];
			}else{
				$nama = $nama[0];
			}
			?>
			<tr style="padding: 0px; vertical-align: middle;">
				<td><?php echo $key; ?></td>
				<td><?php echo $nama; ?></td>
				<?php
				$sql = $db->sql(sprintf("SELECT *, (SELECT count(ketmsk) FROM masuk b WHERE a.nis=b.nis) as ket FROM masuk a WHERE substr(a.tgl, 3, 2) = %s AND substr(a.tgl, 5, 4) = %s AND nis=%s", $bulan, $tahun, $va['nis']));
				foreach ($sql as $key => $v) {
					?>
					<td style="padding: 0px;"><button class="btn btn-success btn-sm mt-3" onclick="delMasuk('<?php echo $v["id"]."_".$bulan."_".$tahun; ?>')" style="padding: 2px 2px 2px 2px;" title="Remove"><i class="fas fa-check"></i></button></td>
					<?php
				}
				$arr = [4,3,2,1,0];
				for ($i=0; $i < 5; $i++) { 
					if($va['ket'] == $i):
						for ($o=0; $o < $arr[$i]; $o++) {
							$all =  $va["nis"]."_".$bulan."_".($va["ket"]+1)."_".$tahun;
							?>
							<td style="padding: 0px;"><button class="btn btn-warning btn-sm mt-3" onclick="addMasuk('<?php echo $all; ?>')" style="padding: 2px 4px 2px 4px;" title="Bayar"><i class="	fas fa-times"></i></button></td>
							<?php
						}
						?>
						<td><?php echo ($va['ket'] < 4)? "<label class='badge badge-danger'>Belum</label>" : "<label class='badge badge-primary'>Lunas</label>";?></td>
						<?php
					endif;
				}
				?>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
}
?>