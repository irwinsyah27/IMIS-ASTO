<?php
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('Asia/Jakarta');
//include ("db.php");

$con = mysqli_connect("localhost","root","J@karta2016","phoenix_db");
//$con = mysqli_connect("localhost","edi","123","phoenix_db");
// Check connection
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
include 'JSON.php';

$latlon = $_GET["latitude"].",".$_GET["latitude"];
$latlon1 = $_GET["latlong"];
$lat = $_GET["latitude"];
$lon = $_GET["longitude"];
$datetime = $_GET["date"]." ".$_GET["time"];
$datetime1 = $_GET["datetime"];

$daten = date("Y-m-d H:i:s");
$tambah = mktime(0,0,0,date('m'),date('d') - 1,date('Y'));
$kemarin = date("Y-m-d", $tambah);
$sekarang = date("Y-m-d");

switch ($_GET["opt"]){
	case "ct":
		ALL_updatecycle($con,'9452bc8f6bdda16f','LD0130','KR15037','0.00000','0.0000','2017-01-03','2017-01-02','2017-01-03 04:37:00');
		break;
	case "idle":
		echo hitung_total_idle1($con,'NBID-EDI','KB12151','1e0cda3af4d98e04','2016-12-22','2016-12-21');
		break;
	case "fpi":
		getFPI($con,$_GET['unit'],$_GET['nip'],0);
		break;
	case "savestopstart":
			$json = new Services_JSON();
			$objord = $json->decode($_POST["ord"]);
			$whr = "";
			$nr = 0;
			foreach ($objord->startstop as $row)
			{
				$query = mysqli_query($con,"SELECT cycle_time_id FROM log_cycle_time WHERE unit='".$row->unit."' AND nip='".$row->nip."' order by cycle_time_id desc limit 1");
				list($cycle_time_id) = mysqli_fetch_array($query);
				$query = mysqli_query($con,"SELECT ct_id FROM log_login_jam_dunia WHERE unit='".$row->unit."' AND nip='".$row->nip."' AND date_stop = '".$row->datetime_start."'");
				if($query && mysqli_num_rows($query) > 0){
					$resp = 1;
				}else{
					$resp = mysqli_query($con,"INSERT INTO log_login_jam_dunia (ct_id,unit, nip, date_stop, date_start) VALUES ('".$cycle_time_id."','".$row->unit."', '".$row->nip."', '".$row->datetime_start."', '".$row->datetime_stop."');");
				}
				
				if(($nr == 0)&&($resp == 1)){
					$whr .= $row->id;
				}elseif(($nr > 0)&&($resp == 1)){
					$whr .= ", ".$row->id;
				}
				$nr++;
			}
			echo $whr;
		break;
	case "speedSave":
			$json = new Services_JSON();
			$objord = $json->decode($_POST["ord"]);
			overspeedoffline($con,$objord);
		break;
	case "gpssave":
			getsaveGPSOffline($con,$_POST["ord"]);
		break;
	case "saveunit":
		$query = mysqli_query($con,"SELECT device_id FROM sync_unit WHERE device_id='".$_GET["device_id"]."'");
		if($query && mysqli_num_rows($query) > 0){
			$resp =  mysqli_query($con,"UPDATE sync_unit SET unit='".$_GET["unit"]."',date_last_update='".$daten."' WHERE device_id='".$_GET["device_id"]."'");
		}else{
			$resp =  mysqli_query($con,"INSERT INTO sync_unit(`device_id`,`unit`,`active`) VALUES ('".$_GET["device_id"]."','".$_GET["unit"]."','1');");
		}
		if($resp != 1){
			echo "0";
		}else{
			echo $resp;
		}
		break;
	case "loginstop":
		//$itemn = explode(" ", $_GET["datetime_start"]);
		//$itemn = explode(" ", $_GET["datetime_stop"]);
		if(isset($_GET["datetime_stop"])){
			$itemn = explode(" ", $_GET["datetime_stop"]);
			$tgl = $itemn[0];
			$time = $itemn[1];
		}else{
			$tgl = date('Y-m-d');
		}
		$sekarang = $tgl;
		$dt = strtotime($tgl);
		$kemarin = date("Y-m-d", $dt - 86400);
		
		$datetimeend = $tgl." ".$_GET["time_stop_position_station"];
		$datetimeend = $_GET["datetime_stop"];
		
		$query = mysqli_query($con,"SELECT daily_absent_id FROM daily_absent WHERE nip='".$_GET["nip"]."' AND unit='".$_GET["unit"]."' AND device_id='".$_GET["device_id"]."' AND DATE(`date`) = '".$sekarang."'");
		if($query && mysqli_num_rows($query) > 0){
			$resp =  mysqli_query($con,"UPDATE daily_absent SET `date_out`='".$sekarang."',time_stop_position_station='".$_GET["time_stop_position_station"]."',latitude_end='".$_GET["latitude_end"]."',longitude_end='".$_GET["longitude_end"]."',hm_akhir='".$_GET["hm_akhir"]."',time_out='".$_GET["time_stop_position_station"]."' WHERE nip='".$_GET["nip"]."' AND unit='".$_GET["unit"]."' AND device_id='".$_GET["device_id"]."' AND DATE(`date`) = '".$sekarang."'");
			
			$query = mysqli_query($con,"SELECT cycle_time_id FROM log_cycle_time WHERE device_id='".$_GET["device_id"]."' AND nip='".$_GET["nip"]."' AND DATE(`datetime_start`) = '".$sekarang."' order by cycle_time_id desc limit 1");
			list($cycle_time_id)=mysqli_fetch_array($query);
			mysqli_query($con,"UPDATE log_cycle_time SET latitude_end='".$_GET["latitude_end"]."',longitude_end='".$_GET["longitude_end"]."',datetime_end='".$datetimeend."' WHERE cycle_time_id='".$cycle_time_id."'");
			ALL_updatecycle($con,$_GET["device_id"],$_GET["unit"],$_GET["nip"],$_GET["latitude_end"],$_GET["longitude_end"],$sekarang,$kemarin,$datetimeend);
		}
		
		$query = mysqli_query($con,"SELECT daily_absent_id FROM daily_absent WHERE nip='".$_GET["nip"]."' AND unit='".$_GET["unit"]."' AND device_id='".$_GET["device_id"]."' AND DATE(`date`) = '".$kemarin."'");
		if($query && mysqli_num_rows($query) > 0){
			$resp =  mysqli_query($con,"UPDATE daily_absent SET `date_out`='".$sekarang."',time_stop_position_station='".$_GET["time_stop_position_station"]."',latitude_end='".$_GET["latitude_end"]."',longitude_end='".$_GET["longitude_end"]."',hm_akhir='".$_GET["hm_akhir"]."',time_out='".$_GET["time_stop_position_station"]."' WHERE nip='".$_GET["nip"]."' AND unit='".$_GET["unit"]."' AND device_id='".$_GET["device_id"]."' AND DATE(`date`) = '".$kemarin."'");
			
			$query = mysqli_query($con,"SELECT cycle_time_id FROM log_cycle_time WHERE unit='".$_GET["unit"]."' AND nip='".$_GET["nip"]."' AND device_id='".$_GET["device_id"]."' AND DATE(`datetime_start`) = '".$kemarin."' order by cycle_time_id desc limit 1");
			list($cycle_time_id)=mysqli_fetch_array($query);
			mysqli_query($con,"UPDATE log_cycle_time SET latitude_end='".$_GET["latitude_end"]."',longitude_end='".$_GET["longitude_end"]."',datetime_end='".$datetimeend."' WHERE cycle_time_id='".$cycle_time_id."'");
			ALL_updatecycle($con,$_GET["device_id"],$_GET["unit"],$_GET["nip"],$_GET["latitude_end"],$_GET["longitude_end"],$sekarang,$kemarin,$datetimeend);
		}
		
		//hitung_total_idle($con,$_GET["unit"], $_GET["nip"],$sekarang);
		//logout_jam_dunia($con,$_GET["unit"], $_GET["nip"], $datetimeend , $_GET["hm_akhir"]);

		if($resp != 1){
			echo "0";
		}else{
			echo $resp;
		}
		break;
	case "loginstart":
		$itemn = explode(" ", $_GET["datetime_start"]);
		$tgl = $itemn[0];
		$dt = strtotime($tgl);
		$kemarin = date("Y-m-d", $dt - 86400);
		//menentukan shift
		//$time = date('H:i:s');
		//$time = $itemn[1];
		if(($_GET["time_start_position_station"] > '14:00:00')&&($_GET["time_start_position_station"] < '23:00:00')){
			$shift = 2;
		}else{
			$shift = 1;
		}
		$query = mysqli_query($con,"SELECT daily_absent_id FROM daily_absent WHERE nip='".$_GET["nip"]."' AND unit='".$_GET["unit"]."' AND device_id='".$_GET["device_id"]."' AND DATE(`date`) = '".$tgl."'");
		if($query && mysqli_num_rows($query) > 0){
			//$resp =  mysqli_query($con,"UPDATE daily_absent SET time_start_position_station='".$_GET["time_start_position_station"]."',latitude_start='".$_GET["latitude_start"]."',longitude_start='".$_GET["longitude_start"]."',hm_awal='".$_GET["hm_awal"]."' WHERE nip='".$_GET["nip"]."' AND unit='".$_GET["unit"]."' AND device_id='".$_GET["device_id"]."' AND DATE(`date`) = '".$tgl."'");
			//if($resp != 1){
			//	$resp =  mysqli_query($con,"UPDATE daily_absent SET time_start_position_station='".$_GET["time_start_position_station"]."',latitude_start='".$_GET["latitude_start"]."',longitude_start='".$_GET["longitude_start"]."',hm_awal='".$_GET["hm_awal"]."' WHERE nip='".$_GET["nip"]."' AND unit='".$_GET["unit"]."' AND device_id='".$_GET["device_id"]."' AND DATE(`date`) = '".$kemarin."'");
			//}
			//$query1 = mysqli_query($con,"SELECT cycle_time_id FROM log_cycle_time WHERE device_id='".$_GET["device_id"]."' AND nip='".$_GET["nip"]."' AND unit='".$_GET["unit"]."' AND DATE(`datetime_start`) = '".$tgl."'");
			//if($query1 && mysqli_num_rows($query1) > 0){}else{
			//	$resp =  mysqli_query($con,"INSERT INTO log_cycle_time(device_id,nip,unit,latitude_start,`longitude_start`,`datetime_start`) VALUES ('".$_GET["device_id"]."','".$_GET["nip"]."','".$_GET["unit"]."','".$_GET["latitude_start"]."','".$_GET["longitude_start"]."','".$_GET["datetime_start"]."')");
			//}		
		}else{
			$query2 = mysqli_query($con,"SELECT cycle_time_id FROM log_cycle_time WHERE device_id='".$_GET["device_id"]."' AND nip='".$_GET["nip"]."' AND unit='".$_GET["unit"]."' AND DATE(`datetime_start`) = '".$tgl."'");
			if($query2 && mysqli_num_rows($query2) > 0){}else{
				mysqli_query($con,"INSERT INTO log_cycle_time(`device_id`,`nip`,`unit`,`latitude_start`,`longitude_start`,`datetime_start`) VALUES ('".$_GET["device_id"]."','".$_GET["nip"]."','".$_GET["unit"]."','".$_GET["latitude_start"]."','".$_GET["longitude_start"]."','".$_GET["datetime_start"]."')");
			}
			$resp =  mysqli_query($con,"INSERT INTO daily_absent(nip,`date`,shift,`time_in`,time_start_position_station,latitude_start,longitude_start,unit,device_id,hm_awal) VALUES ('".$_GET["nip"]."','".$tgl."','".$shift."','".$_GET["time_start_position_station"]."','".$_GET["time_start_position_station"]."','".$_GET["latitude_start"]."','".$_GET["longitude_start"]."','".$_GET["unit"]."','".$_GET["device_id"]."','".$_GET["hm_awal"]."')");
			if($resp == 1){
				$query = mysqli_query($con,"SELECT unit_position_id,date_last_update FROM current_unit_position WHERE device_id='".$_GET["device_id"]."'");
				if($query && mysqli_num_rows($query) > 0){
					$row2 = mysqli_fetch_object($query);
					mysqli_query($con,"UPDATE current_unit_position SET nip='".$_GET["nip"]."',unit='".$_GET["unit"]."',latitude='".$_GET["latitude_start"]."',longitude='".$_GET["longitude_start"]."',altitude='0',accuracy='0',altitude_accuracy='0',heading='0',speed='0',date_last_update='".$_GET["datetime_start"]."' WHERE unit_position_id='".$row2->unit_position_id."'");
				}else{
					mysqli_query($con,"INSERT INTO current_unit_position(device_id,nip,unit,latitude,longitude,altitude,accuracy,altitude_accuracy,heading,speed,date_last_update) VALUES ('".$_GET["device_id"]."','".$_GET["nip"]."','".$_GET["unit"]."','".$_GET["latitude_start"]."','".$_GET["longitude_start"]."','0','0','0','0','".$speed."','".$_GET["datetime_start"]."')");
				}
			}
		}
  
  		//$tmp_datetime_dwi = date('Y-m-d')." ".$_GET["time_start_position_station"];
		//login_jam_dunia($con,$_GET["unit"], $_GET["nip"], $tmp_datetime_dwi, $_GET["hm_awal"]);

		if($resp != 1){
			echo "0";
		}else{
			echo $resp;
		}
		break;
	case "savebreak":
		$resp =  mysqli_query($con,"INSERT INTO rpt_unit_breakdown(device_id,nip,description,date_insert) VALUES ('".$_GET["device_id"]."','".$_GET["nip"]."','".$_GET["description"]."','".$_GET["date_insert"]."')");
		if($resp != 1){
			echo "0";
		}else{
			echo $resp;
		}
		break;
	case "version":
		$json = new Services_JSON();
		$query = mysqli_query($con,"SELECT version_key, date_last_update FROM sync_version");
		if($query && mysqli_num_rows($query) > 0){
			$data = array();
			while($row = mysqli_fetch_object($query)){
				$data[] = $row;
			}
			echo $json->encode($data);
		}else{echo $json->encode(NULL);}
		break;
	case "admin":
		$json = new Services_JSON();
		$query = mysqli_query($con,"SELECT imis_username, imis_password FROM sync_admin");
		if($query && mysqli_num_rows($query) > 0){
			$data = array();
			while($row = mysqli_fetch_object($query)){
				$data[] = $row;
			}
			echo $json->encode($data);
		}else{echo $json->encode(NULL);}
		break;
	case "setting":
		$json = new Services_JSON();
		$query = mysqli_query($con,"SELECT setting_tracker_id, server_url, max_speed, sending_interval FROM sync_setting_tracker");
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
	case "coverageout":
		$json = new Services_JSON();
		$query = mysqli_query($con,"SELECT latitude_x1, longitude_y1, latitude_x2, longitude_y2 FROM sync_coverage_out");
		if($query && mysqli_num_rows($query) > 0){
			$data = array();
			while($row = mysqli_fetch_object($query)){
				$data[] = $row;
			}
			echo $json->encode($data);
		}else{echo $json->encode(NULL);}
		break;
	case "accesspoint":
			$json = new Services_JSON();
			$query = mysqli_query($con,"SELECT wifi_id, wifi_name, imis_user, imis_passwd, latitude, longitude FROM sync_access_point");
			if($query && mysqli_num_rows($query) > 0){
				$data = array();
				while($row = mysqli_fetch_object($query)){
					$data[] = $row;
				}
				echo $json->encode($data);
			}else{echo $json->encode(NULL);}
		break;
	case "masterlosttime":
			$json = new Services_JSON();
			$query = mysqli_query($con,"SELECT master_losttime_id, kode, keterangan FROM master_losttime");
			if($query && mysqli_num_rows($query) > 0){
				$data = array();
				while($row = mysqli_fetch_object($query)){
					$data[] = $row;
				}
				echo $json->encode($data);
			}else{echo $json->encode(NULL);}
		break;
	case "servercek":
			echo '1';
		break;
	default :
		echo 'sync';
		break;
}
function overspeedoffline($con,$ord = null){
	$query = mysqli_query($con,"SELECT max_speed FROM sync_setting_tracker");
	if($query && mysqli_num_rows($query) > 0){
		$row = mysqli_fetch_object($query);
		$speed = $row->max_speed;
	}else{
		$speed = 57;
	}
	$whr = "";
	$nr = 0;
	foreach ($ord->speedF as $row)
	{
		$itemn = explode(" ", $row->date_insert);
		$tgl = $itemn[0];
		$dt = strtotime($tgl);
		$kemarin = date("Y-m-d", $dt - 86400);
		if($row->speed >= $speed){
			$result = mysqli_query($con,"SELECT count(*) AS jlh FROM log_over_speed WHERE unit='".$row->unit."' AND nip='".$row->nip."' AND time_stamp = '".$row->date_insert."'");
			$rowsp = mysqli_fetch_object($result);
			$jlh = $rowsp->jlh;
			if($jlh == 0){
				$resp = mysqli_query($con,"INSERT INTO log_over_speed(device_id,nip,unit,time_stamp,speed,latitude,longitude,altitude,accuracy,altitude_accuracy,heading) VALUES ('".$row->device_id."','".$row->nip."','".$row->unit."','".$row->date_insert."','".$row->speed."','".$row->latitude."','".$row->longitude."','".$row->altitude."','".$row->accuracy."','".$row->altitude_accuracy."','".$row->heading."')");
			}else{
				$resp = 1;
			}
		}else{
			$resp = 1;
		}
		$query = mysqli_query($con,"SELECT daily_absent_id,unit FROM daily_absent WHERE nip='".$row->nip."' AND device_id='".$row->device_id."' AND DATE(`date`) = '".$tgl."'");
		if($query && mysqli_num_rows($query) > 0){
			$row11 = mysqli_fetch_object($query);
			
			// jumlah speed now
			$result = mysqli_query($con,"SELECT count(*) AS jlhspeed1 FROM log_over_speed WHERE speed >= ".$speed." AND unit='".$row11->unit."' AND nip='".$row->nip."' AND device_id='".$row->device_id."' AND DATE(`date_insert`) = '".$tgl."'");
			$row1 = mysqli_fetch_object($result);
			$overspeed1 = $row1->jlhspeed1;
			$overspeed2 = 0;
			$query = mysqli_query($con,"SELECT daily_absent_id,unit FROM daily_absent WHERE nip='".$row->nip."' AND device_id='".$row->device_id."' AND DATE(`date`) = '".$kemarin."'");
			if($query && mysqli_num_rows($query) > 0){
				// cjumlah speed sebelumnya
				$result = mysqli_query($con,"SELECT count(*) AS jlhspeed2 FROM log_over_speed WHERE speed >= ".$speed." AND unit='".$row11->unit."' AND nip='".$row->nip."' AND device_id='".$row->device_id."' AND DATE(`date_insert`) = '".$kemarin."'");
				$row2 = mysqli_fetch_object($result);
				$overspeed2 = $row2->jlhspeed1;
			}
			$overspeed = $overspeed1 + $overspeed2;
			mysqli_query($con,"UPDATE daily_absent SET total_over_speed=".$overspeed." WHERE unit='".$row11->unit."' AND nip='".$row->nip."' AND device_id='".$row->device_id."' AND DATE(`date`) = '".$tgl."'");
	
			
		}else{
			$query = mysqli_query($con,"SELECT daily_absent_id,unit FROM daily_absent WHERE nip='".$row->nip."' AND device_id='".$row->device_id."' AND DATE(`date`) = '".$kemarin."'");
			if($query && mysqli_num_rows($query) > 0){
				$row11 = mysqli_fetch_object($query);
					
				// jumlah speed now
				$result = mysqli_query($con,"SELECT count(*) AS jlhspeed1 FROM log_over_speed WHERE speed >= ".$speed." AND unit='".$row11->unit."' AND nip='".$row->nip."' AND device_id='".$row->device_id."' AND DATE(`date_insert`) = '".$tgl."'");
				$row1 = mysqli_fetch_object($result);
				$overspeed1 = $row1->jlhspeed1;
				$overspeed2 = 0;
				$query = mysqli_query($con,"SELECT daily_absent_id,unit FROM daily_absent WHERE nip='".$row->nip."' AND device_id='".$row->device_id."' AND DATE(`date`) = '".$kemarin."'");
				if($query && mysqli_num_rows($query) > 0){
					// cjumlah speed sebelumnya
					$result = mysqli_query($con,"SELECT count(*) AS jlhspeed2 FROM log_over_speed WHERE speed >= ".$speed." AND unit='".$row11->unit."' AND nip='".$row->nip."' AND device_id='".$row->device_id."' AND DATE(`date_insert`) = '".$kemarin."'");
					$row2 = mysqli_fetch_object($result);
					$overspeed2 = $row2->jlhspeed1;
				}
				$overspeed = $overspeed1 + $overspeed2;
				mysqli_query($con,"UPDATE daily_absent SET total_over_speed=".$overspeed." WHERE unit='".$row11->unit."' AND nip='".$row->nip."' AND device_id='".$row->device_id."' AND DATE(`date`) = '".$tgl."'");
			
				
			}
		}
		if(($nr == 0)&&($resp == 1)){
			$whr .= $row->over_speed_id;
		}elseif(($nr > 0)&&($resp == 1)){
			$whr .= ", ".$row->over_speed_id;
		}
		$nr++;
	}
	echo $whr;
}
function getsaveGPSOffline($con,$ord = null){
	$json = new Services_JSON();
	$objord = $json->decode($ord);
	$whr = "";
	$nr = 0;
	$nr1 = 0;
	foreach ($objord->gpsx as $row)
	{
		$itemn = explode(" ", $row->date_insert);
		$tgl = $itemn[0];
		$dt = strtotime($tgl);
		$kemarin = date('Y-m-d', $dt - 86400);
		
		$query = mysqli_query($con,"SELECT daily_absent_id,unit FROM daily_absent WHERE nip='".$row->nip."' AND device_id='".$row->device_id."' AND DATE(`date`) = '".$tgl."'");
		if($query && mysqli_num_rows($query) > 0){
			$row1 = mysqli_fetch_object($query);
				
			$result = mysqli_query($con,"SELECT count(*) AS jlh FROM log_geolocation WHERE unit='".$row1->unit."' AND nip='".$row->nip."' AND time_stamp_gps = '".$row->time_stamp_gps."'");
			$rowgeo = mysqli_fetch_object($result);
			$jlh = $rowgeo->jlh;
			if($jlh == 0){
				$resp =  mysqli_query($con,"INSERT INTO log_geolocation(device_id,nip,unit,time_stamp,time_stamp_gps,latitude,longitude,altitude,accuracy,altitude_accuracy,heading,speed)
								VALUES ('".$row->device_id."','".$row->nip."','".$row1->unit."','".$row->date_insert."','".$row->time_stamp_gps."','".$row->latitude."',
										'".$row->longitude."','".$row->altitude."','".$row->accuracy."','".$row->altitudeaccuracy."','".$row->heading."','".$row->speed."')");
			}else{
				$resp = 1;
			}
			$query = mysqli_query($con,"SELECT unit_position_id,date_last_update FROM current_unit_position WHERE device_id='".$row->device_id."'");
			if($query && mysqli_num_rows($query) > 0){
				$row2 = mysqli_fetch_object($query);
				if(strtotime($row->date_insert) > strtotime($row2->date_last_update)){
					mysqli_query($con,"UPDATE current_unit_position SET nip='".$row->nip."',unit='".$row1->unit."',latitude='".$row->latitude."',longitude='".$row->longitude."',altitude='".$row->altitude."',accuracy='".$row->accuracy."',altitude_accuracy='".$row->altitude_accuracy."',heading='".$row->heading."',speed='".$row->speed."',date_last_update='".$row->date_insert."' WHERE unit_position_id='".$row2->unit_position_id."'");
				}
			}else{
				mysqli_query($con,"INSERT INTO current_unit_position(device_id,nip,unit,latitude,longitude,altitude,accuracy,altitude_accuracy,heading,speed,date_last_update) VALUES ('".$row->device_id."','".$row->nip."','".$row1->unit."','".$row->latitude."','".$row->longitude."','".$row->altitude."','".$row->accuracy."','".$row->altitude_accuracy."','".$row->heading."','".$row->speed."','".$row->date_insert."')");
			}
			//cek updatecycle
			Load_updatecycle($con, $row->device_id,$row->nip,$row1->unit,$row->latitude,$row->longitude,$row->speed,$row->altitude,$row->accuracy,$row->heading,$row->date_insert,$tgl,$kemarin,$daten);
			//cek coverage
			Load_savecoveragein($con, $row->device_id,$row->nip,$row1->unit,$row->latitude,$row->longitude,$row->date_insert,$tgl,$kemarin);
			// update total cycle
			ALL_updatecycle($con, $row->device_id,$row1->unit,$row->nip,$row->latitude,$row->longitude,$tgl,$kemarin,$row->date_insert);
				
			if(($nr == 0)&&($resp == 1)){
				$whr .= $row->geolocation_id;
			}elseif(($nr > 0)&&($resp == 1)){
				$whr .= ", ".$row->geolocation_id;
			}
		}else{
			$query = mysqli_query($con,"SELECT daily_absent_id,unit FROM daily_absent WHERE nip='".$row->nip."' AND device_id='".$row->device_id."' AND DATE(`date`) = '".$kemarin."'");
			if($query && mysqli_num_rows($query) > 0){
				$row1 = mysqli_fetch_object($query);
				$result = mysqli_query($con,"SELECT count(*) AS jlh FROM log_geolocation WHERE unit='".$row1->unit."' AND nip='".$row->nip."' AND time_stamp_gps = '".$row->time_stamp_gps."'");
				$rowgeo = mysqli_fetch_object($result);
				$jlh = $rowgeo->jlh;
				if($jlh == 0){
					$resp =  mysqli_query($con,"INSERT INTO log_geolocation(device_id,nip,unit,time_stamp,time_stamp_gps,latitude,longitude,altitude,accuracy,altitude_accuracy,heading,speed)
									VALUES ('".$row->device_id."','".$row->nip."','".$row1->unit."','".$row->date_insert."','".$row->time_stamp_gps."','".$row->latitude."',
											'".$row->longitude."','".$row->altitude."','".$row->accuracy."','".$row->altitudeaccuracy."','".$row->heading."','".$row->speed."')");
				}else{
					$resp = 1;
				}
				$query = mysqli_query($con,"SELECT unit_position_id,date_last_update FROM current_unit_position WHERE device_id='".$row->device_id."'");
				if($query && mysqli_num_rows($query) > 0){
					$row2 = mysqli_fetch_object($query);
					if(strtotime($row->date_insert) > strtotime($row2->date_last_update)){
						mysqli_query($con,"UPDATE current_unit_position SET nip='".$row->nip."',unit='".$row1->unit."',latitude='".$row->latitude."',longitude='".$row->longitude."',altitude='".$row->altitude."',accuracy='".$row->accuracy."',altitude_accuracy='".$row->altitude_accuracy."',heading='".$row->heading."',speed='".$row->speed."',date_last_update='".$row->date_insert."' WHERE unit_position_id='".$row2->unit_position_id."'");
					}
				}else{
					mysqli_query($con,"INSERT INTO current_unit_position(device_id,nip,unit,latitude,longitude,altitude,accuracy,altitude_accuracy,heading,speed,date_last_update) VALUES ('".$row->device_id."','".$row->nip."','".$row1->unit."','".$row->latitude."','".$row->longitude."','".$row->altitude."','".$row->accuracy."','".$row->altitude_accuracy."','".$row->heading."','".$row->speed."','".$row->date_insert."')");
				}
				//cek updatecycle
				Load_updatecycle($con, $row->device_id,$row->nip,$row1->unit,$row->latitude,$row->longitude,$row->speed,$row->altitude,$row->accuracy,$row->heading,$row->date_insert,$tgl,$kemarin,$daten);
				//cek coverage
				Load_savecoveragein($con, $row->device_id,$row->nip,$row1->unit,$row->latitude,$row->longitude,$row->date_insert,$tgl,$kemarin);
				// update total cycle
				ALL_updatecycle($con, $row->device_id,$row1->unit,$row->nip,$row->latitude,$row->longitude,$tgl,$kemarin,$row->date_insert);
					
				if(($nr == 0)&&($resp == 1)){
					$whr .= $row->geolocation_id;
				}elseif(($nr > 0)&&($resp == 1)){
					$whr .= ", ".$row->geolocation_id;
				}
				
			}else{
				if($nr == 0){
					$whr .= $row->geolocation_id;
				}elseif($nr > 0){
					$whr .= ", ".$row->geolocation_id;
				}
			}
		}
		$nr++;
	}
	echo $whr;
}
function Load_updatecycle($con, $device_id,$nip,$unit,$lat,$lon,$speed,$altitude,$accuracy,$heading,$datetime,$sekarang,$kemarin,$daten){
	
	$query = mysqli_query($con,"SELECT station_id,latitude,longitude FROM sync_station WHERE station_id IN (1,2)");
	while($row = mysqli_fetch_object($query)){
		$station[$row->station_id]['lat'] = $row->latitude;
		$station[$row->station_id]['lon'] = $row->longitude;
		$jarak = distance($lat, $lon,  $row->latitude, $row->longitude, 'K');
		$jarak = floor($jarak * 1000);
		$station[$row->station_id]['jarak'] = $jarak;
	}
	
	// cek date now
	$query = mysqli_query($con,"SELECT cycle_time_id,latitude_start,longitude_start,time_stasiun_cpp,time_stasiun_port FROM log_cycle_time WHERE device_id='".$device_id."' AND nip='".$nip."' AND DATE(`datetime_start`) = '".$sekarang."' order by cycle_time_id desc limit 1");
	while(list($cycle_time_id,$latitude_start,$longitude_start,$time_stasiun_cpp,$time_stasiun_port)=mysqli_fetch_array($query)){
		$jarakstr = distance($lat, $lon, $latitude_start, $longitude_start, 'K');
		$jarakstr = floor($jarakstr * 1000);
		if(($jarakstr <= 300)&&($time_stasiun_cpp != NULL)&&($time_stasiun_port != NULL)){
			$resp =  mysqli_query($con,"UPDATE log_cycle_time SET datetime_end='".$datetime."',latitude_end='".$lat."',longitude_end='".$lon."' WHERE cycle_time_id='".$cycle_time_id."'");
			if(($time_stasiun_cpp != NULL)&&($time_stasiun_port != NULL)){
				mysqli_query($con,"INSERT INTO log_cycle_time(device_id,nip,unit,latitude_start,`longitude_start`,`datetime_start`) VALUES ('".$device_id."','".$nip."','".$unit."','".$lat."','".$lon."','".$datetime."')");
			}
		}elseif($station[1]['jarak'] <= 300){
			mysqli_query($con,"UPDATE log_cycle_time SET time_stasiun_cpp='".$datetime."' WHERE cycle_time_id='".$cycle_time_id."'");
		}elseif($station[2]['jarak'] <= 300){
			mysqli_query($con,"UPDATE log_cycle_time SET time_stasiun_port='".$datetime."' WHERE cycle_time_id='".$cycle_time_id."'");
		}
		
	}
	// cek date kemarin
	$query = mysqli_query($con,"SELECT cycle_time_id,latitude_start,longitude_start FROM log_cycle_time WHERE device_id='".$device_id."' AND nip='".$nip."' AND DATE(`datetime_start`) = '".$kemarin."' order by cycle_time_id desc limit 1");
	while(list($cycle_time_id,$latitude_start,$longitude_start)=mysqli_fetch_array($query)){
		$jarakstr = distance($lat, $lon, $latitude_start, $longitude_start, 'K');
		$jarakstr = floor($jarakstr * 1000);
		if(($jarakstr <= 300)&&($time_stasiun_cpp != NULL)&&($time_stasiun_port != NULL)){
			mysqli_query($con,"UPDATE log_cycle_time SET datetime_end='".$datetime."',latitude_end='".$lat."',longitude_end='".$lon."' WHERE cycle_time_id='".$cycle_time_id."'");
			if(($time_stasiun_cpp != NULL)&&($time_stasiun_port != NULL)){
				mysqli_query($con,"INSERT INTO log_cycle_time(device_id,nip,unit,latitude_start,`longitude_start`,`datetime_start`) VALUES ('".$device_id."','".$nip."','".$unit."','".$lat."','".$lon."','".$datetime."')");
			}
		}elseif($station[1]['jarak'] <= 300){
			mysqli_query($con,"UPDATE log_cycle_time SET time_stasiun_cpp='".$datetime."' WHERE cycle_time_id='".$cycle_time_id."'");
		}elseif($station[2]['jarak'] <= 300){
			mysqli_query($con,"UPDATE log_cycle_time SET time_stasiun_port='".$datetime."' WHERE cycle_time_id='".$cycle_time_id."'");
		}
	}
}
function All_updatecycle($con, $device_id,$unit,$nip,$lat,$lon,$sekarang,$kemarin,$daten){
	$query = mysqli_query($con,"SELECT station_id,latitude,longitude FROM sync_station WHERE station_id IN (1,2)");
	while(list($station_id,$latitude,$longitude)=mysqli_fetch_array($query)){
		$station[$station_id]['lat'] = $latitude;
		$station[$station_id]['lon'] = $longitude;
	}
	// menentukan sycle all menggunakan geo
	$cycletimeAll = 0;
	$cycletime = 0;
	$cycletime1 = 0;
	$cycletime2 = 0;
	$cycletime3 = 0;
	$cycletime4 = 0;
	$jarak = 0;

	$query = mysqli_query($con,"SELECT cycle_time_id,latitude_start,longitude_start,latitude_end,longitude_end,time_stasiun_cpp,time_stasiun_port FROM log_cycle_time WHERE device_id='".$device_id."' AND nip='".$nip."' AND DATE(`datetime_start`) = '".$sekarang."'");
	while(list($cycle_time_id,$latitude_start,$longitude_start,$latitude_end,$longitude_end,$time_stasiun_cpp,$time_stasiun_port)=mysqli_fetch_array($query)){
		if($latitude_end == '0.000000'){
			$latitude_end = $latitude_start;
		}
		if($longitude_end == '0.000000'){
			$longitude_end = $longitude_start;
		}
		$jarakin = distance($latitude_start, $longitude_start, $latitude_end, $longitude_end, 'K');
		$jarakin = floor($jarakin * 1000);
		if(($jarakin <= 500)&&($time_stasiun_port != NULL)&&($time_stasiun_cpp != NULL)){
			$cycletime++;
		}else{
			if(($time_stasiun_port != NULL)&&($time_stasiun_cpp != NULL)){
				$jarak = distance($station[1]['lat'], $station[1]['lon'], $station[2]['lat'], $station[2]['lon'], 'K');
				$jarak += distance($latitude_start, $longitude_start, $station[1]['lat'], $station[1]['lon'], 'K');
				//$jarak += distance($latitude_end, $longitude_end, $station[2]['lat'], $station[2]['lon'], 'K');
				$jarak += distance($lat, $lon, $station[2]['lat'], $station[2]['lon'], 'K');
			}
			if(($time_stasiun_port == NULL)&&($time_stasiun_cpp != NULL)){
				$jarak = distance($latitude_start, $longitude_start, $station[2]['lat'], $station[2]['lon'], 'K');
				//$jarak += distance($latitude_end, $longitude_end, $station[2]['lat'], $station[2]['lon'], 'K');
				//$jarak += distance($lat, $lon, $station[2]['lat'], $station[2]['lon'], 'K');
				if($latitude_end == NULL){
					$jarak += distance($lat, $lon, $station[2]['lat'], $station[2]['lon'], 'K');
				}else{
					$jarak += distance($lat, $lon, $station[1]['lat'], $station[1]['lon'], 'K');
				}
				
			}
			if(($time_stasiun_port != NULL)&&($time_stasiun_cpp == NULL)){
				$jarak = distance($latitude_start, $longitude_start, $station[1]['lat'], $station[1]['lon'], 'K');
				//$jarak += distance($latitude_end, $longitude_end, $station[1]['lat'], $station[1]['lon'], 'K');
				//$jarak += distance($lat, $lon, $station[1]['lat'], $station[1]['lon'], 'K');
				if($latitude_end == NULL){
					$jarak += distance($lat, $lon, $station[1]['lat'], $station[1]['lon'], 'K');
				}else{
					$jarak += distance($lat, $lon, $station[2]['lat'], $station[2]['lon'], 'K');
				}
			}
			if(($time_stasiun_port == NULL)&&($time_stasiun_cpp == NULL)){
				//$jarak = distance($latitude_start, $longitude_start, $latitude_end, $longitude_end, 'K');
				$jarak = distance($latitude_start, $longitude_start, $lat, $lon, 'K');
			}
			$cycletime1 = 2 * distance($station[2]['lat'], $station[2]['lon'], $station[1]['lat'], $station[1]['lon'], 'K');
			//$cycletime1 += distance($station[2]['lat'], $station[2]['lon'], $station[1]['lat'], $station[1]['lon'], 'K');
			$cycletime3 = number_format($jarak /$cycletime1, 2);
		}
	}

	// kemarin
	$query = mysqli_query($con,"SELECT cycle_time_id,latitude_start,longitude_start,latitude_end,longitude_end,time_stasiun_cpp,time_stasiun_port FROM log_cycle_time WHERE device_id='".$device_id."'AND nip='".$nip."' AND DATE(`datetime_start`) = '".$kemarin."'");
	while(list($cycle_time_id,$latitude_start,$longitude_start,$latitude_end,$longitude_end,$time_stasiun_cpp,$time_stasiun_port)=mysqli_fetch_array($query)){
		if($latitude_end == '0.000000'){
			$latitude_end = $latitude_start;
		}
		if($longitude_end == '0.000000'){
			$longitude_end = $longitude_start;
		}
		$jarakin = distance($latitude_start, $longitude_start, $latitude_end, $longitude_end, 'K');
		$jarakin = floor($jarakin * 1000);
		//echo $jarakin." = ".$time_stasiun_port." = ".$time_stasiun_cpp."<br>";
		if(($jarakin <= 500)&&($time_stasiun_port != NULL)&&($time_stasiun_cpp != NULL)){
			$cycletime2++;
		}else{
			if(($time_stasiun_port != NULL)&&($time_stasiun_cpp != NULL)){
				$jarak = distance($station[1]['lat'], $station[1]['lon'], $station[2]['lat'], $station[2]['lon'], 'K');
				$jarak += distance($latitude_start, $longitude_start, $station[1]['lat'], $station[1]['lon'], 'K');
				//$jarak += distance($latitude_end, $longitude_end, $station[2]['lat'], $station[2]['lon'], 'K');
				$jarak += distance($lat, $lon, $station[2]['lat'], $station[2]['lon'], 'K');
			}
			if(($time_stasiun_port == NULL)&&($time_stasiun_cpp != NULL)){
				$jarak = distance($latitude_start, $longitude_start, $station[2]['lat'], $station[2]['lon'], 'K');
				//$jarak += distance($latitude_end, $longitude_end, $station[2]['lat'], $station[2]['lon'], 'K');
				if($latitude_end == NULL){
					$jarak += distance($lat, $lon, $station[2]['lat'], $station[2]['lon'], 'K');
				}else{
					$jarak += distance($lat, $lon, $station[1]['lat'], $station[1]['lon'], 'K');
				}
			}
			if(($time_stasiun_port != NULL)&&($time_stasiun_cpp == NULL)){
				$jarak = distance($latitude_start, $longitude_start, $station[1]['lat'], $station[1]['lon'], 'K');
				//$jarak += distance($latitude_end, $longitude_end, $station[1]['lat'], $station[1]['lon'], 'K');
				if($latitude_end == NULL){
					$jarak += distance($lat, $lon, $station[1]['lat'], $station[1]['lon'], 'K');
				}else{
					$jarak += distance($lat, $lon, $station[2]['lat'], $station[2]['lon'], 'K');
				}
			}
			if(($time_stasiun_port == NULL)&&($time_stasiun_cpp == NULL)){
				//$jarak = distance($latitude_start, $longitude_start, $latitude_end, $longitude_end, 'K');
				$jarak = distance($latitude_start, $longitude_start, $lat, $lon, 'K');
			}
			$cycletime1 = 2 * distance($station[2]['lat'], $station[2]['lon'], $station[1]['lat'], $station[1]['lon'], 'K');
			//$cycletime1 += distance($station[2]['lat'], $station[2]['lon'], $station[1]['lat'], $station[1]['lon'], 'K');
			$cycletime4 = number_format($jarak /$cycletime1, 2);
		}
	}
	//echo $cycletime."+".$cycletime2."+".$cycletime3."+".$cycletime4;
	$cycletimeAll = $cycletime + $cycletime2 + $cycletime3 + $cycletime4;
	//mysqli_query($con,"INSERT INTO tb_tmp(`remark`) VALUES ('".$device_id." ".$nip." = ".$cycletime."+".$cycletime2."+".$cycletime3."+".$cycletime4."')");
	
	$resp =  mysqli_query($con,"UPDATE daily_absent SET total_cycle_time=".$cycletimeAll.",remark='".$cycletime."+".$cycletime2."+".$cycletime3."+".$cycletime4."' WHERE date_out IS NOT null AND device_id='".$device_id."' AND nip='".$nip."' AND DATE(`date`) = '".$sekarang."'");
	$resp =  mysqli_query($con,"UPDATE daily_absent SET total_cycle_time=".$cycletimeAll.",remark='".$cycletime."+".$cycletime2."+".$cycletime3."+".$cycletime4."' WHERE date_out IS NOT null AND device_id='".$device_id."' AND nip='".$nip."' AND DATE(`date`) = '".$kemarin."'");
	
	$totidle = hitung_total_idle($con,$unit,$nip,$device_id,$sekarang,$kemarin);
	getFPI($con,$unit,$nip,$totidle);
}
function Load_savecoveragein($con, $device_id,$nip,$unit,$lat,$lon,$datetime,$sekarang,$kemarin){
	/* CPP
	 * in	-0.989054,114.394134
	 * ou	-0.988954,114.397134
	 * 
	 * PORT
	 * in 	-1.215681,114.802092
	 * ou	-1.215160, 114.800792
	 */
	$item = explode(" ", $datetime);
	$date = $item[0];
	$time = $item[1];
	$query = mysqli_query($con,"SELECT station_id,latitude_x1,longitude_y1,latitude_x2,longitude_y2 FROM sync_coverage_in WHERE station_id IN (1,2)");
	while(list($station_id,$latitude_x1,$longitude_y1,$latitude_x2,$longitude_y2)=mysqli_fetch_array($query)){
		//if($station_id == 1){
			$jarakin = distance($lat, $lon, $latitude_x1, $longitude_y1, 'K');
			$jarakin = floor($jarakin * 1000);
			$jarakout = distance($lat, $lon, $latitude_x2, $longitude_y2, 'K');
			$jarakout = floor($jarakout * 1000);
			if($jarakin <= 300){
				$query = mysqli_query($con,"SELECT time_in,time_out FROM log_coverage_in WHERE station_id='".$station_id."' AND device_id='".$device_id."' AND nip='".$nip."' AND DATE(`date_in`) = '".$sekarang."' order by coverage_in_id desc limit 1");
				if($query && mysqli_num_rows($query) > 0){
					$row = mysqli_fetch_object($query);
					if(($jarakin <= 300)&&($row->time_in != NULL)&&($row->time_out != NULL)){
						$resp =  mysqli_query($con,"INSERT INTO log_coverage_in(lat,lon,device_id,station_id,nip,unit,date_in,`time_in`)
								VALUES ('".$lat."','".$lon."','".$device_id."','".$station_id."','".$nip."','".$unit."','".$date."','".$time."')");
					}elseif(($jarakin > 300)&&($row->time_in != NULL)&&($row->time_out == NULL)){
						$query1 = mysqli_query($con,"SELECT coverage_in_id FROM log_coverage_in WHERE time_out IS NULL AND station_id='".$station_id."' AND device_id='".$device_id."' AND nip='".$nip."' AND DATE(`date_in`) = '".$sekarang."' order by coverage_in_id desc limit 1");
						if($query1 && mysqli_num_rows($query1) > 0){
							$row = mysqli_fetch_object($query1);
							$resp = mysqli_query($con,"UPDATE log_coverage_in SET time_out='".$time."',lat='".$lat."',lon='".$lon."' WHERE coverage_in_id='".$row->coverage_in_id."'");
						}
						$query1 = mysqli_query($con,"SELECT coverage_in_id FROM log_coverage_in WHERE time_out IS NULL AND station_id='".$station_id."' AND device_id='".$device_id."' AND nip='".$nip."' AND DATE(`date_in`) = '".$kemarin."' order by coverage_in_id desc limit 1");
						if($query1 && mysqli_num_rows($query1) > 0){
							$row = mysqli_fetch_object($query1);
							$resp = mysqli_query($con,"UPDATE log_coverage_in SET time_out='".$time."',lat='".$lat."',lon='".$lon."' WHERE coverage_in_id='".$row->coverage_in_id."'");
						}
					}
				}else{
					$resp =  mysqli_query($con,"INSERT INTO log_coverage_in(lat,lon,device_id,station_id,nip,unit,date_in,`time_in`)
								VALUES ('".$lat."','".$lon."','".$device_id."','".$station_id."','".$nip."','".$unit."','".$date."','".$time."')");
				}
			}
			
		//}
	}
	//return $resp;
}
function distance($lat1, $lon1, $lat2, $lon2, $unit) {
	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);

	if ($unit == "K") {
		return ($miles * 1.609344);
	} else if ($unit == "N") {
		return ($miles * 0.8684);
	} else {
		return $miles;
	}
	/*
	echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
	echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
	echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
	*/
}
function getFPI($con,$unit,$nip,$totidle){
	if(isset($unit)){
		//$query = mysqli_query($con,"SELECT cycle_time_id,datetime_start,datetime_end , TIMEDIFF(datetime_end, datetime_start) as durasi FROM log_cycle_time WHERE TIMEDIFF(datetime_end, datetime_start) > 0 AND fpi IS NULL AND unit='".$unit."' AND nip='".$nip."'");
		$query = mysqli_query($con,"SELECT cycle_time_id,datetime_start,datetime_end , TIMEDIFF(datetime_end, datetime_start) as durasi FROM log_cycle_time WHERE TIMEDIFF(datetime_end, datetime_start) > 0 AND unit='".$unit."' AND nip='".$nip."'");
	}else{
		$query = mysqli_query($con,"SELECT cycle_time_id,datetime_start,datetime_end , TIMEDIFF(datetime_end, datetime_start) as durasi FROM log_cycle_time WHERE TIMEDIFF(datetime_end, datetime_start) > 0 AND fpi IS NULL");
	}
	$nr = 1;
	while(list($cycle_time_id,$datetime_start,$datetime_end,$durasi)=mysqli_fetch_array($query)){
		$secdurasi = Timetosecons($durasi) - $totidle;
		$timedurasi = secondsToTime($secdurasi);
		$item = explode(":", $timedurasi);
		$jam = floor($durasi);
		if($item[1] >= 10){$mnit = $item[1];}else{$mnit = ($item[1]/10);}
		$koma = floor(($mnit * 100) / 60);
		$koma = substr($koma, 0, 1);
		$nilai = $jam.".".$koma;
		//echo $nr." ".$cycle_time_id." = ".$nilai."   ";
		$query1 = mysqli_query($con,"SELECT factor FROM setting_fpi WHERE batas_hm_bawah <= ".$nilai." AND batas_hm_atas >".$nilai."");
		if($query && mysqli_num_rows($query1) > 0){
			$row = mysqli_fetch_object($query1);
			//echo "".$cycle_time_id."-------->".$nilai."=".$row->factor."<br>";
			mysqli_query($con,"UPDATE log_cycle_time SET fpi='".$row->factor."' WHERE cycle_time_id='".$cycle_time_id."'");
		}
		$nr++;
	}
}

function hitung_total_idle($con,$unit,$nip,$device_id,$sekarang,$kemarin){
	$query = mysqli_query($con,"SELECT daily_absent_id FROM daily_absent WHERE nip='".$nip."' AND device_id='".$device_id."' AND DATE(`date`) = '".$sekarang."'");
	$difsec = 0;
	if($query && mysqli_num_rows($query) > 0){
		$query = mysqli_query($con,"SELECT ct_id,SUM(TIME_TO_SEC(TIMEDIFF(date_start,date_stop))) as durasi FROM log_login_jam_dunia WHERE unit='".$unit."' AND nip='".$nip."' AND DATE(`date_stop`) = '".$sekarang."' GROUP BY ct_id");
		while(list($ct_id,$durasi)=mysqli_fetch_array($query)){
			$difsec += $durasi;
			mysqli_query($con,"UPDATE log_cycle_time SET total_idle='".$difsec."' WHERE cycle_time_id='".$ct_id."'");
		}
	}else{
		$query = mysqli_query($con,"SELECT ct_id,SUM(TIME_TO_SEC(TIMEDIFF(date_start,date_stop))) as durasi FROM log_login_jam_dunia WHERE unit='".$unit."' AND nip='".$nip."' AND DATE(`date_stop`) = '".$kemarin."' GROUP BY ct_id");
		while(list($ct_id,$durasi)=mysqli_fetch_array($query)){
			$difsec += $tgl2 - $tgl1;
			mysqli_query($con,"UPDATE log_cycle_time SET total_idle='".$difsec."' WHERE cycle_time_id='".$ct_id."'");
		}
	}
	return $difsec;	
}
function login_jam_dunia($con,$unit,$nip, $date, $hm){ 
	//mysqli_query($con,"INSERT INTO log_login_jam_dunia (unit, nip, date_login, hm_awal) VALUES ('".$unit."', '".$nip."', '".$date."', '".$hm."')"); 
}
function logout_jam_dunia($con,$unit,$nip, $date, $hm){ 
	//mysqli_query($con,"UPDATE log_login_jam_dunia SET date_logout='".$date."', hm_akhir = '".$hm."' WHERE unit='".$unit."' AND nip = '".$nip."' AND date_logout IS NULL"); 
}
function secondsToTime($inputSeconds) {
	$days = floor($inputSeconds / 86400);
	$hours = floor(($inputSeconds - $days * 86400) / 3600);
	$minutes = floor(($inputSeconds - $days * 86400 - $hours * 3600) / 60);
	$seconds = floor($inputSeconds - $days * 86400 - $hours * 3600 - $minutes * 60);

	$days = floor($inputSeconds / 3600);
	if($inputSeconds <= 0){
		$res = "00:00:00";
	}else{
		$res = $days.":".$minutes.":".$seconds;
	}
	return $res;
}
function Timetosecons($time){
	$timeArr = array_reverse(explode(":", $time));
	$seconds = 0;
	foreach ($timeArr as $key => $value)
	{
		if ($key > 2) break;
		$seconds += pow(60, $key) * $value;
	}
	return $seconds;
}
?>
