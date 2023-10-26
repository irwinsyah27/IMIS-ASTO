<?php
class Akses_level_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}
	 

	// fix
	public function get_list_data(){
		$aColumns = array('nama','username','user_id');
		$sIndexColumn = "user_id";
		$sTable = ' user ';
		
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
		//'<label class="pos-rel"><input type="checkbox" class="ace" name="id[]" value="'.$row->nama.'" /><span class="lbl"></span></label>',
		
		foreach($rResult as $row){ 
			$detail = "";
			$detail_1 = "";
			/*
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				$detail = '<a class="blue" href="'.base_url('akses_level/detail/' . $row->nama).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('akses_level/detail/' . $row->nama).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
			}
			*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('akses_level/edit/' . $row->user_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('akses_level/edit/' . $row->user_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('akses_level/delete/' . $row->user_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('akses_level/delete/' . $row->user_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}
			array_push($output["aaData"],array(
				$row->nama,
				$row->username , 
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
 

	public function add_data() {
		$sql = "SELECT count(*) as total FROM user where username = '".strtolower(trim($this->input->post('username')))."'";
		$rs = $this->db->query($sql);
		$r  = $rs->row_array();
		if ($r["total"] > 0) {
			return false;
		} else {
			$data = array( 
				'nama' 			=> trim($this->input->post('nama')),  
				'username' 		=> strtolower(trim($this->input->post('username'))),  
				'passwd' 		=> md5(strtolower(trim($this->input->post('passwd')))) 
			);
		
			$rs = $this->db->insert('user', $data);
			$last_id = $this->db->insert_id();

			$sql1 = "SELECT * FROM user_menu WHERE parent_id = 0 ORDER BY um_order ASC";
			$res1 = $this->db->query($sql1);
			$rs1 = $res1->result_array();
			if (count($rs1) > 0) {
				FOREACH ($rs1 AS $r1) {
					$data1 = array( 
						'user_id' 			=> $last_id,  
						'user_menu_id' 		=> $r1["user_menu_id"],  
						'view' 				=> $this->input->post('view_'.$r1["module_name"]),  
						'add' 				=> $this->input->post('add_'.$r1["module_name"]),  
						'edit' 			=> $this->input->post('update_'.$r1["module_name"]),  
						'del' 			=> $this->input->post('delete_'.$r1["module_name"]) ,  
						'import' 			=> $this->input->post('import_'.$r1["module_name"]) ,  
						'eksport' 			=> $this->input->post('eksport_'.$r1["module_name"]) 
					);
				
					$this->db->insert('user_akses', $data1);
 

					$sql_1 = "SELECT * FROM user_menu WHERE parent_id = ".$r1["user_menu_id"]." ORDER BY um_order ASC";
					$res_1 = $this->db->query($sql_1);
					$rs_1 = $res_1->result_array();
					if (count($rs_1) > 0) {
						FOREACH ($rs_1 AS $r_1) {
							 $data2 = array( 
								'user_id' 			=> $last_id,  
								'user_menu_id' 		=> $r_1["user_menu_id"],  
								'view' 				=> $this->input->post('view_'.$r_1["module_name"]),  
								'add' 				=> $this->input->post('add_'.$r_1["module_name"]),  
								'edit' 			=> $this->input->post('update_'.$r_1["module_name"]),  
								'del' 			=> $this->input->post('delete_'.$r_1["module_name"]) ,  
								'import' 			=> $this->input->post('import_'.$r1["module_name"]) ,  
								'eksport' 			=> $this->input->post('eksport_'.$r1["module_name"]) 
							);
						
							$this->db->insert('user_akses', $data2);
						}
					}

				}
			}
			return true;
		}

		
	}  
	public function edit_data() {

		$data = array( 
				'nama' 			=> trim($this->input->post('nama')),  
				'username' 		=> strtolower(trim($this->input->post('username'))) 
		);
		if ($this->input->post('passwd') <> "") $data['passwd'] = md5(strtolower(trim($this->input->post('passwd')))) ;

		$where = "user_id=".$this->input->post('old_id');
	
		$rs = $this->db->update('user', $data, $where);

		$last_id = $this->input->post('old_id');

		$sql1 = "DELETE FROM user_akses WHERE user_id = ".$this->input->post('old_id');
		$res1 = $this->db->query($sql1);

		$sql1 = "SELECT * FROM user_menu WHERE parent_id = 0 ORDER BY um_order ASC";
		$res1 = $this->db->query($sql1);
		$rs1 = $res1->result_array();
		if (count($rs1) > 0) {
			FOREACH ($rs1 AS $r1) {
				$data1 = array( 
					'user_id' 			=> $last_id,  
					'user_menu_id' 		=> $r1["user_menu_id"],  
					'view' 				=> $this->input->post('view_'.$r1["module_name"]),  
					'add' 				=> $this->input->post('add_'.$r1["module_name"]),  
					'edit' 			=> $this->input->post('update_'.$r1["module_name"]),  
					'del' 			=> $this->input->post('delete_'.$r1["module_name"]) ,  
					'import' 			=> $this->input->post('import_'.$r1["module_name"]) ,  
					'eksport' 			=> $this->input->post('eksport_'.$r1["module_name"]) 
				);
			
				$this->db->insert('user_akses', $data1);


				$sql_1 = "SELECT * FROM user_menu WHERE parent_id = ".$r1["user_menu_id"]." ORDER BY um_order ASC";
				$res_1 = $this->db->query($sql_1);
				$rs_1 = $res_1->result_array();
				if (count($rs_1) > 0) {
					FOREACH ($rs_1 AS $r_1) {
						 $data2 = array( 
							'user_id' 			=> $last_id,  
							'user_menu_id' 		=> $r_1["user_menu_id"],  
							'view' 				=> $this->input->post('view_'.$r_1["module_name"]),  
							'add' 				=> $this->input->post('add_'.$r_1["module_name"]),  
							'edit' 			=> $this->input->post('update_'.$r_1["module_name"]),  
							'del' 			=> $this->input->post('delete_'.$r_1["module_name"]) ,  
							'import' 			=> $this->input->post('import_'.$r1["module_name"]) ,  
							'eksport' 			=> $this->input->post('eksport_'.$r1["module_name"]) 
						);
					
						$this->db->insert('user_akses', $data2);
					}
				}

			}
		}
		return true;

	
		//return $rs = $this->db->insert('weigher', $data);
	}  

	public function getRowDataUser($id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 user a  
	   	 	WHERE
	   	 		a.user_id = '".$id."'  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function getRowDataUserAkses($id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 user_akses a  
	   	 	WHERE
	   	 		a.user_id = '".$id."'  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 


		
}