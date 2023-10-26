<?php
class Master_employee_model extends CI_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAllDataOperator() {
		$sql = "SELECT
					* 
			FROM
	   	 		master_employee
	   		ORDER BY
	   			nama ASC
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
}