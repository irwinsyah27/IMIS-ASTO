<?php
class Estimate_unit_position_model extends MY_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAllDataPerDay($date = "") {
		if ($date =="") $date = date("Y-m-d");

		$sql = "SELECT
					a.*,
					b.name as nama_operator,
					c.unit,
					c.speed,
					c.latitude,
					c.longitude
			FROM
	   	 		daily_absent a 
	   	 	INNER JOIN 
	   	 		sync_operator b 
	   	 	ON
	   	 		a.nip = b.nip
	   	 	INNER JOIN 
	   	 		current_unit_position c
	   	 	ON
	   	 		a.nip = c.nip
	   	 	WHERE
	   	 		a.date = '".$date."'
	   	 		AND DATE_FORMAT(c.time_stamp, '%Y-%m-%d') = '".$date."'
	   		ORDER BY
	   			c.unit
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function getStatusCycleTime($date, $unit) { 
		$sql = "SELECT
					*
			FROM
	   	 		log_cycle_time a  
	   	 	WHERE
	   	 		a.unit = '".$unit."' 
	   	 		AND DATE_FORMAT(a.datetime_start, '%Y-%m-%d') = '".$date."'
	   		ORDER BY
	   			a.cycle_time_id DESC 
	   		LIMIT 1
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
}