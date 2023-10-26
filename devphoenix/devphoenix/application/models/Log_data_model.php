<?php
class Log_data_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}  
	public function get_log_overspeed($nrp="", $date_start="", $date_end="") {  
		$sql = "select  
					a.nip, 
					b.nama,
					a.unit,
					a.time_stamp,
					a.speed,
					a.latitude,
					a.longitude
				FROM 
					log_over_speed a 
				INNER JOIN 
					master_employee b 
				ON 
					a.nip = b.nrp
				WHERE 
					a.nip='".$nrp."' AND 
					DATE_FORMAT(a.time_stamp,'%Y-%m-%d') BETWEEN '".$date_start."'  AND '".$date_end."' 
		";  
		//echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function get_log_cycle_time($nrp="", $date_start="", $date_end="") {  
		$sql = "select  
					a.nip, 
					b.nama,
					a.unit,
					a.datetime_start,
					a.datetime_end,
					TIMEDIFF(a.datetime_end, a.datetime_start) as durasi,
					a.fpi 
				FROM 
					log_cycle_time a 
				INNER JOIN 
					master_employee b 
				ON 
					a.nip = b.nrp
				WHERE 
					a.nip='".$nrp."' AND 
					DATE_FORMAT(a.datetime_start,'%Y-%m-%d') BETWEEN '".$date_start."'  AND '".$date_end."' 
		";  
		// echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
}