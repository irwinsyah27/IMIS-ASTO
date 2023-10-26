<?php
class Leadtime_breakdown_model extends MY_Model { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAllDataBreakdown($type = "" , $breakdown = "") { 
		$sql = "SELECT
					breakdown_id , b.new_eq_num , d.alokasi , c.kode , 
					a.status_breakdown_id , e.lokasi , a.hm , a.km , a.no_wo,
					a.date_time_in,
					a.date_time_out,
					a.eta_rfu_unit,
					a.eta_waiting_part,
					date_format(a.date_time_in,'%Y-%m-%d') as date_in,
					date_format(a.date_time_in,'%H:%i') as time_in, 
					case when (TIMEDIFF(now(),a.date_time_in)  > 0 ) then TIMEDIFF(now(),a.date_time_in)  else '' end as durasi ,
					f.kriteria_komponen , a.diagnosa , a.tindakan , a.status 
			FROM
	   	 		 breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id  
				LEFT JOIN master_breakdown c ON a.master_breakdown_id=c.master_breakdown_id
				LEFT JOIN master_alokasi d ON b.master_alokasi_id = d.master_alokasi_id
				LEFT JOIN master_lokasi e ON a.master_lokasi_id = e.master_lokasi_id
				LEFT JOIN kriteria_komponen f ON a.kriteria_komponen_id=f.kriteria_komponen_id
	   	 	WHERE 
	   	 		a.status = 0";
	   	 if (isset($type) & $type <> "" && $type <> null) {
	   	 	$sql .= " AND  b.master_alokasi_id IN (  ".$type. " ) ";
	   	 }
	   	 if (isset($breakdown) && $breakdown <> "" && $breakdown <> null) {
	   	 	$sql .= " AND  a.master_breakdown_id IN (".$breakdown.")" ;
	   	 }
	   	 $sql  .= "
	   		ORDER BY
	   			TIMEDIFF(now(),a.date_time_in)  DESC,
	   			a.date_time_in
		"; 
		# echo $type ." - ". $sql."<br><br>";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

