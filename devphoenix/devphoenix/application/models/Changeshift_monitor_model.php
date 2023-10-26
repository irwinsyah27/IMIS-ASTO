<?php
class Changeshift_monitor_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	} 

	// fix
	public function get_list_data(){
		$aColumns = array('a.changeshift_id','a.date','a.equip_num','a.nrp','b.nama','a.shift','a.lokasi','a.time_in','a.time_out');
		$sIndexColumn = "a.changeshift_id";
		$sTable = ' changeshift a INNER JOIN master_employee b ON a.nrp = b.nrp';
		
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
		,date_format(a.time_in,'%H:%i') as time_in,
		date_format(a.time_out,'%H:%i') as time_out
					
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
				$edit = '<a class="green" href="'.base_url('changeshift_monitor/edit/' . $row->changeshift_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('changeshift_monitor/edit/' . $row->changeshift_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('changeshift_monitor/delete/' . $row->changeshift_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('changeshift_monitor/delete/' . $row->changeshift_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}

			array_push($output["aaData"],array(
				$row->changeshift_id,  
				$row->date,  
				$row->equip_num,
				$row->nrp,
				$row->nama,   
				$row->shift,
				$row->lokasi,  
				$row->time_in,  
				$row->time_out, 
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
			$this->db->where('changeshift_id',$id);
			$rs = $this->db->delete('changeshift');
			return $rs;
		} else return null;
	}  
	public function add_data() {
		if (trim($_POST['tgl_out']) == "") $_POST['tgl_out'] = trim($this->input->post('tgl'));
		$date_time_in 	= trim($this->input->post('tgl')) ." ".  trim($this->input->post('time_in'));
		$date_time_out 	= $_POST['tgl_out'] ." ".  trim($this->input->post('time_out'));
		$data = array( 
			'date' 		=> trim($this->input->post('tgl')) ,  
			'equip_num'	=> trim($this->input->post('equip_num')),  
			'nrp' 		=> trim($this->input->post('nrp')),  
			'shift' 	=> trim($this->input->post('shift')) , 
			'lokasi' 	=> trim($this->input->post('lokasi')) ,   
			'time_in' 	=> $date_time_in ,  
			'time_out' 	=> $date_time_out 
		);
	
		return $rs = $this->db->insert('changeshift', $data);
	}  

	public function edit_data() { 
		if (trim($this->input->post('tgl_out')) == "") $_POST['tgl_out'] = trim($this->input->post('tgl'));

		$date_time_in 	= trim($this->input->post('tgl')) ." ".  trim($this->input->post('time_in'));

		if (trim($this->input->post('time_out')) == '') {
			$date_time_out = null;
		} else {
			$date_time_out 	= $_POST['tgl_out'] ." ".  trim($this->input->post('time_out'));
		}
		

		$data = array( 
			'date' 		=> trim($this->input->post('tgl')) ,  
			'equip_num'	=> trim($this->input->post('equip_num')),  
			'nrp' 		=> trim($this->input->post('nrp')),  
			'shift' 	=> trim($this->input->post('shift')) , 
			'lokasi' 	=> trim($this->input->post('lokasi')) ,   
			'time_in' 	=> $date_time_in ,  
			'time_out' 	=> $date_time_out
		);
		$where = "changeshift_id = ".$this->input->post('old_id');
	
		return  $rs = $this->db->update('changeshift', $data, $where); 
	}  

	public function getRowData($id) { 
		$sql = "SELECT
					a.*, 
					date_format(a.time_in,'%Y-%m-%d') as date_in,
					date_format(a.time_out,'%Y-%m-%d') as date_out,
					date_format(a.time_in,'%H:%i') as time_in,
					date_format(a.time_out,'%H:%i') as time_out
			FROM
	   	 		 changeshift a  
	   	 	WHERE
	   	 		a.changeshift_id = '".$id."'  
		"; 
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function apakah_data_sudah_ada($nrp, $tgl) { 
		$sql = "SELECT
					*
			FROM
	   	 		 changeshift
	   	 	WHERE
	   	 		nrp = '".$nrp."' AND date = '".$tgl."'
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function getDataPerDate($start = "", $end="") {  
		$sql = "SELECT
					a.date,
					a.equip_num,
					a.nrp,
					a.nama,
					a.shift,
					a.lokasi,
					date_format(a.time_in,'%Y-%m-%d') as date_in,
					date_format(a.time_out,'%Y-%m-%d') as date_out,
					date_format(a.time_in,'%H:%i') as time_in,
					date_format(a.time_out,'%H:%i') as time_out,
					case when (TIMEDIFF(a.time_out,a.time_in)  > 0 ) then TIMEDIFF(a.time_out,a.time_in)  else '' end as durasi
					 
			FROM
	   	 		 changeshift a LEFT JOIN master_employee b ON a.nrp = b.nrp   
	   	 	WHERE
	   	 		a.date >= '".$start."'  AND a.date <= '".$end."' 
	   		ORDER BY
	   			a.nrp,a.date
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

	public function check_apakah_data_sudah_ada($nrp, $date, $shift) { 
		$sql = "SELECT count(*) as total from absensi  WHERE nip = '".$nrp."' AND date = '".$date."' AND shift = ".$shift; 
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
		 
			$date_time_in		= trim($v["G"])." ".trim($v["H"]);
			$date_time_out		= trim($v["I"])." ".trim($v["J"]);
 

			$tmp_ada_data = $this->check_apakah_data_sudah_ada($nip, trim($v["B"]), trim($v["E"]));

			if ($tmp_ada_data  == 0 && $nip <> "") { 
				$data = array( 
					'nip' 					=> $nrp ,
					'date' 					=> trim($v["B"]),  
					'status' 				=> trim($v["F"]),  
					'shift' 				=> trim($v["E"]),   
					'time_in' 				=> trim($date_time_in) , 
					'time_out' 				=> trim($date_time_out) , 
					'bpm_in' 				=> trim($v["L"]),   
					'spo_in' 				=> trim($v["M"]),
					'insert_by' 			=> $_SESSION["username"]
	 
				);
			
				$rs = $this->db->insert('changeshift', $data);
				if (!$rs) $error .=",baris ". $v["C"]." OK";
			} else {
				$error .=",baris ". $v["C"]. " GAGAL";
			}
		
		} 
		return $error;
	}  

	public function list_lokasi() {
		$sql = "SELECT
					distinct  a.master_lokasi_id, a.lokasi
			FROM
	   	 		master_lokasi as a
	   	 	INNER JOIN 
	   	 		changeshift as b
	   	 	ON 
	   	 		a.lokasi = b.lokasi
	   	 	ORDER BY 
	   	 		a.lokasi ASC
		"; 
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  

	public function getDataPerDateUntukAbsensi($start = "", $end="",$terminals_id = '') {  
		$sql = "SELECT
					a.date,
					a.equip_num,
					a.nrp,
					b.nama,  
					a.shift,
					c.lokasi,
					date_format(a.time_in,'%Y%m%d') as date_in,
					date_format(a.time_out,'%Y%m%d') as date_out,
					date_format(a.time_in,'%H%i') as time_in,
					date_format(a.time_out,'%H%i') as time_out,
					case when (TIMEDIFF(a.time_out,a.time_in)  > 0 ) then TIMEDIFF(a.time_out,a.time_in)  else '' end as durasi,
					c.lokasi
					 
			FROM
	   	 		 changeshift a LEFT JOIN master_employee b ON a.nrp = b.nrp   
	   	 	LEFT JOIN 
	   	 		master_lokasi c 
	   	 	ON 
	   	 		a.lokasi = c.lokasi
	   	 	WHERE
	   	 		a.date >= '".$start."'  AND a.date <= '".$end."'";

	   	 if (isset($terminals_id) && $terminals_id <> "" && $terminals_id <> "null") { $sql .= " AND c.terminals_id IN (". $terminals_id.")"; 	} 
	   	
	   	$sql .= " 
	   		ORDER BY
			   a.nrp,a.date
		";   

		//echo $sql;exit;

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  

		
}