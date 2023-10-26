<?php
class Master_sta_model extends CI_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAllDataStaMeter() {
		$sql = "SELECT
					* 
			FROM
	   	 		master_sta_meter
	   		ORDER BY
	   			master_sta_meter_id DESC
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  

    public function getAllDataStaLokasi() {
		$sql = "SELECT
					* 
			FROM
	   	 		sta_lokasi
	   		ORDER BY
	   			sta_lokasi_id DESC
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
}