<?php
class Instruksi_isi_bensin_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	} 

	// fix
	public function get_list_data(){
		$aColumns = array('b.new_eq_num','a.shift','a.date_instruksi','a.total_liter','a.date_fill','a.time_fill','a.hm_before','a.hm_after','fuel_refill_id','total_realisasi');
		$sIndexColumn = "fuel_refill_id";
		$sTable = ' fuel_refill a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id ';
		
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
		//echo $sQuery;exit;
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
				$detail = '<a class="blue" href="'.base_url('instruksi_isi_bensin/detail/' . $row->fuel_refill_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('instruksi_isi_bensin/detail/' . $row->fuel_refill_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
			}
			*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('instruksi_isi_bensin/edit/' . $row->fuel_refill_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('instruksi_isi_bensin/edit/' . $row->fuel_refill_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('instruksi_isi_bensin/delete/' . $row->fuel_refill_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('instruksi_isi_bensin/delete/' . $row->fuel_refill_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}
			array_push($output["aaData"],array(
				$row->new_eq_num,
				$row->shift, 
				$row->date_instruksi, 
				$row->total_liter,  
				$row->date_fill, 
				$row->total_realisasi,  
				$row->time_fill,  
				$row->hm_before,  
				$row->hm_after,  
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
			$this->db->where('fuel_refill_id',$id);
			$this->db->where('date_realisasi','IS NULL');
			$rs = $this->db->delete('fuel_refill');
			return $rs;
		} else return null;
	}  
	public function add_data() {
		$data = array( 
			'equipment_id' 		=> trim($this->input->post('equipment_id')),  
			'shift' 			=> trim($this->input->post('shift')),  
			'date_instruksi' 	=> trim($this->input->post('date_instruksi')) ,
			'total_liter' 		=> trim($this->input->post('total_liter')) , 
			'insert_by' 		=> $_SESSION["username"] 
		);
	
		return $rs = $this->db->insert('fuel_refill', $data);
	}  

	public function edit_data() {
		$data = array( 
			'equipment_id' 	=> trim($this->input->post('equipment_id')),  
			'shift' 		=> trim($this->input->post('shift')),  
			'date_instruksi' 		=> trim($this->input->post('date_instruksi')) ,
			'total_liter' 			=> trim($this->input->post('total_liter')) 
		);
		$where = "fuel_refill_id=".$this->input->post('old_id');
	
		return $rs = $this->db->update('fuel_refill', $data, $where);
	
		//return $rs = $this->db->insert('weigher', $data);
	}  

	public function getRowData($id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 fuel_refill a  
	   	 	WHERE
	   	 		a.fuel_refill_id = '".$id."'  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
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
	   	 		date_insert DESC
	   	 	LIMIT 1  
		";  
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


		
}