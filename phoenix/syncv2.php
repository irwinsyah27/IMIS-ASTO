<?php
# v2.1 201703051313
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('Asia/Jakarta'); 

include 'adodb/adodb.inc.php'; 
$driver = 'mysqli';
$db  	= adoNewConnection($driver); 
$db->connect('localhost','root','J@karta2016','phoenix_db');
#$db->connect('localhost','root','','phoenix_db');

$con = mysqli_connect("localhost","root","J@karta2016","phoenix_db");
#$con = mysqli_connect("localhost","root","","phoenix_db");
include 'JSON.php';

switch ($_GET["opt"]){
	case "loggeolocation":
				$id 					= $_GET["id"];
				$nip 					= $_GET["nip"];
				$unit 					= $_GET["unit"];
				$device_id 				= $_GET["device_id"];
				$status					= $_GET["status"];				// login , logout , idle, start, overspeed, normal, geofence
				$datetime_tab			= $_GET["date_insert"];
				$hm						= $_GET["hm"]; 	

				$latitude 				= $_GET["latitude"];
				$longitude 				= $_GET["longitude"];
				$altitude 				= $_GET["altitude"];
				$accuracy 				= $_GET["accuracy"];
				$altitude_accuracy 		= $_GET["altitudeaccuracy"]; 
				$heading 				= $_GET["heading"]; 
				$speed 					= $_GET["speed"]; 
				$time_stamp_gps 		= $_GET["date_insert"];
 
				$pass = checkStatusData($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				
				if ($pass > 0) {
					// tidak dimasukkan karena ada yang double
				} else { 
					insert_log_geolocation($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
					insert_update_current_unit_position($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
					login_daily_absent($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
					insert_update_log_over_speed($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
					insert_update_geofences($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);  
					insert_update_idle_start($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);  
					check_if_one_cycle_time($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
					logout_daily_absent($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status, $hm);
				} 
				echo $id; 
		break;     
	case "saveunit": 
		$query = mysqli_query($con,"SELECT device_id FROM sync_unit WHERE device_id='".$_GET["device_id"]."'"); 
		if($query && mysqli_num_rows($query) > 0){
			$resp =  mysqli_query($con,"UPDATE sync_unit SET unit='".$_GET["unit"]."',date_last_update='".date("Y-m-d H:i:s")."' WHERE device_id='".$_GET["device_id"]."'");
		}else{
			$resp =  mysqli_query($con,"INSERT INTO sync_unit(`device_id`,`unit`,`active`) VALUES ('".$_GET["device_id"]."','".$_GET["unit"]."','1');");
		}
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
	case "servercek":
			echo '1';
		break;
	default :
		//echo 'sync';
		break;
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
	if ($status == "login") { 
		$date 		= new DateTime($datetime_tab); 
		$tgl  		= $date->format('Y-m-d');	
		$time_in  	= $date->format('H:i:s');
		$shift		= check_shift($date->format('H')) ;  
	  
		// check apakah sudah login dan belum logout
		$sql_check 	= "SELECT count(*) as total FROM daily_absent WHERE nip='".$nip."'  AND date <= '".$tgl."'  AND date_out IS NULL AND time_out IS NULL LIMIT 1";
		$rs_check 	= $db->GetRow($sql_check); 
		if($rs_check["total"] > 0) {  
			// sudah ada, tapi belum logout. diabaikan.
		} else { 
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
			$rs 	= $db->execute($SQL);
			
			# inisiasi start ct
			if ($rs) { 
				insert_log_cycle_time($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status);
			}  
		}   
	}
} 

function insert_update_current_unit_position($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="") {
	# unit position adalah posisi unit. Tidak masalah dia ganti-ganti tab. Asumsi tab adalah unit. Jadi harus uniq. 
	# disini kita bisa mengetahui kondisi paling akhir unit. (tab, nip, info geo, last update)
	$sql_check 	= "SELECT * FROM current_unit_position WHERE device_id='".$device_id."' GROUP BY unit_position_id LIMIT 1";
	$rs_check 	= $db->GetRow($sql_check); 
	if($rs_check["unit_position_id"] == "") { 
		$SQL = "SELECT * FROM current_unit_position WHERE unit_position_id = -1";
		$result = $db->execute($SQL);

		$data = array(
		    'device_id' 					=> $device_id,
		    'nip' 							=> $nip,
		    'unit' 							=> $unit, 
		    'time_stamp' 					=> $datetime_tab, 
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
		$rs 	= $db->execute($SQL); 
	} else {
		# jika ada data, pastikan dicheck data yang akan diupdate adalah data terbaru.
		if ($datetime_tab > $rs_check["date_last_update"]) {
			$SQL = "SELECT * FROM current_unit_position WHERE unit_position_id = ". $rs_check["unit_position_id"];
			$result = $db->execute($SQL);

			$data = array(
			    'device_id' 					=> $device_id,
			    'nip' 							=> $nip, 
			    'time_stamp' 					=> $datetime_tab, 
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
			$rs 	= $db->execute($SQL); 
		}  
	}
}



function insert_log_geolocation($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="", $hm= "") {
	# log geolocation adalah data mentah yang selalu ditangkap oleh server. Dari sinilah sumber data semuanya.  
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
	$rs 	= $db->execute($SQL); 
	return $rs; 
}


function insert_update_log_over_speed($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="") {
	# check apakah over speed. Jika ya, update total_over_speed di table daily_absent dan masukkan data ke log_over_speed.
	# nilai over speed ambil dari table sync_setting_tracker
	if ($status == "overspeed") {
		$sql_check 	= "SELECT max_speed FROM sync_setting_tracker";
		$rs_check 	= $db->GetRow($sql_check); 
		$max_speed 	= $rs_check["max_speed"];
		if ($speed >= $max_speed) { 
			# asumsi : operator tidak boleh nyambung shift. Jika nyambung shift, maka query ini salah.
			$tmp_time = new DateTime($datetime_tab);
			$date_absen = $tmp_time->format("Y-m-d");

			# operator harus belum logout 
			$sql_check 	= "SELECT * FROM daily_absent WHERE nip='".$nip."' AND unit = '".$unit."' AND date <='".$date_absen."'  AND date_out IS NULL AND time_out IS NULL LIMIT 1";
			$rs_check 	= $db->GetRow($sql_check); 
			if ($rs_check["daily_absent_id"] <> "") {
				$sql_update = "UPDATE daily_absent SET total_over_speed = total_over_speed + 1 WHERE daily_absent_id = '".$rs_check["daily_absent_id"]."'"; 
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
					$rs_1 			= $db->execute($sql_insert);
				} 
			} else {} # operator sudah logout semua
		} else {} # tidak overspeed
	}
}  
 
function insert_update_geofences($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="") {
	# check apakah masuk/logout area geofences. 
	# jika masuk/logout area cpp dan port, maka insert/update table log_coverage_in dan log_cycle_time
	
	$tmp_time 		= new DateTime($datetime_tab); 
	$time 			= $tmp_time->format("H:i:s");
	$curr_date 		= $tmp_time->format("Y-m-d");  
	$t 				= explode("-", $curr_date);
	$date_yesterday = date("Y-m-d",mktime(0, 0, 0, $t[1], $t[2] - 1, $t[0] ));

	#apakah ada data di log coverage tp belum keluar
	$sql_cov_in 	= "SELECT coverage_in_id FROM log_coverage_in WHERE nip = '".$nip."' AND unit = '".$unit."' AND  date_in <='".$curr_date."' AND date_out IS NULL AND time_out IS NULL LIMIT 1";
	$rs_cov_in		= $db->GetRow($sql_cov_in);
	$id_1 			= $rs_cov_in["coverage_in_id"];
	 
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

			if ($distance <= $radius_geofence) { 
				if ($id_1 > 0) {
					// dibiarkan
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
					$rs_1 				= $db->execute($sql_insert_cov_in); 
				}
				# update log_cycle_time jika di posisi cpp/port
				if ($station_id == "1" || $station_id == "2" ) {
					update_station_log_cycle_time($db, $nip , $unit , $datetime_tab , $station_id) ;
				} 
			}else {
				if ($id_1 > 0) { 
					$sql_update_cov_in 	= "UPDATE log_coverage_in SET time_out = '".$time."', date_out = '".$curr_date."' WHERE coverage_in_id = '".$id_1."'";
					$rs_update_cov_in	= $db->execute($sql_update_cov_in);
				}  
			}
		}
	} 
}  


function update_station_log_cycle_time($db, $nip="", $unit="", $datetime_tab="", $station_id="") {
	write_file("masuk ke function update_station_log_cycle_time");
	# Data inisiasi log_cycle_time harus udah ada dulu.
	# Jika ketemu cpp atau port, maka akan update cpp/portnya
	
	$update_data = false;
	$date 		= new DateTime($datetime_tab); 
	$curr_date  = $date->format('Y-m-d');	
	$curr_time  = $date->format('H:i:s');

	if ($station_id == 1) {
		$field = "time_stasiun_cpp";
	} else {
		$field = "time_stasiun_port";
	} 


	# check apakah hari kemarin sudah ada start yang belum selesai (nyeberang hari.)
	$sql_ct = "SELECT * FROM log_cycle_time WHERE nip = '".$nip."' AND unit = '".$unit."' AND DATE_FORMAT(datetime_start,'%Y-%m-%d')  <='".$curr_date."' AND datetime_end IS NULL LIMIT 1";
	$rs_ct	= $db->GetRow($sql_ct); 
	if ($rs_ct["cycle_time_id"] <> "") {
		if ($rs_ct[$field] == NULL) { 
			$sql_update_ct 	= "UPDATE log_cycle_time SET ".$field." = '".$datetime_tab."' WHERE cycle_time_id = '".$rs_ct["cycle_time_id"]."'";
			$rs_update_ct	= $db->execute($sql_update_ct);
		}
	}
}


function radius_geofence($db="") {

	#$sql_check 			= "SELECT radius_geofence FROM sync_setting_tracker";
	#$rs_check 			= $db->GetRow($sql_check); 
	#$radius_geofence 	= $rs_check["radius_geofence"];
	#return $radius_geofence;
	return 300;
}

function update_end_log_cycle_time($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="") {
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
  
  
	$filter_logout_or_not 	=	""; 
	$date 		= new DateTime($datetime_tab); 
	$curr_date  = $date->format('Y-m-d');	
	$curr_time  = $date->format('H:i:s');
  
 	if ($status == "logout") {
 		$filter_logout_or_not = "";
 	} else  {
 		$filter_logout_or_not = " AND time_stasiun_cpp IS NOT NULL AND time_stasiun_port IS NOT NULL ";
 	}

	# check apakah ada hari  sudah ada start yang belum selesai (nyeberang hari.)
	$sql_ct = "SELECT * FROM log_cycle_time WHERE nip = '".$nip."' AND unit = '".$unit."' AND  DATE_FORMAT(datetime_start,'%Y-%m-%d') <='".$curr_date."' ".$filter_logout_or_not." AND datetime_end IS NULL  LIMIT 1";
	$rs_ct	= $db->GetRow($sql_ct);
	if ($rs_ct["cycle_time_id"] <> "") {
		if (($status != "logout")) {
			# check triger end cycle time :
			# 1. status = logout
			# 2. kembali ke titik start
			# 3. Tidak pernah logout.
			  
			$radius_geofence 	= radius_geofence($db);
	 		$distance = 1000 * distance($latitude, $longitude, $rs_ct["latitude_start"], $rs_ct["longitude_start"], $unitnya="K");

	 		if ($distance <= $radius_geofence) { 
		 		# update end
				$sql_update_end_ct 		= "SELECT * FROM log_cycle_time WHERE cycle_time_id = ". $rs_ct["cycle_time_id"];
				$result_update_end_ct	= $db->execute($sql_update_end_ct);

				$data = array( 
				    'latitude_end' 		=> $latitude, 
				    'longitude_end' 	=> $longitude,
				    'datetime_end'		=> $datetime_tab
				); 

				$sql_update 	= $db->getUpdateSql($result_update_end_ct,$data);  
				$rs_update 		= $db->execute($sql_update);

				# update FPI
				update_fpi($db, $rs_ct["cycle_time_id"]);

				# update total_cycle_time di table daily_absent
				$daily_absent_id = get_daily_absent_id($db, $nip, $time_stamp_gps);
				$sql_update_ct_daily_absent = "UPDATE daily_absent SET total_cycle_time = total_cycle_time+1 WHERE daily_absent_id = ". $daily_absent_id;
				$db->execute($sql_update_ct_daily_absent);

				# inisiasi start ct
				insert_log_cycle_time($db, $nip, $unit, $device_id, $latitude, $longitude, $altitude, $accuracy, $altitude_accuracy, $heading, $speed, $time_stamp_gps, $datetime_tab, $status);
			} else {
				# belum kembali ke titik awal
			}
		} else if (($status == "logout")) {
			# update date end cycle time  
			$sql_update_end_ct 		= "SELECT * FROM log_cycle_time WHERE cycle_time_id = ". $rs_ct["cycle_time_id"]; 
			$result_update_end_ct	= $db->execute($sql_update_end_ct);

			$data = array( 
			    'latitude_end' 		=> $latitude, 
			    'longitude_end' 	=> $longitude,
			    'datetime_end'		=> $datetime_tab
			); 

			$sql_update 	= $db->getUpdateSql($result_update_end_ct,$data);  
			$rs_update 		= $db->execute($sql_update);

			# update FPI
			update_fpi($db, $rs_ct["cycle_time_id"]);

			# hitung jarak
			$jarak_tempuh = hitung_persentase_ritase($rs_ct,$db, $nip, $rs_ct["latitude_start"] , $rs_ct["longitude_start"], $latitude, $longitude);


			# update total_cycle_time daily_absent
			$daily_absent_id = get_daily_absent_id($db, $nip, $time_stamp_gps);
			$sql_update_ct_daily_absent = "UPDATE daily_absent SET total_cycle_time = (total_cycle_time + ".$jarak_tempuh.") WHERE daily_absent_id = ". $daily_absent_id;
			$db->execute($sql_update_ct_daily_absent);
		} 
	} else {
		// belum 1 ct
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
		$tmp = distance($latitude_start, $longitude_start, $latitude_end, $longitude_end, $unitnya="K"); 
		$jarak_tempuh =  $total_jarak - $tmp;
	} else if(($rs_ct["time_stasiun_cpp"] != NULL) && ($rs_ct["time_stasiun_port"] == NULL)){ 
		$jarak_tempuh = distance($latitude_start, $longitude_start, $lat_cpp, $lon_cpp, $unitnya="K"); 
		$jarak_tempuh += distance($latitude_end, $longitude_end, $lat_cpp, $lon_cpp, $unitnya="K"); 
	} else if(($rs_ct["time_stasiun_cpp"] == NULL) && ($rs_ct["time_stasiun_port"] != NULL)){ 
		$jarak_tempuh = distance($latitude_start, $longitude_start, $lat_port, $lon_port, $unitnya="K"); 
		$jarak_tempuh += distance($latitude_end, $longitude_end, $lat_port, $lon_port, $unitnya="K");  
	} else if(($rs_ct["time_stasiun_cpp"] == NULL) && ($rs_ct["time_stasiun_port"] == NULL)){ 
		$jarak_tempuh = distance($latitude_start, $longitude_start, $latitude_end, $longitude_end, $unitnya="K"); 
	}
	$jarak = $jarak_tempuh / $total_jarak; 
	return number_format($jarak, 2);
}

function get_daily_absent_id($db, $nip, $time_stamp_gps) {
	// syarat : absen nya belum pernah logout.

	$tmp_time 	= new DateTime($time_stamp_gps);  
	$curr_date 	= $tmp_time->format("Y-m-d"); 

	# check apakah operator login di shift 2 (hari kemarin) dan belum logout
	$sql_1 	= "SELECT * FROM daily_absent WHERE nip='".$nip."'  AND date <= '".$curr_date."' AND date_out IS NULL AND time_out IS NULL LIMIT 1";
	$rs_1 	= $db->GetRow($sql_1); 
	return $rs_1["daily_absent_id"]; 
}

function insert_update_idle_start($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="") {
	# Logic idle start : 
	# 1. Mencatat tgl jam mulai idle
	# 2. Mencatat tgl jam mulai jalan lagi
	# 3. Mengupdate total_idle di log_cycle_time. syaratnya, datetime_end masih kosong

	# Jadi, kita harus mencari dia ada di CT yang mana agar mempermudah perhitungan.
	# 1. date_time idle > date_time_start ct
	# 2. date_time_end ct IS NULL 
	$date 		= new DateTime($datetime_tab); 
	$curr_date  = $date->format('Y-m-d');	
	$curr_time  = $date->format('H:i:s');


	if ($status == "idle" || $status =="start") { 
		# cari cycle time id 
		$sql_ct = "SELECT * FROM log_cycle_time WHERE nip = '".$nip."' AND unit = '".$unit."' AND DATE_FORMAT(datetime_start,'%Y-%m-%d') <= '". $curr_date ."'  AND datetime_end IS NULL LIMIT 1";
		$rs_ct	= $db->GetRow($sql_ct);
		$ct_id 	= $rs_ct["cycle_time_id"];

		# check apakah sudah ada data start idle di log_login_jam_dunia  
		$sql_check_jdunia = "SELECT * FROM log_login_jam_dunia WHERE nip = '".$nip."' AND unit = '".$unit."' AND DATE_FORMAT(date_stop,'%Y-%m-%d')  <= '".$curr_date."' AND date_start IS NULL LIMIT 1";
		$rs_check_jdunia = $db->GetRow($sql_check_jdunia);
		if ($rs_check_jdunia["log_login_jam_dunia_id"] <> "") {
			# check status, jika idle lewat. Jika start, update
			if ($status == "start") { 
				$sql_update_jdunia 	= "UPDATE log_login_jam_dunia SET date_start = '".$datetime_tab."' WHERE log_login_jam_dunia_id = '".$rs_check_jdunia["log_login_jam_dunia_id"]."'";
				$rs_update_jdunia 	= $db->Execute($sql_update_jdunia); 

				# Update total_idle
				if ($ct_id <> "") {
					$sql_update_idle_ct = "UPDATE log_cycle_time SET total_idle = total_idle + (SELECT TIME_TO_SEC(TIMEDIFF(date_start, date_stop)) FROM log_login_jam_dunia WHERE log_login_jam_dunia_id=".$rs_check_jdunia["log_login_jam_dunia_id"].") WHERE cycle_time_id = ".$ct_id;
					$rs_update_idle_ct 	= $db->Execute($sql_update_idle_ct);
				} 
			} else { }
		} else {
			# jika data mulai idle belum ada, maka add
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
				$rs_jd 		= $db->execute($sql_jd); 
			} else { }
		}	 
	}  
}


function logout_daily_absent($db, $nip = "", $unit = "", $device_id = "", $latitude = "", $longitude = "", $altitude = "", $accuracy = "", $altitude_accuracy = "", $heading = "", $speed = "", $time_stamp_gps = "", $datetime_tab = "", $status = "", $hm = "") {
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

	 
		$sql_check 	= "SELECT * FROM daily_absent WHERE nip='".$nip."'  AND date <= '".$curr_date."' AND date_out IS NULL AND time_out IS NULL LIMIT 1";
		$rs_check 	= $db->GetRow($sql_check); 
		if($rs_check["daily_absent_id"] <> "") { 
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
			$rs 			= $db->execute($sql_update_da);

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
	$date 		= new DateTime($datetime_tab); 
	$curr_date  = $date->format('Y-m-d');	
	$curr_time  = $date->format('H:i:s');
	 
	# check apakah hari ini ada yang belum start.
	$sql_cov_in = "SELECT * FROM log_cycle_time WHERE nip = '".$nip."' AND unit = '".$unit."' AND  DATE_FORMAT(datetime_start,'%Y-%m-%d') <='".$curr_date."' AND datetime_end IS NULL ";
	$rs_cov_in	= $db->GetRow($sql_cov_in);
	if ($rs_cov_in["cycle_time_id"] <> "") {
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
		$rs_1 			= $db->execute($sql_insert_ct); 
	} 
}
function write_file($text) {
	/*
	$myfile = fopen("log_sync.txt", "a+") or die("Unable to open file!"); 
	fwrite($myfile, "(".date("Y-m-d H:i:s").") ".$text."\n"); 
	fclose($myfile);
	*/
}


function checkStatusData($db, $nip="", $unit="", $device_id="", $latitude="", $longitude="", $altitude="", $accuracy="", $altitude_accuracy="", $heading="", $speed="", $time_stamp_gps="", $datetime_tab="", $status="", $hm= "") {
	$SQL = "SELECT count(*) as total FROM log_geolocation WHERE device_id = '".$device_id."' AND nip = '".$nip."'  AND unit = '".$unit."'  AND time_stamp = '".$time_stamp_gps."'  AND latitude = '".$latitude."' AND longitude = '".$longitude."' AND status = '".$status."' ";
	#echo $SQL."<BR>";
	$result = $db->GetRow($SQL);
	return $result["total"]; 
}
/*****************************************************************************/
/************************* END FUNCTION SCRIPT DWI ***************************/
/*****************************************************************************/ 
?>
