<?php
class Breakdown_model extends MY_Model{	  
	function __construct(){
		parent::__construct();
	}
	 

	// fix
	public function get_list_data(){
		//'a.status_breakdown_id',
		$aColumns = array('breakdown_id','b.new_eq_num','d.alokasi','c.kode','e.lokasi','a.hm','a.km','a.date_time_in','a.date_time_out','TIMEDIFF(a.date_time_out, a.date_time_in)','a.diagnosa','f.kode','f.kriteria_komponen','a.tindakan','a.status','a.no_wo');
		$sIndexColumn = "breakdown_id";
		$sTable = ' breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id  
		LEFT JOIN master_breakdown c ON a.master_breakdown_id = c.master_breakdown_id
		LEFT JOIN master_alokasi d ON b.master_alokasi_id = d.master_alokasi_id
		LEFT JOIN master_lokasi e ON a.master_lokasi_id = e.master_lokasi_id
		LEFT JOIN kriteria_komponen f ON a.kriteria_komponen_id=f.kriteria_komponen_id
		';
		
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
		,case when (TIMEDIFF(a.date_time_out,a.date_time_in)  > 0 ) then TIMEDIFF(a.date_time_out,a.date_time_in)  else '' end as durasi 
		,f.kode as kode_kriteria
		,c.kode as kode_breakdown,
		CASE when (date_time_out='0000-00-00 00:00:00') then '' else date_time_out end as date_time_out   
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
				$detail = '<a class="blue" href="'.base_url('breakdown/detail/' . $row->breakdown_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('breakdown/detail/' . $row->breakdown_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
			}
			*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1" )  { //&& $row->status <> 1 
				$edit = '<a class="green" href="'.base_url('breakdown/edit/' . $row->breakdown_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('breakdown/edit/' . $row->breakdown_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1" && $row->status <> 1)  {
				$delete = '<a class="red" href="'.base_url('breakdown/delete/' . $row->breakdown_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('breakdown/delete/' . $row->breakdown_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}

			 
				//$row->status_breakdown_id, 
			array_push($output["aaData"],array(
				$row->breakdown_id,
				$row->new_eq_num, 
				$row->alokasi, 
				$row->kode_breakdown,   
				$row->lokasi,  
				$row->hm,  
				$row->km,  
				$row->date_time_in,  
				$row->date_time_out,  
				$row->durasi,
				$row->diagnosa,
				$row->kode_kriteria,
				$row->kriteria_komponen,
				$row->tindakan,
				$row->no_wo,
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

	public function get_list_data_pcr_all(){
		//'a.status_breakdown_id',
		$aColumns = array('breakdown_id','b.new_eq_num','d.alokasi','c.kode','e.lokasi','a.hm','a.km','a.date_time_in','a.date_time_out','TIMEDIFF(a.date_time_out, a.date_time_in)','f.kriteria_komponen','a.diagnosa','a.tindakan','a.status','a.no_wo');
		$sIndexColumn = "breakdown_id";
		$sTable = ' breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id  
		LEFT JOIN master_breakdown c ON a.master_breakdown_id=c.master_breakdown_id
		LEFT JOIN master_alokasi d ON b.master_alokasi_id = d.master_alokasi_id
		LEFT JOIN master_lokasi e ON a.master_lokasi_id = e.master_lokasi_id
		LEFT JOIN kriteria_komponen f ON a.kriteria_komponen_id=f.kriteria_komponen_id
		';
		
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
		,case when (TIMEDIFF(a.date_time_out,a.date_time_in)  > 0 ) then TIMEDIFF(a.date_time_out,a.date_time_in)  else '' end as durasi 
		, CASE when (date_time_out='0000-00-00 00:00:00') then '' else date_time_out end as date_time_out  
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
				$detail = '<a class="blue" href="'.base_url('breakdown/detail/' . $row->breakdown_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('breakdown/detail/' . $row->breakdown_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
			}
			*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1" )  {
				$edit = '<a class="green" href="'.base_url('breakdown_pcr/edit/' . $row->breakdown_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('breakdown_pcr/edit/' . $row->breakdown_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1" )  {
				$delete = '<a class="red" href="'.base_url('breakdown_pcr/delete/' . $row->breakdown_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('breakdown_pcr/delete/' . $row->breakdown_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}

			 
				//$row->status_breakdown_id, 
			array_push($output["aaData"],array(
				$row->breakdown_id,
				$row->new_eq_num, 
				$row->alokasi, 
				$row->kode,   
				$row->lokasi,  
				$row->hm,  
				$row->km,  
				$row->date_time_in,  
				$row->date_time_out,  
				$row->durasi,
				$row->kriteria_komponen,
				$row->diagnosa,
				$row->tindakan,
				$row->no_wo,
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

	// fix
	public function get_list_data_pcr(){
		$aColumns = array('breakdown_id','b.new_eq_num','a.shift','a.date_time_in','a.date_time_out','a.description','c.station_name','d.kode');
		$sIndexColumn = "breakdown_id";
		$sTable = ' breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id LEFT JOIN  sync_station c ON a.station_id=c.station_id LEFT JOIN master_breakdown d ON a.master_breakdown_id=d.master_breakdown_id';
		
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
		$sWhere = " WHERE 1 = 1 AND status = 1 ";
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
		//'<label class="pos-rel"><input type="checkbox" class="ace" name="id[]" value="'.$row->weigher_id.'" /><span class="lbl"></span></label>',
		
		foreach($rResult as $row){ 
			$detail = "";
			$detail_1 = "";
			/*
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				$detail = '<a class="blue" href="'.base_url('breakdown/detail/' . $row->breakdown_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('breakdown/detail/' . $row->breakdown_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
			}
			*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('breakdown/edit/' . $row->breakdown_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('breakdown/edit/' . $row->breakdown_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('breakdown/delete/' . $row->breakdown_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('breakdown/delete/' . $row->breakdown_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->new_eq_num.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}

			array_push($output["aaData"],array(
				$row->kode,
				$row->new_eq_num,
				$row->shift, 
				$row->date_time_in, 
				$row->date_time_out,  
				$row->description,  
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
			$this->db->where('breakdown_id',$id);
			$rs = $this->db->delete('breakdown');
			return $rs;
		} else return null;
	}  
	public function add_data() {

		$tmp_ada_data = $this->check_apakah_data_sudah_ada($this->input->post('equipment_id'), $this->input->post('date_time_in'));
		if ($tmp_ada_data == 0) {
			$data = array( 
				'equipment_id' 			=> trim($this->input->post('equipment_id')) ,
				'master_breakdown_id' 	=> trim($this->input->post('master_breakdown_id')),  
				'status_breakdown_id' 	=> 'B/D',  
				'master_lokasi_id' 		=> trim($this->input->post('master_lokasi_id')),  
				'hm' 					=> trim($this->input->post('hm')),  
				'km' 					=> trim($this->input->post('km')), 
				'date_time_in' 			=> trim($this->input->post('date_time_in')) , 
				'diagnosa' 				=> trim($this->input->post('diagnosa')),  
				'insert_by' 			=> $_SESSION["username"]
			);


			try {

				if (!$this->db->insert('breakdown', $data))
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
			'equipment_id' 			=> trim($this->input->post('equipment_id')) ,
			'master_breakdown_id' 	=> trim($this->input->post('master_breakdown_id')),  
			'status_breakdown_id' 	=> 'B/D',  
			'master_lokasi_id' 		=> trim($this->input->post('master_lokasi_id')),  
			'hm' 					=> trim($this->input->post('hm')),  
			'km' 					=> trim($this->input->post('km')), 
			//'date_time_in' 			=> trim($this->input->post('date_time_in')) , 
			'diagnosa' 				=> trim($this->input->post('diagnosa')) ,
			'kriteria_komponen_id' 	=> trim($this->input->post('kriteria_komponen_id')) ,
			'tindakan' 				=> trim($this->input->post('tindakan')),   
			'date_time_out' 		=> trim($this->input->post('date_time_out')), 
			'eta_rfu_unit' 			=> trim($this->input->post('eta_rfu_unit')),   
			'eta_waiting_part' 		=> trim($this->input->post('eta_waiting_part')),   
			'no_wo' 				=> trim($this->input->post('no_wo'))
		);
		$where = "breakdown_id=".$this->input->post('old_id');
	
		return $rs = $this->db->update('breakdown', $data, $where);
	}  

	public function update_data_pcr() {
		$data = array( 
			'kriteria_komponen_id' 	=> trim($this->input->post('kriteria_komponen_id')) ,
			'tindakan' 				=> trim($this->input->post('tindakan')),   
			'date_time_out' 		=> trim($this->input->post('date_time_out')),
			'eta_rfu_unit' 			=> trim($this->input->post('eta_rfu_unit')),   
			'eta_waiting_part' 		=> trim($this->input->post('eta_waiting_part')),   
			'update_pcr_by' 		=> $_SESSION["username"] ,
			'date_insert_pcr' 		=> date("Y-m-d H:i"), 
			'date_ready' 			=> date("Y-m-d H:i"), 
			'no_wo' 				=> trim($this->input->post('no_wo')),   
			'hm' 					=> trim($this->input->post('hm')),  
			'km' 					=> trim($this->input->post('km')),  
			'status' 				=> 1,  
			'warning_part' 			=> trim($this->input->post('warning_part')) 
		);
		$where = "breakdown_id=".$this->input->post('old_id');
	
		return $rs = $this->db->update('breakdown', $data, $where);
	}  

	public function update_data_pcr_tanpa_close() {
		$data = array( 
			'kriteria_komponen_id' 	=> trim($this->input->post('kriteria_komponen_id')) ,
			'tindakan' 				=> trim($this->input->post('tindakan')),   
			'date_time_out' 		=> trim($this->input->post('date_time_out')),
			'eta_rfu_unit' 			=> trim($this->input->post('eta_rfu_unit')),   
			'eta_waiting_part' 		=> trim($this->input->post('eta_waiting_part')),    
			'update_pcr_by' 		=> $_SESSION["username"] ,
			'date_insert_pcr' 		=> date("Y-m-d H:i"), 
			'no_wo' 				=> trim($this->input->post('no_wo')),   
			'hm' 					=> trim($this->input->post('hm')),  
			'km' 					=> trim($this->input->post('km')) ,  
			'warning_part' 			=> trim($this->input->post('warning_part')) 
		);
		$where = "breakdown_id=".$this->input->post('old_id');
	
		return $rs = $this->db->update('breakdown', $data, $where);
	}  

	public function getRowData($id) { 
		$sql = "SELECT
					*
			FROM
	   	 		 breakdown a  
	   	 	WHERE
	   	 		a.breakdown_id = '".$id."'  
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function getMasterBreakdown() { 
		$sql = "SELECT
					*
			FROM
	   	 		 master_breakdown a   
		";
		//echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	
	public function antrian_instruksi($type = "" , $breakdown = "") { 
		$sql = "SELECT
					breakdown_id , b.new_eq_num , d.alokasi , c.kode , 
					a.status_breakdown_id , e.lokasi , a.hm , a.km ,  
					a.date_time_in,
					a.date_time_out,
					date_format(a.date_time_in,'%Y-%m-%d') as date_in,
					date_format(a.date_time_in,'%H:%i') as time_in, 
					case when (TIMEDIFF(now(),a.date_time_in)  > 0 ) then TIMEDIFF(now(),a.date_time_in)  else '' end as durasi ,
					f.kriteria_komponen , a.diagnosa , a.tindakan , a.status , no_wo
			FROM
	   	 		 breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id  
				LEFT JOIN master_breakdown c ON a.master_breakdown_id=c.master_breakdown_id
				LEFT JOIN master_alokasi d ON b.master_alokasi_id = d.master_alokasi_id
				LEFT JOIN master_lokasi e ON a.master_lokasi_id = e.master_lokasi_id
				LEFT JOIN kriteria_komponen f ON a.kriteria_komponen_id=f.kriteria_komponen_id
	   	 	WHERE 
	   	 		a.status = 0";
	   	 if (isset($type)  & $type <> "" && $type <> null) {
	   	 	$sql .= " AND  b.master_alokasi_id IN ( ".$type .")";
	   	 }
	   	 if (isset($breakdown) && $breakdown <> "" && $breakdown <> null) {
	   	 	$sql .= " AND  a.master_breakdown_id IN (  ".$breakdown .")";
	   	 }
	   	 $sql  .= "
	   	 	ORDER BY 
	   	 		a.breakdown_id  DESC
		";  
		#echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 

/*
					date_format(a.date_time_out,'%Y-%m-%d') as date_out,
					date_format(a.date_time_out,'%H:%i') as time_out, 
					*/
	public function getDataPerDate($start = "", $end="") {  
		$sql = "SELECT
					breakdown_id , b.new_eq_num , d.alokasi , c.kode , 
					a.status_breakdown_id , e.lokasi , a.hm , a.km ,  
					date_format(a.date_time_in,'%Y-%m-%d') as date_in,
					date_format(a.date_time_in,'%H:%i') as time_in,
					case when (TIMEDIFF(a.date_time_out,a.date_time_in)  > 0 ) then TIMEDIFF(a.date_time_out,a.date_time_in)  else '' end as durasi ,
					f.kriteria_komponen , a.diagnosa , a.tindakan , a.status ,f.kode as kode_kriteria ,
					no_wo,
					date_format(a.date_insert_pcr,'%Y-%m-%d') as date_ready,
					date_format(a.date_insert_pcr,'%H:%i') as time_ready

					,CASE when (date_time_out='0000-00-00 00:00:00') then '' else date_format(a.date_time_out,'%Y-%m-%d') end as date_out  
					,CASE when (date_time_out='0000-00-00 00:00:00') then '' else date_format(a.date_time_out,'%H:%i') end as time_out 
			FROM
	   	 		 breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id  
				LEFT JOIN master_breakdown c ON a.master_breakdown_id=c.master_breakdown_id
				LEFT JOIN master_alokasi d ON b.master_alokasi_id = d.master_alokasi_id
				LEFT JOIN master_lokasi e ON a.master_lokasi_id = e.master_lokasi_id
				LEFT JOIN kriteria_komponen f ON a.kriteria_komponen_id=f.kriteria_komponen_id
	   	 	WHERE
				date_format(a.date_time_in,'%Y-%m-%d') BETWEEN '".$start."' AND '".$end."' OR
	   	 		date_format(a.date_time_out,'%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'
	   		ORDER BY
	   			a.breakdown_id DESC
		";   
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}  
	public function getDataOpen($start = "", $end="") {  
		$sql = "SELECT
					breakdown_id , b.new_eq_num , d.alokasi , c.kode , 
					a.status_breakdown_id , e.lokasi , a.hm , a.km ,  
					date_format(a.date_time_in,'%Y-%m-%d') as date_in,
					date_format(a.date_time_in,'%H:%i') as time_in,
					case when (TIMEDIFF(a.date_time_out,a.date_time_in)  > 0 ) then TIMEDIFF(a.date_time_out,a.date_time_in)  else '' end as durasi ,
					f.kriteria_komponen , a.diagnosa , a.tindakan , a.status ,f.kode as kode_kriteria ,
					no_wo,
					date_format(a.date_insert_pcr,'%Y-%m-%d') as date_ready,
					date_format(a.date_insert_pcr,'%H:%i') as time_ready

					,CASE when (date_time_out='0000-00-00 00:00:00') then '' else date_format(a.date_time_out,'%Y-%m-%d') end as date_out  
					,CASE when (date_time_out='0000-00-00 00:00:00') then '' else date_format(a.date_time_out,'%H:%i') end as time_out 
			FROM
	   	 		 breakdown a LEFT JOIN master_equipment b ON a.equipment_id = b.master_equipment_id  
				LEFT JOIN master_breakdown c ON a.master_breakdown_id=c.master_breakdown_id
				LEFT JOIN master_alokasi d ON b.master_alokasi_id = d.master_alokasi_id
				LEFT JOIN master_lokasi e ON a.master_lokasi_id = e.master_lokasi_id
				LEFT JOIN kriteria_komponen f ON a.kriteria_komponen_id=f.kriteria_komponen_id
	   	 	WHERE
	   	 		(date_format(a.date_time_in,'%Y-%m-%d') < '".$start."'  AND a.status = 0)  
	   		ORDER BY
	   			a.breakdown_id DESC
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
	public function check_data_and_insert_if_empty_v2($table="", $field="", $val="", $pk="") {
		$val 	= strtoupper(trim($val));  
		
		if ($val <> "") {
			$sql = "SELECT * from $table WHERE LOWER($field) = '".strtolower(trim($val))."'";
			$res  = $this->db->query($sql);
			$rs = $res->row_array(); 
			if ($rs[$field] =="") {
				$sqli = "INSERT INTO $table ($pk, $field) VALUES ('".trim($val)."','".trim($val)."')";
				$resi = $this->db->query($sqli); 
				return $val;
			} else {
				return $rs[$pk];
			}  	
		}
		
	} 
	public function check_data_and_insert_if_empty_v3($table="", $field="", $kode = "", $val="", $pk="") {
		$kode 	= strtoupper(trim($kode));  
		
		if ($kode <> "") {
			$sql = "SELECT * from $table WHERE LOWER($field) = '".strtolower(trim($kode))."'";
			$res  = $this->db->query($sql);
			$rs = $res->row_array(); 
			if ($rs[$field] =="") {
				$sqli = "INSERT INTO $table (kode, kriteria_komponen) VALUES ('".trim($kode)."','".trim($val)."')";
				$resi = $this->db->query($sqli); 
				$lastid = $this->db->insert_id();
				return $lastid;
			} else {
				return $rs[$pk];
			}  	
		}
		
	}
	public function get_unit_id($unit) {
		$unit 	= strtoupper(trim($unit));  
		 
		$sql = "SELECT * from master_equipment WHERE LOWER(new_eq_num) = '".strtolower(trim($unit))."'"; 
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 

		if ($rs["master_equipment_id"] =="") {
			$sqli = "INSERT INTO master_equipment (new_eq_num) VALUES ('".trim($unit)."')";
			$resi = $this->db->query($sqli);
			$lastid = $this->db->insert_id();
			return $lastid;
		} else {
			return $rs["master_equipment_id"];
		} 

	}

	public function check_apakah_data_sudah_ada($unit, $date) { 
		$sql = "SELECT count(*) as total   from breakdown  WHERE equipment_id = '".$unit."' &&  date_time_in ='".$date."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs["total"] ; 
	}
	/*
	public function check_apakah_data_sudah_ada_v2($unit, $date) { 
		$sql = "SELECT count(*) as total , breakdown_id from breakdown  WHERE equipment_id = '".$unit."' &&  date_time_in ='".$date."' GROUP BY breakdown_id";
		$res  = $this->db->query($sql);
		$rs = $res->row_array(); 
		return $rs ; 
	}
	*/

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

			if (trim($v["B"]) <> "") {
				$equipment_id  			= $this->check_data_and_insert_if_empty("master_equipment","new_eq_num",$v["B"],"master_equipment_id");  
				$master_breakdown_id  	= $this->check_data_and_insert_if_empty("master_breakdown","kode",$v["D"],"master_breakdown_id");     
				#$status_breakdown_id  	= $this->check_data_and_insert_if_empty_v2("status_breakdown","status_breakdown",$v["E"],"status_breakdown_id");       
				$kriteria_komponen_id  	= $this->check_data_and_insert_if_empty_v3("kriteria_komponen","kode",$v["N"],$v["O"],"kriteria_komponen_id");  
				$master_lokasi_id  	= $this->check_data_and_insert_if_empty("master_lokasi","lokasi",$v["E"],"master_lokasi_id");  


				$date_time_in		= $v["H"]." ".$v["I"];
				$date_time_out		= $v["J"]." ".$v["K"];

				$tmp_ada_data = "";
				//$tmp_ada_data = $this->check_apakah_data_sudah_ada_v2($equipment_id, $date_time_in);
				$tmp_ada_data = $this->check_apakah_data_sudah_ada($equipment_id, $date_time_in);
				if ($tmp_ada_data  == 0) {
					$status = 0;
					if (isset($v["P"]) && strlen(trim($v["P"]))>0) $status = 1;

					$data = array( 
						'equipment_id' 			=> $equipment_id ,
						'master_breakdown_id' 	=> $master_breakdown_id,  
						'status_breakdown_id' 	=> 'B/D',  
						'master_lokasi_id' 		=> $master_lokasi_id,  
						'hm' 					=> trim($v["F"]),  
						'km' 					=> trim($v["G"]), 
						'date_time_in' 			=> trim($date_time_in) , 
						'date_time_out' 		=> trim($date_time_out) , 
						'diagnosa' 				=> $v["M"],   
						'tindakan' 				=> $v["P"],   
						'no_wo' 				=> $v["Q"],   
						'kriteria_komponen_id' 	=> $kriteria_komponen_id,   
						'status' 				=> $status,   
						'insert_by' 			=> $_SESSION["username"]
		 
					);
				
					$rs = $this->db->insert('breakdown', $data); 
					if (!$rs) $error .=",baris ". $v["A"];
				} else { 
					$status = 0;
					if (isset($v["P"]) && strlen(trim($v["P"]))>0) $status = 1;
					$data = array(   
						'kriteria_komponen_id' 	=> $kriteria_komponen_id ,   
						'status' 				=> $status
					);
					//$where = "breakdown_id =".$tmp_ada_data["breakdown_id"];
					$where = "equipment_id = '".$equipment_id."' &&  date_time_in ='".$date_time_in."'";
					$rs = $this->db->update('breakdown', $data, $where); 
					if (!$rs) $error .=",baris ". $v["A"];
				}
			}
			
		
		} 
		return $error;
	}  

	public function gelAllDataTable() { 
		$sql = "SELECT
					*
			FROM
	   	 		 kriteria_komponen
	   	 	ORDER BY 
	   	 		kode  ASC
		"; 
		#echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function getMasterAlokasi() { 
		$sql = "SELECT
					*
			FROM
	   	 		 master_alokasi
	   	 	ORDER BY 
	   	 		alokasi  ASC
		"; 
		#echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function getMasterBreakdown2() { 
		$sql = "SELECT
					*
			FROM
	   	 		 master_breakdown
	   	 	ORDER BY 
	   	 		kode  ASC
		"; 
		#echo $sql;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 


		
}