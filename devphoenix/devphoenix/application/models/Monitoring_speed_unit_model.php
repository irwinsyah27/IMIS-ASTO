<?php
class Monitoring_speed_unit_model extends MY_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAllDataPerDay($date = "") {
		if ($date =="") $date = date("Y-m-d");

		$sql = "SELECT * FROM sync_setting_tracker";
		$tmp_rs = $this->db->query($sql);
		$tmp_r = $tmp_rs->row_array();
		$over_speed= $tmp_r["max_speed"];

		$sql = "SELECT
					a.*,
					b.nama as nama_operator,
					a.unit, 
					a.shift
			FROM
	   	 		daily_absent a 
	   	 	INNER JOIN 
	   	 		master_employee b 
	   	 	ON
	   	 		a.nip = b.nrp
	   	 	WHERE
	   	 		a.date = '".$date."'
	   		ORDER BY
	   			a.total_over_speed DESC, b.nama
		"; 

		//echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		$i = 0;
		if (count($rs) > 0) {
			FOREACH ($rs AS $r) {
				$nip 				= $r["nip"];
				$nama_operator 		= $r["nama_operator"];
				$unit 				= $r["unit"];
				$shift 				= $r["shift"];
				$total_over_speed 	= $r["total_over_speed"];

				$tmp = $this->getAverageAndOverSpeed($nip, $unit, $date, $shift);
				$average 			= $tmp["average"];
				$max_speed 	= $tmp["max_speed"];

				$data[$i]["nip"] 				= $nip;
				$data[$i]["nama_operator"] 		= $nama_operator ." (S".$shift.")";
				$data[$i]["unit"] 				= $unit;
				$data[$i]["shift"] 				= $shift;
	  			$data[$i]["average"] 			= $average;
	  			$data[$i]["total_over_speed"] 	= $total_over_speed;
				$data[$i]["max_speed"] = $max_speed; 
	  			$i += 1;
			}
		}
		#exit;
		return $data;
	} 
	public function getAverageAndOverSpeed($nip, $unit, $date, $shift) {
		if ($date =="") $date = date("Y-m-d");
		$date_in = $date;

		$tmp = explode("-", $date);
		$date_out = date("Y-m-d",mktime(0, 0, 0, $tmp[1], $tmp[2] + 1, $tmp[0] ));  

		$sql = "SELECT
					FORMAT((sum(CAST(speed AS DECIMAL(10,2))) / count(*)),2) as average,
					max(CAST(speed AS DECIMAL(10,2))) as max_speed
			FROM
	   	 		log_geolocation 
	   	 	WHERE
	   	 		CAST(speed AS DECIMAL(10,2)) > 0
	   	 		AND nip = '".$nip."'
	   	 		AND unit = '".$unit."' ";
	   	if ($shift == 1) {
	   		$sql .= "
	   	 		AND DATE_FORMAT(time_stamp, '%Y-%m-%d') = '".$date."'";
	   	} else if ($shift == 2) {
	   		$sql .= "
	   	 		AND DATE_FORMAT(time_stamp, '%Y-%m-%d') BETWEEN '".$date_in ."' AND '".$date_out."'";
	   	}
	   	 $sql .= " GROUP BY
	   			nip, unit
		";
		//echo $sql."<br><br>";;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
}