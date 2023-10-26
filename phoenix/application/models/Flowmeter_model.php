<?php

class Flowmeter_model extends MY_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// fix
	public function get_list_data()
	{
		$tera = 1000;

		$aColumns = [
			'flowmeter_id',
			'tgl',
			'null',
			'status',
			'b.name',
			'flowmeter_awal',
			'flowmeter_akhir',
			'sounding_awal',
			'sounding_akhir',
			'null',
			'volume_by_sounding',
			'null'
		];

		$sIndexColumn = "flowmeter_id";
		$sTable = ' flowmeter LEFT JOIN master_fuel_tank b ON b.id = fuel_tank_id';

		/* Paging */
		$sLimit = "";
		if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1'){
			$sLimit = "LIMIT ".($_GET['iDisplayStart']).", ".
			($_GET['iDisplayLength']);
		}

		/* Ordering */
		$sOrder = "";
		if(isset($_GET['iSortCol_0'])) {
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
			$detail = "";
			$detail_1 = "";
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('flowmeter/edit/' . $row->flowmeter_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('flowmeter/edit/' . $row->flowmeter_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('flowmeter/delete/' . $row->flowmeter_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->tgl.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('flowmeter/delete/' . $row->flowmeter_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->tgl.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}

			$volumeByFlowmeter = $row->flowmeter_akhir - $row->flowmeter_awal;

			array_push($output["aaData"],array(
				$row->flowmeter_id,
				$row->tgl,
				$volumeByFlowmeter < 0 ? 'OUT' : 'IN',
				$row->status,
				$row->name,
				number_format($row->flowmeter_awal),
				number_format($row->flowmeter_akhir),
				number_format($row->sounding_awal),
				number_format($row->sounding_akhir),
				number_format($volumeByFlowmeter),
				number_format($row->volume_by_sounding),
				number_format($row->volume_by_sounding - $volumeByFlowmeter),
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

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = array(
				'tgl' 					=> $v["B"],
				'status' 				=> $v["C"],
				'fuel_tank_id' 			=> $v["D"],
				'flowmeter_awal' 		=> $v["E"],
				'flowmeter_akhir' 		=> $v["F"],
				'sounding_awal' 		=> $v["G"],
				'sounding_akhir' 		=> $v["H"],
				'volume_by_sounding' 	=> $v["I"],
				'insert_by' 			=> $_SESSION["username"]
			);

			$rs = $this->db->insert('flowmeter', $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksportDataPerTgl($start = "", $end="")
	{
		$tera = 1000;
		$sql = "SELECT
				flowmeter.*,
				if(flowmeter_awal - flowmeter_akhir > 0 , 'IN', 'OUT') as trx,
				(flowmeter_akhir - flowmeter_awal) as volume_by_flowmeter,
				volume_by_sounding - (flowmeter_akhir - flowmeter_awal) as selisih_volume,
				master_fuel_tank.name AS fuel_tank
			FROM
	   	 		flowmeter
			LEFT JOIN
				master_fuel_tank
			ON
				master_fuel_tank.id = flowmeter.fuel_tank_id
	   	 	WHERE
	   	 		tgl >= '".$start."'  AND tgl <= '".$end."'
	   		ORDER BY
	   			flowmeter_id DESC
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}



}
