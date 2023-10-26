<?php
class Chartfuel_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}
	 
 
	
	public function gettonperjamperkpp($tgl) { 
		$sql = "select 
					date_format(a.time_weigher,'%H') as jam, 
					sum(a.netto) as berat 
				FROM 
					weigher a
				INNER JOIN 
					master_equipment b 
				ON 
					a.equipment_id = b.master_equipment_id
				WHERE 
					a.date_weigher='".$tgl."' AND b.master_owner_id = 1
				GROUP BY 
					date_format(a.time_weigher,'%H')
				ORDER BY 
					date_format(a.time_weigher,'%H')
  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function getfuelperjamkppcurrdate($tgl) { 
		$sql = "select 
					date_format(a.time_fill_start,'%H') as jam, 
					sum(a.total_realisasi) as berat 
				FROM 
					fuel_refill a 
				INNER JOIN 
					master_equipment b 
				ON 
					a.equipment_id = b.master_equipment_id
				WHERE 
					a.date_fill='".$tgl."' AND a.time_fill_start >='05:00' AND a.time_fill_start <'24:00'
					AND b.master_owner_id = 1 
				GROUP BY 
					date_format(a.time_fill_start,'%H')
				ORDER BY 
					date_format(a.time_fill_start,'%H')
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function getfuelperjamkppnextdate($tgl) { 
		$sql = "select 
					date_format(a.time_fill_start,'%H') as jam, 
					sum(a.total_realisasi) as berat 
				FROM 
					fuel_refill a 
				INNER JOIN 
					master_equipment b 
				ON 
					a.equipment_id = b.master_equipment_id
				WHERE 
					a.date_fill='".$tgl."' AND a.time_fill_start >='00:00' AND a.time_fill_start <'05:00'
					AND b.master_owner_id = 1 
				GROUP BY 
					date_format(a.time_fill_start,'%H')
				ORDER BY 
					date_format(a.time_fill_start,'%H')
  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

	public function getdurasiperjam($tgl) { 
		$sql = "select 
					date_format(a.time_fill_start,'%H') as jam, 
					sum(TIMEDIFF(a.time_fill_end,a.time_fill_start)),
					count(*) as total_unit, 
					( sum(TIMESTAMPDIFF(MINUTE, time_fill_start , time_fill_end )) / count(*) ) as durasi
				FROM 
					fuel_refill a 
				INNER JOIN 
					master_equipment b 
				ON 
					a.equipment_id = b.master_equipment_id
				WHERE 
					a.date_fill='".$tgl."'
					AND b.master_owner_id = 1  AND 
					TIMESTAMPDIFF(MINUTE, time_fill_start , time_fill_end )  > 0 AND
					TIMESTAMPDIFF(MINUTE, time_fill_start , time_fill_end )  < 10
				GROUP BY 
					date_format(a.time_fill_start,'%H')
				ORDER BY 
					date_format(a.time_fill_start,'%H')
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

	public function get_liter_per_hm($tgl_awal , $tgl_akhir , $owner= "1") { 
		$sql = "select 
					e.keterangan as egi,
					sum(coalesce(total_realisasi,0)) as total_realisasi, 
					sum(coalesce(hm,0) - coalesce(hm_last,0)  ) as total_hm, 
					FORMAT(sum(coalesce(total_realisasi,0)) / ( sum(coalesce(hm,0) - coalesce(hm_last,0) )),2) as avg
				FROM 
					fuel_refill a 
				INNER JOIN 
					master_equipment b 
				ON 
					a.equipment_id = b.master_equipment_id
				INNER JOIN 
					master_egi e ON b.master_egi_id = e.master_egi_id
				WHERE 
					a.date_fill BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'
					AND b.master_owner_id = ".$owner."  AND 
					b.master_alokasi_id = 1
				GROUP BY 
					e.keterangan 
		";  
					//e.master_egi_id IN (1,2,3,4,5)
		# echo $sql."<br><br>";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

		
}