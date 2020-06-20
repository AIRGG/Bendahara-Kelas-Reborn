<?php
/**
 * 
 */
header("Access-Control-Allow-Origin: *");
class database
{
	private $host = "localhost";
	private $user = "root";
	private $pass = "";
	private $name = "db_bendahara_kelas";

	public $act;
	function __construct()
	{
		$this->connect();
	}
	private function connect(){
		$this->act = new mysqli($this->host, $this->user, $this->pass, $this->name);
		if (!$this->act) {
			die($this->act->connect_errno.$this->act->connect_error);
			return false;
		}
	}
}
/**
 * 
 */
class excecution extends database
{
	function safe($str){
		return $this->act->real_escape_string($str);
	}
	function sql($q){
		$query = $this->act->query($this->safe($q));
		return $query;
	}
	function getLastId($tbl="masuk"){
		$q = $this->act->prepare("SELECT id FROM $tbl ORDER BY id DESC LIMIT 1");
		$q->execute();
		$id = $q->get_result()->fetch_array()['id'];
		$q->close();
		$id = ($id == null)? 1:$id+1;
		return $id;

		// $sql = sprintf("SELECT id FROM %s ORDER BY id DESC LIMIT 1", $this->safe($tbl));
		// $query = $this->sql($sql);
		// $query = $query->fetch_array()["id"];
		// $query = ($query == null)? 1 : $query+1;
		// return $query;
	}
// ==================================================================================================
	
	//--| MASUK |--\\
	//--| MASUK |--\\
	//--| MASUK |--\\

	function delMasuk($id){
		$q = $this->act->prepare("DELETE FROM masuk WHERE id=?");
		$q->bind_param("i", $id);
		$q->execute();
		$q->close();
		// $sql = $this->sql(sprintf("DELETE FROM masuk WHERE id=%s", $id));
		// return $sql;
	}
	function addMasuk($id){
		$all = explode("_", $id);
		if ($all[1] < 10) {
			$bln = "0".$all[1];
		}else{
			$bln = $all[1];
		}
		$datenya = date("d".$bln.$all[3]);
		$id = $this->getLastId();
		$nis = $all[0];
		$nominal = 3750; // Nominal perminggu
		$sts = $all[2];
		$q = $this->act->prepare("INSERT INTO masuk(id, tgl, nis, nominal, ketmsk) VALUES(?,?,?,?,?)");
		$q->bind_param("issss", $id,$datenya,$nis,$nominal,$sts);
		$q->execute();
		$q->close();
		// $this->qry("INSERT INTO masuk(id, tgl, nis, nominal, ketmsk) VALUES('$id', '$datenya', '$nis', '$nominal', '$sts')");
		// $sql = $this->act->query(sprintf("INSERT INTO masukmasuk(id, tgl, nis, nominal, ketmsk) VALUES(%s, %s, %s, %s, %s)", $this->getLastId(), $datenya, $all[0], 2500, $all[2]));
		// return $sql;
	}

// ==================================================================================================

	//--| OUT |--\\
	//--| OUT |--\\
	//--| OUT |--\\

	function addOut($dt){
		$jsn = json_decode($dt, true);
		$id = $this->getLastId("keluar");
		$jml = $jsn['jml'];
		$tgl = $jsn['tgl'];
		$event = $jsn['event'];
		$q = $this->act->prepare("INSERT INTO keluar VALUES(?,?,?,?)");
		$q->bind_param("isss", $id,$tgl,$event,$jml);
		$q->execute();
		$q->close();
		// $this->qry("INSERT INTO keluar VALUES('$id','$tgl','$event','$jml')");
	}
	function getEditOut($id){
		// $sql = $this->sql(sprintf("SELECT * FROM keluar WHERE id=%s", $id));
		$q = $this->act->prepare("SELECT * FROM keluar WHERE id=?");
		$q->bind_param("i", $id);
		$q->execute();
		$sql = $q->get_result();
		foreach ($sql as $key => $v) {
			echo $v['id']."_".$v['tgl']."_".$v['event']."_".$v['nominal'];
		}
		$q->close();
	}
	function editOut($dt){
		$jsn = json_decode($dt, true);
		$id = $jsn['id'];
		$jml = $jsn['jml'];
		$tgl = $jsn['tgl'];
		$event = $jsn['event'];
		$q = $this->act->prepare("UPDATE keluar SET tgl=?, event=?, nominal=? WHERE id=?");
		$q->bind_param("sssi", $tgl,$event,$jml,$id);
		$q->execute();
		$q->close();
		// $this->qry("UPDATE keluar SET tgl='$tgl', event='$event', nominal='$jml' WHERE id='$id'");
	}
	function delOut($id){
		$q = $this->act->prepare("DELETE FROM keluar WHERE id=?");
		$q->bind_param("i",$id);
		$q->execute();
		$q->close();
		// $sql = $this->sql(sprintf("DELETE FROM keluar WHERE id=%s", $id));
		// return $sql;
	}
// ==================================================================================================

