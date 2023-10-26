<?php
class Master_kerusakan_model extends CI_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAllDataRoad() {
		$sql = "SELECT
					* 
			FROM
	   	 		jenis_kerusakan
	   		ORDER BY
	   			master_kerusakan_id ASC
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
}