<?php
class monitor_43_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}

	// fix
	public function get_list_data(){
 
		$aColumns = array('id','date_travel','eq_num','shift','muatan_1','muatan_2','muatan_3','kosongan_1','kosongan_2','kosongan_3');
		$sIndexColumn = "id";
		$sTable = ' vw_travel_time_43';
		
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
		//'<label class="pos-rel"><input type="checkbox" class="ace" name="id[]" value="'.$row->weigher_id.'" /><span class="lbl"></span></label>',
		
		foreach($rResult as $row){ 
			$detail = "";
			$detail_1 = "";
			/*
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				$detail = '<a class="blue" href="'.base_url('timbangan_port/detail/' . $row->weigher_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('timbangan_port/detail/' . $row->weigher_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
			}
			*/
			// $edit = "";
			// $edit_1 = "";
			// if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
			// 	$edit = '<a class="green" href="'.base_url('monitor_43/edit/' . $row->id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
			// 	$edit_1 = '<li><a href="'.base_url('monitor_43/edit/' . $row->id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			// }
			// $delete = "";
			// $delete_1 = "";
			// if (_USER_ACCESS_LEVEL_DELETE == "1")  {
			// 	$delete = '<a class="red" href="'.base_url('monitor_43/delete/' . $row->id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->id'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
			// 	$delete_1 = '<li><a href="'.base_url('monitor_43/delete/' . $row->id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->id.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			// }

			array_push($output["aaData"],array(
				$row->id,
				$row->date_travel,
				$row->eq_num, 
				$row->shift, 
				$row->muatan_1, 
				$row->muatan_2, 
				$row->muatan_3, 
				$row->kosongan_1, 
				$row->kosongan_2, 
				$row->kosongan_3
				// '<div class="hidden-sm hidden-xs action-buttons">
				// 	'.$detail.'
				// 	'.$edit.'
				// 	'.$delete.'
				// </div>
				// <div class="hidden-md hidden-lg">
				// 	<div class="inline pos-rel"><button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto"><i class="ace-icon fa fa-caret-down icon-only bigger-120"></i></button>
				// 		<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
				// 			'.$detail_1.'
				// 			'.$edit_1.'
				// 			'.$delete_1.'
				// 		</ul>
				// 	</div>
				// </div>'
			));
		}     
		echo json_encode($output);
	}

	// public function delete($id= "") {
	// 	if (isset($id) && $id <> "") {
	// 		$this->db->where('weigher_id',$id);
	// 		$rs = $this->db->delete('weigher');
	// 		return $rs;
	// 	} else return null;
	// }  

	// public function add_data() { 
	// 	$netto = $this->input->post('bruto') - $this->input->post('tara') ;
	// 	$data = array( 
	// 		'ritase' 	=> '1',  
	// 		'equipment_id' 	=> trim($this->input->post('equipment_id')),  
	// 		'shift' 		=> trim($this->input->post('shift')),   
	// 		'netto' 		=> $netto, 
	// 		'bruto' 		=> $this->input->post('bruto'),
	// 		'tara' 			=> $this->input->post('tara') ,
	// 		'insert_by' 	=> $_SESSION["username"] , 
	// 		'date_weigher' 	=> trim($this->input->post('date_weigher')) ,
	// 		'time_weigher' 	=> trim($this->input->post('time_weigher')) , 
	// 		'station_id'	=> '3',
	// 		'no_doket' 		=> trim($this->input->post('no_doket')) , 
	// 		'material_id' 	=> trim(strtoupper($this->input->post('material_id')))
	// 	);
	
	// 	return $rs = $this->db->insert('weigher', $data);
	// }  

	public function check_unit($unit) {
		$unit 	= strtoupper(trim($unit)); 
	
		$sql = "SELECT * from master_equipment WHERE LOWER(new_eq_num) = '".strtolower(trim($unit))."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs["new_eq_num"] ; 
		
	}

	public function check_apakah_data_sudah_ada($eq_num, $date_travel, $shift , $muatan_1 , $muatan_2, $muatan_3, $kosongan_1, $kosongan_2, $kosongan_3) { 
		$sql = "SELECT count(*) as total from travel_time_43 WHERE eq_num = '".$eq_num."' AND shift =".$shift." AND date_travel ='".$date_travel."' AND muatan_1 ='".$muatan_1."' AND muatan_2 ='".$muatan_2."' AND muatan_3 ='".$muatan_3."' AND kosongan_1 ='".$kosongan_1."' AND kosongan_2 ='".$kosongan_2."' AND kosongan_3 = '".$kosongan_3."' ";
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"] ; 
	}

	public function import_data($list_data) {  
		$i = 0;
		//log_message('debug', $list_data); 
		FOREACH ($list_data AS $k => $v) {
			$i += 1;
			$eq_num  		= $this->check_unit($v["B"]);  
			$date_travel	= $v["C"];
			//$v["G"] 		= str_replace("S", "", $v["G"]);
			$shift			= $v["D"];
			$muatan_1		= $v["E"];
			$muatan_2		= $v["F"];
			$muatan_3		= $v["G"];
			$kosongan_1		= $v["H"];
			$kosongan_2		= $v["I"];
			$kosongan_3		= $v["J"];
			

			// if (empty($v["M"])) $v["M"] = "";
			// if (empty($v["N"])) $v["N"] = "";
			
			// $no_doket		= $v["M"]; 
			// $material_id	= $v["N"]; 

			$tmp_ada_data = $this->check_apakah_data_sudah_ada($eq_num, $date_travel ,$shift, $muatan_1 , $muatan_2, $muatan_3, $kosongan_1, $kosongan_2, $kosongan_3);

			if ($tmp_ada_data  == 0 && $eq_num <> "") { 
				$data = array( 
					'eq_num' 		=> $eq_num,  
					'date_travel' 	=> $date_travel,   
					'shift' 		=> $shift, 
					'muatan_1' 		=> $muatan_1,
					'muatan_2' 		=> $muatan_2,
					'muatan_3' 		=> $muatan_3, 
					'kosongan_1' 	=> $kosongan_1,
					'kosongan_2' 	=> $kosongan_2, 
					'kosongan_3'	=> $kosongan_3,
					'insert_by' 	=> $_SESSION["username"], 
				);
			
				$rs = $this->db->insert('travel_time_43', $data);
				if (!$rs) $error .=",baris ". $v["B"];
			} else {
				$error .=",baris ". $v["B"]. " GAGAL";
			}
		
		} 
		return $error;
	}  

	// public function edit_data() {
	// 	#$netto = $this->input->post('bruto') - $this->input->post('tara');
	// 	$netto = $this->input->post('bruto') - $this->input->post('tara') ;
	// 	$data = array( 
	// 		'ritase' 	=> '1',  
	// 		'equipment_id' 	=> trim($this->input->post('equipment_id')),  
	// 		'shift' 		=> trim($this->input->post('shift')),   
	// 		'netto' 		=> $netto, 
	// 		'bruto' 		=> $this->input->post('bruto'),
	// 		'tara' 			=> trim($this->input->post('tara')) ,
	// 		'date_weigher' 	=> trim($this->input->post('date_weigher')) ,
	// 		'time_weigher' 	=> trim($this->input->post('time_weigher')) , 
	// 		'station_id'	=> '3',
	// 		'no_doket' 		=> trim($this->input->post('no_doket')) , 
	// 		'material_id' 	=> trim(strtoupper($this->input->post('material_id')))
	// 	);
	// 	$where = "weigher_id=".$this->input->post('old_id');
	
	// 	return $rs = $this->db->update('weigher', $data, $where); 
	// }  

	public function getRowData($id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 weigher a  
	   	 	WHERE
	   	 		a.weigher_id = '".$id."'  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 

	public function getTimbanganPort($start = "", $end="") {  
		$sql = "SELECT
					weigher_id,b.new_eq_num AS unit, e.keterangan as egi ,
					d.kode as owner, c.alokasi,a.date_weigher
			FROM
	   	 		weigher a 
	   	 	LEFT JOIN 
	   	 		master_equipment b 
	   	 	ON 
	   	 		a.equipment_id = b.master_equipment_id 
			LEFT JOIN master_alokasi c ON b.master_alokasi_id = c.master_alokasi_id
			LEFT JOIN master_owner d ON b.master_owner_id = d.master_owner_id
			LEFT JOIN master_egi e ON b.master_egi_id = e.master_egi_id
	   	 	WHERE
	   	 		a.date_weigher >= '".$start."'  AND a.date_weigher <= '".$end."' AND station_id = 2
	   		ORDER BY
	   			a.weigher_id DESC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function getTaraKemarin($id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 weigher a  
	   	 	WHERE
	   	 		a.equipment_id = '".$id."' AND station_id  = 3
	   	 	ORDER BY weigher_id DESC LIMIT 1
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 

		
}