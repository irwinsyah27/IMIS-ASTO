<?php
class Fatigue_monitor_model extends MY_MODEL { 
	function __construct(){
		parent::__construct();
	}

	public function get_list_data()
	{
		$tera = 1000;

		$aColumns = [
			'frm_id',
			'date_prajob',
			'nrp_opr',
			'nama_opr',
			'nrp_gl',
			'nama_gl',
			'shift',
			'lokasi',
			'no_unit',
			'JAM_TIDUR_HARI_PRA_JOB',
			'JAM_TIDUR_SEBELUM_HARI_PRA_JOB',
			'jam_butuh_pengawasan',
			'jam_stop_bekerja',
			'STATUS_FATIGUE'
		];

		$sIndexColumn = "frm_id";
		$sTable = 'vw_status_fatigue';

		/* Paging */
		$sLimit = "";
		if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1'){
			$sLimit = "LIMIT ".($_GET['iDisplayStart']).", ".
			($_GET['iDisplayLength']);
		}

		/* Ordering */
		$sOrder = "";
		if(isset($_GET['iSortCol_0'])) {
			$sOrder = "ORDER BY";
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

			foreach ($aColumns as $c) {
				$sWhere .= $c." LIKE '%".($_GET['sSearch'])."%' OR ";
			}

			$sWhere = substr_replace( $sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		for($i=0 ; $i<count($aColumns) ; $i++) {
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
		FROM $sTable
		$sWhere
		$sOrder
		$sLimit
		";
		# echo $sQuery;exit;
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
		//'<label class="pos-rel"><input type="checkbox" class="ace" name="id[]" value="'.$row->flowmeter_id.'" /><span class="lbl"></span></label>',

		foreach($rResult as $row)
		{
			array_push($output["aaData"],array(
				$row->frm_id,
				$row->date_prajob,
				$row->nrp_opr,
				$row->nama_opr,
				$row->nrp_gl,
				$row->nama_gl,
				$row->shift,
				$row->lokasi,
				$row->no_unit,
				$row->JAM_TIDUR_HARI_PRA_JOB,
				$row->JAM_TIDUR_SEBELUM_HARI_PRA_JOB,
				$row->jam_butuh_pengawasan,
				$row->jam_stop_bekerja,
				$row->STATUS_FATIGUE
			));
		}

		echo json_encode($output);
	}
 
	public function getGl() {
		$sql = "
				SELECT 	nrp, nama
				FROM
					master_employee
				WHERE 
					master_departemen_id = 4 
					AND master_posisi_id = 8
				ORDER BY nama
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}

	public function getLokasi() {
		$sql = "
				select 
					master_lokasi_id,lokasi 
				from 
					master_lokasi
				where 
					master_lokasi_id in (141,163,164,165) 
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}
}