<?php
class Report_entry_data_model extends MY_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAntrian($station = "", $tgl="") {  
		$sql = "SELECT
					a.*, b.nama
			FROM
	   	 		log_coverage_in a LEFT JOIN master_employee b ON a.nip = b.nrp
	   	 	WHERE
	   	 		a.date_in = '".$tgl."'  AND a.station_id = '".$station."' 
	   		ORDER BY
	   			a.date_in, a.time_in DESC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function getTimbanganCpp($start = "", $end="") {  
		$sql = "SELECT
					weigher_id,b.new_eq_num,a.shift,a.netto,a.tara,a.bruto,a.date_weigher,a.time_weigher
					,c.keterangan as owner
					,d.keterangan as egi
					, a.ritase
			FROM
	   	 		weigher a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id
	   	 	LEFT JOIN 
	   	 		master_owner c 
	   	 	ON 
	   	 		b.master_owner_id = c.master_owner_id
	   	 	LEFT JOIN 
	   	 		master_egi d 
	   	 	ON 
	   	 		b.master_egi_id = d.master_egi_id 
	   	 	WHERE
	   	 		a.date_weigher >= '".$start."'  AND a.date_weigher <= '".$end."' AND station_id = 1
	   		ORDER BY
	   			a.date_weigher DESC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function getTimbanganPort($start = "", $end="") {  
		$sql = "SELECT
					weigher_id,b.new_eq_num,a.shift,a.netto,a.tara,a.bruto,a.date_weigher,a.time_weigher
					,c.keterangan as owner
					,d.keterangan as egi
					, a.ritase
			FROM
	   	 		weigher a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id
	   	 	LEFT JOIN 
	   	 		master_owner c 
	   	 	ON 
	   	 		b.master_owner_id = c.master_owner_id
	   	 	LEFT JOIN 
	   	 		master_egi d 
	   	 	ON 
	   	 		b.master_egi_id = d.master_egi_id 
	   	 	WHERE
	   	 		a.date_weigher >= '".$start."'  AND a.date_weigher <= '".$end."' AND station_id = 2
	   		ORDER BY
	   			a.date_weigher DESC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function getInstruksiIsiSolar($start = "", $end="") {  
		$sql = "SELECT
					fuel_refill_id,
					b.new_eq_num,
					a.shift,
					a.date_instruksi,
					a.total_liter,
					a.date_fill,
					a.time_fill_start,
					a.time_fill_end,
					a.hm,
					a.nrp,
					c.alokasi as alokasi
			FROM
	   	 		fuel_refill a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id
	   	 	LEFT JOIN 
	   	 		master_alokasi c 
	   	 	ON 
	   	 		b.master_alokasi_id = c.master_alokasi_id
	   	 	WHERE
	   	 		a.date_fill >= '".$start."'  AND a.date_fill <= '".$end."' 
	   		ORDER BY
	   			a.date_fill DESC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function getPitstop($start = "", $end="") {  
		$sql = "SELECT
					pitstop_id,b.new_eq_num,a.shift,a.date_time_in,a.date_time_out,a.description,c.station_name
			FROM
	   	 		pitstop a 
	   	 	LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id LEFT JOIN  sync_station c ON a.station_id=c.station_id
	   	 	WHERE
	   	 		DATE_FORMAT(a.date_time_in,'%Y-%m-%d') >= '".$start."'  AND DATE_FORMAT(a.date_time_in,'%Y-%m-%d') <= '".$end."' 
	   		ORDER BY
	   			a.date_time_in DESC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function getWorkshop($start = "", $end="") {  
		$sql = "SELECT
					breakdown_id,b.new_eq_num,a.shift,a.date_time_in,a.date_time_out,a.description,c.station_name,d.kode
			FROM
	   	 		breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id LEFT JOIN  sync_station c ON a.station_id=c.station_id LEFT JOIN master_breakdown d ON a.master_breakdown_id=d.master_breakdown_id
	   	 	WHERE
	   	 		DATE_FORMAT(a.date_time_in,'%Y-%m-%d') >= '".$start."'  AND DATE_FORMAT(a.date_time_in,'%Y-%m-%d') <= '".$end."' 
	   		ORDER BY
	   			a.date_time_in DESC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function getReportAbsensi($start = "", $end="") {  
		$sql = "SELECT
					a.nip, b.nama, a.date, a.shift, a.time_in, a.time_out, a.bpm_in , a.spo_in
					, c.time_in as time_in_mancal , c.time_out as time_out_mancal, c.total_over_speed, c.total_cycle_time
					, c.hm_awal, c.hm_akhir, c.unit
			FROM
	   	 		master_employee b 
	   	 	LEFT JOIN 
	   	 		absensi a 
	   	 	ON 
	   	 		a.nip = b.nrp
	   	 	LEFT JOIN 
	   	 		daily_absent c 
	   	 	ON 
	   	 		b.nrp = c.nip AND a.nip=c.nip AND a.date=c.date AND a.shift = c.shift 
	   	 	WHERE
	   	 		a.date  >= '".$start."'  AND a.date <= '".$end."' 
	   		ORDER BY
	   			b.nama, a.date ASC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function getPrajob($start = "", $end="") {  
		$sql = "SELECT
					a.*, b.nama 
			FROM
	   	 		pra_job a 
	   	 	INNER JOIN 
	   	 		master_employee b 
	   	 	ON 
	   	 		a.nrp = b.nrp 
	   	 	WHERE
	   	 		a.tanggal_pra_job  >= '".$start."'  AND a.tanggal_pra_job <= '".$end."' 
	   		ORDER BY
	   			b.nama, a.tanggal_pra_job ASC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
}