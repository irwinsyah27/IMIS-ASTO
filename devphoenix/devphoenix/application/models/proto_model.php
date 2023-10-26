<?php
class proto_model extends MY_Model{	
	protected $_table_name = 'guru';
	protected $_order_by = 'grID';
	protected $_pk			= 'grID';
	
	function __construct(){
		parent::__construct();
	}
	
	public $rules_upload = array(
		'userfile' => array(
			'field' => 'userfile', 
			'label' => 'Nama File', 
			'rules' => 'trim|required|xss_clean'
		)		 
	);
	
	public $rules_add_update = array(
		'nama' => array(
			'field' => 'grNama', 
			'label' => 'Nama Lengkap', 
			'rules' => 'trim|required|xss_clean'
		),
		'nip' => array(
			'field' => 'grNIP', 
			'label' => 'NIP', 
			'rules' => 'trim|required|xss_clean|callback__unique_nip'
		), 
		/*'email' => array(
			'field' => 'grEmail', 
			'label' => 'Email', 
			'rules' => 'trim|required|xss_clean|valid_email'
		),*/ 
		'tgl' => array(
			'field' => 'grTgLahir', 
			'label' => 'Tanggal Lahir', 
			'rules' => 'trim|required|xss_clean'
		) 
	);

	// fix
	public function get_list_data(){
		$aColumns = array('proto_id','kode','nama');
		$sIndexColumn = "proto_id";
		$sTable = ' proto ';
		
		/* Paging */
		$sLimit = "";
		if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1'){
			$sLimit = "LIMIT ".mysql_real_escape_string($_GET['iDisplayStart']).", ".
			mysql_real_escape_string($_GET['iDisplayLength']);
		}
		
		/* Ordering */
		$sOrder = "";
		if(isset($_GET['iSortCol_0'])){
			$sOrder = "ORDER BY  ";
			for ($i=0 ; $i<intval($_GET['iSortingCols']) ; $i++){
				if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true"){
					$sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
				 	".mysql_real_escape_string($_GET['sSortDir_'.$i]) .", ";
				}
			}
		
			$sOrder = substr_replace($sOrder, "", -2);
			if($sOrder == "ORDER BY"){
				$sOrder = "";
			}
		}
		
		/* Filtering */
		$sWhere = "";
		if(isset($_GET['sSearch']) && $_GET['sSearch'] != ""){
			$sWhere = "WHERE (";
			for($i=0; $i<count($aColumns); $i++){
				$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch'])."%' OR ";
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
				$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
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
		//'<label class="pos-rel"><input type="checkbox" class="ace" name="id[]" value="'.$row->proto_id.'" /><span class="lbl"></span></label>',
		
		foreach($rResult as $row){ 
			$detail = "";
			$detail_1 = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				$detail = '<a class="blue" href="'.base_url('proto/detail/' . $row->proto_id).'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				$detail_1 = '<li><a href="'.base_url('proto/detail/' . $row->proto_id).'" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span></a></li>';	
			}
			$edit = "";
			$edit_1 = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="green" href="'.base_url('proto/edit/' . $row->proto_id).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$edit_1 = '<li><a href="'.base_url('proto/edit/' . $row->proto_id).'" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a></li>';
			}
			$delete = "";
			$delete_1 = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete = '<a class="red" href="'.base_url('proto/delete/' . $row->proto_id).'"  role="button"  data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$delete_1 = '<li><a href="'.base_url('proto/delete/' . $row->proto_id).'" class="tooltip-error" data-rel="tooltip" title="Delete" data-toggle="modal" data-confirm="Apakah anda yakin ingin menghapus data '.$row->nama.'?"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a></li>';
			}
			array_push($output["aaData"],array(
				$row->kode,
				$row->nama, 
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
			$this->db->where('proto_id',$id);
			$rs = $this->db->delete('proto');
			return $rs;
		} else return null;
	}
	
	public function get_new(){
		$guru = new stdClass();
		$guru->grNama = '';
		$guru->grJabatan = '';
		$guru->grTpLahir = '';
		$guru->grPendidikan = '';
		$guru->grAgama = '';
		$guru->grJK = '';
		$guru->grStatus = '';
		$guru->grTgLahir = '';
		$guru->grThMasuk = '';
		$guru->grThLulus = '';
		$guru->grJurusan = '';
		$guru->grAlamat = '';
		$guru->grNPWP = '';
		$guru->grNIP = '';
		$guru->grTelp = '';
		$guru->grPswd = '';
		$guru->grEmail = '';
		$guru->grTingkat = '';
		$guru->grPenataran = '';
		$guru->grNUPTK = '';
		return $guru;
	}
	
	public function cekNIP($nip){
		$this->db->where('grNIP',$nip);
		$Q = $this->db->get('guru');
		return $Q->row();
	}
	
	/*public function chkDelete($id){
		$this->db->where('id_grup',$id);
		$rs = $this->db->get('users');
		return $rs->num_rows();
	}*/
}