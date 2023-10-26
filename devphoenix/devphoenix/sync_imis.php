<?php
//error_reporting(E_ALL & ~E_NOTICE);
//date_default_timezone_set('Asia/Jakarta');
//include ("db.php");

$con = mysqli_connect("localhost","root","J@karta2016","phoenix_db");
//$con = mysqli_connect("localhost","root","","phoenix_db");
//$con = mysqli_connect("localhost","edisitus","qwerty11","edisitus_phoenix");
// Check connection
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
include 'JSON.php';

switch ($_GET["opt"]){
	case "historyupload":
		$json = new Services_JSON();
		$daten = date('Y-m-d');
		$query = mysqli_query($con,"SELECT t1.nrp,t1.hm,t1.km,new_eq_num AS unit,DATE_FORMAT(t1.date_insert,'%H:%i %d-%m-%Y') as `datetime`,total_realisasi AS liter FROM fuel_refill AS t1 
				INNER JOIN master_equipment AS t2 ON t2.master_equipment_id=t1.equipment_id
				WHERE t1.realisasi_by='".$_GET["user"]."' AND t1.insert_from=2 AND DATE(t1.date_insert)='".$daten."'");
		if($query && mysqli_num_rows($query) > 0){
			$data = array();
			while($row = mysqli_fetch_object($query)){
				$data[] = $row;
			}
			echo $json->encode($data);
		}else{echo $json->encode(NULL);}
		break;
	case "savefuelAll":
		$json = new Services_JSON();
		$objord = $json->decode($_POST["ord"]);
		$whr = "";
		$nr = 0;
		foreach ($objord->fuel as $row)
		{
			$item = explode(' ', $row->tgl); //-Tue Dec 13 2016 12:46:57 GMT+0700 (WIB)
			$date = $item[3]."-".date('m')."-".$item[2];
			$query = mysqli_query($con,"SELECT hm,km FROM fuel_refill WHERE equipment_id='".$row->unit."' AND nrp='".$row->nrp."' ORDER BY `fuel_refill_id` DESC LIMIT 0,1");
			if($query && mysqli_num_rows($query) > 0){
				$rown = mysqli_fetch_object($query);
				$hm_last = $rown->hm;
				$km_last = $rown->km;
			}else{
				$hm_last = 0;
				$km_last = 0;
			}
			$resp =  mysqli_query($con,"INSERT INTO fuel_refill(`equipment_id`,`nrp`,`date_fill`,`time_fill_start`,`time_fill_end`,`total_realisasi`,`shift`,`hm`,`km`,`hm_last`,`km_last`,`realisasi_by`,`insert_from`) 
				VALUES ('".$row->unit."','".$row->nrp."','".$date."','".$row->jam_mulai."','".$row->jam_selesai."','".$row->pengisian."','".$row->shift."','".$row->hm."','".$row->km."','".$hm_last."','".$km_last."','".$row->user."','2');");
			if(($nr == 0)&&($resp == 1)){
				$whr .= $row->id;
			}elseif(($nr > 0)&&($resp == 1)){
				$whr .= ", ".$row->id;
			}
			$nr++;
				
		}
		echo $whr;
		break;
	case "savefuel":
		$item = explode(' ', $_GET["tglisi"]); //-Tue Dec 13 2016 12:46:57 GMT+0700 (WIB)
		$date = $item[3]."-".date('m')."-".$item[2];
		$query = mysqli_query($con,"SELECT hm,km FROM fuel_refill WHERE equipment_id='".$_GET["unit"]."' AND nrp='".$_GET["nrp"]."' ORDER BY `fuel_refill_id` DESC LIMIT 0,1");
		if($query && mysqli_num_rows($query) > 0){
			$row = mysqli_fetch_object($query);
			$hm_last = $row->hm;
			$km_last = $row->km;
		}else{
			$hm_last = 0;
			$km_last = 0;
		}
		$resp =  mysqli_query($con,"INSERT INTO fuel_refill(`equipment_id`,`nrp`,`date_fill`,`time_fill_start`,`time_fill_end`,`total_realisasi`,`shift`,`hm`,`km`,`hm_last`,`km_last`,`realisasi_by`,`insert_from`) 
				VALUES ('".$_GET["unit"]."','".$_GET["nrp"]."','".$date."','".$_GET["jamisi"]."','".$_GET["jamselesai"]."','".$_GET["nilai"]."','".$_GET["shift"]."','".$_GET["hm"]."','".$_GET["km"]."','".$hm_last."','".$km_last."','".$_GET["user"]."','2');");
		if($resp != 1){
			echo "0";
		}else{
			echo $resp;
		}
		break;
	case "equipment":
		$json = new Services_JSON();
		$query = mysqli_query($con,"SELECT master_equipment_id,new_eq_num FROM master_equipment");
		if($query && mysqli_num_rows($query) > 0){
			$data = array();
			while($row = mysqli_fetch_object($query)){
				$data[] = $row;
			}
			echo $json->encode($data);
		}else{echo $json->encode(NULL);}
		break;
	case "user":
		$json = new Services_JSON();
		$query = mysqli_query($con,"SELECT user_id,nama,username,passwd FROM user");
		if($query && mysqli_num_rows($query) > 0){
			$data = array();
			while($row = mysqli_fetch_object($query)){
				$data[] = $row;
			}
			echo $json->encode($data);
		}else{echo $json->encode(NULL);}
		break;
	case "operator":
		$json = new Services_JSON();
		$query = mysqli_query($con,"SELECT operator_id,nip,name FROM sync_operator");
		if($query && mysqli_num_rows($query) > 0){
			$data = array();
			while($row = mysqli_fetch_object($query)){
				$data[] = $row;
			}
			echo $json->encode($data);
		}else{echo $json->encode(NULL);}
		break;
	default :
		echo 'sync';
		break;
}

?>