	//--| CATATAN |--\\
	//--| CATATAN |--\\
	//--| CATATAN |--\\

	function addCatatan($dt){
		$id = $this->getLastId("catatan");
		$sts = 0;
		$tgl = date("Y-m-d");
		// $this->qry("INSERT INTO catatan VALUES('$id','$tgl','$dt','$sts')");
		$q = $this->act->prepare("INSERT INTO catatan VALUES(?,?,?,?)");
		$q->bind_param("ssss",$id, $tgl, $dt, $sts);
		$q->execute();
		$q->close();
		// $sql = $this->sql(sprintf("INSERT INTO catatan VALUES('%s', '%s', '%s', '%s')", $id, $tgl, $dt, $sts));
		// return $sql;
	}
	function getEditCatatan($id){
		$sql = $this->act->prepare("SELECT * FROM catatan WHERE id=?");
		$sql->bind_param("s", $id);
		$sql->execute();
		$sql = $sql->get_result();
		foreach ($sql as $key => $v) {
			echo $v['id']."_".$v['isi'];
		}
		$sql->close();

		// $sql = $this->sql(sprintf("SELECT * FROM catatan WHERE id=%u", $id));
		// foreach ($sql as $key => $v) {
		// 	echo $v['id']."_".$v['isi'];
		// }
		// $arr = array();
		// foreach ($sql as $key => $v) {
		// 	array_push($arr, $v['id'], $v['isi']);
		// }
		// return $arr;
	}
	function editCatatan($dt){
		$jsn = json_decode($dt, true);
		$id = $jsn['id'];
		$isi = $jsn['isi'];
		// $this->qry("UPDATE catatan SET isi='$isi' WHERE id='$id'");
		$stm = $this->act->prepare("UPDATE catatan SET isi=? WHERE id=?");
		$stm->bind_param("ss", $isi, $id);
		$stm->execute();
		$stm->close();
	}
	function changeSts($id){
		// $q = $this->sql(sprintf("SELECT sts FROM catatan WHERE id=%u", $id));
		$q = $this->act->prepare("SELECT sts FROM catatan WHERE id=?");
		$q->bind_param("s", $id);
		$q->execute();
		$sts = $q->get_result()->fetch_array()["sts"];
		$q->close();

		$sts = ($sts == 1)? 0 : 1;
		// echo $sts;
		// $this->qry("UPDATE catatan SET sts='$sts' WHERE id='$id'");

		$stm = $this->act->prepare("UPDATE catatan SET sts=? WHERE id=?");
		$stm->bind_param("ss", $sts,$id);
		$stm->execute();
		$stm->close();

		// $this->ss("UPDATE catatan SET sts=? WHERE id=?", "ss", array($sts,$id));
		// call_user_func_array(function, param_arr)
	}
	function delCatatan($id){
		$q = $this->act->prepare("DELETE FROM catatan WHERE id=?");
		$q->bind_param("i", $id);
		$q->execute();
		$q->close();

		// $this->ss("DELETE FROM catatan WHERE id=?", "i", $id);
		// $sql = $this->sql(sprintf("DELETE FROM catatan WHERE id=%u", $id));
		// return $sql;
	}
	function login($nis){
		$q = $this->act->prepare("SELECT * FROM siswa WHERE nis=?");
		$q->bind_param("s",$nis);
		$q->execute();
		$l = $q->get_result()->num_rows;
		$q->close();

		$q = $this->act->prepare("SELECT nama FROM siswa WHERE nis=?");
		$q->bind_param("s",$nis);
		$q->execute();
		$nama = $q->get_result()->fetch_array()['nama'];
		$q->close();
		$nama = explode(" ", $nama);
		if ($nama[0][0] == "M") {
			$nama = $nama[1];
		}else{
			$nama = $nama[0];
		}

		if ($l == 1) {
			// echo "Berhasil_".ucwords(strtolower($nama));
			echo "Berhasil_".ucwords(strtolower($nama));
		}else{
			echo "Gagal_0";
		}
		// echo $lvl;
		// print_r($lvl);
	}


	// function getAllName(){
	// 	$query = $this->sql("SELECT * FROM siswa");
	// 	$name = array();
	// 	foreach ($query as $key => $v) {
	// 		array_push($name, $v['nis']);
	// 	}
	// 	return $name;
	// }
	// function dateTgl($tgl){
	// 	// return str_pad($tgl, 2, "0", STR_PAD_LEFT);
	// 	// $date = DateTime::createFromFormat("d", $tgl);
	// 	$date = DateTime::createFromFormat("dd-MM-yyyy", $tgl);
	// 	return $date->format("yyyy-MM-dd");
	// }
}
?>