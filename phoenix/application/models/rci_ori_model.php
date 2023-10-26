<?php
class rci_model extends MY_Model{
	function __construct(){
		parent::__construct();
    }

//fix
    public function get_list_data(){
        $aColumns = array('a.rci_id','a.nrp','b.nama','a.date_rc','a.shift','c.lokasi','d.jenis_kerusakan','a.severity');
        $sIndexColumn = "a.rci_id";
		$sTable = ' rc_index a LEFT JOIN master_employee b ON a.nrp = b.nrp
		LEFT JOIN master_lokasi c ON c.master_lokasi_id = a.lokasi_id
		LEFT JOIN master_problem_road d ON d.master_problem_road_id = a.problem_road_id ';

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
			$detail = '<a class="blue" href="'.base_url('daily_absent/detail/' . $row->weigher_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
			$detail_1 = '<li><a href="'.base_url('daily_absent/detail/' . $row->weigher_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
		}
		*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('rci/edit/' . $row->rci_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('rci/edit/' . $row->rci_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('rci/delete/' . $row->rci_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('rci/delete/' . $row->rci_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}

			array_push($output["aaData"],array(
				$row->rci_id,
				$row->lokasi_id,
				$row->problem_road_id,   
				$row->nrp,
				$row->nama,   
				$row->date_rc,
				$row->shift,  
				$row->lokasi,  
				$row->jenis_kerusakan,  
				$row->severity,  
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
	
	public function list_terminal() {
		$sql = "SELECT
					distinct a.name, a.terminals_id
			FROM
	   	 		terminals a
	   	 	INNER JOIN 
	   	 		absensi b
	   	 	ON 
	   	 		a.zona = b.zona AND
	   	 		a.terminal_number = b.terminal_number
	   	 	ORDER BY 
	   	 		a.name ASC
		"; 
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  

    public function delete($id= "") {
		if (isset($id) && $id <> "") {
			$this->db->where('rci_id',$id);
			$rs = $this->db->delete('rc_index');
			return $rs;
		} else return null;
    }  

    public function add_data() {
		$tmp_ada_data = $this->check_apakah_data_sudah_ada($this->input->post('master_lokasi_id'), $this->input->post('date_rc'));
		if ($tmp_ada_data == 0) {
			$data = array( 
				'nrp' 		        => trim($this->input->post('nrp')),  
				'date_rc' 	        => trim($this->input->post('date_rc')),  
				'shift' 	        => trim($this->input->post('shift')) ,  
				'lokasi_id' 		=> trim($this->input->post('lokasi_id')) ,  
				'problem_road_id' 	=> trim($this->input->post('problem_road_id')), 
				'severity'      	=> trim($this->input->post('severity')),
				'insert_by'  		=> trim($_SESSION['username'])
			);


			try {

				if (!$this->db->insert('rc_index', $data))
				{
					$error = $this->db->error();
					throw new Exception('Database error! Error Code [' . $error['code'] . '] Error: ' . $error['message']);
					
				} else {
					$my_array = array(true,"Data berhasil disimpan !");
					return $my_array;
				}
				
			} catch (Exception $e) {
				// this will not catch DB related errors. But it will include them, because this is more general. 
				//log_message('error: ', $e->getMessage());
				$my_array = array(false,"Data gagal disimpan : ".$e=>getMessage());
				return $my_array;
			}

		} else {
			$my_array = array(false,"Data sudah ada !");
			return $my_array;
		}
	}  
	public function update_data() { 
		$data = array( 
			'date_rc' 	        => trim($this->input->post('date_rc')) , 
			'nrp' 		        => trim($this->input->post('nrp')),   
			'shift' 	        => trim($this->input->post('shift')) ,  
			'lokasi_id' 		=> trim($this->input->post('lokasi_id')) ,  
			'problem_road_id' 	=> trim($this->input->post('problem_road_id')), 
			'severity'      	=> trim($this->input->post('severity')),
			'updated_by'  		=> trim($_SESSION['username'])
		);
		$where = "rci_id = ".$this->input->post('old_id');
	
		return  $rs = $this->db->update('rc_index', $data, $where); 
	}
	
	public function getMasterProblemRoad() { 
		$sql = "SELECT
					*
			FROM
	   	 		 master_problem_road a   
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

    public function getRowData($id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 rc_index a  
	   	 	WHERE
	   	 		a.rci_id = '".$id."'  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	}
	
    public function getDataPerDate($start = "", $end="") {  
		$sql = "SELECT
					a.rci_id,
					a.lokasi_id,
					a.problem_road_id,
					a.nrp,
                    b.nama,  
					a.date_rc,
					a.shift,
					c.lokasi,
					d.jenis_kerusakan,
					a.severity
			FROM
	   	 		 rc_index a LEFT JOIN master_employee b ON a.nrp = b.nrp   
				LEFT JOIN master_lokasi c ON a.lokasi_id = c.master_lokasi_id
				LEFT JOIN master_problem_road d ON a.problem_road_id = d.master_problem_road_id
	   	 	WHERE
	   	 		a.date_rc >= '".$start."'  AND a.date_rc <= '".$end."' 
	   		ORDER BY
	   			a.rci_id DESC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}
	
	public function check_data_and_insert_if_empty($table="", $field="", $val="", $pk="") {
		$val 	= strtoupper(trim($val));  
		if ($val <> "") { 
			$sql = "SELECT * from $table WHERE LOWER($field) = '".strtolower(trim($val))."'";
			#echo $sql."<br>";
			$res  = $this->db->query($sql);
			$rs = $res->row_array(); 
			if ($rs[$field] =="") {
				$sqli = "INSERT INTO $table ($field) VALUES ('".trim($val)."')";
				$resi = $this->db->query($sqli);
				$lastid = $this->db->insert_id();
				#echo $lastid." <br><br>";
				return $lastid;
			} else {
				#echo $rs[$pk]." <br><br>";
				return $rs[$pk];
			}  
		}
	}
	
/*   public function import_data($list_data) {  
		$i = 0;
		FOREACH ($list_data AS $k => $v) {
			$i += 1;  
			$status  = 0;
			if (empty($v["B"])) $v["B"] = "";
			if (empty($v["C"])) $v["C"] = "";
			if (empty($v["D"])) $v["D"] = "";
			if (empty($v["E"])) $v["E"] = "";
			if (empty($v["F"])) $v["F"] = "";
			if (empty($v["G"])) $v["G"] = "";
			if (empty($v["H"])) $v["H"] = "";
			if (empty($v["I"])) $v["I"] = "";
			if (empty($v["J"])) $v["J"] = "";
			if (empty($v["K"])) $v["K"] = "";
			if (empty($v["L"])) $v["L"] = "";
			if (empty($v["M"])) $v["M"] = "";
			if (empty($v["N"])) $v["N"] = "";
			if (empty($v["O"])) $v["O"] = "";
			if (empty($v["P"])) $v["P"] = "";
			if (empty($v["Q"])) $v["Q"] = "";
			if (empty($v["R"])) $v["R"] = "";

			$nrp  			= $this->check_data_and_insert_if_empty("master_employee","nrp","nrp","nama",$v["C"],$v["D"]);  
		 

			if ($tmp_ada_data  == 0 && $nrp <> "") { 
				$data = array( 
					'nrp' 					=> $nrp ,
					'date_rc' 				=> trim($v["B"]),  
					'shift' 				=> trim($v["E"]),   
					'sta' 				    => trim($v["F"]),   
                    'jenis_kerusakan' 		=> trim($v["G"]),
                    'severity' 	        	=> trim($v["H"]),
					'insert_by' 			=> $_SESSION["username"]
	 
				);
			
				$rs = $this->db->insert('rc_index', $data);
				if (!$rs) $error .=",baris ". $v["C"]." OK";
			} else {
				$error .=",baris ". $v["C"]. " GAGAL";
			}
		
		} 
		return $error;
   } */
}	  