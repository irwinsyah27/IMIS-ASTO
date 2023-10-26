<?php
class Unit_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	} 

	// fix
	public function get_list_data(){
		$aColumns = array('a.master_equipment_id','a.new_eq_num','b.alokasi','c.keterangan','d.keterangan','a.status', 'a.standby');
		$sIndexColumn = "a.master_equipment_id";
		$sTable = ' master_equipment a LEFT JOIN master_alokasi b ON a.master_alokasi_id = b.master_alokasi_id
				LEFT JOIN master_egi c ON a.master_egi_id = c.master_egi_id 
				LEFT JOIN master_owner d ON a.master_owner_id = d.master_owner_id
		';
		
		# $this->db->debug = true;

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
		$sWhere = " WHERE 1 = 1  ";
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
		, c.keterangan as egi
		, d.kode as type
		FROM $sTable
		$sWhere
		$sOrder
		$sLimit
		";
		/// echo $sQuery;exit;
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
				$detail = '<a class="blue" href="'.base_url('unit/detail/' . $row->weigher_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('unit/detail/' . $row->weigher_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
			}
			*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('unit/edit/' . $row->master_equipment_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('unit/edit/' . $row->master_equipment_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$fk = $this->check_apakah_sudah_digunakan_ditable_lain("weigher", "equipment_id", $row->master_equipment_id);
				$fk2 = $this->check_apakah_sudah_digunakan_ditable_lain("fuel_refill", "equipment_id", $row->master_equipment_id);
				$fk3 = $this->check_apakah_sudah_digunakan_ditable_lain2("daily_absent", "unit", $row->new_eq_num);
				if ($fk == 0 && $fk2 == 0 && $fk3 == 0  ) {
					$delete = '<a class="red" href="'.base_url('unit/delete/' . $row->master_equipment_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
					$delete_1 = '<li><a href="'.base_url('unit/delete/' . $row->master_equipment_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
				}
			}
			array_push($output["aaData"],array(
				$row->master_equipment_id,
				$row->new_eq_num,
				$row->alokasi,  
				$row->egi,  
				$row->type,  
				$row->status,
				$row->standby,  
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
			$this->db->where('master_equipment_id',$id);
			$rs = $this->db->delete('master_equipment');
			return $rs;
		} else return null;
	}  
	public function add_data() {
		$data = array( 
			'new_eq_num' 			=> trim($this->input->post('new_eq_num')),  
			'master_alokasi_id' 	=> trim($this->input->post('master_alokasi_id')) ,
			'master_egi_id' 		=> trim($this->input->post('master_egi_id')) ,
			'master_owner_id' 		=> trim($this->input->post('master_owner_id'))  , 
			'status' 				=> trim($this->input->post('status')) ,
			'standby' 				=> trim($this->input->post('standby'))
		);
	
		return $rs = $this->db->insert('master_equipment', $data);
	}  

	public function edit_data() { 
		$data = array( 
			'new_eq_num' 			=> trim($this->input->post('new_eq_num')),  
			'master_alokasi_id' 	=> trim($this->input->post('master_alokasi_id')) ,
			'master_egi_id' 		=> trim($this->input->post('master_egi_id')) ,
			'master_owner_id' 		=> trim($this->input->post('master_owner_id'))  , 
			'status' 				=> trim($this->input->post('status')) ,
			'standby' 				=> trim($this->input->post('standby'))
		);
		$where = "master_equipment_id = ".$this->input->post('old_id');
	
		return  $rs = $this->db->update('master_equipment', $data, $where); 
	}  

	public function getRowData($id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 master_equipment a  
	   	 	WHERE
	   	 		a.master_equipment_id = '".$id."'  
		"; 
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function getAlldata() { 
		$sql = "SELECT
					a.*,
					b.alokasi as alokasi,
					c.keterangan as egi,
					d.kode as owner
			FROM
	   	 		 master_equipment a 
	   	 	LEFT JOIN 
	   	 		master_alokasi b 
	   	 	ON 
	   	 		a.master_alokasi_id = b.master_alokasi_id
	   	 	LEFT JOIN 
	   	 		master_egi c
	   	 	ON 
	   	 		a.master_egi_id = c.master_egi_id 
	   	 	LEFT JOIN 
	   	 		master_owner d
	   	 	ON 
	   	 		a.master_owner_id = d.master_owner_id 
	   	 	ORDER BY a.new_eq_num 
		"; 
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
 
	
	public function check_data_and_insert_if_empty($table="", $field="", $val="", $pk="") {
		$val 	= strtoupper(trim($val));  
		if ($val <> "") { 
			$sql = "SELECT * from $table WHERE LOWER($field) = '".strtolower(trim($val))."'"; 
			$res  = $this->db->query($sql);
			$rs = $res->row_array(); 
			if ($rs[$field] =="") {
				$sqli = "INSERT INTO $table ($field) VALUES ('".trim($val)."')";
				$resi = $this->db->query($sqli);
				$lastid = $this->db->insert_id(); 
				return $lastid;
			} else { 
				return $rs[$pk];
			}  
		}
	}

	public function check_apakah_data_sudah_ada($table, $field, $value) { 
		$sql = "SELECT count(*) as total from $table  WHERE $field = '".$value."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"]; 
	}
	public function import_data($list_data) {  
		$i = 0;
		FOREACH ($list_data AS $k => $v) {
			$i += 1; 

			$tmp_ada_data = $this->check_apakah_data_sudah_ada("master_equipment","new_eq_num", $v["B"]);
			if ($tmp_ada_data == 0) {
				$alokasi_id = $this->check_data_and_insert_if_empty("master_alokasi" ,"alokasi", $v["C"], "master_alokasi_id");
				$egi 		= $this->check_data_and_insert_if_empty("master_egi" ,"keterangan", $v["D"], "master_egi_id");
				$owner 		= $this->check_data_and_insert_if_empty("master_owner" ,"kode", $v["E"], "master_owner_id");

				$data = array(  
					'new_eq_num' 		=> $v["B"],  
					'master_alokasi_id' => $alokasi_id, 
					'master_egi_id' 	=> $egi, 
					'master_owner_id' 	=> $owner, 
					'status' 			=> $v["F"],
					'standby'			=> $v["G"], 
					'insert_by' 		=> $_SESSION["username"]  
				);
			
				$rs = $this->db->insert('master_equipment', $data);
				if (!$rs) $error .=",baris ". $v["B"]; 
			} else {
				$error .=",baris ". $v["B"]." GAGAL"; 
			}
		} 
		return $error;
	}  

	public function check_apakah_sudah_digunakan_ditable_lain($table, $field, $val) { 
		$sql = "SELECT count(*) as total  from ".$table."  WHERE lower(".$field.") = ".strtolower(trim($val)); 
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"] ; 
	} 

	public function check_apakah_sudah_digunakan_ditable_lain2($table, $field, $val) { 
		$sql = "SELECT count(*) as total  from ".$table."  WHERE lower(".$field.") = '".strtolower(trim($val))."'"; 
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"] ; 
	} 


		
}