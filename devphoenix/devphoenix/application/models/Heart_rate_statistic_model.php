<?php
class Heart_rate_statistic_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	} 
	public function get_statistic_per_day($tgl) { 
		$sql = "select
					 b.nama, 
					a.bpm_in
				FROM 
					daily_absent a 
				INNER JOIN 
					master_employee b 
				ON 
					a.nip = b.nrp
				WHERE 
					a.date >='".$tgl."'  AND 
					a.status = 'M'
				ORDER BY b.nama
		"; 
		#echo $sql."<br><br>";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	 
  


	

		
}