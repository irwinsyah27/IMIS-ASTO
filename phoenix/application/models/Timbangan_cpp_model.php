<?php
class timbangan_cpp_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}
	 

	// fix
	public function get_list_data(){
		
		$aColumns = array('weigher_id','b.new_eq_num','e.keterangan','d.keterangan','c.alokasi','a.date_weigher','a.shift','a.time_weigher','a.bruto','a.tara','a.netto','ritase','no_doket','material_id');
		$sIndexColumn = "weigher_id";
		$sTable = ' weigher a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id 
		LEFT JOIN master_alokasi c ON b.master_alokasi_id = c.master_alokasi_id
		LEFT JOIN master_owner d ON b.master_owner_id = d.master_owner_id
		LEFT JOIN master_egi e ON b.master_egi_id = e.master_egi_id';
		
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
		$sWhere = " WHERE 1 = 1 AND station_id = 1 ";
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
		, e.keterangan as egi, d.keterangan as owner
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
		//'<label class="pos-rel"><input type="checkbox" class="ace" name="id[]" value="'.$row->weigher_id.'" /><span class="lbl"></span></label>',
		
		foreach($rResult as $row){ 
			$detail = "";
			$detail_1 = "";
			/*
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				$detail = '<a class="blue" href="'.base_url('timbangan_cpp/detail/' . $row->weigher_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('timbangan_cpp/detail/' . $row->weigher_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
			}
			*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('timbangan_cpp/edit/' . $row->weigher_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('timbangan_cpp/edit/' . $row->weigher_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('timbangan_cpp/delete/' . $row->weigher_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('timbangan_cpp/delete/' . $row->weigher_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}
			array_push($output["aaData"],array(
				$row->weigher_id,
				$row->new_eq_num,
				$row->egi, 
				$row->owner, 
				$row->alokasi, 
				$row->date_weigher, 
				$row->shift, 
				$row->time_weigher, 
				$row->bruto, 
				$row->tara, 
				$row->netto, 
				$row->ritase, 
				$row->no_doket, 
				$row->material_id, 
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
			$this->db->where('weigher_id',$id);
			$rs = $this->db->delete('weigher');
			return $rs;
		} else return null;
	}  
	public function add_data() {  
		$netto = $this->input->post('bruto') - $this->input->post('tara');
		$data = array( 
			'ritase' 	=> '1',  
			'equipment_id' 	=> trim($this->input->post('equipment_id')),  
			'shift' 		=> trim($this->input->post('shift')),  
			'netto' 		=> $netto, 
			'bruto' 		=> $this->input->post('bruto'),
			'tara' 			=> trim($this->input->post('tara')) ,
			'date_weigher' 	=> trim($this->input->post('date_weigher')) ,
			'time_weigher' 	=> trim($this->input->post('time_weigher')) , 
			'insert_by' 	=> $_SESSION["username"] , 
			'station_id'	=> '1',
			'no_doket' 		=> trim($this->input->post('no_doket')) , 
			'material_id' 	=> trim(strtoupper($this->input->post('material_id')))
		);
	
		return $rs = $this->db->insert('weigher', $data);
	}  

	public function check_unit($unit, $egi, $owner , $lokasi) {
		$unit 	= strtoupper(trim($unit)); 
		$egi 	= strtoupper(trim($egi)); 
		$owner 	= strtoupper(trim($owner)); 
		$lokasi = strtoupper(trim($lokasi)); 
		# egi 
		$sql = "SELECT * from master_egi WHERE LOWER(keterangan) = '".strtolower(trim($egi))."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		if ($rs["master_egi_id"] =="") {
			$sqli = "INSERT INTO master_egi (keterangan) VALUES ('".trim($egi)."')";
			$resi = $this->db->query($sqli);
			$egi_id = $this->db->insert_id();
		} else {
			$egi_id = $rs["master_egi_id"];
		}
		# owner 
		$sql = "SELECT * from master_owner WHERE LOWER(keterangan) = '".strtolower(trim($owner))."'"; 
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		if ($rs["master_owner_id"] =="") {
			$sqli = "INSERT INTO master_owner (keterangan) VALUES ('".trim($owner)."')";
			$resi = $this->db->query($sqli);
			$owner_id = $this->db->insert_id();
		} else {
			$owner_id = $rs["master_owner_id"];
		}
		# lokasi 
		$sql = "SELECT * from master_alokasi WHERE LOWER(alokasi) = '".strtolower(trim($lokasi))."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		if ($rs["master_alokasi_id"] =="") {
			$sqli = "INSERT INTO master_alokasi (alokasi) VALUES ('".trim($lokasi)."')";
			$resi = $this->db->query($sqli);
			$alokasi_id = $this->db->insert_id();
		} else {
			$alokasi_id = $rs["master_alokasi_id"];
		}

		$sql = "SELECT * from master_equipment WHERE LOWER(new_eq_num) = '".strtolower(trim($unit))."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		if ($rs["master_equipment_id"] =="") {
			$sqli = "INSERT INTO master_equipment (master_egi_id, new_eq_num, master_owner_id, master_alokasi_id) VALUES ('".$egi_id."','".$unit."','".$owner_id."','".$alokasi_id."')"; 
			$resi = $this->db->query($sqli);
			$unit_id = $this->db->insert_id();
			return $unit_id;
		} else {
			return $rs["master_equipment_id"];
		}

	}

	public function check_apakah_data_sudah_ada($unit, $tgl, $time, $shift) { 
		$sql = "SELECT count(*) as total from weigher  WHERE equipment_id = ".$unit." AND shift =".$shift." AND date_weigher ='".$tgl."'  AND time_weigher = '".$time."' ";
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"] ; 
	}
	public function import_data($list_data) {  
		$i = 0;
		FOREACH ($list_data AS $k => $v) {
			$i += 1;
			$truck_id  		= $this->check_unit($v["B"] , $v["C"] , $v["D"]  ,$v["E"] );  
			$tanggal		= $v["F"];
			$v["G"] 		= str_replace("S", "", $v["G"]);
			$shift			= $v["G"];
			$time			= $v["H"];
			$bruto			= $v["I"];
			$tara			= $v["J"];
			$netto			= $v["K"]; 

			if (empty($v["M"])) $v["M"] = "";
			if (empty($v["N"])) $v["N"] = "";
			
			$no_doket		= $v["M"]; 
			$material_id	= $v["N"]; 

			$tmp_ada_data = $this->check_apakah_data_sudah_ada($truck_id, $tanggal , $time , $shift);

			if ($tmp_ada_data  == 0 && $truck_id <> "") {
				$data = array( 
					'ritase' 		=> '1',  
					'equipment_id' 	=> $truck_id,  
					'shift' 		=> $shift,   
					'netto' 		=> $netto, 
					'bruto' 		=> $bruto,
					'tara' 			=> $tara,
					'insert_by' 	=> $_SESSION["username"] , 
					'date_weigher' 	=> $tanggal ,
					'time_weigher' 	=> $time , 
					'station_id'	=> '1',
					'no_doket' 		=> $no_doket , 
					'material_id' 	=> $material_id , 
				);
			
				$rs = $this->db->insert('weigher', $data);
				if (!$rs) $error .=",baris ". $v["C"];
			} else {
				$error .=",baris ". $v["C"]. " GAGAL";
			}
		
		} 
		return $error;
	}  

	public function edit_data() {
		$netto = $this->input->post('bruto') - $this->input->post('tara');
		$data = array( 
			'ritase' 	=> '1',  
			'equipment_id' 	=> trim($this->input->post('equipment_id')),  
			'shift' 		=> trim($this->input->post('shift')),  
			'netto' 		=> $netto, 
			'bruto' 		=> $this->input->post('bruto'),
			'tara' 			=> trim($this->input->post('tara')) ,
			'date_weigher' 	=> trim($this->input->post('date_weigher')) ,
			'time_weigher' 	=> trim($this->input->post('time_weigher')) , 
			'station_id'	=> '1',
			'no_doket' 		=> trim($this->input->post('no_doket')) , 
			'material_id' 	=> trim(strtoupper($this->input->post('material_id')))
		);
		$where = "weigher_id=".$this->input->post('old_id');
	
		return $rs = $this->db->update('weigher', $data, $where); 
	}  

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

	public function getTimbanganCpp($start = "", $end="") {  
		$sql = "SELECT
					weigher_id,b.new_eq_num AS unit, e.keterangan as egi ,
					d.keterangan as owner, c.alokasi,a.date_weigher,a.shift,a.time_weigher,a.bruto,
					a.tara,a.netto,ritase,no_doket,material_id
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
	   	 		a.date_weigher >= '".$start."'  AND a.date_weigher <= '".$end."' AND station_id = 1
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
	   	 		a.equipment_id = '".$id."'   AND station_id  = 1
	   	 	ORDER BY weigher_id DESC LIMIT 1
		";
		#echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 


		
}