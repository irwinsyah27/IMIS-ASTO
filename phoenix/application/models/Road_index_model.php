<?php
class Road_index_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
		$this->load->library('upload', [
			'upload_path' => './uploads/',
			'allowed_types' => 'gif|png|jpg|jpeg|pdf|xls|xlsx|doc|docx|zip',
		]);
	} 

	// fix
	public function get_list_data(){
		$aColumns = array('a.rci_id','a.nip','b.nama','a.date_awal','a.file_awal','a.date_akhir','a.file_akhir','a.master_shift_id','e.sta_lokasi','f.sta_meter','d.kerusakan','g.severity','a.status');
		$sIndexColumn = "a.rci_id";
		$sTable = ' rc_index a INNER JOIN master_employee b ON a.nip = b.nrp
		INNER JOIN master_shift c ON a.master_shift_id = c.master_shift_id 
		INNER JOIN jenis_kerusakan d ON a.master_kerusakan_id = d.master_kerusakan_id
		INNER JOIN sta_lokasi e ON a.sta_lokasi_id = e.sta_lokasi_id
		INNER JOIN master_sta_meter f ON a.master_sta_meter_id = f.master_sta_meter_id
		INNER JOIN master_severity g ON a.master_severity_id = g.master_severity_id';
		
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
		,date_format(a.date_awal,'%d-%M-%Y') as date_awal,
		date_format(a.date_akhir,'%d-%M-%Y') as date_akhir
					
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
				$detail = '<a class="blue" href="'.base_url('road_index/detail/' . $row->weigher_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('road_index/detail/' . $row->weigher_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
			}
			*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('road_index/edit/' . $row->rci_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('road_index/edit/' . $row->rci_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('road_index/delete/' . $row->rci_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('road_index/delete/' . $row->rci_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}

			array_push($output["aaData"],array(
				$row->rci_id,  
				$row->nip,
				$row->nama,   
				$row->date_awal,  
				$row->file_awal,
				$row->date_akhir,
				$row->file_akhir,  
				$row->master_shift_id,  
				$row->sta_lokasi,
				$row->sta_meter,  
				$row->kerusakan,
				$row->severity,  
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

	public function delete($id= "") {
		if (isset($id) && $id <> "") {
			$this->db->where('rci_id',$id);
			$rs = $this->db->delete('rc_index');
			return $rs;
		} else return null;
	}  
	public function add_data() {
		$date_time_in 	= trim($this->input->post('date_awal'));
		if ($this->upload->do_upload('file_awal'))
		{
			$file = $this->upload->data();
			$post['file_awal'] = '/uploads/'.$file['file_name'];
		}
		$this->db->insert('rc_index', $post);
		
		$data = array( 
			'nip' 					=> trim($this->input->post('nip')),   
			'date_awal' 			=> $date_time_in, 
			'master_shift_id' 		=> trim($this->input->post('master_shift_id')) ,  
			'sta_lokasi_id' 		=> trim($this->input->post('sta_lokasi_id')) ,
			'master_sta_meter_id' 	=> trim($this->input->post('master_sta_meter_id')),  
			'master_kerusakan_id'	=> trim($this->input->post('master_kerusakan_id')), 
			'master_severity_id' 	=> trim($this->input->post('master_severity_id')),
			'status' 				=> trim($this->input->post('status')),
			'insert_by' 			=> $_SESSION["username"]
		);
	
		return $rs = $this->db->insert('rc_index', $data);
	}  

	public function edit_data() { 

		$date_time_in 	= trim($this->input->post('date_awal'));

		if (trim($this->input->post('date_akhir')) == '') {
			$date_time_out = null;
		} else {
			$date_time_out 	= trim($this->input->post('date_akhir'));
		}
		

		$data = array( 
			'nip' 					=> trim($this->input->post('nip')),  
			'date_awal' 			=> $date_time_in ,  
			'date_akhir' 			=> $date_time_out , 
			'master_shift_id' 		=> trim($this->input->post('master_shift_id')) ,   
			'sta_lokasi_id' 		=> trim($this->input->post('sta_lokasi_id')) ,
			'master_sta_meter_id' 	=> trim($this->input->post('master_sta_meter_id')),  
			'master_kerusakan_id' 	=> trim($this->input->post('master_kerusakan_id')),
			'master_severity_id' 	=> trim($this->input->post('master_severity_id')),
			'file_akhir' 			=> trim($this->input->post('file_akhir')),
			'status' 				=> trim($this->input->post('status')),
			'updated_by' 			=> $_SESSION["username"]
		);
		$where = "rci_id = ".$this->input->post('old_id');
	
		return  $rs = $this->db->update('rc_index', $data, $where); 
	}  

	public function getRowData($id) { 
		$sql = "SELECT
					a.*, 
					date_format(a.date_awal,'%d-%m-%Y') as date_in,
					date_format(a.date_akhir,'%d-%m-%Y') as date_out
			FROM
	   	 		 rc_index a  
	   	 	WHERE
	   	 		a.rci_id = '".$id."'  
		"; 
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function apakah_data_sudah_ada($nrp,$sta_lokasi_id,$master_sta_meter_id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 rc_index
	   	 	WHERE
	   	 		nip = '".$nrp."' AND sta_lokasi_id = '".$sta_lokasi_id."' AND master_sta_meter_id = '".$master_sta_meter_id."'
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 

	public function getDataPerDate($start = "", $end="") {  
		$sql = "SELECT
					a.rci_id,
					a.nip,
					b.nama,  
					date_format(a.date_awal,'%d-%m-%Y') as date_in,
					date_format(a.date_akhir,'%d-%m-%Y') as date_out,
					a.master_shift_id,
					e.sta_lokasi,
					f.sta_meter,
					d.kerusakan,
					g.severity,
					date_format(a.date_insert,'%d-%m-%Y') as date_insert,
					a.status
			FROM
					rc_index a INNER JOIN master_employee b ON a.nip = b.nrp
					INNER JOIN master_shift c ON a.master_shift_id = c.master_shift_id 
					INNER JOIN jenis_kerusakan d ON a.master_kerusakan_id = d.master_kerusakan_id
					INNER JOIN sta_lokasi e ON a.sta_lokasi_id = e.sta_lokasi_id
					INNER JOIN master_sta_meter f ON a.master_sta_meter_id = f.master_sta_meter_id
					INNER JOIN master_severity g ON a.master_severity_id = g.master_severity_id
	   	 	WHERE
				date_format(a.date_awal,'%Y-%m-%d') BETWEEN '".$start."' AND '".$end."' OR
	   	 		date_format(a.date_akhir,'%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'
	   		ORDER BY
			   a.rci_id DESC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  

	public function check_data_and_insert_if_empty($table="", $pk, $field1="", $field2 = "", $val1="", $val2="") {
		$val1 	= strtoupper(trim($val1));  
		
		if ($val1 <> "") {
			$sql = "SELECT * from $table WHERE LOWER($field1) = '".strtolower(trim($val1))."'";
			$res  = $this->db->query($sql);
			$rs = $res->row_array(); 
			if ($rs[$field1] =="") {
				$sqli = "INSERT INTO $table ($field1, $field2) VALUES ('".trim($val1)."','".trim($val2)."')";
				$resi = $this->db->query($sqli);  
				return $field1;
			} else {
				return $rs[$pk];
			}  	
		}
		
	}

	public function check_apakah_data_sudah_ada($nip, $date, $master_shift_id) { 
		$sql = "SELECT count(*) as total from rc_index  WHERE nip = '".$nip."' AND date_awal = '".$date."' AND master_shift_id = ".$master_shift_id; 
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"] ; 
	}

	public function import_data($list_data) {  
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

			$nip  			= $this->check_data_and_insert_if_empty("master_employee","nrp","nrp","nama",$v["C"],$v["D"]);  
		 
			$date_time_in		= trim($v["C"]);
			$date_time_out		= trim($v["E"]);
 

			$tmp_ada_data = $this->check_apakah_data_sudah_ada($nip, trim($v["B"]), trim($v["C"]));

			if ($tmp_ada_data  == 0 && $nip <> "") { 
				$data = array( 
					'nip' 					=> $nip ,
					'date_awal' 			=> trim($date_time_in) , 
					'file_awal'				=> trim($v["D"]),
					'date_akhir' 			=> trim($date_time_out) ,
					'file_akhir'			=> trim($v["F"]), 
					'master_shift_id' 		=> trim($v["G"]),   
					'sta_lokasi_id' 		=> trim($v["H"]),   
					'master_sta_meter_id' 	=> trim($v["I"]),   
					'master_kerusakan_id' 	=> trim($v["J"]),
					'master_severity_id'	=> trim($v["K"]),
					'status'				=> trim($v["L"]),
					'insert_by' 			=> $_SESSION["username"]
	 
				);
			
				$rs = $this->db->insert('rc_index', $data);
				if (!$rs) $error .=",baris ". $v["C"]." OK";
			} else {
				$error .=",baris ". $v["B"]. " GAGAL";
			}
		
		} 
		return $error;
	}
}  

		
