<?php
class Setting_unit_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}
	 

	// fix
	public function get_list_data(){ 
		$aColumns = array('setting_unit_id','unit');
		$sIndexColumn = "setting_unit_id";
		$sTable = ' setting_unit';
		
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
		//'<label class="pos-rel"><input type="checkbox" class="ace" name="id[]" value="'.$row->setting_unit_id.'" /><span class="lbl"></span></label>',
		
		$hari = array ("1" => "Senin",
							"2" => "Selasa",
							"3" => "Rabu",
							"4" => "Kamis",
							"5" => "Jumat",
							"6" => "Sabtu",
							"7" => "Minggu",
	);

		foreach($rResult as $row){ 
			$detail = "";
			$detail_1 = ""; 
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('setting_unit/edit/' . $row->setting_unit_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('setting_unit/edit/' . $row->setting_unit_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('setting_unit/delete/' . $row->setting_unit_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data ?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('setting_unit/delete/' . $row->setting_unit_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data ?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}
			array_push($output["aaData"],array(
				$row->setting_unit_id, 
				$row->unit,  
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
			$this->db->where('setting_unit_id',$id);
			$rs = $this->db->delete('setting_unit');
			return $rs;
		} else return null;
	}  
	public function add_data() { 
		$data = array(  
			'unit' 			=> trim($this->input->post('unit')),
			'insert_by' 	=> $_SESSION["username"] 
		);
	
		return $rs = $this->db->insert('setting_unit', $data);
	}    
	public function check_apakah_data_sudah_ada($unit) { 
		$sql = "SELECT count(*) as total from setting_unit  WHERE unit = '".$unit."' ";
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"] ; 
	}
	public function import_data($list_data) {  
		$i = 0;
		FOREACH ($list_data AS $k => $v) {
			$i += 1; 

			$tmp_ada_data = $this->check_apakah_data_sudah_ada($v["B"]);

			if ($tmp_ada_data  == 0 && $v["B"] <> "") { 
				$data = array(  
					'unit' 			=> $v["B"], 
					'insert_by' 	=> $_SESSION["username"]  
				);
			
				$rs = $this->db->insert('setting_unit', $data);
				if (!$rs) $error .=",baris ". $v["B"]; 
			} else {
				$error .=",baris ". $v["B"]. " GAGAL";
			}
		} 
		return $error;
	}  

	public function edit_data() { 
		$data = array( 
			'unit' 			=> trim($this->input->post('unit')) 
		);
		$where = "setting_unit_id=".$this->input->post('old_id');
	
		return $rs = $this->db->update('setting_unit', $data, $where); 
	}  

	public function getRowData($id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 setting_unit a  
	   	 	WHERE
	   	 		a.setting_unit_id = '".$id."'  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function getAlldata(){  
		$sql = "SELECT
					a.*
			FROM
	   	 		setting_unit a
	   		ORDER BY
	   			a.unit ASC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}
	public function getListDataBerdasarkanHari() {  
		$sql = "SELECT
					a.*
			FROM
	   	 		setting_unit a 
	   		ORDER BY
	   			a.unit ASC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	/*
	public function getListDataBerdasarkanHari($hari) {  
		$sql = "SELECT
					a.*
			FROM
	   	 		setting_unit a
	   	 	LEFT JOIN 
	   	 		master_equipment b
	   	 	ON 
	   	 		a.unit = b.new_eq_num 
	   	 	LEFT JOIN 
	   	 		pitstop c 
	   	 	ON 
	   	 		b.master_equipment_id = c.equipment_id
	   	 	WHERE
	   	 		a.day = $hari AND DATE_FORMAT('c.date_time_in','%N')
	   		ORDER BY
	   			a.setting_unit_id ASC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
 	*/

		
}