<?php
class Master_posisi_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	} 

	// fix
	public function get_list_data(){
		$aColumns = array('master_posisi_id','keterangan','status');
		$sIndexColumn = "master_posisi_id";
		$sTable = ' master_posisi ';
		
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
				$detail = '<a class="blue" href="'.base_url('master_posisi/detail/' . $row->weigher_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('master_posisi/detail/' . $row->weigher_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
			}
			*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('master_posisi/edit/' . $row->master_posisi_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('master_posisi/edit/' . $row->master_posisi_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$fk = $this->check_apakah_sudah_digunakan_ditable_lain("master_employee", "master_posisi_id", $row->master_posisi_id);
				if ($fk == 0) {
 					$delete = '<a class="red" href="'.base_url('master_posisi/delete/' . $row->master_posisi_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->keterangan.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
					$delete_1 = '<li><a href="'.base_url('master_posisi/delete/' . $row->master_posisi_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->keterangan.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
				}
			}
			array_push($output["aaData"],array(
				$row->master_posisi_id, 
				$row->keterangan,   
				$row->status,   
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

	public function check_apakah_sudah_digunakan_ditable_lain($table, $field, $val) { 
		$sql = "SELECT count(*) as total  from ".$table."  WHERE lower(".$field.") = '".strtolower(trim($val))."'"; 
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"] ; 
	}

	public function delete($id= "") {
		if (isset($id) && $id <> "") {
			$this->db->where('master_posisi_id',$id);
			$rs = $this->db->delete('master_posisi');
			return $rs;
		} else return null;
	}  
	public function add_data() {
		$data = array(  
			'keterangan' 	=> trim($this->input->post('keterangan')) , 
			'status' 		=> trim($this->input->post('status')) ,
			'insert_by' 	=> $_SESSION["username"] 
		);
	
		return $rs = $this->db->insert('master_posisi', $data); 
	}  

	public function edit_data() { 
		$data = array(  
			'keterangan' 		=> trim($this->input->post('keterangan')) , 
			'status' 		=> trim($this->input->post('status')) ,
			'insert_by' 	=> $_SESSION["username"] 
		);
		$where = "master_posisi_id = ".$this->input->post('old_id');
	
		return  $rs = $this->db->update('master_posisi', $data, $where); 
	}  

	public function getRowData($id) { 
		$sql = "SELECT
					* 
			FROM
	   	 		 master_posisi a  
	   	 	WHERE
	   	 		a.master_posisi_id = '".$id."'  
		"; 
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function getAlldata() { 
		$sql = "SELECT
					* 
			FROM
	   	 		 master_posisi 
	   	 	ORDER BY kode 
		"; 
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
 
	public function check_apakah_data_sudah_ada($unit) { 
		$sql = "SELECT count(*) as total  from master_posisi  WHERE lower(keterangan) = '".strtolower(trim($unit))."'"; 
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"] ; 
	}
	
	public function import_data($list_data) {  
		$i = 0;
		FOREACH ($list_data AS $k => $v) {
			$i += 1; 
			
			$tmp_ada_data = $this->check_apakah_data_sudah_ada($v["B"]);
			if ($tmp_ada_data == 0) {
				$data = array(  
					'keterangan' 	=> $v["B"],  
					'status' 		=> $v["C"],  
					'insert_by' 	=> $_SESSION["username"] 
				);
			
				$rs = $this->db->insert('master_posisi', $data);
				if (!$rs) $error .="baris ". $v["B"]."\n"; 
			} else {
				if (!$rs) $error .="baris ". $v["B"]." : ". $v["B"]." sudah ada\n\r<br>"; 
			}
		} 
		return $error;
	}  


		
}