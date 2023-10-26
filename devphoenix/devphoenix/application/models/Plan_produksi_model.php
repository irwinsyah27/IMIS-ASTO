<?php
class Plan_produksi_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}
	 

	// fix
	public function get_list_data(){
 

		$aColumns = array('plan_produksi_id','b.plan_category','a.date','a.time_start','a.time_end','a.delay');
		$sIndexColumn = "plan_produksi_id";
		$sTable = ' plan_produksi a LEFT JOIN plan_category b ON a.plan_category_id = b.plan_category_id';
		
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
		//'<label class="pos-rel"><input type="checkbox" class="ace" name="id[]" value="'.$row->plan_produksi_id.'" /><span class="lbl"></span></label>',
		
		foreach($rResult as $row){ 
			$detail = "";
			$detail_1 = "";
			/*
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				$detail = '<a class="blue" href="'.base_url('plan_produksi/detail/' . $row->plan_produksi_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('plan_produksi/detail/' . $row->plan_produksi_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
			}
			*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('plan_produksi/edit/' . $row->plan_produksi_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('plan_produksi/edit/' . $row->plan_produksi_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('plan_produksi/delete/' . $row->plan_produksi_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->plan_category.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('plan_produksi/delete/' . $row->plan_produksi_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->plan_category.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}
			array_push($output["aaData"],array(
				$row->plan_produksi_id,
				$row->plan_category,
				$row->date, 
				$row->time_start, 
				$row->time_end, 
				$row->delay, 
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
			$this->db->where('plan_produksi_id',$id);
			$rs = $this->db->delete('plan_produksi');
			return $rs;
		} else return null;
	}  
	public function add_data() { 
		$data = array(  
			'plan_category_id' 	=> trim($this->input->post('plan_category_id')),  
			'date' 				=> trim($this->input->post('date')),   
			'time_start' 		=> $this->input->post('time_start'), 
			'time_end' 			=> $this->input->post('time_end'),
			'delay' 			=> trim($this->input->post('delay')) ,
			'insert_by' 		=> $_SESSION["username"] 
		);
	
		return $rs = $this->db->insert('plan_produksi', $data);
	}   
	
	public function check_data_and_insert_if_empty($table="", $pk, $field1="" , $val1="") {
		$val1 	= strtoupper(trim($val1));  
		
		if ($val1 <> "") {
			$sql = "SELECT * from $table WHERE LOWER($field1) = '".strtolower(trim($val1))."'";
			$res  = $this->db->query($sql);
			$rs = $res->row_array(); 
			if ($rs[$field1] =="") {
				$sqli = "INSERT INTO $table ($field1) VALUES ('".trim($val1)."')";
				$resi = $this->db->query($sqli);  
				return $field1;
			} else {
				return $rs[$pk];
			}  	
		} 
	}

	public function check_apakah_data_sudah_ada($plan_category_id, $date, $time_start) { 
		$sql = "SELECT count(*) as total from plan_produksi  WHERE plan_category_id = ".$plan_category_id." AND date = '".$date."'  AND time_start = '".$time_start."' ";
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"] ; 
	}

	public function import_data($list_data) {  
		$i = 0;
		FOREACH ($list_data AS $k => $v) {
			$i += 1;
			$plan_id  		= $this->check_data_and_insert_if_empty("plan_category" ,"plan_category_id", "plan_category" , $v["B"]);  

			$tanggal		= $v["C"]; 
			$time_start		= $v["D"];
			$time_end		= $v["E"];
			$delay			= $v["F"];  

			$tmp_ada_data = $this->check_apakah_data_sudah_ada($plan_id, $tanggal, $time_start);

			if ($tmp_ada_data  == 0 && $plan_id <> "") { 
				$data = array(  
					'plan_category_id' 	=> $plan_id,  
					'date' 				=> $tanggal,   
					'time_start' 		=> $time_start, 
					'time_end' 			=> $time_end,
					'delay' 			=> $delay,
					'insert_by' 		=> $_SESSION["username"]  
				);
			
				$rs = $this->db->insert('plan_produksi', $data);
				if (!$rs) $error .=",baris ". $v["B"];
			} else {
				$error .=",baris ". $v["B"]. " GAGAL";
			}
		
		} 
		return $error;
	}  

	public function edit_data() {
		#$netto = $this->input->post('bruto') - $this->input->post('tara');
		$data = array( 
			'plan_category_id' 	=> trim($this->input->post('plan_category_id')),  
			'date' 		=> trim($this->input->post('date')),   
			'time_start' 		=> $this->input->post('time_start'), 
			'time_end' 		=> $this->input->post('time_end'),
			'delay' 			=> trim($this->input->post('delay')) 
		);
		$where = "plan_produksi_id=".$this->input->post('old_id');
	
		return $rs = $this->db->update('plan_produksi', $data, $where); 
	}  

	public function getRowData($id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 plan_produksi a  
	   	 	WHERE
	   	 		a.plan_produksi_id = '".$id."'  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 

	public function getDataByTgl($start = "", $end="") {  
		$sql = "SELECT
					a.plan_produksi_id , b.plan_category , a.date , a.time_start , a.time_end , a.delay
			FROM
	   	 		plan_produksi a LEFT JOIN plan_category b ON a.plan_category_id = b.plan_category_id
	   	 	WHERE
	   	 		a.date >= '".$start."'  AND a.date <= '".$end."'  
	   		ORDER BY
	   			a.plan_produksi_id DESC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}   
	public function getListPlan() {  
		$sql = "SELECT
						*
				FROM
		   	 		plan_category
		   		ORDER BY
		   			plan_category ASC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}   

		
}