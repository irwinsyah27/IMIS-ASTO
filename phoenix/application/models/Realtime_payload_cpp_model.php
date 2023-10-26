<?php
class Realtime_payload_cpp_model extends MY_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAllDataPerDay($date = "") {
		if ($date =="") $date = date("Y-m-d");

		$sql = "SELECT
					a.unit,
					b.nama as nama_operator, 
					e.keterangan as egi,
					d.master_equipment_id ,
					a.date as tgl_payload,
					a.shift as shift
			FROM
	   	 		daily_absent a 
	   	 	LEFT JOIN 
	   	 		master_employee b 
	   	 	ON
	   	 		a.nip = b.nrp 
	   	 	LEFT JOIN 
	   	 		master_equipment d 
	   	 	ON 	
	   	 		a.unit = d.new_eq_num 
	   	 	LEFT JOIN 
	   	 		master_egi e 
	   	 	ON 
	   	 		d.master_egi_id = e.master_egi_id
	   	 	WHERE
	   	 		a.date = '".$date."' 
	   		ORDER BY
	   			b.nama ASC
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function getTonnageDataPerDay($equipment_id, $date, $shift) { 
		$sql = "SELECT
					a.netto
			FROM
	   	 		weigher a  
	   	 	WHERE
	   	 		a.date_weigher = '".$date."'
	   	 		AND a.equipment_id = '".$equipment_id."' 
	   	 		AND a.station_id = '2' AND 
	   	 		a.shift = ".$shift."
	   		ORDER BY
	   			a.date_insert ASC
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function getAllDataPerDayFromTimbangan($date = "" , $shift = "") {
		if ($date =="") $date = date("Y-m-d");
		if ($shift =="") $shift = 1; 

		$sql = "SELECT
					d.new_eq_num as unit, 
					e.keterangan as egi,
					d.master_equipment_id ,
					a.date_weigher as tgl_payload,
					a.time_weigher as time_weigher,
					a.shift as shift,
					a.date_insert,
					a.netto
			FROM
	   	 		weigher a  
	   	 	LEFT JOIN 
	   	 		master_equipment d 
	   	 	ON 	
	   	 		a.equipment_id = d.master_equipment_id 
	   	 	LEFT JOIN 
	   	 		master_egi e 
	   	 	ON 
	   	 		d.master_egi_id = e.master_egi_id
	   	 	WHERE
	   	 		a.date_weigher = '".$date."' 
	   	 		AND a.station_id = '1'  # id 1 = CPP
	   	 		AND  a.shift = ".$shift."
	   		ORDER BY
	   			d.new_eq_num, a.date_insert ASC
		";
		#echo $sql; exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
}