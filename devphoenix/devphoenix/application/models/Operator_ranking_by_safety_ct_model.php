<?php
class Operator_ranking_by_safety_ct_model extends MY_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAllDataPerDay($date = "") {
		if ($date =="") $date = date("Y-m-d");

		$sql = "SELECT
					a.nip,
					a.unit, 
					b.nama as nama_operator, 
					e.keterangan as egi,
					d.master_equipment_id ,
					a.date as tgl_payload,
					a.time_start_position_station,
					a.time_stop_position_station,
					a.shift
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
	   			b.nama
		"; 
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function getCTDataPerDay($nip, $unit, $datetime_in, $datetime_out) { 
		$sql = "SELECT
					a.*, 
					case when DATE_FORMAT(a.datetime_end,' %d %b %Y') IS NOT NULL then TIMESTAMPDIFF(MINUTE,a.datetime_start,a.datetime_end) div 60 else '' end as jam_pengerjaan,
					case when DATE_FORMAT(a.datetime_end,' %d %b %Y') IS NOT NULL then MOD(TIMESTAMPDIFF(MINUTE,a.datetime_start,a.datetime_end) , 60) else '' end as menit_pengerjaan,
					SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(a.datetime_end,a.datetime_start)) - total_idle) as durasi
			FROM
	   	 		log_cycle_time a  
	   	 	WHERE
	   	 		a.datetime_start BETWEEN '".$datetime_in."' AND '".$datetime_out."' 
	   	 		AND a.unit = '".$unit."'  
	   	 		AND a.nip = '".$nip."'
	   		ORDER BY
	   			a.datetime_start ASC
		";
		# echo $sql."<br><br>";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
}