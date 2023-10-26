<?php
class Hourly_monitoring_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}
	 
 
	public function getRowData($id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 breakdown a  
	   	 	WHERE
	   	 		a.breakdown_id = '".$id."'  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function getMasterBreakdown() { 
		$sql = "SELECT
					*
			FROM
	   	 		 master_breakdown a   
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function gettotaltonperjam($tgl, $station) { 
		$sql = "select 
					date_format(time_weigher,'%H') as jam, 
					sum(netto) as berat 
				FROM 
					weigher 
				WHERE 
					date_weigher='".$tgl."'  
					AND station_id = $station
				GROUP BY 
					date_format(time_weigher,'%H')
				ORDER BY 
					date_format(time_weigher,'%H')
  
		";
		#echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function gettotaltonpershift($tgl, $station) { 
		$sql = "select 
					shift as jam, 
					sum(netto) as berat 
				FROM 
					weigher 
				WHERE 
					date_weigher='".$tgl."' 
					AND station_id = $station
				GROUP BY 
					shift
				ORDER BY 
					shift
  
		";
		#echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function gettonperjamperstasiun($tgl, $station = 1) { 
		$sql = "select 
					date_format(time_weigher,'%H') as jam, 
					sum(netto) as berat 
				FROM 
					weigher 
				WHERE 
					station_id = $station AND date_weigher='".$tgl."' 
				GROUP BY 
					date_format(time_weigher,'%H')
				ORDER BY 
					date_format(time_weigher,'%H')
  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function getcycletimeperjam($tgl,$station) { 
		$sql = "select 
					date_format(time_weigher,'%H') as jam, 
					sum(ritase) as berat 
				FROM 
					weigher 
				WHERE 
					station_id = $station AND date_weigher='".$tgl."' 
				GROUP BY 
					date_format(time_weigher,'%H') 
				ORDER BY 
					date_format(time_weigher,'%H') 
  
		";
		#echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function get_total_unit_active_per_shift($tgl) { 
		$sql = "select 
					shift , 
					count(*) as total_unit 
				FROM 
					daily_absent 
				WHERE 
					date='".$tgl."'  
				GROUP BY 
					shift
				ORDER BY 
					shift
  
		";
		#echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function get_total_payload_per_shift($tgl, $station) { 
		$sql = "select 
					shift , 
					sum(netto) as berat,
					count(*) as total 
				FROM 
					weigher 
				WHERE 
					date_weigher='".$tgl."' AND 
					station_id = $station
				GROUP BY 
					shift
				ORDER BY 
					shift
  
		";
		#echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

	public function get_total_unit() { 
		$sql = "select 
					count(*) as total
				FROM  
					master_equipment b 
				WHERE 
					b.master_owner_id = 1 AND b.master_alokasi_id = 1
		";
		#echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs["total"];
	}  
	public function get_total_unit_breakdown($datetime_end = "") { 
		$sql = "select 
					count(*) as total
				FROM 
					breakdown a
				INNER JOIN 
					master_equipment b
				ON 
					a.equipment_id = b.master_equipment_id
				WHERE
					b.master_owner_id = 1 
					AND a.date_time_in <  '$datetime_end' 
					AND a.date_ready >=  '$datetime_end' 
		";
		#echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs["total"];
	} 

	public function get_summary_unit_aktif_per_user_per_egi() { 
		$sql = "select
					c.keterangan as owner,
					d.keterangan as egi,
					count(*) as total
				FROM  
					master_equipment b 
				INNER JOIN 
					master_owner c 
				ON 
					b.master_owner_id = c.master_owner_id 
				INNER JOIN 
					master_egi d 
				ON
					b.master_egi_id = d.master_egi_id
				WHERE 
					b.master_owner_id IN ('1', '2') AND d.master_egi_id IN ('1', '2', '3')
				GROUP BY c.keterangan , d.keterangan
		";
		#echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs["total"];
	}  

	public function get_total_payload_per_hour($tgl, $station = "", $office = "") { 
		$sql = "SELECT  
					date_format(time_weigher,'%H') as jam , 
					d.kode , 
					e.keterangan as egi,  
					sum(a.netto) / 1000 as produksi, 
					sum(ritase) as ritase, 
					FORMAT(((sum(a.netto) / sum(ritase)) / 1000),2) as payload ,
					 count(DISTINCT equipment_id) as unit
				FROM 
					weigher a 
				LEFT JOIN 
					master_equipment b ON a.equipment_id = b.master_equipment_id 
				LEFT JOIN 
					master_alokasi c ON b.master_alokasi_id = c.master_alokasi_id
				LEFT JOIN 
					master_owner d ON b.master_owner_id = d.master_owner_id
				LEFT JOIN 
					master_egi e ON b.master_egi_id = e.master_egi_id
				WHERE 
					a.date_weigher = '".$tgl."' AND  
					station_id = ".$station ;  

		if (isset($office) && $office <> "") {
			$sql .= " AND d.kode = '".$office."'";
		}   

		$sql .= "
				GROUP BY 
					date_format(time_weigher,'%H') ,
					d.kode , 
					e.keterangan 
					"; 
		//echo $sql."<br><br>";
		/*
		if ($office == "SAM") {
			echo $sql;exit;
		}
		*/
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

	public function get_total_payload_per_periode_hour($array_hour, $tgl, $station = "", $office = "", $egi = "") { 
		$sql = "SELECT   
					d.kode , 
					e.keterangan as egi,  
					sum(a.netto) / 1000 as produksi, 
					sum(ritase) as ritase, 
					FORMAT(((sum(a.netto) / sum(ritase)) / 1000),2) as payload ,
					 count(DISTINCT equipment_id) as unit
				FROM 
					weigher a 
				LEFT JOIN 
					master_equipment b ON a.equipment_id = b.master_equipment_id 
				LEFT JOIN 
					master_alokasi c ON b.master_alokasi_id = c.master_alokasi_id
				LEFT JOIN 
					master_owner d ON b.master_owner_id = d.master_owner_id
				LEFT JOIN 
					master_egi e ON b.master_egi_id = e.master_egi_id
				WHERE 
					date_format(time_weigher,'%H') IN  (".$array_hour.") AND 
					a.date_weigher = '".$tgl."' AND  
					station_id = ".$station ;  

		if (isset($office) && $office <> "") {
			$sql .= " AND d.kode = '".$office."'";
		}   
		if (isset($egi) && $egi <> "") {
			$sql .= " AND e.keterangan = '".$egi."'";
		}   

		$sql .= "
				GROUP BY  
					d.kode , 
					e.keterangan 
					"; 
		//echo $sql."<br><br>";  exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function get_data_owner() { 
		$sql = "select
					*
				FROM   
					master_owner 
				WHERE 
					master_owner_id IN ('1', '2','3') 
				ORDER BY 
					kode
		";
		#echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function get_data_owner_and_egi($tgl) { 
		$sql = "select
					distinct b.kode as owner , c.keterangan as egi
				FROM  
					weigher aa 
				INNER JOIN 
					master_equipment a 
				ON 
					aa.equipment_id = a.master_equipment_id
				INNER JOIN  
					master_owner b 
				ON 
					a.master_owner_id = b.master_owner_id 
				INNER JOIN 
					master_egi c 
				ON 
					a.master_egi_id = c.master_egi_id
				WHERE 
					b.master_owner_id IN ('1', '2','3')  AND date_weigher = '".$tgl."'
				ORDER BY 
					b.kode, c.keterangan
		";
		#echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  


	

		
}