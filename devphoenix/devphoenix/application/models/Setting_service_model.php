<?php
class Setting_service_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}
	 

	// fix
	public function get_list_data(){ 
		$aColumns = array('setting_service_id','tgl','unit','keterangan_ps');
		$sIndexColumn = "setting_service_id";
		$sTable = ' setting_service';
		
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
		//'<label class="pos-rel"><input type="checkbox" class="ace" name="id[]" value="'.$row->setting_service_id.'" /><span class="lbl"></span></label>',
		
		foreach($rResult as $row){ 
			$detail = "";
			$detail_1 = ""; 
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('setting_service/edit/' . $row->setting_service_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('setting_service/edit/' . $row->setting_service_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('setting_service/delete/' . $row->setting_service_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->tgl.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('setting_service/delete/' . $row->setting_service_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->tgl.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}
			array_push($output["aaData"],array(
				$row->setting_service_id, 
				$row->tgl,
				$row->unit,  
				$row->keterangan_ps,  
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
			$this->db->where('setting_service_id',$id);
			$rs = $this->db->delete('setting_service');
			return $rs;
		} else return null;
	}  
	public function add_data() { 
		$data = array(  
			'tgl' 			=> trim($this->input->post('tgl')),  
			'unit' 			=> trim($this->input->post('unit')),
			'keterangan_ps' => trim($this->input->post('keterangan_ps')),
			'insert_by' 	=> $_SESSION["username"] 
		);
	
		return $rs = $this->db->insert('setting_service', $data);
	}    

	public function check_apakah_data_sudah_ada($tgl, $unit) { 
		$sql = "SELECT count(*) as total from setting_service  WHERE tgl = '".$tgl."' AND unit = '".$unit."' ";
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"] ; 
	}

	public function import_data($list_data) {  
		$i = 0;
		FOREACH ($list_data AS $k => $v) {
			$i += 1; 

			$tmp_ada_data = $this->check_apakah_data_sudah_ada($v["B"], $v["C"]);

			if ($tmp_ada_data  == 0 && $v["B"] <> "") {  
				$data = array(  
					'tgl' 			=> $v["B"],  
					'unit' 			=> $v["C"], 
					'keterangan_ps' => $v["D"],
					'insert_by' 	=> $_SESSION["username"]  
				);
			
				$rs = $this->db->insert('setting_service', $data);
				if (!$rs) $error .=",baris ". $v["C"]; 
			} else {
				$error .=",baris ". $v["C"]. " GAGAL";
			}
		} 
		return $error;
	}  

	public function edit_data() { 
		$data = array( 
			'tgl' 			=> trim($this->input->post('tgl')),  
			'unit' 			=> trim($this->input->post('unit')),
			'keterangan_ps' => trim($this->input->post('keterangan_ps')),
		);
		$where = "setting_service_id=".$this->input->post('old_id');
	
		return $rs = $this->db->update('setting_service', $data, $where); 
	}  

	public function getRowData($id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 setting_service a  
	   	 	WHERE
	   	 		a.setting_service_id = '".$id."'  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function eksportDataPerTgl($start = "", $end="") {  
		$sql = "SELECT
					*
			FROM
	   	 		setting_service   
	   	 	WHERE tgl >= '$start' AND tgl <= '$end'
	   		ORDER BY
	   			setting_service_id ASC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function getListDataBerdasarkanTgl($tgl) {  
		$sql = "SELECT
					a.*  , b.master_equipment_id
			FROM
	   	 		setting_service a 
	   	 	INNER JOIN 
	   	 		master_equipment b 
	   	 	ON 
	   	 		a.unit = b.new_eq_num  
	   	 	WHERE
	   	 		a.tgl = '".$tgl."'
	   		ORDER BY
	   			b.new_eq_num
		";   
		//echo $sql."<br>";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  

	public function jsonGetListDataScheduleServiceByTgl($tgl)
	{					
		$unit_in = "";
		$sql = "SELECT
					distinct a.equipment_id , b.new_eq_num
			FROM
	   	 		breakdown a 
	   	 	INNER JOIN 
	   	 		master_equipment b 
	   	 	ON 
	   	 		a.equipment_id = b.master_equipment_id 
	   	 	WHERE
	   	 		a.master_breakdown_id = 1 AND ( 
		   	 		(DATE_FORMAT(date_time_in,'%Y-%m-%d')   < '$tgl'  AND a.status = 0 )
					OR 
					(DATE_FORMAT(date_time_in,'%Y-%m-%d')   = '$tgl')
				)
	   		ORDER BY
	   			 b.new_eq_num
		";  
		#echo $sql."<br>";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		if (count($rs)>0) {
			FOREACH ($rs AS $r) {
				$unit_in[$r["equipment_id"]] = "IN";
			}
		}

		$sql = "SELECT
					a.*  , b.master_equipment_id, b.new_eq_num
			FROM
	   	 		setting_service a 
	   	 	INNER JOIN 
	   	 		master_equipment b 
	   	 	ON 
	   	 		a.unit = b.new_eq_num  
	   	 	WHERE
	   	 		a.tgl = '".$tgl."'
	   		ORDER BY
	   			b.new_eq_num
		";  
		#echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		if (count($rs)>0) {
			$no = 0;
			FOREACH ($rs AS $l) {
				$tmp = $l["master_equipment_id"];
				$data[$no]["unit"] 				= $l["unit"];  
				$data[$no]["keterangan_ps"] 	= $l["keterangan_ps"];  
				if (isset($unit_in[$tmp])) {
					$data[$no]["masuk"]	= "IN";
				} else {
					$data[$no]["masuk"]	= "";
				}
				$no += 1;
			}
		}
		echo json_encode($data, JSON_NUMERIC_CHECK); 
	} 
 

		
}