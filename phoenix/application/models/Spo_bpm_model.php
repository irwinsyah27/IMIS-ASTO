<?php
class Spo_bpm_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}  
	public function get_data_spo_bpm_pegawai($nrp="", $month="", $year="") { 
		$month = (strlen($month)<2)?'0'.$month:$month;
		$sql = "select  
					DATE_FORMAT(date,'%d') as tgl,
					b.nrp,
					b.nama,
					a.date,
					a.bpm_in,
					a.spo_in 
				FROM 
					absensi a 
				INNER JOIN 
					master_employee b 
				ON 
					a.nip = b.nrp
				WHERE 
					nip='".$nrp."' AND 
					DATE_FORMAT(date,'%Y-%m') like '".$year."-".$month."%' 
		";
		#echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
}