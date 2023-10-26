<?php
class Operator_model extends MY_Model{
	function __construct(){
		parent::__construct();
	}

	// fix
	public function get_list_data(){
		$aColumns = array('a.operator_id','a.nrp','a.nama','b.keterangan','c.keterangan','d.kode','a.status');
		$sIndexColumn = "a.operator_id";
		$sTable = ' master_employee a LEFT JOIN master_posisi b ON a.master_posisi_id = b.master_posisi_id
		LEFT JOIN  master_departemen c ON a.master_departemen_id = c.master_departemen_id
		LEFT JOIN master_owner d ON a.master_owner_id = d.master_owner_id';

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
		, b.keterangan as posisi,
		c.keterangan as departemen
		, d.kode as perusahaan
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
				$detail = '<a class="blue" href="'.base_url('operator/detail/' . $row->weigher_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('operator/detail/' . $row->weigher_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';
			}
			*/
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('operator/edit/' . $row->operator_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('operator/edit/' . $row->operator_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$fk = $this->check_apakah_sudah_digunakan_ditable_lain("daily_absent", "nip", $row->nrp);
				$fk2 = $this->check_apakah_sudah_digunakan_ditable_lain("absensi", "nip", $row->nrp);
				$fk3 = $this->check_apakah_sudah_digunakan_ditable_lain("fuel_refill", "nrp", $row->nrp);
				if ($fk == 0 && $fk2 == 0 && $fk3 == 0) {
					$delete = '<a class="red" href="'.base_url('operator/delete/' . $row->operator_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
					$delete_1 = '<li><a href="'.base_url('operator/delete/' . $row->operator_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
				}
			}
			array_push($output["aaData"],array(
				$row->operator_id,
				$row->nrp,
				$row->nama,
				$row->posisi,
				$row->departemen,
				$row->perusahaan,
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
			$this->db->where('operator_id',$id);
			$rs = $this->db->delete('master_employee');
			return $rs;
		} else return null;
	}
	public function add_data() {
		$data = array(
			'nrp' 					=> trim($this->input->post('nrp')),
			'nama' 					=> trim($this->input->post('nama')) ,
			'status' 				=> trim($this->input->post('status')) ,
			'master_posisi_id' 		=> trim($this->input->post('master_posisi_id')) ,
			'master_owner_id' 		=> trim($this->input->post('master_owner_id')) ,
			'master_departemen_id' 	=> trim($this->input->post('master_departemen_id'))
		);

		return $rs = $this->db->insert('master_employee', $data);
	}

	public function edit_data() {
		$data = array(
			'nrp' 					=> trim($this->input->post('nrp')),
			'nama' 					=> trim($this->input->post('nama')) ,
			'status' 				=> trim($this->input->post('status')) ,
			'master_posisi_id' 		=> trim($this->input->post('master_posisi_id')) ,
			'master_owner_id' 		=> trim($this->input->post('master_owner_id')) ,
			'master_departemen_id' 	=> trim($this->input->post('master_departemen_id'))
		);
		$where = "operator_id = ".$this->input->post('old_id');

		return  $rs = $this->db->update('master_employee', $data, $where);
	}

	public function getRowData($id) {
		$sql = "SELECT
					*
			FROM
	   	 		 master_employee a
	   	 	WHERE
	   	 		a.operator_id = '".$id."'
		";
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	}
	public function getAlldata() {
		$sql = "SELECT
					a.*,
					b.keterangan as posisi ,
					c.keterangan as departemen,
					d.kode as perusahaan
			FROM
	   	 		 master_employee a
	   	 	LEFT JOIN
	   	 		master_posisi b
	   	 	ON
	   	 		a.master_posisi_id = b.master_posisi_id
	   	 	LEFT JOIN
	   	 		master_departemen c
	   	 	ON
	   	 		a.master_departemen_id = c.master_departemen_id
	   	 	LEFT JOIN
	   	 		master_owner d
	   	 	ON
	   	 		a.master_owner_id = d.master_owner_id
	   	 	ORDER BY nama
		";
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}

	public function check_data_and_insert_if_empty($table="", $field="", $val="", $pk="") {
		#$val 	= strtoupper(trim($val));
		#if ($val <> "") {
		$sql = "SELECT * from $table WHERE LOWER($field) = '".strtolower(trim($val))."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		if ($rs[$pk] != "") {
			return $rs[$pk];
		} else {
			$sqli = "INSERT INTO $table ($field) VALUES ('".$val."')";
			$resi = $this->db->query($sqli);
			$unit_id = $this->db->insert_id();
			return $unit_id;
		}
	}
	public function check_apakah_data_sudah_ada($var) {
		$sql = "SELECT count(*) as total  from master_employee  WHERE lower(nrp) = '".strtolower(trim($var))."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs["total"] ;
	}

	public function import_data($list_data) {
		$i = 0;
		FOREACH ($list_data AS $k => $v) {
			$i += 1;

			$v["B"] = str_replace(" ", "", $v["B"]);

			$master_posisi_id 	= $this->check_data_and_insert_if_empty("master_posisi" ,"keterangan", $v["D"], "master_posisi_id");
			$master_jabatan_id 	= $this->check_data_and_insert_if_empty("master_departemen" ,"keterangan", $v["E"], "master_departemen_id");
			$master_perusahaan	= $this->check_data_and_insert_if_empty("master_owner" ,"kode", $v["F"], "master_owner_id");

			$tmp_ada_data = $this->check_apakah_data_sudah_ada($v["B"]);
			if ($tmp_ada_data == 0) {
				$data = array(
					'nrp' 						=> $v["B"],
					'nama' 						=> $v["C"],
					'master_posisi_id' 			=> $master_posisi_id,
					'master_departemen_id' 		=> $master_jabatan_id,
					'master_owner_id' 			=> $master_perusahaan,
					'status' 					=> $v["G"],
					'insert_by' 				=> $_SESSION["username"]
				);

				$rs = $this->db->insert('master_employee', $data);
				if (!$rs) $error .=",baris ". $v["B"];
			} else {
				if (!$rs) $error .="baris ". $v["A"]." : ". $v["B"]." sudah ada\n\r<br>";
			}
		}
		return $error;
	}



}
