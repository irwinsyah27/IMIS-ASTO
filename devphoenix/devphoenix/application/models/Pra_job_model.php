<?php
class pra_job_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}
	 

	// fix
	public function get_list_data(){
		$aColumns = array('a.pra_job_id','a.tanggal_pra_job','shift','a.nrp','b.nama','a.status_persetujuan');
		$sIndexColumn = "a.pra_job_id";
		$sTable = ' pra_job a  INNER JOIN master_employee b ON a.nrp = b.nrp ';
		
		/* Paging */
		$sLimit = "";
		if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1'){
			$sLimit = "LIMIT ".($_GET['iDisplayStart']).", ".
			($_GET['iDisplayLength']);
		}
		
		/* Ordering */
		$sOrder = "";
		if(isset($_GET['iSortCol_0'])){
			$sOrder = "ORDER BY  ";
			for ($i=0 ; $i<intval($_GET['iSortingCols']) ; $i++){
				if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true"){
					$sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
				 	".($_GET['sSortDir_'.$i]) .", ";
				}
			}
		
			$sOrder = substr_replace($sOrder, "", -2);
			if($sOrder == "ORDER BY"){
				$sOrder = "";
			}
		}
		
		/* Filtering */
		$sWhere = " WHERE 1 = 1 ";
		if(isset($_GET['sSearch']) && $_GET['sSearch'] != ""){
			$sWhere .= "AND (";
			for($i=0; $i<count($aColumns); $i++){
				$sWhere .= $aColumns[$i]." LIKE '%".($_GET['sSearch'])."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3);
			$sWhere .= ')';
		}
		
		/* Individual column filtering */
		for($i=0 ; $i<count($aColumns) ; $i++){
			if(isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && isset($_GET['sSearch_'.$i]) && $_GET['sSearch_'.$i] != ''){
				if($sWhere == ""){
					$sWhere = "WHERE ";
				} else {
					$sWhere .= " AND ";
				}
				$sWhere .= $aColumns[$i]." LIKE '%".($_GET['sSearch_'.$i])."%' ";
			}
		}
		
		/* Get data to display */
		$sQuery = "
		SELECT  SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		,case when (TIMEDIFF(a.bangun_tidur_kemarin,a.mulai_tidur_kemarin)  > 0 ) then TIMEDIFF(a.bangun_tidur_kemarin,a.mulai_tidur_kemarin)  else '' end as lama_tdr_kemarin
		,case when (TIMEDIFF(a.bangun_tidur_hari_ini,a.mulai_tidur_hari_ini)  > 0 ) then TIMEDIFF(a.bangun_tidur_hari_ini,a.mulai_tidur_hari_ini)  else '' end as lama_tdr_sekarang
		,CASE status_persetujuan
    		WHEN '1' THEN 'Tidak disetujui'
    	    WHEN '2' THEN 'Butuh pengawasan'
    		WHEN '3' THEN 'Disetujui'
    		ELSE ''
    		END AS label_status_persetujuan
		FROM $sTable
		$sWhere
		$sOrder
		$sLimit
		";
		#echo $sQuery;exit;
		$rResult = $this->db->query($sQuery)->result();
		
		/* Data set length after filtering */
		$sQuery = "
			SELECT FOUND_ROWS() AS filter_total
		";
		$aResultFilterTotal = $this->db->query($sQuery)->row();
		$iFilteredTotal = $aResultFilterTotal->filter_total;
		
		/* Total data set length */
		$sQuery = "
			SELECT COUNT(".$sIndexColumn.") AS total
			FROM $sTable
		";
		$aResultTotal = $this->db->query($sQuery)->row();
		$iTotal = $aResultTotal->total;
		
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		//'<label class="pos-rel"><input type="checkbox" class="ace" name="id[]" value="'.$row->pra_job_id.'" /><span class="lbl"></span></label>',
		
		foreach($rResult as $row){ 
			$detail = "";
			$detail_1 = "";
			
			if (_USER_ACCESS_LEVEL_VIEW == "1")  {
				$edit = '<a class="green" href="'.base_url('pra_job/edit/' . $row->pra_job_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('pra_job/edit/' . $row->pra_job_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1" && $row->status_persetujuan == "" )  {
				$edit = '<a class="green" href="'.base_url('pra_job/edit/' . $row->pra_job_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('pra_job/edit/' . $row->pra_job_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1" && $row->status_persetujuan == "")  {
				$delete = '<a class="red" href="'.base_url('pra_job/delete/' . $row->pra_job_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('pra_job/delete/' . $row->pra_job_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}
			array_push($output["aaData"],array(
				$row->pra_job_id,
				$row->tanggal_pra_job, 
				$row->shift, 
				$row->nrp,
				$row->nama, 
				$row->lama_tdr_kemarin, 
				$row->lama_tdr_sekarang,   
				$row->label_status_persetujuan,   
				'<div class="hidden-sm hidden-xs action-buttons">
					'.$detail.'
					'.$edit.'
					'.$delete.'
				</div>
				<div class="hidden-md hidden-lg">
					<div class="inline pos-rel"><button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto"><i class="ace-icon fa fa-caret-down icon-only bigger-120"></i></button>
						<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
							'.$detail_1.'
							'.$edit_1.'
							'.$delete_1.'
						</ul>
					</div>
				</div>'
			));
		}     
		echo json_encode($output);
	}

	public function delete($id= "") {
		if (isset($id) && $id <> "") {
			$this->db->where('pra_job_id',$id);
			$rs = $this->db->delete('pra_job');
			return $rs;
		} else return null;
	}   

	public function getRowData($id) { 
		$sql = "SELECT
					a.*, b.nama, 
					date_format(mulai_tidur_hari_ini, '%H:%i') as mulai_tidur_hari_ini,
					date_format(bangun_tidur_hari_ini, '%H:%i') as bangun_tidur_hari_ini
			FROM
	   	 		 pra_job a  INNER JOIN master_employee b ON a.nrp = b.nrp
	   	 	WHERE
	   	 		a.pra_job_id = '".$id."'  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 

	public function apakah_data_sudah_ada($nrp, $tgl) { 
		$sql = "SELECT
					*
			FROM
	   	 		 pra_job
	   	 	WHERE
	   	 		nrp = '".$nrp."' AND tanggal_pra_job = '".$tgl."'
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function eksportDataPerTgl($start = "", $end="") {  
		$sql = "SELECT
					a.*,
					b.nama
					,case when (TIMEDIFF(a.bangun_tidur_kemarin,a.mulai_tidur_kemarin)  > 0 ) then TIMEDIFF(a.bangun_tidur_kemarin,a.mulai_tidur_kemarin)  else '' end as lama_tdr_kemarin
					,case when (TIMEDIFF(a.bangun_tidur_hari_ini,a.mulai_tidur_hari_ini)  > 0 ) then TIMEDIFF(a.bangun_tidur_hari_ini,a.mulai_tidur_hari_ini)  else '' end as lama_tdr_sekarang
					, CASE status_persetujuan
			    		WHEN '1' THEN 'TIDAK DISETUJUI'
			    	    WHEN '2' THEN 'BUTUH PENGAWASAN'
			    		WHEN '3' THEN 'DISETUJUI'
			    		ELSE ''
			    		END AS label_status_persetujuan , 
					date_format(a.bangun_tidur_kemarin,'%Y-%m-%d') as tgl_bangun_tidur_kemarin,
					date_format(a.bangun_tidur_kemarin,'%H:%i') as jam_bangun_tidur_kemarin,
					date_format(a.mulai_tidur_kemarin,'%Y-%m-%d') as tgl_mulai_tidur_kemarin,
					date_format(a.mulai_tidur_kemarin,'%H:%i') as jam_mulai_tidur_kemarin, 

					date_format(a.bangun_tidur_hari_ini,'%Y-%m-%d') as tgl_bangun_tidur_skr,
					date_format(a.bangun_tidur_hari_ini,'%H:%i') as jam_bangun_tidur_skr,
					date_format(a.mulai_tidur_hari_ini,'%Y-%m-%d') as tgl_mulai_tidur_skr,
					date_format(a.mulai_tidur_hari_ini,'%H:%i') as jam_mulai_tidur_skr  
			    	,CASE rekomendasi
			    		WHEN '1' THEN 'Tidak disetujui'
			    	    WHEN '2' THEN 'Butuh pengawasan'
			    		WHEN '3' THEN 'Disetujui'
			    		ELSE ''
			    		END AS label_status_rekomendasi
			    	, d.keterangan as prediksi_pengawasan
			    	, c.keterangan as prediksi_stop_bekerja
			FROM
	   	 		pra_job a  INNER JOIN master_employee b ON a.nrp = b.nrp 
	   	 		LEFT JOIN 
	   	 			master_prediksi_pengawasan c 
	   	 		ON 
	   	 			a.prediksi_butuh_pengawasan = c.master_prediksi_pengawasan
	   	 		LEFT JOIN 
	   	 			master_prediksi_stop_bekerja d 
	   	 		ON 
	   	 			a.prediksi_stop_bekerja = d.master_prediksi_stop_bekerja
	   	 	WHERE
	   	 		 tanggal_pra_job >= '".$start."'  AND tanggal_pra_job <= '".$end."'
	   		ORDER BY
	   			 b.nama ASC, a.tanggal_pra_job ASC
		";   
		#echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  

	public function check_apakah_data_sudah_ada($nrp, $tanggal_pra_job) { 
		$sql = "SELECT count(*) as total from pra_job  WHERE nrp = '".$nrp."' AND tanggal_pra_job = '".$tanggal_pra_job."' ";
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"] ; 
	}
	public function import_data($list_data) {  
		$i = 0;
		FOREACH ($list_data AS $k => $v) {
			$i += 1; 
			$tanggal_pra_job  			= $v["B"];  
			$shift  					= $v["C"];   
			$nrp						= $v["D"]; 
			$mulai_tidur_kemarin		= $v["F"]." ".$v["G"];
			$bangun_tidur_kemarin		= $v["H"]." ".$v["I"]; 
			$mulai_tidur_hari_ini		= $v["J"]." ".$v["K"];
			$bangun_tidur_hari_ini		= $v["L"]." ".$v["M"];
 
			$apakah_sedang_minum_obat					= strtoupper($v["P"]); 
			$apakah_sedang_ada_masalah					= strtoupper($v["Q"]); 
			$apakah_siap_bekerja						= strtoupper($v["R"]); 
			/*
			$apakah_mempunyai_apd_yang_sesuai			= strtoupper($v["S"]); 
			$apakah_dalam_kondisi_fit					= strtoupper($v["T"]); 
			$apakah_memerlukan_ijin_khusus				= strtoupper($v["U"]); 
			$apakah_memahami_prosedur					= strtoupper($v["V"]); 
			$apakah_mempunyai_peralatan_yang_benar		= strtoupper($v["W"]); 
			$apakah_ada_aktivitas_lain_disekitar_saya	= strtoupper($v["X"]); 
			$apakah_mengenali_bahaya					= strtoupper($v["Y"]); 
			$apakah_focus								= strtoupper($v["Z"]); 
			$apakah_atasan_mengetahui					= strtoupper($v["AA"]);  
			$apakah_pekerjaan_bisa_dilanjutkan			= strtoupper($v["AB"]);  
			*/
 
			switch (strtoupper(trim($v["S"]))) {
				case "DISETUJUI"		: $status_approval = 3; break;
				case "TIDAK DISETUJUI"	: $status_approval = 1; break;
				case "BUTUH PENGAWASAN"	: $status_approval = 2; break;
				default 				: $status_approval = ''; break;
			}  

			$tmp_ada_data = $this->check_apakah_data_sudah_ada($nrp, $tanggal_pra_job);

			if ($tmp_ada_data  == 0 && $tanggal_pra_job <> "") { 
				$data = array( 
					'nrp'						=> trim($nrp),
					'tanggal_pra_job' 			=> trim($tanggal_pra_job),  
					'shift' 					=> trim($shift),  
					'mulai_tidur_kemarin' 		=> trim($mulai_tidur_kemarin),  
					'bangun_tidur_kemarin' 		=> trim($bangun_tidur_kemarin),  
					'mulai_tidur_hari_ini' 		=> trim($mulai_tidur_hari_ini), 
					'bangun_tidur_hari_ini' 	=> trim($bangun_tidur_hari_ini),   
					'apakah_sedang_minum_obat' 					=> trim($apakah_sedang_minum_obat),  
					'apakah_sedang_ada_masalah' 				=> trim($apakah_sedang_ada_masalah),  
					'apakah_siap_bekerja' 						=> trim($apakah_siap_bekerja),  
					/*
					'apakah_mempunyai_apd_yang_sesuai' 			=> trim($apakah_mempunyai_apd_yang_sesuai),  
					'apakah_dalam_kondisi_fit' 					=> trim($apakah_dalam_kondisi_fit),  
					'apakah_memerlukan_ijin_khusus' 			=> trim($apakah_memerlukan_ijin_khusus),  
					'apakah_memahami_prosedur' 					=> trim($apakah_memahami_prosedur),  
					'apakah_mempunyai_peralatan_yang_benar' 	=> trim($apakah_mempunyai_peralatan_yang_benar),  
					'apakah_ada_aktivitas_lain_disekitar_saya' 	=> trim($apakah_ada_aktivitas_lain_disekitar_saya),  
					'apakah_mengenali_bahaya' 					=> trim($apakah_mengenali_bahaya),  
					'apakah_focus' 								=> trim($apakah_focus),  
					'apakah_atasan_mengetahui' 					=> trim($apakah_atasan_mengetahui),  
					'apakah_pekerjaan_bisa_dilanjutkan'			=> trim($apakah_pekerjaan_bisa_dilanjutkan), 
					*/
					'insert_by' 								=> $_SESSION["username"]  
				);
				if ($status_approval > 0) {
					$data['status_persetujuan']	= $status_approval;
				} 
			
				$rs = $this->db->insert('pra_job', $data);
				if (!$rs) $error .=",baris ". $v["D"];
			} else {
				$error .=",baris ". $v["D"]. " GAGAL";
			}
		
		} 
		return $error;
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
		$rs = $res->row_array();
		return $rs;
	}   

		
}