<?php
class Pitstop_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}
	 

	// fix
	public function get_list_data(){
		$aColumns = array('pitstop_id','c.lokasi','b.new_eq_num','a.shift','a.date_time_in','a.date_time_out','a.description','hm');
		$sIndexColumn = "pitstop_id";
		$sTable = ' pitstop a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id 
		LEFT JOIN  master_lokasi c ON a.station_id=c.master_lokasi_id';
		
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
				$detail = '<a class="blue" href="'.base_url('pitstop/detail/' . $row->pitstop_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('pitstop/detail/' . $row->pitstop_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
			}
			*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('pitstop/edit/' . $row->pitstop_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('pitstop/edit/' . $row->pitstop_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('pitstop/delete/' . $row->pitstop_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('pitstop/delete/' . $row->pitstop_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}

			$dateout = "";
			if ($row->date_time_out != "0000-00-00 00:00:00") $dateout = $row->date_time_out;
			array_push($output["aaData"],array(
				$row->pitstop_id,
				$row->lokasi,
				$row->new_eq_num,
				$row->shift, 
				$row->date_time_in, 
				$dateout,  
				$row->description,  
				$row->hm,  
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
			$this->db->where('pitstop_id',$id);
			$rs = $this->db->delete('pitstop');
			return $rs;
		} else return null;
	}  
	public function add_data() {
		$data = array( 
			'insert_by' 	=> $_SESSION["username"],  
			'station_id' 	=> trim($this->input->post('station_id')),  
			'shift' 		=> trim($this->input->post('shift')),  
			'equipment_id' 		=> trim($this->input->post('equipment_id')) ,
			'hm' 					=> trim($this->input->post('hm')),  
			'date_time_in' 			=> trim($this->input->post('date_time_in')) 
		);
	
		return $rs = $this->db->insert('pitstop', $data);
	}  

	public function add_data_to_breakdown() {
		$data = array( 
			'equipment_id' 			=> trim($this->input->post('equipment_id')) ,
			'master_breakdown_id' 	=> 1,  
			'status_breakdown_id' 	=> 'B/D',  
			'master_lokasi_id' 		=> trim($this->input->post('station_id')), 
			'hm' 					=> trim($this->input->post('hm')),   
			//'hm' 					=> trim($this->input->post('hm')),  
			//'km' 					=> trim($this->input->post('km')), 
			'date_time_in' 			=> trim($this->input->post('date_time_in')) , 
			'diagnosa' 				=> trim($this->input->post('description')),   
			// 'no_wo' 				=> trim($this->input->post('no_wo')),   
			'insert_by' 			=> $_SESSION["username"]
		);
	
		return $rs = $this->db->insert('breakdown', $data);
	}  

	public function update_data() {
		$data = array( 
			'station_id' 	=> trim($this->input->post('station_id')),  
			'shift' 		=> trim($this->input->post('shift')),  
			'equipment_id' 		=> trim($this->input->post('equipment_id')) ,
			'date_time_in' 			=> trim($this->input->post('date_time_in')) ,
			'date_time_out' 			=> trim($this->input->post('date_time_out')) ,
			'hm' 					=> trim($this->input->post('hm')),  
			'status'			=> 1,
			'description' 			=> trim($this->input->post('description')) 
		);
		$where = "pitstop_id=".$this->input->post('old_id');
	
		return $rs = $this->db->update('pitstop', $data, $where);
	}  
	public function getRowData($id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 pitstop a  
	   	 	WHERE
	   	 		a.pitstop_id = '".$id."'  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 

	public function getDataPistop($start = "", $end="") {  
		$sql = "SELECT
					c.lokasi as station_name,
					b.new_eq_num,
					a.shift,
					date_format(a.date_time_in,'%Y-%m-%d') as date_in,
					date_format(a.date_time_in,'%H:%i') as time_in,
					date_format(a.date_time_out,'%Y-%m-%d') as date_out,
					date_format(a.date_time_out,'%H:%i') as time_out, 
					a.description,
					pitstop_id,
					a.hm
			FROM
	   	 		pitstop a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id 
	   	 		LEFT JOIN  master_lokasi c ON a.station_id=c.master_lokasi_id
	   	 	WHERE
	   	 		date_format(a.date_time_in,'%Y-%m-%d')>= '".$start."'  AND date_format(a.date_time_in,'%Y-%m-%d') <= '".$end."'
	   		ORDER BY
	   			a.pitstop_id DESC
		";   
		#echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  

	public function get_station_id($unit) {
		$unit 	= strtoupper(trim($unit));  
		 
		$sql = "SELECT * from master_lokasi WHERE LOWER(lokasi) = '".strtolower(trim($unit))."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		if ($rs["master_lokasi_id"] =="") {
			$sqli = "INSERT INTO master_lokasi (lokasi) VALUES ('".trim($unit)."')";
			$resi = $this->db->query($sqli);
			$lastid = $this->db->insert_id();
			return $lastid;
		} else {
			return $rs["master_lokasi_id"];
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

	public function check_apakah_data_sudah_ada($equipment_id, $date_time_in) { 
		$sql = "SELECT count(*) as total from pitstop  WHERE equipment_id = ".$equipment_id." AND date_time_in ='".$date_time_in."' ";
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"] ; 
	}
	public function import_data($list_data) {  
		$i = 0;
		FOREACH ($list_data AS $k => $v) {
			$i += 1; 
			$station_id  	= $this->get_station_id($v["B"]);  
			$unit_id  		= $this->get_unit_id($v["C"]);   
			$shift			= $v["D"];
			$date_start		= $v["E"]." ".$v["F"];
			$date_end		= $v["G"]." ".$v["H"];
			$desc			= $v["I"];   
			$hm				= $v["J"];   

			$tmp_ada_data = $this->check_apakah_data_sudah_ada($unit_id, $date_start);

			if ($tmp_ada_data  == 0 && $unit_id <> "") {
				$data = array( 
					'station_id' 		=> $station_id,  
					'equipment_id' 		=> $unit_id,  
					'shift' 			=> $shift,  
					'date_time_in' 		=> $date_start,  
					'date_time_out' 	=> $date_end, 
					'description' 		=> $desc,  
					'hm' 				=> $hm,  
					'insert_by' 		=> $_SESSION["username"]  
				);
			
				$rs = $this->db->insert('pitstop', $data);
				if (!$rs) $error .=",baris ". $v["C"];
			} else {
				$error .=",baris ". $v["C"]. " GAGAL";
			}
		
		} 
		return $error;
	}  

	public function get_list_data_antrian() {
		$sql = "SELECT
					pitstop_id , c.lokasi , b.new_eq_num , a.shift , a.date_time_in , a.date_time_out , a.description , hm
					,TIMEDIFF(now(),a.date_time_in)  as durasi
			FROM
	   	 		 pitstop a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id 
				LEFT JOIN  master_lokasi c ON a.station_id=c.master_lokasi_id
	   	 	WHERE 
	   	 		a.status <> 1
	   		ORDER BY
	   			TIMEDIFF(now(),a.date_time_in)  DESC,
	   			a.date_time_in
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function get_list_ws() {
		$data = "";
		$sql = "SELECT
					*
			FROM
	   	 		master_lokasi
	   	 	WHERE 
	   	 		master_lokasi_id IN ('93','94')
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		if (count($rs)) {
			FOREACH ($rs AS $r) {
				$data[$r["master_lokasi_id"]]  = $r["lokasi"];
			}
		}
		return $data;
	} 

		
}