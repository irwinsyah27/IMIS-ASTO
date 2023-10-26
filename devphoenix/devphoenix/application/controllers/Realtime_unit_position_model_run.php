<?php
class Realtime_unit_position_model_run extends MY_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	
	public function getLokasi($date) {
		$sql = "
				SELECT  
					latitude, longitude
				FROM 
					current_unit_position_run a
				WHERE
					DATE(time_stamp) = '".$date."'
				";
				//echo $sql; limit 1
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) {
			$stmt = $res->result_array();
			return $stmt;
		} else {
			return null;
		}
	}
	public function getLokasiTesting() {
		$sql = "
				SELECT  
					DISTINCT latitude, longitude, time_stamp
				FROM 
					log_geolocation 
				ORDER BY 
					time_stamp ASC
				";
				//echo $sql; limit 1
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) {
			$stmt = $res->result_array();
			return $stmt;
		} else {
			return null;
		}
	}
	public function get_tracker_test() {
		$sql = "
				SELECT 
					
					DISTINCT(a.sessionId), 
					 a.gpsTime as max_gpsTime,
					CAST(a.latitude AS CHAR) as latitude,
					CAST(a.longitude AS CHAR) as longitude, 
					CAST(a.speed AS CHAR) as speed, 
					CAST(a.direction AS CHAR) as direction, 
					 CAST(a.distance AS CHAR) as distance, 
					  a.locationMethod, 
					  DATE_FORMAT(a.gpsTime, '%b %e %Y %h:%i%p') as gpsTime, 
					  a.userName, 
					  a.phoneNumber,
					  CAST(a.sessionID AS CHAR) as sessionID, 
					  CAST(a.accuracy AS CHAR) as accuracy, 
					  a.extraInfo,
					  a.GPSLocationID
				FROM 
					gpslocations a 
				WHERE 
					a.sessionID != '0' 
					&& CHAR_LENGTH(a.sessionID) != 0 
					&& a.gpstime != '0000-00-00 00:00:00' 
				";
				//echo $sql; limit 1
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) {
			$stmt = $res->result_array();
			return $stmt;
		} else {
			return null;
		}
	}

	public function get_tracker() {
		$sql = "
				SELECT 
					
					DISTINCT(a.sessionId), 
					 a.gpsTime as max_gpsTime,
					CAST(a.latitude AS CHAR) as latitude,
					CAST(a.longitude AS CHAR) as longitude, 
					a.speed as speed, 
					CAST(a.direction AS CHAR) as direction, 
					 CAST(a.distance AS CHAR) as distance, 
					  a.locationMethod, 
					  DATE_FORMAT(a.gpsTime, '%b %e %Y %h:%i%p') as gpsTime, 
					  a.userName, 
					  a.phoneNumber,
					  CAST(a.sessionID AS CHAR) as sessionID, 
					  CAST(a.accuracy AS CHAR) as accuracy, 
					  a.extraInfo,
					  a.GPSLocationID
				FROM 
					gpslocations a 
				WHERE 
					a.sessionID != '0' 
					&& CHAR_LENGTH(a.sessionID) != 0 
					&& a.gpstime != '0000-00-00 00:00:00' 
				";
				//echo $sql; limit 1
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) {
			$stmt = $res->result_array();
			return $stmt;
		} else {
			return null;
		}
	}

	public function get_tracker_uniq() {
		$date = date("Y-m-d");
					// CAST(a.distance AS CHAR) as distance, 
		$sql = "
				SELECT  
					DISTINCT(a.unit), 
					MAX(a.time_stamp) max_gpsTime,
					CAST(a.latitude AS CHAR) as latitude,
					CAST(a.longitude AS CHAR) as longitude, 
					CAST(a.speed AS CHAR) as speed, 
					CAST(a.heading AS CHAR) as direction, 
				  a.speed as locationMethod, 
				  DATE_FORMAT(a.time_stamp, '%b %e %Y %h:%i%p') as gpsTime, 
				  a.unit as userName, 
				  a.nip as phoneNumber,
				  CAST(a.unit_position_id AS CHAR) as sessionID, 
				  CAST(a.accuracy AS CHAR) as accuracy, 
				  a.nip as extraInfo,
				  a.unit_position_id as GPSLocationID
				FROM 
					current_unit_position_run a 
				INNER JOIN 
					daily_absent b
				ON
					a.nip = b.nip  
				WHERE  
					 DATE(a.time_stamp) = '".$date."' 
				GROUP BY a.unit_position_id
				";
	
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) {
			$stmt = $res->result_array();
			return $stmt;
		} else {
			return null;
		}
	}
	public function getdataunit($date) {
		$sql = "SELECT
					distinct unit
			FROM
	   	 		 current_unit_position
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}
	public function getalldeviceonlinetoday() {  
		$sql = "SELECT 
					DISTINCT(sessionId), 
					MAX(gpsTime) gpsTime, 
  				  	CONCAT('{ \"latitude\":\"', 
						CAST(latitude AS CHAR),
						'\", \"longitude\":\"', 
						CAST(longitude AS CHAR), 
						'\", \"speed\":\"', 
						CAST(speed AS CHAR), 
						'\", \"direction\":\"', 
						CAST(direction AS CHAR), 
						'\", \"distance\":\"', 
						CAST(distance AS CHAR), 
						'\", \"locationMethod\":\"', 
						locationMethod, 
						'\",\"gpsTime\":\"', 
						DATE_FORMAT(gpsTime, '%b %e %Y %h:%i%p'), 
						'\", \"userName\":\"', 
						userName, 
						'\",\"phoneNumber\":\"', 
						phoneNumber, 
						'\", \"sessionID\":\"', 
						CAST(sessionID AS CHAR), 
						'\", \"accuracy\":\"', 
						CAST(accuracy AS CHAR), 
						'\", \"extraInfo\":\"',
						extraInfo, '\" }') json
  					FROM 
						gpslocations
  					WHERE 
						sessionID != '0' 
						&& CHAR_LENGTH(sessionID) != 0 
						&& DATE_FORMAT( gpstime, '%Y-%m-%d' ) = DATE_FORMAT( NOW( ) , '%Y-%m-%d' ) 
  					GROUP BY 
					sessionID"; 
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) { 
			$stmt = $res->result_array();
		    $json = '{ "locations": [';

		    foreach ($stmt as $row) {
		        $json .= $row['json'];
		        $json .= ',';
		    }

		    $json = rtrim($json, ",");
		    $json .= '] }';
			return $json;
		} else {
			return null;
		}  
	}  
	/*
	public function getallroutesformap() {  
		$sql = 'CALL prcGetAllRoutesForMap();';
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) { 
			$stmt = $res->result_array();
		    $json = '{ "locations": [';

		    foreach ($stmt as $row) {
		        $json .= $row['json'];
		        $json .= ',';
		    }

		    $json = rtrim($json, ",");
		    $json .= '] }';
			return $json;
		} else {
			return null;
		}  
	}  
	*/

	public function getallroutesformap() {
		$sql = "
				SELECT 
					DISTINCT(sessionId), 
					MAX(gpsTime) gpsTime,
					CONCAT('{ 
								\"latitude\":\"', 
								CAST(latitude AS CHAR),
								'\", \"longitude\":\"',CAST(longitude AS CHAR), 
								'\", \"speed\":\"', CAST(speed AS CHAR), '\", 
								\"direction\":\"',  CAST(direction AS CHAR), 
								'\",\"distance\":\"', CAST(distance AS CHAR), '\", 
								\"locationMethod\":\"', locationMethod, '\", 
								\"gpsTime\":\"', DATE_FORMAT(gpsTime, '%b %e %Y %h:%i%p'), '\", 
								\"userName\":\"', userName, '\", 
								\"phoneNumber\":\"',  phoneNumber, '\", 
								\"sessionID\":\"',  CAST(sessionID AS CHAR), 
								'\", \"accuracy\":\"',  CAST(accuracy AS CHAR), 
								'\", \"extraInfo\":\"', extraInfo, '\" 
							}') json
				FROM 
					gpslocations
				WHERE 
					sessionID != '0' 
					&& CHAR_LENGTH(sessionID) != 0 
					&& gpstime != '0000-00-00 00:00:00'
			GROUP BY sessionID
				";
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) {
			$stmt = $res->result_array();
			$json = '{ "locations": [';
	
			foreach ($stmt as $row) {
				$json .= $row['json'];
				$json .= ',';
			}
	
			$json = rtrim($json, ",");
			$json .= '] }';
			return $json;
		} else {
			return null;
		}
	}
	
	public function getrouteformap($id = "") {  
    
	    $sessionid   = isset($id) ? $id : '0'; 
		
		// $sql = 'CALL prcGetRouteForMap(\''.$sessionid.'\');';
		$sql = "
				SELECT 
					CONCAT('{ 
							\"latitude\":\"', CAST(latitude AS CHAR),'\", 
							\"longitude\":\"', CAST(longitude AS CHAR), '\",
							\"speed\":\"', CAST(speed AS CHAR), '\", 
							\"direction\":\"', CAST(direction AS CHAR), '\", 
							\"distance\":\"', CAST(distance AS CHAR), '\", 
							\"locationMethod\":\"', locationMethod, '\", 
							\"gpsTime\":\"', DATE_FORMAT(gpsTime, '%b %e %Y %h:%i%p'), '\", 
							\"userName\":\"', userName, '\", 
							\"phoneNumber\":\"', phoneNumber, '\", 
							\"sessionID\":\"', CAST(sessionID AS CHAR), '\", 
							\"accuracy\":\"', CAST(accuracy AS CHAR), '\", 
							\"extraInfo\":\"', extraInfo, '\" 
						}') json 
				FROM gpslocations
  				WHERE sessionID = '".$sessionid."'
  				ORDER BY lastupdate
				";
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) { 
			$stmt = $res->result_array();
		    $json = '{ "locations": [';

		    foreach ($stmt as $row) {
		        $json .= $row['json'];
		        $json .= ',';
		    }

		    $json = rtrim($json, ",");
		    $json .= '] }';
			return $json;
		} else {
			return null;
		}  
	}  
	public function deleteroute($id = "") {   
	    $sessionid   = isset($id) ? $id : '0';  
		
		$sql = 'CALL prcDeleteRoute(\''.$sessionid.'\');';  
		$res = $this->db->query($sql); 
	}  
	public function updatelocation($params) {   
		/*
		$sql = "INSERT INTO gpslocations (latitude, longitude, speed, direction, distance, gpsTime, locationMethod, userName, phoneNumber,  sessionID, accuracy, extraInfo, eventType)
   VALUES ('".$params['latitude']."', '".$params['longitude']."', '".$params['speed']."', '".$params['direction']."', '".$params['distance']."', '".$params['date']."', '".$params['locationmethod']."', '".$params['username']."', '".$params['phonenumber']."', '".$params['sessionid']."', '".$params['accuracy']."', '".$params['extrainfo']."', '".$params['eventtype']."')";
		#echo $sql;
		$res = $this->db->query($sql);
		*/

		$tmp_sql = "SELECT count(*) as total FROM gpslocations WHERE username = '".$params['username']."' AND gpsTime like '".$params['_date']."%';";
		#echo $tmp_sql;exit;
		$tmp_rs = $this->db->query($tmp_sql);
		$temp = $tmp_rs->row_array();
		if ($temp["total"] == 0) {
			
		$data = array(
			'latitude' 				=> $params['latitude'],  
			'longitude' 			=> $params['longitude'],  
			'speed' 				=> $params['speed'],  
			'direction' 			=> $params['direction'],  
			'distance' 				=> $params['distance'],  
			'gpsTime' 				=> $params['_date'],  
			'locationmethod' 		=> $params['locationmethod'],  
			'username' 				=> $params['username'],  
			'phonenumber' 			=> $params['phonenumber'],  
			'sessionid' 			=> $params['sessionid'],  
			'accuracy' 				=> $params['accuracy'],  
			'extrainfo' 			=> $params['extrainfo'],  
			'eventtype' 			=> $params['eventtype']
		);
	
		$rs = $this->db->insert('gpslocations', $data); 
		#return $this->db->last_query();
		
		}
		
		
	}  
	public function getroutes() {  
		$sql = 'CALL prcGetRoutes();';
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) { 
			$stmt = $res->result_array();
		    $json = '{ "routes": [';

		    foreach ($stmt as $row) {
		        $json .= $row['json'];
		        $json .= ',';
		    }

		    $json = rtrim($json, ",");
		    $json .= '] }';
			return $json;
		} else {
			return null;
		}   
	}  
}