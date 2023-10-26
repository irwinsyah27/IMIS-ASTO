<?php
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('Asia/Jakarta');
//include ("db.php");
 
//echo "Ok";

include 'adodb/adodb.inc.php'; 
$driver = 'mysqli';
$db  	= adoNewConnection($driver); 
$db->connect('localhost','root','J@karta2016','phoenix_db');

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
	case "loginstart":
				$nip 					= $_GET["nip"];
				$unit 					= $_GET["unit"];
				$device_id 				= $_GET["device_id"];
				$status					= "login";				// login , logout , idle, start, overspeed, normal, geofence
				$datetime_tab			= $_GET["datetime_start"];
				$hm						= $_GET["hm_awal"]; 	

				$latitude 				= $_GET["latitude_start"];
				$longitude 				= $_GET["longitude_start"];
				$altitude 				= "";
				$accuracy 				= "";
				$altitude_accuracy 		= ""; 
				$heading 				= "";
				$speed 					= 0; 
				$time_stamp_gps 		= $_GET["datetime_start"];

				write_file("==============================================");
				write_file("masuk ke case loginstart");
				write_file("nip : ".$nip);
				write_file("unit : ".$unit);
				write_file("device_id : ".$device_id);
				write_file("status : ".$status);
				write_file("datetime_tab : ".$datetime_tab);
				write_file("hm : ".$hm);
				write_file("latitude : ".$latitude);
				write_file("longitude : ".$longitude);
				write_file("altitude : ".$altitude);
				write_file("accuracy : ".$accuracy);
				write_file("altitude_accuracy : ".$altitude_accuracy);
				write_file("heading : ".$heading);
				write_file("speed : ".$speed);
				write_file("time_stamp_gps : ".$time_stamp_gps); 

				login_daily_absent($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_update_current_unit_position($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_log_geolocation($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_update_log_over_speed($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_update_geofences($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);  
				insert_update_idle_start($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);  
				check_if_one_cycle_time($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				logout_daily_absent($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);


		/*
		$itemn = explode(" ", $_GET["datetime_start"]);
		$tgl = $itemn[0];
		$dt = strtotime($tgl);
		$kemarin = date("Y-m-d", $dt - 86400); 
		if(($_GET["time_start_position_station"] > '14:00:00')&&($_GET["time_start_position_station"] < '23:00:00')){
			$shift = 2;
		}else{
			$shift = 1;
		}
		$query = mysqli_query($con,"SELECT daily_absent_id FROM daily_absent WHERE nip='".$_GET["nip"]."' AND unit='".$_GET["unit"]."' AND device_id='".$_GET["device_id"]."' AND DATE(`date`) = '".$tgl."'");
		if($query && mysqli_num_rows($query) > 0){ 
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

		if($resp != 1){
			echo "0";
		}else{
			echo $resp;
		}
		*/
		echo 1;
		break;
	case "speedSave":

			write_file("masuk ke case speedSave"); 
			
			$json = new Services_JSON();
			$objord = $json->decode($_POST["ord"]);
			overspeedoffline($db, $con,$objord);
		break;


	case "ct":


				write_file("masuk ke case ct");

		ALL_updatecycle($con,'9452bc8f6bdda16f','LD0130','KR15037','0.00000','0.0000','2017-01-03','2017-01-02','2017-01-03 04:37:00');
		break;
	case "idle":
				write_file("masuk ke case idle"); 

		echo hitung_total_idle1($con,'NBID-EDI','KB12151','1e0cda3af4d98e04','2016-12-22','2016-12-21');
		break;
	case "fpi":
				write_file("masuk ke case fpi"); 

		getFPI($con,$_GET['unit'],$_GET['nip'],0);
		break;
	case "savestopstart":
				write_file("==============================================");
			write_file("masuk ke case savestopstart"); 

			$json = new Services_JSON();
			$objord = $json->decode($_POST["ord"]);
			$whr = "";
			$nr = 0;
			foreach ($objord->startstop as $row)
			{
				#$sql_ct = "SELECT *, count(*) as total FROM log_cycle_time WHERE nip = '".$row->nip."' AND unit = '".$row->unit."' AND datetime_start <= '". $row->datetime_start ."'  AND datetime_end IS NULL LIMIT 1 ";
				#$query = mysqli_query($con,$sql_ct);
				
				$query = mysqli_query($con,"SELECT cycle_time_id FROM log_cycle_time WHERE unit='".$row->unit."' AND nip='".$row->nip."'  AND datetime_start <= '". $row->datetime_start ."'  AND datetime_end IS NULL LIMIT 1");
				write_file("sql : SELECT cycle_time_id FROM log_cycle_time WHERE unit='".$row->unit."' AND nip='".$row->nip."'  AND datetime_start <= '". $row->datetime_start ."'  AND datetime_end IS NULL LIMIT 1"); 

				list($cycle_time_id) = mysqli_fetch_array($query);
				$query = mysqli_query($con,"SELECT ct_id FROM log_login_jam_dunia WHERE unit='".$row->unit."' AND nip='".$row->nip."' AND date_stop = '".$row->datetime_start."'");
				write_file("sql : SELECT ct_id FROM log_login_jam_dunia WHERE unit='".$row->unit."' AND nip='".$row->nip."' AND date_stop = '".$row->datetime_start."'"); 

				if($query && mysqli_num_rows($query) > 0){
					$resp = 1;
				}else{
					$resp = mysqli_query($con,"INSERT INTO log_login_jam_dunia (ct_id,unit, nip, date_stop, date_start) VALUES ('".$cycle_time_id."','".$row->unit."', '".$row->nip."', '".$row->datetime_start."', '".$row->datetime_stop."');");
					write_file("sql : INSERT INTO log_login_jam_dunia (ct_id,unit, nip, date_stop, date_start) VALUES ('".$cycle_time_id."','".$row->unit."', '".$row->nip."', '".$row->datetime_start."', '".$row->datetime_stop."');"); 

				}

				# Update total_idle jam dunia
				$sql_update_idle_ct = "UPDATE log_cycle_time SET total_idle = total_idle + (SELECT TIME_TO_SEC(TIMEDIFF(date_start, date_stop)) FROM log_login_jam_dunia ORDER BY log_login_jam_dunia_id DESC  LIMIT 1) WHERE cycle_time_id = ".$cycle_time_id;
				write_file("sql : ".$sql_update_idle_ct); 

				$rs_update_idle_ct = mysqli_query($con,$sql_update_idle_ct); 

				
				if(($nr == 0)&&($resp == 1)){
					$whr .= $row->id;
				}elseif(($nr > 0)&&($resp == 1)){
					$whr .= ", ".$row->id;
				}
				$nr++;
			}
			echo $whr;
		break;
	case "gpssave":
				write_file("==============================================");
			write_file("masuk ke case gpssave");
			getsaveGPSOffline($db, $con,$_POST["ord"]);
		break;
	case "saveunit":
				write_file("==============================================");
		write_file("masuk ke case saveunit");
		$query = mysqli_query($con,"SELECT device_id FROM sync_unit WHERE device_id='".$_GET["device_id"]."'");

		write_file("sql : SELECT device_id FROM sync_unit WHERE device_id='".$_GET["device_id"]."'");

		if($query && mysqli_num_rows($query) > 0){
			$resp =  mysqli_query($con,"UPDATE sync_unit SET unit='".$_GET["unit"]."',date_last_update='".$daten."' WHERE device_id='".$_GET["device_id"]."'");
			write_file("sql : UPDATE sync_unit SET unit='".$_GET["unit"]."',date_last_update='".$daten."' WHERE device_id='".$_GET["device_id"]."'");
		}else{
			$resp =  mysqli_query($con,"INSERT INTO sync_unit(`device_id`,`unit`,`active`) VALUES ('".$_GET["device_id"]."','".$_GET["unit"]."','1');");
			write_file("sql : INSERT INTO sync_unit(`device_id`,`unit`,`active`) VALUES ('".$_GET["device_id"]."','".$_GET["unit"]."','1');");
		}
		if($resp != 1){
			echo "0";
		}else{
			echo $resp;
		}
		break;
	case "loginstop":

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

				write_file("==============================================");
				$nip 					= $_GET["nip"];
				$unit 					= $_GET["unit"];
				$device_id 				= $_GET["device_id"];
				$status					= "logout";				// login , logout , idle, start, overspeed, normal, geofence
				$datetime_tab			= $datetimeend;
				$hm						= $_GET["hm_akhir"]; 	

				$latitude 				= $_GET["latitude_end"];
				$longitude 				= $_GET["longitude_end"];
				$altitude 				= "";
				$accuracy 				= "";
				$altitude_accuracy 		= ""; 
				$heading 				= "";
				$speed 					= 0; 
				$time_stamp_gps 		= $datetimeend;

				write_file("masuk ke case loginstop");
				write_file("nip : ".$nip);
				write_file("unit : ".$unit);
				write_file("device_id : ".$device_id);
				write_file("status : ".$status);
				write_file("datetime_tab : ".$datetime_tab);
				write_file("hm : ".$hm);
				write_file("latitude : ".$latitude);
				write_file("longitude : ".$longitude);
				write_file("altitude : ".$altitude);
				write_file("accuracy : ".$accuracy);
				write_file("altitude_accuracy : ".$altitude_accuracy);
				write_file("heading : ".$heading);
				write_file("speed : ".$speed);
				write_file("time_stamp_gps : ".$time_stamp_gps); 

				login_daily_absent($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_update_current_unit_position($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_log_geolocation($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_update_log_over_speed($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_update_geofences($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);  
				insert_update_idle_start($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);  
				check_if_one_cycle_time($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				logout_daily_absent($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);

		/*
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
		*/
		echo 1;
		break;
	case "savebreak":
				write_file("==============================================");
		write_file("masuk ke case savebreak");
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
function overspeedoffline($db, $con,$ord = null){
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
			if ($row->speed >= $speed) { 
				$nip 					= $row->nip;
				$unit 					= $row->unit;
				$device_id 				= $row->device_id;
				$status					= "overspeed";				// login , logout , idle, start, overspeed, normal, geofence
				$datetime_tab			= $row->date_insert;
				$hm						= ""; 	

				$latitude 				= $row->latitude;
				$longitude 				= $row->longitude;
				$altitude 				= $row->altitude;
				$accuracy 				= $row->accuracy;
				$altitude_accuracy 		= $row->altitude_accuracy; 
				$heading 				= $row->heading;
				$speed 					= $row->speed; 
				$time_stamp_gps 		= $row->date_insert;

				write_file("masuk ke loop overspeed");
				write_file("nip : ".$nip);
				write_file("unit : ".$unit);
				write_file("device_id : ".$device_id);
				write_file("status : ".$status);
				write_file("datetime_tab : ".$datetime_tab);
				write_file("hm : ".$hm);
				write_file("latitude : ".$latitude);
				write_file("longitude : ".$longitude);
				write_file("altitude : ".$altitude);
				write_file("accuracy : ".$accuracy);
				write_file("altitude_accuracy : ".$altitude_accuracy);
				write_file("heading : ".$heading);
				write_file("speed : ".$speed);
				write_file("time_stamp_gps : ".$time_stamp_gps); 
				write_file("speed : ".$row->speed);  
				write_file("max speed : ".$speed); 

				login_daily_absent($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_update_current_unit_position($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_log_geolocation($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_update_log_over_speed($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_update_geofences($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);  
				insert_update_idle_start($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);  
				check_if_one_cycle_time($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				logout_daily_absent($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
			}
		/*
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
		*/
		if ($whr <> "") $whr .= ",";
		$whr .= $row->over_speed_id;
	}
	echo $whr;
}
function getsaveGPSOffline($db, $con,$ord = null){
	$json = new Services_JSON();
	$objord = $json->decode($ord);
	$whr = "";
	$nr = 0;
	$nr1 = 0;
	foreach ($objord->gpsx as $row)
	{
		$query = mysqli_query($con,"SELECT unit FROM sync_unit WHERE device_id='".$row->device_id."' ");
		write_file("sql : SELECT unit FROM sync_unit WHERE device_id='".$row->device_id."' ");

		if($query && mysqli_num_rows($query) > 0){
			$row1 = mysqli_fetch_object($query);
		}
				$nip 					= $row->nip;
				$unit 					= $row1->unit;
				$device_id 				= $row->device_id;
				$status					= "normal";				// login , logout , idle, start, overspeed, normal, geofence
				$datetime_tab			= $row->date_insert;
				$hm						= ""; 	

				$latitude 				= $row->latitude;
				$longitude 				= $row->longitude;
				$altitude 				= $row->altitude;
				$accuracy 				= $row->accuracy;
				$altitude_accuracy 		= $row->altitudeaccuracy; 
				$heading 				= $row->heading;
				$speed 					= $row->speed; 
				$time_stamp_gps 		= $row->date_insert;

				write_file("masuk ke loop getsaveGPSOffline");
				write_file("nip : ".$nip);
				write_file("unit : ".$unit);
				write_file("device_id : ".$device_id);
				write_file("status : ".$status);
				write_file("datetime_tab : ".$datetime_tab);
				write_file("hm : ".$hm);
				write_file("latitude : ".$latitude);
				write_file("longitude : ".$longitude);
				write_file("altitude : ".$altitude);
				write_file("accuracy : ".$accuracy);
				write_file("altitude_accuracy : ".$altitude_accuracy);
				write_file("heading : ".$heading);
				write_file("speed : ".$speed);
				write_file("time_stamp_gps : ".$time_stamp_gps); 
				write_file("speed : ".$row->speed);  
				write_file("max speed : ".$speed); 


				login_daily_absent($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_update_current_unit_position($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_log_geolocation($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_update_log_over_speed($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				insert_update_geofences($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);  
				insert_update_idle_start($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);  
				check_if_one_cycle_time($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				logout_daily_absent($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);

		/*
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
		*/
		if ($whr <> "") $whr .= ","; 
		$whr .= $row->geolocation_id;
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
/*
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
}
*/
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

/*****************************************************************************/
/************************* START FUNCTION SCRIPT DWI *************************/
/*****************************************************************************/ 

function check_shift($jam) {
	if ($jam>0 && $jam <= 15) {
		$shift = 1;
	} else if ($jam > 15 && $jam < 24) {
		$shift = 2;
	}
	return $shift;
}

function login_daily_absent($db, $nip = "", $unit = "", $device_id = "", $latitude = "", $longitude = "", $altitude = "", $accuracy = "", $altitude_accuracy = "", $heading = "", $speed = "", $time_stamp_gps = "", $datetime_tab = "", $status = "", $hm = "") {
	//check apakah nip, unit, dan shift itu sudah ada apa belum. Jika belum, insert.  
	
	write_file("Masuk ke function login_daily_absent");
	if ($status == "login") {
		write_file("Status = login");

		$date 		= new DateTime($datetime_tab); 
		$tgl  		= $date->format('Y-m-d');	
		$time_in  	= $date->format('H:i:s');
		$shift		= check_shift($date->format('H')) ;
  

		#$date->sub(new DateInterval('P1D'));
		#$date_yesterday = $date->format("Y-m-d");

		$t 	= explode("-", $tgl);
		$date_yesterday = date("Y-m-d",mktime(0, 0, 0, $t[1], $t[2] - 1, $t[0] ));
	 
		$sql_check 	= "SELECT *, count(*) as total FROM daily_absent WHERE nip='".$nip."'  AND date = '".$date_yesterday."' AND date_out IS NULL AND time_out IS NULL";
		write_file("1020 : ".$sql_check);

		$rs_check 	= $db->GetRow($sql_check); 
		if($rs_check["total"] > 0) {
			// Sudah ada, tapi kemarin belum logout. Sekarang mau absent lagi. diabaikan 
		} else {
			// check apakah sudah login di tgl itu.
			$sql_check 	= "SELECT count(*) as total FROM daily_absent WHERE nip='".$nip."'  AND date = '".$tgl."'  AND date_out IS NULL AND time_out IS NULL";
			write_file("1028 : ".$sql_check);

			$rs_check 	= $db->GetRow($sql_check); 
			if($rs_check["total"] > 0) {  
				// sudah ada, tapi belum logout. diabaikan.
			} else if($rs_check["total"] == 0) { 
				// Ada 2 kemungkina : 
				// 1. belum login
				// 2. Sudah login tapi juga sudah logout.
				//Untuk kasus 2, berarti akan ada beberapa record untuk hari itu, sesuai jml login dan logoutnya
				
				$SQL = "SELECT * FROM daily_absent WHERE daily_absent_id = -1";
				$result = $db->execute($SQL);

				$data = array(
				    'device_id' 					=> $device_id,
				    'nip' 							=> $nip,
				    'unit' 							=> $unit,
				    'date' 							=> $tgl,
				    'latitude_start' 				=> $latitude, 
				    'longitude_start' 				=> $longitude, 
				    'hm_awal' 						=> $hm, 
				    'shift' 						=> $shift, 
				    'time_in' 						=> $time_in, 
				    'time_start_position_station' 	=> $datetime_tab 
				); 

				$SQL 	= $db->getInsertSql($result,$data); 
				write_file("1056 : ".$SQL);
				$rs 	= $db->execute($SQL);
				
				# inisiasi start ct
				if ($rs) { 
					insert_log_cycle_time($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status);
				}  
			}  
		}
	}
} 

function insert_update_current_unit_position($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="") {
	write_file("masuk ke function insert_update_current_unit_position");

	# unit position adalah posisi unit. Tidak masalah dia ganti-ganti tab. Asumsi tab adalah unit. Jadi harus uniq. 
	# disini kita bisa mengetahui kondisi paling akhir unit. (tab, nip, info geo, last update)
	$sql_check 	= "SELECT unit_position_id, count(*) as total FROM current_unit_position WHERE unit='".$unit."' GROUP BY unit_position_id";
	write_file("1074 : ". $sql_check);

	$rs_check 	= $db->GetRow($sql_check); 
	if($rs_check["total"] == 0) { 
		$SQL = "SELECT * FROM current_unit_position WHERE unit_position_id = -1";
		$result = $db->execute($SQL);

		$data = array(
		    'device_id' 					=> $device_id,
		    'nip' 							=> $nip,
		    'unit' 							=> $unit, 
		    'time_stamp' 					=> $time_stamp_gps, 
		    'latitude' 						=> $latitude, 
		    'longitude' 					=> $longitude, 
		    'altitude' 						=> $altitude, 
		    'accuracy' 						=> $accuracy, 
		    'altitude_accuracy' 			=> $altitude_accuracy, 
		    'heading' 						=> $heading , 
		    'speed' 						=> $speed , 
		    'date_last_update' 				=> $datetime_tab 
		); 

		$SQL 	= $db->getInsertSql($result,$data); 
		write_file("1097 : ". $SQL);
		$rs 	= $db->execute($SQL); 
	} else {
		# jika ada data, pastikan dicheck data yang akan diupdate adalah data terbaru.
		if ($datetime_tab > $rs_check["date_last_update"]) {
			$SQL = "SELECT * FROM current_unit_position WHERE unit_position_id = ". $rs_check["unit_position_id"];
			write_file("1103 : ". $SQL);
			$result = $db->execute($SQL);

			$data = array(
			    'device_id' 					=> $device_id,
			    'nip' 							=> $nip, 
			    'time_stamp' 					=> $latitude, 
			    'latitude' 						=> $latitude, 
			    'longitude' 					=> $longitude, 
			    'altitude' 						=> $altitude, 
			    'accuracy' 						=> $accuracy, 
			    'altitude_accuracy' 			=> $altitude_accuracy, 
			    'heading' 						=> $heading , 
			    'speed' 						=> $speed , 
			    'date_last_update' 				=> $datetime_tab 
			); 

			$SQL 	= $db->getUpdateSql($result,$data); 
			write_file("1121 : ". $SQL);
			$rs 	= $db->execute($SQL); 
		}  
	}
}



function insert_log_geolocation($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="", $hm= "") {
	write_file("masuk ke function insert_log_geolocation ");

	# log geolocation adalah data mentah yang selalu ditangkap oleh server. Dari sinilah sumber data semuanya. 
	# tgl 7 jan 2017, ditambah field status. Untuk mengetahui lemparan data di generate dari action apa.
	$SQL = "SELECT * FROM log_geolocation WHERE geolocation_id = -1";
	$result = $db->execute($SQL);

	$data = array(
	    'device_id' 					=> $device_id,
	    'nip' 							=> $nip,
	    'unit' 							=> $unit, 
	    'time_stamp' 					=> $datetime_tab, 
	    'time_stamp_gps' 				=> $time_stamp_gps, 
	    'latitude' 						=> $latitude, 
	    'longitude' 					=> $longitude, 
	    'altitude' 						=> $altitude, 
	    'accuracy' 						=> $accuracy, 
	    'altitude_accuracy' 			=> $altitude_accuracy, 
	    'heading' 						=> $heading , 
	    'speed' 						=> $speed , 
	    'status' 						=> $status , 
	    'hm' 							=> $hm 
	); 

	$SQL 	= $db->getInsertSql($result,$data); 
	write_file("1155 : ". $SQL);
	$rs 	= $db->execute($SQL); 
	return $rs; 
}


function insert_update_log_over_speed($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="") {
	write_file("masuk ke function insert_log_geolocation ");

	# check apakah over speed. Jika ya, mupdate total_over_speed di table daily_absent dan asukkan ke log_over_speed.
	# nilai over speed ambil dari table sync_setting_tracker
	if ($status == "overspeed") {
		$sql_check 	= "SELECT max_speed FROM sync_setting_tracker";
		$rs_check 	= $db->GetRow($sql_check); 
		$max_speed 	- $rs_check["max_speed"];
		if ($speed >= $max_speed) { 
			# asumsi : operator tidak boleh nyambung shift. Jika nyambung shift, maka query ini salah.
			$tmp_time = new DateTime($datetime_tab);
			$date_absen = $tmp_time->format("Y-m-d");

			#check apakah operator dan unit absent di tgl itu. 
			# jika tidak ada, berarti dia shift 2. cari di tgl sebelumnya karena tgl absen nya adalah tgl kemarin. Selain itu, berarti salah
			$sql_update = "";
			$sql_check 	= "SELECT *, count(*) as total FROM daily_absent WHERE nip='".$nip."' AND unit = '".$unit."' AND date='".$date_absen."'";
			write_file("1179 : ".$sql_check);

			$rs_check 	= $db->GetRow($sql_check); 
			if ($rs_check["total"]  > 0) {
				$sql_update = "UPDATE daily_absent SET total_over_speed = total_over_speed + 1 WHERE daily_absent_id = '".$rs_check["daily_absent_id"]."'";
			} else {
				#$tmp_time->sub(new DateInterval('P1D'));
				#$date_absen_kemarin 	= $tmp_date->format('Y-m-d');   

				$t 	= explode("-", $date_absen);
				$date_absen_kemarin = date("Y-m-d",mktime(0, 0, 0, $t[1], $t[2] - 1, $t[0] ));

				$sql_check2 	= "SELECT *, count(*) as total FROM daily_absent WHERE nip='".$nip."' AND unit = '".$unit."' AND date='".$date_absen_kemarin."'";
				write_file("1192 : ".$sql_check2);
				$rs_check2 		= $db->GetRow($sql_check2); 
				if ($rs_check2["total"]  > 0) {
					$sql_update = "UPDATE daily_absent SET total_over_speed = total_over_speed + 1 WHERE daily_absent_id = '".$rs_check2["daily_absent_id"]."'";
				}
			}
			if ($sql_update <> "") {
				write_file("1199 : ".$sql_update);
				$rs 	= $db->execute($sql_update);
				if ($rs) {
					$sql_1 = "SELECT * FROM log_over_speed WHERE over_speed_id = -1";
					$result_1 = $db->execute($sql_1);

					$data = array(
					    'device_id' 					=> $device_id,
					    'nip' 							=> $nip,
					    'unit' 							=> $unit, 
					    'time_stamp' 					=> $datetime_tab, 
					    'speed' 						=> $speed ,
					    'latitude' 						=> $latitude, 
					    'longitude' 					=> $longitude, 
					    'altitude' 						=> $altitude, 
					    'accuracy' 						=> $accuracy, 
					    'altitude_accuracy' 			=> $altitude_accuracy, 
					    'heading' 						=> $heading 
					); 

					$sql_insert 	= $db->getInsertSql($result_1,$data); 
					write_file("1220 : ".$sql_insert);
					$rs_1 			= $db->execute($sql_insert);
				} 
			} 
		} 
	}
}  


function insert_update_geofences($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="") {
	write_file("masuk ke function insert_update_geofences");
	# check apakah masuk/logout area geofences. 
	# jika masuk/logout area cpp dan port, maka insert/update table log_coverage_in dan log_cycle_time
	
	//if ($status == "geofence") {
		# ambil radius geofence
		$radius_geofence 	= radius_geofence($db); 

		# check geofence
		$sql_geofence 		= "SELECT * FROM sync_coverage_in ORDER BY station_id";
		$rs_geofence 		= $db->GetAll($sql_geofence);
		if (count($rs_geofence) > 0) {
			FOREACH ($rs_geofence AS $r) {
				$latx 		= $r["latitude_x1"];
				$longy 		= $r["longitude_y1"];
				$station_id	= $r["station_id"];

				$distance = 1000 * distance($latx, $longy, $latitude, $longitude, $unitnya="K");


				# apakah masuk ke area geofence ?
				if ($distance <= $radius_geofence) {
					#echo "Distance ".$station_id	.": ".$distance." : ". $radius_geofence."  : kurang<br>";
					# Dalam 1 hari, 1 operator dengan 1 unit, di tanggal dan shift itu, bisa masuk dan logout beberapa kali
					# Kombinasi : 
					#	1. Tgl hari itu masih kosong
					#	2. Tgl hari itu sudah ada in
					#	3. Tgl hari itu sudah ada in dan out
					#	4. Out untuk tanggal kemarin (nyeberang hari) 
					
					$tmp_time = new DateTime($datetime_tab);

					$time 		= $tmp_time->format("H:i:s");
					$curr_date 	= $tmp_time->format("Y-m-d");

					#$tmp_time->sub(new DateInterval('P1D'));
					#$date_yesterday = $tmp_time->format("Y-m-d");

					$t 	= explode("-", $curr_date);
					$date_yesterday = date("Y-m-d",mktime(0, 0, 0, $t[1], $t[2] - 1, $t[0] ));

					# check apakah nyeberang hari
					$sql_cov_in = "SELECT *, count(*) as total FROM log_coverage_in WHERE nip = '".$nip."' AND unit = '".$unit."' AND  date_in='".$date_yesterday."' AND date_out IS NULL AND time_out IS NULL ";
					write_file("sql check cov kemarin : ".$sql_cov_in);
					$rs_cov_in	= $db->GetRow($sql_cov_in);
					if ($rs_cov_in["total"] > 0) {
						# UPDATE time out
						$sql_update_cov_in 	= "UPDATE log_coverage_in SET time_out = '".$time."', date_out = '".$curr_date."' WHERE coverage_in_id = '".$rs_cov_in["coverage_in_id"]."'";
						write_file("1278 : ".$sql_update_cov_in);
						$rs_update_cov_in	= $db->execute($sql_update_cov_in);
					} else {
						# check apakah data sudah ada
						$sql_cov_in_2 	= "SELECT *, count(*) as total FROM log_coverage_in WHERE nip = '".$nip."' AND unit = '".$unit."' AND  date_in='".$curr_date."' AND date_out IS NULL AND time_out IS NULL ";
						write_file("1283 : ".$sql_cov_in_2);
						$rs_cov_in_2	= $db->GetRow($sql_cov_in_2);
						if ($rs_cov_in_2["total"] > 0) {
							# UPDATE time out curr date
							$sql_update_cov_in_2 	= "UPDATE log_coverage_in SET time_out = '".$time."', date_out = '".$curr_date."' WHERE coverage_in_id = '".$rs_cov_in_2["coverage_in_id"]."'";
							write_file("1288 : ".$sql_update_cov_in_2);

							$rs_update_cov_in_2		= $db->execute($sql_update_cov_in_2);
						} else {
							# insert new cov in
							$sql_insert_cov_in 		= "SELECT * FROM log_coverage_in WHERE coverage_in_id = -1";
							$result_insert_cov_in	= $db->execute($sql_insert_cov_in);

							$data = array(
							    'device_id' 			=> $device_id,
							    'nip' 					=> $nip,
							    'unit' 					=> $unit, 
							    'date_in' 				=> $curr_date, 
							    'time_in' 				=> $time ,
							    'lat' 					=> $latitude, 
							    'lon' 					=> $longitude, 
							    'station_id' 			=> $station_id 
							); 

							$sql_insert_cov_in 	= $db->getInsertSql($result_insert_cov_in,$data); 
							write_file("1308 : ".$sql_insert_cov_in);
							$rs_1 				= $db->execute($sql_insert_cov_in);
						}
					}
					# update log_cycle_time jika di posisi cpp/port
					if ($station_id == "1" || $station_id == "2" ) {
						update_station_log_cycle_time($db, $nip , $unit , $datetime_tab , $station_id) ;
					} 
				}
			}
		}
	//}
}  


function update_station_log_cycle_time($db, $nip="", $unit="", $datetime_tab="", $station_id="") {
	write_file("masuk ke function update_station_log_cycle_time");
	# Data inisiasi log_cycle_time harus udah ada dulu.
	# Jika ketemu cpp atau port, maka akan update cpp/portnya
	
	$update_data = false;

	if ($station_id == 1) {
		$field = "time_stasiun_cpp";
	} else {
		$field = "time_stasiun_port";
	}

	$tmp_time = new DateTime($datetime_tab);

	$time 		= $tmp_time->format("H:i:s");
	$curr_date 	= $tmp_time->format("Y-m-d");

	#$tmp_time->sub(new DateInterval('P1D'));
	#$date_yesterday = $tmp_time->format("Y-m-d");

	$t 	= explode("-", $curr_date);
	$date_yesterday = date("Y-m-d",mktime(0, 0, 0, $t[1], $t[2] - 1, $t[0] ));

	# check apakah hari kemarin sudah ada start yang belum selesai (nyeberang hari.)
	$sql_ct = "SELECT *, count(*) as total FROM log_cycle_time WHERE nip = '".$nip."' AND unit = '".$unit."' AND  DATE_FORMAT(datetime_start,'%Y-%m-%d')='".$date_yesterday."' AND datetime_end IS NULL ";
	write_file("1349 : ".$sql_ct);
	#echo "dwi kemarin : ". $sql_ct."<br>";
	$rs_ct	= $db->GetRow($sql_ct);
	if ($rs_ct["total"] > 0) {
		$update_data = true;
	} else {
		# check apakah hari ini ada yang belum start.
		$sql_ct = "SELECT *, count(*) as total FROM log_cycle_time WHERE nip = '".$nip."' AND unit = '".$unit."' AND  DATE_FORMAT(datetime_start,'%Y-%m-%d')='".$curr_date."' AND datetime_end IS NULL ";
		write_file("1357 : ".$sql_ct);
		#echo "dwi sekarang : ". $sql_ct."<br>";
		$rs_ct	= $db->GetRow($sql_ct);
		if ($rs_ct["total"] > 0) {
			$update_data = true;
		} 
	}
	if ($update_data == true) {
		if ($rs_ct[$field] == NULL) { 
			$sql_update_ct 	= "UPDATE log_cycle_time SET ".$field." = '".$datetime_tab."' WHERE cycle_time_id = '".$rs_ct["cycle_time_id"]."'";
			write_file("1367 : ".$sql_update_ct);
			#echo "dwi update : ". $sql_update_ct."<br>";
			$rs_update_ct	= $db->execute($sql_update_ct);
		}
	}
}


function radius_geofence($db="") {

	#$sql_check 			= "SELECT radius_geofence FROM sync_setting_tracker";
	#$rs_check 			= $db->GetRow($sql_check); 
	#$radius_geofence 	= $rs_check["radius_geofence"];
	#return $radius_geofence;
	return 100;
}

function update_end_log_cycle_time($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="") {
	write_file("masuk ke function update_end_log_cycle_time");
	# Update end cycle time jika :
	# 1. unit sudah kembali ke titik start --> time cpp not null, time port not null, date end null 
	# 2. operator menekan tombol logout. --> ada kemungkinan time cpp dan port null atau terisi. yang pasti date end harus null 

	# Unit kembali ke tititk start : 
	# 1. Data inisiasi log_cycle_time harus udah ada dulu.
	# 2. cpp atau port harus ada isinya
	# 3. Update table daily_absent, total_cycle_time += 1;
	# 4. Create record baru untuk inisiasi start CT

	# Operator menekan tombol logout
	# 1. Data inisiasi log_cycle_time harus sudah ada dulu
	# 2. Update table daily_absent, total_cycle_time = total_cycle_time + prorata jarak
  
 
	$update_data 			= false;
	$filter_logout_or_not 	=	"";

	$tmp_time 	= new DateTime($datetime_tab);

	$time 		= $tmp_time->format("H:i:s");
	$curr_date 	= $tmp_time->format("Y-m-d");

	#$tmp_time->sub(new DateInterval('P1D'));
	#$date_yesterday = $tmp_time->format("Y-m-d");

	$t 	= explode("-", $curr_date);
	$date_yesterday = date("Y-m-d",mktime(0, 0, 0, $t[1], $t[2] - 1, $t[0] ));
 
 	// CASE 1. kembali ke titik awal
 	if ($status == "logout") {
 		$filter_logout_or_not = "";
 	} else  {
 		$filter_logout_or_not = " AND time_stasiun_cpp IS NOT NULL AND time_stasiun_port IS NOT NULL ";
 	}

	# check apakah hari kemarin sudah ada start yang belum selesai (nyeberang hari.)
	$sql_ct = "SELECT *, count(*) as total FROM log_cycle_time WHERE nip = '".$nip."' AND unit = '".$unit."' AND  DATE_FORMAT(datetime_start,'%Y-%m-%d')='".$date_yesterday."' ".$filter_logout_or_not." AND datetime_end IS NULL GROUP BY cycle_time_id LIMIT 1";
	write_file("1424 : ".$sql_ct);

	#echo "dwi kemarin : ". $sql_ct."<br>";
	$rs_ct	= $db->GetRow($sql_ct);
	if ($rs_ct["total"] > 0) { 
		$update_data = true; 
	} else {
		# check apakah hari ini ada yang belum start.
		$sql_ct = "SELECT *, count(*) as total FROM log_cycle_time WHERE nip = '".$nip."' AND unit = '".$unit."' AND  DATE_FORMAT(datetime_start,'%Y-%m-%d')='".$curr_date."' ".$filter_logout_or_not." AND datetime_end IS NULL GROUP BY cycle_time_id LIMIT 1";
		write_file("1433 : ".$sql_ct);
		#echo "dwi sekarang : ". $sql_ct."<br>";
		$rs_ct	= $db->GetRow($sql_ct);
		if ($rs_ct["total"] > 0) {
			$update_data = true; 
		} 
	}

	if (($update_data == true) && ($status != "logout")) {
		# check triger end cycle time :
		# 1. status = logout
		# 2. kembali ke titik start
		# 3. Tidak pernah logout.
		  
		$radius_geofence 	= radius_geofence($db);
 		$distance = 1000 * distance($latitude, $longitude, $rs_ct["latitude_start"], $rs_ct["longitude_start"], $unitnya="K");

 		if ($distance <= $radius_geofence) { 
 			#echo "dwi distance end logcycletime : ". $distance."<br>";
	 		# update end
			$sql_update_end_ct 		= "SELECT * FROM log_cycle_time WHERE cycle_time_id = ". $rs_ct["cycle_time_id"];
			$result_update_end_ct	= $db->execute($sql_update_end_ct);

			$data = array( 
			    'latitude_end' 		=> $latitude, 
			    'longitude_end' 	=> $longitude,
			    'datetime_end'		=> $datetime_tab
			); 

			$sql_update 	= $db->getUpdateSql($result_update_end_ct,$data); 
			write_file("1463 : ".$sql_update);
			$rs_update 		= $db->execute($sql_update);

			# update FPI
			update_fpi($db, $rs_ct["cycle_time_id"]);

			# update total_cycle_time di table daily_absent
			$daily_absent_id = get_daily_absent_id($db, $nip, $time_stamp_gps);
			$sql_update_ct_daily_absent = "UPDATE daily_absent SET total_cycle_time = total_cycle_time+1 WHERE daily_absent_id = ". $daily_absent_id;
			write_file("1472 : ".$sql_update_ct_daily_absent);
			# echo "dwi update 1 cycle : ". $sql_update_ct_daily_absent."<br>";
			$db->execute($sql_update_ct_daily_absent);

			# inisiasi start ct
			insert_log_cycle_time($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status);
		} 
	} else if (($update_data == true) && ($status == "logout")) {
		# update date end cycle time  
		$sql_update_end_ct 		= "SELECT * FROM log_cycle_time WHERE cycle_time_id = ". $rs_ct["cycle_time_id"];
		# echo "dwi sekarang : ". $sql_update_end_ct."<br>";
		$result_update_end_ct	= $db->execute($sql_update_end_ct);

		$data = array( 
		    'latitude_end' 		=> $latitude, 
		    'longitude_end' 	=> $longitude,
		    'datetime_end'		=> $datetime_tab
		); 

		$sql_update 	= $db->getUpdateSql($result_update_end_ct,$data); 
		write_file("1492 : ".$sql_update);
		$rs_update 		= $db->execute($sql_update);

		# update FPI
		update_fpi($db, $rs_ct["cycle_time_id"]);

		# hitung jarak
		$jarak_tempuh = hitung_persentase_ritase($rs_ct,$db, $nip, $rs_ct["latitude_start"] , $rs_ct["longitude_start"], $latitude, $longitude);


		# update total_cycle_time daily_absent
		$daily_absent_id = get_daily_absent_id($db, $nip, $time_stamp_gps);
		$sql_update_ct_daily_absent = "UPDATE daily_absent SET total_cycle_time = (total_cycle_time + ".$jarak_tempuh.") WHERE daily_absent_id = ". $daily_absent_id;
		write_file("1505 : ".$sql_update_ct_daily_absent);
		# echo "dwi update totalct : ". $sql_update_ct_daily_absent."<br>";
		$db->execute($sql_update_ct_daily_absent);
	} 
}

function hitung_persentase_ritase($rs_ct, $db, $nip, $latitude_start, $longitude_start, $latitude_end, $longitude_end) {
	$jarak_tempuh 	= 0;
	$total_jarak 	= 0;
	$sql_station = "SELECT * FROM sync_station WHERE station_id IN (1,2)";
	$rs_station = $db->GetAll($sql_station); 
	if (count($rs_station) > 0) {
		FOREACH ($rs_station AS $r) {
			if ($r["station_id"] == 1) {
				$lat_cpp 		= $r["latitude"];
				$lon_cpp 		= $r["longitude"];
			} else if ($r["station_id"] == 2) {
				$lat_port 		= $r["latitude"];
				$lon_port 		= $r["longitude"];
			} 

			$total_jarak += 2 * distance($latitude_end, $longitude_end, $r["latitude"], $r["longitude"], $unitnya="K"); 
		}
	}
	if(($rs_ct["time_stasiun_cpp"] != NULL) && ($rs_ct["time_stasiun_port"] != NULL)){
		write_file("1543 : Time cpp dan port kosong");
		$tmp = distance($latitude_start, $longitude_start, $latitude_end, $longitude_end, $unitnya="K"); 
		$jarak_tempuh =  $total_jarak - $tmp;
	} else if(($rs_ct["time_stasiun_cpp"] != NULL) && ($rs_ct["time_stasiun_port"] == NULL)){
		write_file("1543 : Time cpp tidak null dan port null");
		$jarak_tempuh = distance($latitude_start, $longitude_start, $lat_cpp, $lon_cpp, $unitnya="K"); 
		$jarak_tempuh += distance($latitude_end, $longitude_end, $lat_cpp, $lon_cpp, $unitnya="K"); 
	} else if(($rs_ct["time_stasiun_cpp"] == NULL) && ($rs_ct["time_stasiun_port"] != NULL)){
		write_file("1543 : Time cpp  null dan port tidak null");
		$jarak_tempuh = distance($latitude_start, $longitude_start, $lat_port, $lon_port, $unitnya="K"); 
		$jarak_tempuh += distance($latitude_end, $longitude_end, $lat_port, $lon_port, $unitnya="K");  
	} else if(($rs_ct["time_stasiun_cpp"] == NULL) && ($rs_ct["time_stasiun_port"] == NULL)){
		write_file("1543 : Time cpp  null dan port null");
		$jarak_tempuh = distance($latitude_start, $longitude_start, $latitude_end, $longitude_end, $unitnya="K"); 
	}
	$jarak = $jarak_tempuh / $total_jarak;
	
	write_file("1554 : latitude start = ".$latitude_start." longitude start = ".$longitude_start);
	write_file("1554 : latitude end = ".$latitude_end." longitude end = ".$longitude_end);
	write_file("1554 : Jarak tempuh = ".$jarak_tempuh." Total Jarak = ".$total_jarak);
	write_file("1554 : persentase jarak = ".$jarak);

	return number_format($jarak, 2);
}

function get_daily_absent_id($db, $nip, $time_stamp_gps) {
	// syarat : absen nya belum pernah logout.

	$tmp_time 	= new DateTime($time_stamp_gps);  
	$curr_date 	= $tmp_time->format("Y-m-d");

	#$tmp_time->sub(new DateInterval('P1D'));
	#$date_yesterday 		= $tmp_time->format("Y-m-d"); 

	$t 	= explode("-", $curr_date);
	$date_yesterday = date("Y-m-d",mktime(0, 0, 0, $t[1], $t[2] - 1, $t[0] ));

	# check apakah operator login di shift 2 (hari kemarin) dan belum logout
	$sql_1 	= "SELECT *, count(*) as total FROM daily_absent WHERE nip='".$nip."'  AND date = '".$date_yesterday."' AND date_out IS NULL AND time_out IS NULL ";
	$rs_1 	= $db->GetRow($sql_1);
	if ($rs_1["total"] > 0) {
		return $rs_1["daily_absent_id"];
	} else {
		# Jika shift 2 tidak ada, check apakah operator login hari ini. syarat belum logout.
		$sql_2	= "SELECT *, count(*) as total FROM daily_absent WHERE nip='".$nip."'  AND date = '".$curr_date."' AND date_out IS NULL AND time_out IS NULL ";
		#echo "dwi daily_absent_id : ". $sql_2."<br>";
		$rs_2 	= $db->GetRow($sql_2);
		if ($rs_2["total"] > 0) {
			return $rs_2["daily_absent_id"];
		}
	}
}

function insert_update_idle_start($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="") {
	write_file("masuk ke function insert_update_idle_start");
	# Logic idle start : 
	# 1. Mencatat tgl jam mulai idle
	# 2. Mencatat tgl jam mulai jalan lagi
	# 3. Mengupdate total_idle di log_cycle_time

	# Jadi, kita harus mencari dia ada di CT yang mana agar mempermudah perhitungan.
	# 1. date_time idle > date_time_start ct
	# 2. date_time_end ct IS NULL

	# Kombinasi kondisi :
	# 1. Bisa nyeberang tgl. khusus untuk shift 2. 
	   
	$update_start = false;
	$tmp_time 	= new DateTime($datetime_tab);  
	$curr_date 	= $tmp_time->format("Y-m-d");

	#$tmp_time->sub(new DateInterval('P1D'));
	#$date_yesterday 		= $tmp_time->format("Y-m-d");
	#$date_time_yesterday 	= $tmp_time->format("Y-m-d H:i:s");

	$t 	= explode("-", $curr_date);
	$date_yesterday = date("Y-m-d",mktime(0, 0, 0, $t[1], $t[2] - 1, $t[0] ));

	# cari ct nya
	if ($status == "idle" || $status =="start") { 
		$sql_ct = "SELECT *, count(*) as total FROM log_cycle_time WHERE nip = '".$nip."' AND unit = '".$unit."' AND datetime_start <= '". $time_stamp_gps ."'  AND datetime_end IS NULL ";
		write_file("1601 : ".$sql_ct);
		$rs_ct	= $db->GetRow($sql_ct);
		if ($rs_ct["total"] > 0) {
			$ct_id 	= $rs_ct["cycle_time_id"];

			# check apakah ada di log_login_jam_dunia kemarin
			$sql_check_jdunia = "SELECT *, count(*) as total FROM log_login_jam_dunia WHERE nip = '".$nip."' AND unit = '".$unit."' AND DATE_FORMAT(date_stop,'%Y-%m-%d')='".$date_yesterday."' AND date_start IS NULL";
			write_file("1608 : ".$sql_check_jdunia);
			$rs_check_jdunia = $db->GetRow($sql_check_jdunia);
			if ($rs_check_jdunia["total"] > 0) {
				# jika ada data, check apakah status idle atau start. jika idle, diamkan. Jika start, maka update
				if ($status == "start") {
					# update start. Update total_idle di log_cycle_time
					$update_start = true;
				}
			} else {
				# check apakah ada di log_login_jam_dunia hari ini
				$sql_check_jdunia = "SELECT *, count(*) as total FROM log_login_jam_dunia WHERE nip = '".$nip."' AND unit = '".$unit."' AND DATE_FORMAT(date_stop,'%Y-%m-%d')='".$curr_date."' AND date_start IS NULL";
				write_file("1619 : ".$sql_check_jdunia);
				$rs_check_jdunia = $db->GetRow($sql_check_jdunia);
				if ($rs_check_jdunia["total"] > 0) {
					# check status, jika idle lewat. Jika start, update
					if ($status == "start") { 
						$update_start = true;
					}
				} else {
					# jika data tidak ada, maka asumsi status = idle. baru pertama kali.
					if ($status == "idle") { 
						$sql_insert_jd 		= "SELECT * FROM log_login_jam_dunia WHERE log_login_jam_dunia_id = -1";
						$result_insert_jd	= $db->execute($sql_insert_jd);

						$data = array(
						    'ct_id' 		=> $ct_id, 
						    'nip' 			=> $nip,
						    'unit' 			=> $unit, 
						    'date_stop'		=> $datetime_tab 
						); 

						$sql_jd		= $db->getInsertSql($result_insert_jd,$data); 
						write_file("1640 : ".$sql_jd);
						$rs_jd 		= $db->execute($sql_jd); 

					} 
				}	
			}

			if ($update_start == true) {
				# update start. 
				$sql_update_jdunia 	= "UPDATE log_login_jam_dunia SET date_start = '".$datetime_tab."' WHERE log_login_jam_dunia_id = '".$rs_check_jdunia["log_login_jam_dunia_id"]."'";
				write_file("1675 : ".$sql_update_jdunia);
				$rs_udpate_jdunia 	= $db->Execute($sql_update_jdunia); 


				# Update total_idle jam dunia
				$sql_update_idle_ct = "UPDATE log_cycle_time SET total_idle = total_idle + (SELECT TIME_TO_SEC(TIMEDIFF(date_start, date_stop)) FROM log_login_jam_dunia WHERE log_login_jam_dunia_id=".$rs_check_jdunia["log_login_jam_dunia_id"].") WHERE cycle_time_id = ".$ct_id;
				write_file("1708 : ".$sql_update_idle_ct);
				# echo "dwi update total idle : ". $sql_update_idle_ct."<br>";;
				$rs_update_idle_ct 	= $db->Execute($sql_update_idle_ct);
			}
		}
	}  
}


function logout_daily_absent($db, $nip = "", $unit = "", $device_id = "", $latitude = "", $longitude = "", $altitude = "", $accuracy = "", $altitude_accuracy = "", $heading = "", $speed = "", $time_stamp_gps = "", $datetime_tab = "", $status = "", $hm = "") {
	write_file("masuk ke function logout_daily_absent");
	#Kasus logout
	#1. logout dihari yang sama
	#2. Logout dihari yang berbeda. Kasus shift 2, dimana nyeberang hari.

	#Proses :
	#1. Update tgl, jam, lat lon, hm table daily_absent
	#2. Update table log_cycle_time
	#3. update total_cycle_time di table daily_absent
	 
	if ($status == "logout") {
		$date 		= new DateTime($datetime_tab); 
		$curr_date  = $date->format('Y-m-d');	
		$jam  		= $date->format('H:i:s');	

		#$date->sub(new DateInterval('P1D'));
		#$date_yesterday 		= $date->format("Y-m-d"); 

		$t 	= explode("-", $curr_date);
		$date_yesterday = date("Y-m-d",mktime(0, 0, 0, $t[1], $t[2] - 1, $t[0] ));
	 
		$sql_check 	= "SELECT *, count(*) as total FROM daily_absent WHERE nip='".$nip."'  AND date = '".$date_yesterday."' AND date_out IS NULL AND time_out IS NULL";
		write_file("1740 : ".$sql_check);
		$rs_check 	= $db->GetRow($sql_check); 
		if($rs_check["total"] > 0) { 
			# udate ct
			update_end_log_cycle_time($db, $nip , $unit , $device_id , $latitude , $longitude , $altitude , $accuracy , $altitude_accuracy , $heading , $speed , $time_stamp_gps , $datetime_tab , $status );

			$SQL = "SELECT * FROM daily_absent WHERE daily_absent_id = ".$rs_check["daily_absent_id"];
			$result = $db->execute($SQL);

			$data = array(
			    'date_out' 						=> $curr_date,
			    'time_out' 						=> $jam,
			    'time_stop_position_station' 	=> $datetime_tab ,
			    'latitude_end' 					=> $latitude, 
			    'longitude_end' 				=> $longitude, 
			    'hm_akhir' 						=> $hm
			); 

			$sql_update_da 	= $db->getUpdateSql($result,$data); 
			write_file("1759 : ".$sql_update_da);
			$rs 			= $db->execute($sql_update_da);

		} else {
			$sql_check2 	= "SELECT *, count(*) as total FROM daily_absent WHERE nip='".$nip."'  AND date = '".$curr_date."' AND date_out IS NULL AND time_out IS NULL";
			write_file("1764 : ".$sql_check2);
			$rs_check2 		= $db->GetRow($sql_check2); 
			if($rs_check2["total"] > 0) { 

				# udate ct
				update_end_log_cycle_time($db, $nip , $unit , $device_id , $latitude , $longitude , $altitude , $accuracy , $altitude_accuracy , $heading , $speed , $time_stamp_gps , $datetime_tab , $status );

				$sql_insert2 = "SELECT * FROM daily_absent WHERE daily_absent_id = ".$rs_check2["daily_absent_id"];
				$result_insert2 = $db->execute($sql_insert2);

				$data = array(
				    'date_out' 						=> $curr_date,
				    'time_out' 						=> $jam,
				    'time_stop_position_station' 	=> $datetime_tab ,
				    'latitude_end' 					=> $latitude, 
				    'longitude_end' 				=> $longitude, 
				    'hm_akhir' 						=> $hm
				); 

				$sql_update_insert2 	= $db->getUpdateSql($result_insert2,$data); 
				write_file("1784 : ".$sql_update_insert2);
				$rs_insert2 			= $db->execute($sql_update_insert2);

			}
		}
	} 
}

function check_if_one_cycle_time($db, $nip = "", $unit = "", $device_id = "", $latitude = "", $longitude = "", $altitude = "", $accuracy = "", $altitude_accuracy = "", $heading = "", $speed = "", $time_stamp_gps = "", $datetime_tab = "", $status = "", $hm = "") {
	#Prasarat : 
	#1. status != logout
	#2. current posisi sama dengan posisi start

	#Proses :
	#1. Update tgl, jam, lat lon, hm table daily_absent
	#2. Update table log_cycle_time
	#3. update total_cycle_time di table daily_absent
	 
	if ($status != "logout") { 
		update_end_log_cycle_time($db, $nip , $unit , $device_id , $latitude , $longitude , $altitude , $accuracy , $altitude_accuracy , $heading , $speed , $time_stamp_gps , $datetime_tab , $status );
	} 
}

function distance($lat1, $lon1, $lat2, $lon2, $unit="K") { 

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
	
	#echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
	#echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
	#echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
}

function update_fpi($db, $cycle_time_id) {
	if ($cycle_time_id <> "") {
		$sql 		= "SELECT SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(datetime_end,datetime_start)) - total_idle) as durasi FROM log_cycle_time WHERE cycle_time_id=".$cycle_time_id;
		$rs 		= $db->GetRow($sql);
		$jam_menit 	= $rs["durasi"];
		$nilai 		= convertJamMenit($jam_menit);

		$sql 		= "SELECT factor FROM setting_fpi WHERE batas_hm_atas >= '".$nilai."' AND batas_hm_bawah <'".$nilai."'";
		$rs 		= $db->GetRow($sql);
		$fpi 		= $rs["factor"];
		if ($fpi == null) $fpi = 0;

		$sql 		= "UPDATE log_cycle_time SET fpi='".$fpi."' WHERE cycle_time_id='".$cycle_time_id."'";
		$rs 		= $db->Execute($sql);
	}
}
function convertJamMenit ($var) {
	$tmp = explode(":", $var); 
	$jam = $tmp[0]  + number_format( ($tmp[1] / 60),2 ); 
	return $jam; 
}	


function insert_log_cycle_time($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="") {
	#Jika data belum ada, maka entry baru.
	#log cycle time mirip dengan log coverage in
	#Antara start dan end, bisa nyeberang hari.	
	 
	write_file("masuk ke function insert_log_cycle_time");

	$tmp_time = new DateTime($datetime_tab);

	$time 		= $tmp_time->format("H:i:s");
	$curr_date 	= $tmp_time->format("Y-m-d");

	#$tmp_time->sub(new DateInterval('P1D'));
	#$date_yesterday = $tmp_time->format("Y-m-d");
	
	$t 	= explode("-", $curr_date);
	$date_yesterday = date("Y-m-d",mktime(0, 0, 0, $t[1], $t[2] - 1, $t[0] ));

	# check apakah hari kemarin sudah ada start yang belum selesai (nyeberang hari.)
	$sql_cov_in = "SELECT *, count(*) as total FROM log_cycle_time WHERE nip = '".$nip."' AND unit = '".$unit."' AND  DATE_FORMAT(date_in,'%Y-%m-%d')='".$date_yesterday."' AND datetime_end IS NULL ";
	write_file("1871 : ".$sql_cov_in);

	$rs_cov_in	= $db->GetRow($sql_cov_in);
	if ($rs_cov_in["total"] > 0) {
		# inisiasi start sudah ada. tidak melakukan apa-apa.
	} else {
		# check apakah hari ini ada yang belum start.
		$sql_cov_in = "SELECT *, count(*) as total FROM log_cycle_time WHERE nip = '".$nip."' AND unit = '".$unit."' AND  DATE_FORMAT(date_in,'%Y-%m-%d')='".$curr_date."' AND datetime_end IS NULL ";
		write_file("1979 : ".$sql_cov_in);
		$rs_cov_in	= $db->GetRow($sql_cov_in);
		if ($rs_cov_in["total"] > 0) {
			# tidak ada action apa apa
		} else {
			# mulai start ct
			$sql_insert_ct 		= "SELECT * FROM log_cycle_time WHERE cycle_time_id = -1";
			$result_insert_ct	= $db->execute($sql_insert_ct);

			$data = array(
			    'device_id' 			=> $device_id,
			    'nip' 					=> $nip,
			    'unit' 					=> $unit, 
			    'datetime_start'		=> $datetime_tab,
			    'latitude_start'		=> $latitude,
			    'longitude_start'		=> $longitude 
			); 

			$sql_insert_ct 	= $db->getInsertSql($result_insert_ct,$data); 
			write_file("1898 : ".$sql_insert_ct);
			$rs_1 			= $db->execute($sql_insert_ct); 
		}
	}
}
function write_file($text) {
	/*
	$myfile = fopen("log_sync.txt", "a+") or die("Unable to open file!"); 
	fwrite($myfile, "(".date("Y-m-d H:i:s").") ".$text."\n"); 
	fclose($fp);
	*/
}
/*****************************************************************************/
/************************* END FUNCTION SCRIPT DWI ***************************/
/*****************************************************************************/ 
?>
