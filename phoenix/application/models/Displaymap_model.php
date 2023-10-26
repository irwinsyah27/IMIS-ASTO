<?php
class Displaymap_model extends MY_MODEL { 
	function __construct(){
		parent::__construct();
	}
	public function getallroutesformap() {
		$sql = "
				SELECT 
					DISTINCT(a.unit),  
					a.latitude,
					a.longitude,
					a.speed,
					a.heading as direction, 
					a.unit,
					a.nip,
					a.accuracy,
					a.time_stamp  as gpsTime ,
					b.nama as userName
				FROM 
					current_unit_position a
				LEFT JOIN 
					master_employee b
				ON 
					nip = b.nrp
				WHERE 
					DATE_FORMAT(time_stamp,'%Y-%m-%d') = '".date("Y-m-d")."' 
			GROUP BY unit
				"; 
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) {
			$stmt = $res->result_array();
			$json = '{ "locations": [';
			
			foreach ($stmt as $r) {
				$speed = $r["speed"] / 0.62137;
				$json .= "{";
				$json .= "\"latitude\":\"".$r["latitude"]."\",";
				$json .= "\"longitude\":\"".$r["longitude"]."\",";
				$json .= "\"speed\":\"".$speed."\",";
				$json .= "\"direction\":\"".$r["direction"]."\","; 
				$json .= "\"unit\":\"".$r["unit"]."\",";
				$json .= "\"nip\":\"".$r["nip"]."\",";
				$json .= "\"accuracy\":\"".$r["accuracy"]."\",";
				$json .= "\"userName\":\"".$r["userName"]."\",";
				$json .= "\"gpsTime\":\"".$r["gpsTime"]."\"";
				$json .= "}";
				$json .= ',';
			}
	
			$json = rtrim($json, ",");
			$json .= '] }';
			return $json;
		} else {
			return null;
		}
	}

	public function getroutes() { 
		$sql = "SELECT
					distinct unit 
			FROM
	   	 		 current_unit_position 
	   	 	ORDER BY 
	   	 		unit
		";
		//echo $sql;
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) {
			$stmt = $res->result_array(); 
			$json = '{ "sessionID": ['; 
			foreach ($stmt as $r) {
				$json .= "{";
				$json .= "\"unit\":\"".$r["unit"]."\""; 
				$json .= "}";
				$json .= ',';
			}
	
			$json = rtrim($json, ","); 
			$json .= '] }';
			return $json;
		} else {
			return null;
		}
	} 

	public function getdataunit() { 
		$sql = "SELECT
					distinct unit
			FROM
	   	 		 current_unit_position   
		"; 
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

	public function getrouteformap($unit, $start, $end, $filter) {
		$whr = "";
		if($filter == 1){
			// 
			$whr = " AND a.speed >= 57 "; 
		}elseif($filter == 2){
			$whr = " AND a.speed < 57 ";
		}
		$sql = "
				SELECT 
					a.unit, 
					a.time_stamp,
					a.latitude,
					a.longitude,
					a.speed,
					a.heading as direction,
					a.time_stamp,
					a.unit,
					a.nip,
					a.accuracy,
					a.time_stamp_gps as gpsTime ,
					b.nama as userName
				FROM 
					log_geolocation a 
				LEFT JOIN 
					master_employee b
				ON 
					nip = b.nrp
				WHERE a.time_stamp >='".$start."' AND a.time_stamp < '".$end."'  AND  a.unit = '".$unit."' ".$whr."
			ORDER BY  a.time_stamp
				"; 
 		#echo $sql;exit;
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) {
			$stmt = $res->result_array();
			$json = '{ "locations": [';
	
			foreach ($stmt as $r) {
				//$speed = $r["speed"] / 0.62137;
				$speed = $r["speed"];
				
				$json .= "{";
				$json .= "\"latitude\":\"".$r["latitude"]."\",";
				$json .= "\"longitude\":\"".$r["longitude"]."\",";
				$json .= "\"speed\":\"".$speed."\",";
				$json .= "\"direction\":\"".$r["direction"]."\",";
				$json .= "\"time_stamp\":\"".$r["time_stamp"]."\",";
				$json .= "\"gpsTime\":\"".$r["gpsTime"]."\",";
				$json .= "\"unit\":\"".$r["unit"]."\",";
				$json .= "\"nip\":\"".$r["nip"]."\",";
				$json .= "\"userName\":\"".$r["userName"]."\",";
				$json .= "\"accuracy\":\"".$r["accuracy"]."\"";
				$json .= "}";
				$json .= ',';
			}
	
			$json = rtrim($json, ",");
			$json .= '] }';
			return $json;
		} else {
			return NULL;
		}
	}
	
}