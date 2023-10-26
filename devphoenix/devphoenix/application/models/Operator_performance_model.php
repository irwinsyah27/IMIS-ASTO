<?php
class Operator_performance_model extends MY_MODEL { 
	function __construct(){
		parent::__construct();
	}
 
	public function getAllDataPerPeriode($date = "") {
		if ($date =="") $date = date("Y-m-d"); 
		$sql = "SELECT
					a.nip, 
					b.nama as nama_operator, 
					a.date, 
					a.shift, 
					DATE_FORMAT(a.time_in,'%Y-%m-%d %H:%i') as time_in, 
					DATE_FORMAT(a.time_out, '%Y-%m-%d %H:%i') as time_out, 
					a.bpm_in , 
					a.spo_in
					, DATE_FORMAT(c.time_in,'%H:%i') as time_in_mancal , 
					DATE_FORMAT(c.time_out,'%H:%i') as time_out_mancal, 
					c.total_over_speed 
					,c.total_cycle_time 
					, c.hm_awal, 
					c.hm_akhir, 
					c.unit
					, case when DATE_FORMAT(a.time_out,' %H:%i') IS NOT NULL then TIMEDIFF(a.time_out,a.time_in)  else '' end as durasi_in 
					, status_persetujuan 
					, CASE d.status_persetujuan
				    		WHEN '1' THEN 'Tidak disetujui'
				    	    WHEN '2' THEN 'Butuh pengawasan'
				    		WHEN '3' THEN 'Disetujui'
				    		ELSE ''
				    		END AS label_status_persetujuan
				    , d.bangun_tidur_hari_ini
				    , e.jam as jam_pengawasan
				    , f.jam as jam_stop
				    , DATE_FORMAT( DATE_ADD(d.bangun_tidur_hari_ini, INTERVAL e.jam HOUR)  ,' %H:%i') as titik_jam_pengawasan
				    , DATE_FORMAT( DATE_ADD(d.bangun_tidur_hari_ini , INTERVAL f.jam HOUR)  ,' %H:%i')as titik_jam_stop
				    , (
						SELECT 
							sum(ritase) 
						FROM 
							weigher 
						WHERE  
							weigher.date_weigher =  a.date 
							AND weigher.shift = a.shift
							AND weigher.equipment_id = g.master_equipment_id
							AND weigher.station_id = 2
							
					) as ritasi
					, (
						SELECT 
							FORMAT(((sum(netto)  / sum(ritase)) / 1000) , 2)
						FROM 
							weigher 
						WHERE  
							weigher.date_weigher =  a.date 
							AND weigher.shift = a.shift
							AND weigher.equipment_id = g.master_equipment_id
							AND weigher.station_id = 2 
					) as avg_ct
			FROM
	   	 		master_employee b 
	   	 	INNER JOIN 
	   	 		absensi a 
	   	 	ON 
	   	 		a.nip = b.nrp
	   	 	LEFT JOIN 
	   	 		daily_absent c 
	   	 	ON 
	   	 		b.nrp = c.nip AND a.nip=c.nip AND a.date=c.date AND a.shift = c.shift 
	   	 	LEFT JOIN 
	   	 		pra_job d 
	   	 	ON 
	   	 		a.nip = d.nrp AND a.date = d.tanggal_pra_job AND a.shift = d.shift
	   	 	LEFT JOIN 
	   	 		master_prediksi_pengawasan e 
	   	 	ON 
	   	 		d.prediksi_butuh_pengawasan = e.master_prediksi_pengawasan
	   	 	LEFT JOIN 
	   	 		master_prediksi_stop_bekerja f
	   	 	ON 
	   	 		d.prediksi_stop_bekerja = f.master_prediksi_stop_bekerja
	   	 	LEFT JOIN 
	   	 		master_equipment g 
	   	 	ON 
	   	 		c.unit = g.new_eq_num
	   	 	WHERE
	   	 		a.date  = '".$date."' 
	   		ORDER BY
	   			DATE_FORMAT( DATE_ADD(d.bangun_tidur_hari_ini, INTERVAL e.jam HOUR)  ,' %H:%i')   DESC";
		//echo $sql;exit;
		// AND TIMESTAMP(weigher.date_weigher, weigher.time_weigher) BETWEEN a.time_in AND a.time_out
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
}