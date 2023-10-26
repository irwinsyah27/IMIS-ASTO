<?php
class Test_update_track_model extends CI_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function update_table_current_location() {
		$sql = "SELECT
					*
			FROM
	   	 		log_geolocation_sample
	   	 	WHERE 
	   	 		status = 0
	   		ORDER BY
	   			geolocation_id
	   		LIMIT 1
		";
		$res = $this->db->query($sql);
		$rs = $res->row_array();
  
 		if (count($rs) > 0) {
			$data = array( 
				'latitude' 	=> $rs["latitude"],  
				'longitude' => $rs["longitude"],  
				'speed' 	=> $rs["speed"]
			);
			$where = "unit_position_id = 1";
		
			$this->db->update('current_unit_position', $data, $where); 

			if ($rs["speed"] > 50) {
				$sql_update_over_speed = "UPDATE daily_absent SET total_over_speed = total_over_speed+1 WHERE daily_absent_id = 1";
				$res = $this->db->query($sql_update_over_speed);
			} 

			$data_log = array( 
				'status' 	=> 1
			);
			$where = "geolocation_id = ".$rs["geolocation_id"];
		
			$this->db->update('log_geolocation_sample', $data_log, $where); 
 		} else {
 			$sql_update_over_speed = "UPDATE daily_absent SET total_over_speed = 0 WHERE daily_absent_id = 1";
			$res = $this->db->query($sql_update_over_speed);
 			$sql_update_over_speed = "UPDATE current_unit_position SET speed = 0, latitude='', longitude='' WHERE unit_position_id = 1";
			$res = $this->db->query($sql_update_over_speed);

 			$sql_update_over_speed = "UPDATE log_geolocation_sample SET status = 0 ";
			$res = $this->db->query($sql_update_over_speed); 

			# UPDATE daily_absent SET total_over_speed = 0 WHERE daily_absent_id = 1;UPDATE log_geolocation_sample SET status = 0;UPDATE current_unit_position SET speed = 0, latitude='', longitude='' WHERE unit_position_id = 1;
 		}
	} 
}