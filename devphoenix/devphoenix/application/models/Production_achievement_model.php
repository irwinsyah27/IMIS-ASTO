<?php
class Production_achievement_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}
	public function get_total_today($tgl, $station) { 
		$sql = "select  
					sum(netto) as berat 
				FROM 
					weigher 
				WHERE 
					date_weigher='".$tgl."'  
					AND station_id = $station
  
		"; 
		#echo $sql."<br><br>";exit;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	}
	public function get_total_month_to_today($tgl_awal, $tgl, $station) { 
		$sql = "select  
					sum(netto) as berat 
				FROM 
					weigher 
				WHERE 
					date_weigher>='".$tgl_awal."' AND
					date_weigher<='".$tgl."'  
					AND station_id = $station 
  
		"; 
		# echo $sql."<br><br>";
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function get_total_per_hari($tgl_awal, $tgl, $station) { 
		$sql = "select
					DATE_FORMAT(date_weigher,'%d') as tgl,
					sum(netto) as berat 
				FROM 
					weigher 
				WHERE 
					date_weigher>='".$tgl_awal."' AND
					date_weigher<='".$tgl."'  
					AND station_id = $station 
				GROUP BY 
  					DATE_FORMAT(date_weigher,'%d')
		"; 
		#echo $sql."<br><br>";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	 
  


	

		
}