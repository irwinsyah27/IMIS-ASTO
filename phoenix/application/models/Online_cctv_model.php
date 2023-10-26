<?php
class Online_cctv_model extends MY_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAllData() {
		$sql = "SELECT
					*
			FROM
	   	 		akses_cctv a 
	   	 	WHERE
	   	 		a.ip_address IS NOT NULL
	   	 	ORDER BY 
	   	 		urutan ASC
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
}