	public function getAllDataWarningPart($type = "" , $breakdown = "") { 
		$sql = "SELECT
					breakdown_id , b.new_eq_num , d.alokasi , c.kode , 
					a.status_breakdown_id , e.lokasi , a.hm , a.km , a.no_wo,
					a.date_time_in,
					a.date_time_out,
					a.eta_rfu_unit,
					a.eta_waiting_part,
					date_format(a.date_time_in,'%Y-%m-%d') as date_in,
					date_format(a.date_time_in,'%H:%i') as time_in, 
					case when (TIMEDIFF(now(),a.date_time_in)  > 0 ) then TIMEDIFF(now(),a.date_time_in)  else '' end as durasi ,
					f.kriteria_komponen , a.diagnosa , a.tindakan , a.status , a.warning_part
			FROM
	   	 		 breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id  
				LEFT JOIN master_breakdown c ON a.master_breakdown_id=c.master_breakdown_id
				LEFT JOIN master_alokasi d ON b.master_alokasi_id = d.master_alokasi_id
				LEFT JOIN master_lokasi e ON a.master_lokasi_id = e.master_lokasi_id
				LEFT JOIN kriteria_komponen f ON a.kriteria_komponen_id=f.kriteria_komponen_id
	   	 	WHERE 
	   	 		a.status = 0  AND a.warning_part IS NOT NULL  AND a.warning_part != '' ";
	   	 if (isset($type) & $type <> "" && $type <> null) {
	   	 	$sql .= " AND  b.master_alokasi_id IN (  ".$type. " ) ";
	   	 }
	   	 if (isset($breakdown) && $breakdown <> "" && $breakdown <> null) {
	   	 	$sql .= " AND  a.master_breakdown_id IN (".$breakdown.")" ;
	   	 }
	   	 $sql  .= "
	   		ORDER BY
	   			TIMEDIFF(now(),a.date_time_in)  DESC,
	   			a.date_time_in
		"; 
		// echo $type ." - ". $sql."<br><br>";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function getSummaryByType() {
		$sql = "SELECT
					distinct (d.alokasi) as type , count(*) as total
				FROM
	   	 		 	breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id  
				LEFT JOIN master_breakdown c ON a.master_breakdown_id=c.master_breakdown_id
				LEFT JOIN master_alokasi d ON b.master_alokasi_id = d.master_alokasi_id
				LEFT JOIN master_lokasi e ON a.master_lokasi_id = e.master_lokasi_id
				LEFT JOIN kriteria_komponen f ON a.kriteria_komponen_id=f.kriteria_komponen_id
	   	 	WHERE 
	   	 		a.status <> 1
	   	 	 GROUP BY d.alokasi
	   		ORDER BY
	   			d.alokasi
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

	public function getSummaryByEGI() {
		$sql = "SELECT
					distinct (d.keterangan) as egi , count(*) as total
				FROM
	   	 		 	breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id  
				LEFT JOIN master_breakdown c ON a.master_breakdown_id=c.master_breakdown_id
				LEFT JOIN master_egi d ON b.master_egi_id = d.master_egi_id
				LEFT JOIN master_lokasi e ON a.master_lokasi_id = e.master_lokasi_id
				LEFT JOIN kriteria_komponen f ON a.kriteria_komponen_id=f.kriteria_komponen_id
	   	 	WHERE 
	   	 		a.status <> 1
	   	 	 GROUP BY d.keterangan
	   		ORDER BY
	   			d.keterangan
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function getTotalUnitByType() {
		$sql = "SELECT
					distinct (d.alokasi) as type , count(*) as total
				FROM
	   	 		 	master_equipment b 
				LEFT JOIN master_alokasi d ON b.master_alokasi_id = d.master_alokasi_id  
				WHERE 
					d.master_alokasi_id IN (1,2,3,4,5,10,32,12,14) AND b.status = 1
	   	 	 GROUP BY d.alokasi
	   		ORDER BY
	   			d.alokasi ASC
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

	public function getTotalUnitByEGI() {
		$sql = "SELECT
					distinct (d.keterangan) as egi , count(*) as total
				FROM
	   	 		 	master_equipment b 
				LEFT JOIN master_egi d ON b.master_egi_id = d.master_egi_id  
				WHERE 
					d.master_egi_id IN (1,2,3,4,6,7,12,15,23,26,54,69,575,582,648,669) AND b.status = 1 AND b.master_owner_id = '1'
	   	 	 GROUP BY d.keterangan
	   		ORDER BY
	   			d.keterangan ASC
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function getTotalUnitHaulingNonKpp() {
		$sql = "SELECT
					count(*) as total
				FROM
	   	 		 	master_equipment b 
				INNER JOIN master_alokasi d ON b.master_alokasi_id = d.master_alokasi_id  
				INNER JOIN master_owner c ON b.master_owner_id  = c.master_owner_id
	   	 	WHERE 
	   	 		b.master_alokasi_id = 1  AND b.master_owner_id <> 1  AND b.status = 1
		";
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs["total"];
	} 
	public function getSummaryByTypeReady() {
		$sql = "SELECT
					distinct (d.alokasi) as type , count(*) as total
				FROM
	   	 		 	breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id  
				LEFT JOIN master_breakdown c ON a.master_breakdown_id=c.master_breakdown_id
				LEFT JOIN master_alokasi d ON b.master_alokasi_id = d.master_alokasi_id
				LEFT JOIN master_lokasi e ON a.master_lokasi_id = e.master_lokasi_id
				LEFT JOIN kriteria_komponen f ON a.kriteria_komponen_id=f.kriteria_komponen_id
	   	 	WHERE 
				b.status = 1
	   	 	GROUP BY d.alokasi
	   		ORDER BY
	   			d.alokasi DESC
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	
	public function getSummaryByEGIReady() {
		$sql = "SELECT
					distinct (d.keterangan) as egi , count(*) as total
				FROM
	   	 		 	breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id  
				LEFT JOIN master_breakdown c ON a.master_breakdown_id=c.master_breakdown_id
				LEFT JOIN master_egi d ON b.master_egi_id = d.master_egi_id
				LEFT JOIN master_lokasi e ON a.master_lokasi_id = e.master_lokasi_id
				LEFT JOIN kriteria_komponen f ON a.kriteria_komponen_id=f.kriteria_komponen_id
	   	 	WHERE 
				b.status = 1
	   	 	GROUP BY d.keterangan
	   		ORDER BY
	   			d.keterangan DESC
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

	
	public function getUnitReadyToday() { 
		$sql = "SELECT
					b.new_eq_num , 

					date_format(a.date_time_out,'%H:%i') as time_out, 
					case when (TIMEDIFF(a.date_time_out,a.date_time_in)  > 0 ) then TIMEDIFF(a.date_time_out,a.date_time_in)  else '' end as durasi  
			FROM
	   	 		 breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id  
				LEFT JOIN master_breakdown c ON a.master_breakdown_id=c.master_breakdown_id
				LEFT JOIN master_alokasi d ON b.master_alokasi_id = d.master_alokasi_id
				LEFT JOIN master_lokasi e ON a.master_lokasi_id = e.master_lokasi_id
				LEFT JOIN kriteria_komponen f ON a.kriteria_komponen_id=f.kriteria_komponen_id
	   	 	WHERE 
	   	 		a.status = 1 and date_format(a.date_time_in,'%Y-%m-%d') <= '".date("Y-m-d")."' 
	   	 		AND date_format(a.date_time_out,'%Y-%m-%d') <= '".date("Y-m-d")."'"  ; 
	   	 $sql  .= "
	   		ORDER BY
	   			a.date_time_out  DESC 
	   		LIMIT 10
		"; 
		// echo  $sql."<br><br>";exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
}