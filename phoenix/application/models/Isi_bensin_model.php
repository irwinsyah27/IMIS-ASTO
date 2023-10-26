<?php
class Isi_bensin_model extends MY_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// fix
	public function get_list_data(){
		$aColumns = [
			'a.fuel_refill_id',
			'a.date_fill',
			'd.name',
			'b.new_eq_num',
			'c.alokasi',
			'a.shift',
			'a.total_realisasi',
			'a.hm',
			'a.km',
			'a.hm_last',
			'a.km_last',
			'a.nrp',
			'a.time_fill_start',
			'a.time_fill_end',
			'a.realisasi_by',
			'a.realisasi_by'
		];

		$sIndexColumn = "fuel_refill_id";
		$sTable = ' fuel_refill a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id
		LEFT JOIN master_alokasi c ON b.master_alokasi_id = c.master_alokasi_id LEFT JOIN master_fuel_tank d ON d.id = a.fuel_tank_id';

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
		,case when (TIMEDIFF(a.time_fill_end,a.time_fill_start)  > 0 ) then TIMEDIFF(a.time_fill_end,a.time_fill_start)  else '' end as durasi
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
		//'<label class="pos-rel"><input type="checkbox" class="ace" name="id[]" value="'.$row->weigher_id.'" /><span class="lbl"></span></label>',

		foreach($rResult as $row){
			$detail = "";
			$detail_1 = "";
			/*
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				$detail = '<a class="blue" href="'.base_url('isi_bensin/detail/' . $row->fuel_refill_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('isi_bensin/detail/' . $row->fuel_refill_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';
			}
			*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('isi_bensin/edit/' . $row->fuel_refill_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('isi_bensin/edit/' . $row->fuel_refill_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('isi_bensin/delete/' . $row->fuel_refill_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('isi_bensin/delete/' . $row->fuel_refill_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}

			if ($row->realisasi_by == "fuel") $insert_by = "MainTank45"; else $insert_by = $row->realisasi_by;

			array_push($output["aaData"],array(
				$row->fuel_refill_id,
				$row->date_fill,
				$row->name,
				$row->new_eq_num,
				$row->alokasi,
				$row->shift,
				$row->total_realisasi,
				$row->hm,
				$row->km,
				$row->hm_last,
				$row->km_last,
				$row->nrp,
				$row->time_fill_start,
				$row->time_fill_end,
				$row->durasi,
				$insert_by,
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

	public function antrian_instruksi() {
		$sql = "SELECT
				a.*, b.new_eq_num as unit
			FROM
	   	 		 fuel_refill a
	   	 	LEFT JOIN
	   	 		master_equipment b
	   	 	ON
	   	 		a.equipment_id = b.master_equipment_id
			LEFT JOIN
				master_fuel_tank c
			ON
				c.id = a.fuel_tank_id
	   	 	WHERE
	   	 		date_realisasi IS NULL
	   	 	ORDER BY
	   	 		a.date_instruksi
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}

	public function check_unit($unit, $egi, $owner , $lokasi) {
		$unit 	= strtoupper(trim($unit));
		$egi 	= strtoupper(trim($egi));
		$owner 	= strtoupper(trim($owner));
		$lokasi = strtoupper(trim($lokasi));
		# egi
		$sql = "SELECT * from master_egi WHERE LOWER(keterangan) = '".strtolower(trim($egi))."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		if ($rs["master_egi_id"] =="") {
			$sqli = "INSERT INTO master_egi (keterangan) VALUES ('".trim($egi)."')";
			$resi = $this->db->query($sqli);
			$egi_id = $this->db->insert_id();
		} else {
			$egi_id = $rs["master_egi_id"];
		}
		# owner
		$sql = "SELECT * from master_owner WHERE LOWER(keterangan) = '".strtolower(trim($owner))."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		if ($rs["master_owner_id"] =="") {
			$sqli = "INSERT INTO master_owner (keterangan) VALUES ('".trim($owner)."')";
			$resi = $this->db->query($sqli);
			$owner_id = $this->db->insert_id();
		} else {
			$owner_id = $rs["master_owner_id"];
		}
		# lokasi
		$sql = "SELECT * from master_alokasi WHERE LOWER(alokasi) = '".strtolower(trim($lokasi))."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		if ($rs["master_alokasi_id"] =="") {
			$sqli = "INSERT INTO master_alokasi (alokasi) VALUES ('".trim($lokasi)."')";
			$resi = $this->db->query($sqli);
			$alokasi_id = $this->db->insert_id();
		} else {
			$alokasi_id = $rs["master_alokasi_id"];
		}

		$sql = "SELECT * from master_equipment WHERE LOWER(new_eq_num) = '".strtolower(trim($unit))."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		if ($rs["master_equipment_id"] =="") {
			$sqli = "INSERT INTO master_equipment (master_egi_id, new_eq_num, master_owner_id, master_alokasi_id) VALUES ('".$egi_id."','".$unit."','".$owner_id."','".$alokasi_id."')";
			$resi = $this->db->query($sqli);
			$unit_id = $this->db->insert_id();
			return $unit_id;
		} else {
			return $rs["master_equipment_id"];
		}

	}

	public function get_unit_id($unit) {
		$unit 	= strtoupper(trim($unit));

		$sql = "SELECT * from master_equipment WHERE LOWER(new_eq_num) = '".strtolower(trim($unit))."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		if ($rs["master_equipment_id"] =="") {
			$sqli = "INSERT INTO master_equipment (new_eq_num) VALUES ('".trim($unit)."')";
			$resi = $this->db->query($sqli);
			$lastid = $this->db->insert_id();
			return $lastid;
		} else {
			return $rs["master_equipment_id"];
		}
	}

	public function get_fuel_tank_id($fuel_tank_name){
		$fuel_tank_name = strtoupper(trim($fuel_tank_name));

		$sql = "SELECT * from  master_fuel_tank  WHERE name LIKE '".$fuel_tank_name."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		if ($rs["id"] =="") {
			return "";
		} else {
			return $rs["id"];
		}
	}

	public function check_apakah_data_sudah_ada($unit, $shift, $date_fill, $time_fill) {
		$sql = "SELECT count(*) as total   from fuel_refill  WHERE equipment_id = ".$unit." AND shift =".$shift." AND date_fill ='".$date_fill."'  AND time_fill_start = '".$time_fill."' ";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs["total"] ;
	}

	public function check_apakah_maintank_terdaftar($fuel_tank_id){
		$sql = "SELECT count(*) as total from master_fuel_tank  WHERE name like %".$fuel_tank_id."%";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs["total"] ;
	}

	public function import_data($list_data) {
		$i = 0;
		FOREACH ($list_data AS $k => $v) {
			$i += 1;
			#$truck_id  		= $this->check_unit($v["B"] , $v["C"] , $v["D"]  ,$v["F"] );

			$truck_id  		= $this->get_unit_id($v["C"]);
			$fuel_tank_id   =  $this->get_fuel_tank_id($v["D"]);
			$v["E"] 		= str_replace("S", "", $v["E"]);

			$tmp_ada_data = $this->check_apakah_data_sudah_ada($truck_id, trim($v["E"]), trim($v["B"]) , trim($v["L"]));

			if ($tmp_ada_data  == 0 && $truck_id <> "" && $fuel_tank_id <> "" ) {
				$data = array(
					'nrp' 				=> trim($v["K"]),
					'equipment_id' 		=> $truck_id,
					'shift' 			=> trim($v["E"]),
					'total_realisasi' 	=> trim($v["F"]) ,
					'date_fill' 		=> trim($v["B"]),
					'time_fill_start' 	=> trim($v["L"]),
					'time_fill_end' 	=> trim($v["M"]),
					'hm' 				=> trim($v["G"]),
					'km' 				=> trim($v["H"]),
					'hm_last' 			=> trim($v["I"]),
					'km_last' 			=> trim($v["J"]),
					'fuel_tank_id'		=> $fuel_tank_id,
					'realisasi_by' 		=> $_SESSION["username"]
				);

				$rs = $this->db->insert('fuel_refill', $data);
				if (!$rs) $error .=",baris ". $v["C"];
			} else {
				$error .=",baris ". $v["C"]. " GAGAL";
			}

		}
		return $error;
	}

	public function getCycleTime($equipment_id) {
		$sql = "SELECT
					total_cycle_time
			FROM
	   	 		 daily_absent a
	   	 	INNER JOIN
	   	 		  master_equipment b ON a.unit = b.new_eq_num
	   	 	WHERE
	   	 		b.master_equipment_id = '".$equipment_id."'
	   	 	ORDER BY
	   	 		a.date_insert DESC
	   	 	LIMIT 1
		";
		//echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	}

	public function getSettingCycleTime() {
		$sql = "SELECT
					*
			FROM
	   	 		 master_table_setting
		";
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	}

	public function getDataPengisianSolar($start = "", $end="") {
		$sql = "SELECT
					a.fuel_refill_id, d.name, a.date_fill,b.new_eq_num,a.shift,
					a.total_realisasi,a.hm,a.km,a.nrp,a.time_fill_start,
					a.time_fill_end,km_last, hm_last, c.alokasi
					,case when (TIMEDIFF(a.time_fill_end,a.time_fill_start)  > 0 ) then TIMEDIFF(a.time_fill_end,a.time_fill_start)  else '' end as durasi,
					a.realisasi_by
			FROM fuel_refill a
	   	 	LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id
	   	 	LEFT JOIN master_alokasi c ON b.master_alokasi_id = c.master_alokasi_id
	   	 	LEFT JOIN master_fuel_tank d ON d.id = a.fuel_tank_id
	   	 	WHERE a.date_fill >= '".$start."'  AND a.date_fill <= '".$end."'
	   		ORDER BY a.fuel_refill_id DESC
		";
		#echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}
	public function gethmterakhir($equipment_id) {
		$sql = "SELECT
					*
			FROM
	   	 		 fuel_refill
	   	 	WHERE
	   	 		equipment_id = '".$equipment_id."'
	   	 	ORDER BY
	   	 		fuel_refill_id DESC
	   	 	LIMIT 1
		";
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	}

}
