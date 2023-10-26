<?php
class Master_equipment_model extends CI_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAllData() {
		$sql = "SELECT
					a.*,
					a.new_eq_num as unit,
					b.alokasi
			FROM
	   	 		master_equipment a 
	   	 	LEFT JOIN 
	   	 		master_alokasi b
	   	 	ON 
	   	 		a.master_alokasi_id = b.master_alokasi_id
	   		ORDER BY
	   			new_eq_num ASC
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
}