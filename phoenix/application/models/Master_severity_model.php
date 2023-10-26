<?php
class Master_severity_model extends CI_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAllDataSeverity() {
		$sql = "SELECT
					* 
			FROM
	   	 		master_severity
	   		ORDER BY
			   	master_severity_id ASC
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
}