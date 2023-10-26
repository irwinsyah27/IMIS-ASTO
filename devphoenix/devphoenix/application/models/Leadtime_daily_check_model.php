<?php
class Leadtime_daily_check_model extends MY_MODEL { 
	function __construct(){
		parent::__construct();
	}
 /*
	public function getAllData() {
		$sql = "SELECT
					b.new_eq_num as eq_num,
					DATE_FORMAT(a.date_time_in,' %d %b %Y') as date_in,
					DATE_FORMAT(a.date_time_in,' %H:%i') as time_in,
					case when date_time_out IS NOT NULL then DATE_FORMAT(a.date_time_out,' %d %b %Y')  else '' end as date_out,
					case when DATE_FORMAT(a.date_time_out,' %d %b %Y') IS NOT NULL then DATE_FORMAT(a.date_time_out,' %H:%i') else '' end as time_out,
					case when DATE_FORMAT(a.date_time_out,' %d %b %Y') IS NOT NULL then TIMESTAMPDIFF(MINUTE,a.date_time_in,a.date_time_out) div 60 else '' end as jam_pengerjaan,
					case when DATE_FORMAT(a.date_time_out,' %d %b %Y') IS NOT NULL then MOD(TIMESTAMPDIFF(MINUTE,a.date_time_in,a.date_time_out) , 60) else '' end as menit_pengerjaan,
					c.ip_address,
					c.description
			FROM
	   	 		pitstop a 
	   	 	INNER JOIN 
	   	 		master_equipment b 
	   	 	ON
	   	 		a.equipment_id = b.master_equipment_id
	   	 	LEFT JOIN 
	   	 		akses_cctv c
	   	 	ON
	   	 		a.station_id = c.station_id
	   	 	WHERE 
	   	 		TIMEDIFF(a.date_time_out,a.date_time_in)  < 0
	   		ORDER BY
	   			TIMEDIFF(now(),a.date_time_in) DESC
	   			,a.date_time_in
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	*/

	public function getAllData() {
		$sql = "SELECT
					c.lokasi as station_name,
					b.new_eq_num,
					a.shift,
					date_format(a.date_time_in,'%Y-%m-%d') as date_in,
					date_format(a.date_time_in,'%H:%i') as time_in,
					date_format(a.date_time_out,'%Y-%m-%d') as date_out,
					date_format(a.date_time_out,'%H:%i') as time_out, 
					a.description,
					pitstop_id,
					TIMEDIFF(now(),a.date_time_in) as durasi ,
					TIMEDIFF(a.date_time_out,a.date_time_in) as durasi_in_out,
					TIMESTAMPDIFF(MINUTE, a.date_time_in, now()) as durasi_menit
			FROM
	   	 		pitstop a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id 
	   	 		LEFT JOIN  master_lokasi c ON a.station_id=c.master_lokasi_id
	   	 	WHERE
	   	 		TIMEDIFF(a.date_time_out,a.date_time_in)  IS NULL
	   		ORDER BY
	   			TIMEDIFF(now(),a.date_time_in) DESC
		";
		#echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

	public function get_data_selesai_daily_check($tgl="") {
		if ($tgl == "") $tgl = date("Y-m-d");

		$sql = "SELECT 
					b.new_eq_num, 
					date_format(a.date_time_out,'%H:%i') as time_out, 
					TIMEDIFF(a.date_time_out,a.date_time_in) as durasi_in_out
			FROM
	   	 		pitstop a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id  
	   	 	WHERE
	   	 		TIMEDIFF(a.date_time_out,a.date_time_in)  IS NOT NULL
	   	 		AND 
	   	 		date_format(a.date_time_in,'%Y-%m-%d')  = '".$tgl."'
	   		ORDER BY
	   			TIMEDIFF(a.date_time_out,a.date_time_in) DESC
		";
		#echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

	public function get_total_selesai_daily_check($tgl="") {
		if ($tgl == "") $tgl = date("Y-m-d");

		$sql = "SELECT 
					count(*) as total
			FROM
	   	 		pitstop a  
	   	 	WHERE
	   	 		TIMEDIFF(a.date_time_out,a.date_time_in)  IS NOT NULL
	   	 		AND 
	   	 		date_format(a.date_time_in,'%Y-%m-%d')  = '".$tgl."' 
		";
		#echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 

	public function getTotalPlanDailycheckBerdasarkanHari($hari) {  
		$sql = "SELECT
					count(*) as total
			FROM
	   	 		setting_dailycheck a 
	   	 	WHERE
	   	 		a.day = $hari 
		";   
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	}  

	public function getTotalPlanDailycheckMenjadibreakdown($hari) {  
		$tgl = date("Y-m-d");

		$sql = "
				SELECT count(*) as total 
				FROM breakdown 
				WHERE 
					date_format(date_time_in,'%Y-%m-%d')  = '".$tgl."'  
					AND equipment_id IN  (
						SELECT
							master_equipment_id
						FROM
				   	 		setting_dailycheck 
				   	 	INNER JOIN 
				   	 		master_equipment 
				   	 	ON 
				   	 		trim(upper(setting_dailycheck.unit))  = trim(upper(master_equipment.new_eq_num))
				   	 	WHERE
				   	 		setting_dailycheck.day = ".$hari ." 
					) 
		";   
		//echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	}  
}