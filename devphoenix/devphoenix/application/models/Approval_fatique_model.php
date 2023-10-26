<?php
class Approval_fatique_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}
	   
	public function antrian_instruksi($terminals_id= "") {
		$sql = "SELECT
					a.*,
					b.nama
					,case when (TIMEDIFF(a.bangun_tidur_kemarin,a.mulai_tidur_kemarin)  > 0 ) then TIMEDIFF(a.bangun_tidur_kemarin,a.mulai_tidur_kemarin)  else '' end as lama_tdr_kemarin
					,case when (TIMEDIFF(a.bangun_tidur_hari_ini,a.mulai_tidur_hari_ini)  > 0 ) then TIMEDIFF(a.bangun_tidur_hari_ini,a.mulai_tidur_hari_ini)  else '' end as lama_tdr_sekarang
					,c.bpm_in as bpm
					,c.spo_in as spo 
					,CASE rekomendasi
    		WHEN '1' THEN 'Tidak disetujui'
    	    WHEN '2' THEN 'Butuh pengawasan'
    		WHEN '3' THEN 'Disetujui'
    		ELSE ''
    		END AS label_rekomendasi
			FROM
	   	 		pra_job a  INNER JOIN master_employee b ON a.nrp = b.nrp 
	   	 	LEFT JOIN 
	   	 		absensi c 
	   	 	ON 
	   	 		b.nrp = c.nip AND a.tanggal_pra_job = c.date
	   	 	WHERE
	   	 		status_persetujuan IS NULL ";
 
	   	if (isset($terminals_id) && $terminals_id <> "" && $terminals_id <> "null") { $sql .= " AND a.terminals_id IN (". $terminals_id.")"; 	} 
	   	
	   	$sql .= "
	   	 	ORDER BY 
	   	 		a.pra_job_id ASC
		";  
		# echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	   
	public function get_data_operator_absensi($nrp, $tgl) {
		$sql = "SELECT
					a.*,
					b.nama
					,case when (TIMEDIFF(a.bangun_tidur_kemarin,a.mulai_tidur_kemarin)  > 0 ) then TIMEDIFF(a.bangun_tidur_kemarin,a.mulai_tidur_kemarin)  else '' end as lama_tdr_kemarin
					,case when (TIMEDIFF(a.bangun_tidur_hari_ini,a.mulai_tidur_hari_ini)  > 0 ) then TIMEDIFF(a.bangun_tidur_hari_ini,a.mulai_tidur_hari_ini)  else '' end as lama_tdr_sekarang
					,c.bpm_in as bpm
					,c.spo_in as spo
			FROM
	   	 		pra_job a  INNER JOIN master_employee b ON a.nrp = b.nrp 
	   	 	LEFT JOIN 
	   	 		absensi c 
	   	 	ON 
	   	 		b.nrp = c.nip AND a.tanggal_pra_job = c.date
	   	 	WHERE
	   	 		a.nrp = '".$nrp."' AND a.tanggal_pra_job = '".$tgl."' 
	   	 	ORDER BY 
	   	 		a.pra_job_id ASC
		"; 
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function list_zona() {
		$sql = "SELECT
					distinct zona
			FROM
	   	 		terminals 
	   	 	ORDER BY 
	   	 		zona ASC
		"; 
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function list_terminal() {
		$sql = "SELECT
					*
			FROM
	   	 		terminals 
	   	 	ORDER BY 
	   	 		name ASC
		"; 
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  


		
}