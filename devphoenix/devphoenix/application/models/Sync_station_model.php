<?php
class Sync_station_model extends CI_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAllDataStation() {
		$sql = "SELECT
					*
			FROM
	   	 		sync_station 
	   		ORDER BY
	   			urutan ASC
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function getStationCPP() {
		$sql = "SELECT
					*
			FROM
	   	 		sync_station 
	   	 	WHERE station_id = 1
		";
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function getStationPort() {
		$sql = "SELECT
					*
			FROM
	   	 		sync_station 
	   	 	WHERE station_id = 2
		";
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	}  
}