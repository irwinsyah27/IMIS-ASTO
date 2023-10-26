<?php
class rpt_isi_solar_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}
	 
 
	public function list_egi() { 
		$sql = "SELECT
					*
			FROM
	   	 		 master_egi
	   	 	ORDER BY keterangan  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

	public function get_total_pengisian_per_egi($tgl) { 
		$sql = "SELECT
					sum(total_realisasi) as total, 
					c.master_egi_id ,
					c.keterangan as egi
			FROM
	   	 		 fuel_refill a   
	   	 	INNER JOIN 
	   	 		master_equipment b
	   	 	ON
	   	 		a.equipment_id = b.master_equipment_id
	   	 	INNER JOIN 
	   	 		master_egi c 
	   	 	ON 
	   	 		b.master_egi_id = c.master_egi_id
	   	 	WHERE 
	   	 		a.date_fill = '".$tgl."'
	   	 	GROUP BY c.master_egi_id ,
					c.keterangan
		"; 
		#echo $sql."<br>";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  

		
}