<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Road_index extends CI_Controller {

   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"])) header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">road_index</li>';

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "road_index";
		$this->data['check_menu']	= $menu;

		$this->load->model('road_index_model');
		$this->load->model('master_employee_model');
		$this->load->model('master_kerusakan_model');
		$this->load->model('Master_sta_model');
		$this->load->model('Master_shift_model');
		$this->load->model('Master_severity_model');
		$this->load->library('upload', [
			'upload_path' => './uploads/',
			'allowed_types' => 'gif|png|jpg|jpeg|pdf|xls|xlsx|doc|docx|zip',
		]);

		#$this->data["list_severity"]  = ["Low" => "Low", "Medium"=>"Medium", "High"=>"High" ];
		

		# akses level
		$akses = $this->road_index_model->user_akses("road_index");
		define('_USER_ACCESS_LEVEL_VIEW', $akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD', $akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE', $akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE', $akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL', '');
		define('_USER_ACCESS_LEVEL_IMPORT', $akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);

   	}

	public function index()
	{
			$this->view();
	}

	public function view()
	{
		$this->data['title'] 		= "List Road Condition Index";
		$this->data['js'] 			= 'road_index/js_view';
		$this->data['sview'] 		= 'road_index/view';
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->road_index_model->get_list_data();
	}

	public function delete($id){
		$rs = $this->road_index_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('road_index/');
	}

	public function add()
	{
		$this->data['list_operator'] 		= $this->master_employee_model->getAllDataOperator();
		$this->data["list_problem_road"]    = $this->master_kerusakan_model->getAllDataRoad();
		$this->data["list_sta_lokasi"]    	= $this->Master_sta_model->getAllDataStaLokasi();
		$this->data["list_sta_meter"]    	= $this->Master_sta_model->getAllDataStaMeter();
		$this->data["list_shift"]    		= $this->Master_shift_model->getAlldata();
		$this->data["list_severity"]    	= $this->Master_severity_model->getAllDataSeverity();

		//$this->data['list_problem_road'] 	= $this->mylib->generate2darray("master_problem_road" , "master_problem_road_id","jenis_kerusakan"); 

		$this->data['title'] 		= "Tambah Data Road Index Condition";
		$this->data['js'] 			= 'road_index/js_add';
		$this->data['sview'] 		= 'road_index/add';
		$this->load->view(_TEMPLATE , $this->data);

	}

	public function add_data()
	{
		$rs = $this->road_index_model->apakah_data_sudah_ada($this->input->post("sta_lokasi_id"));
		if ($rs["sta_lokasi_id"] <>"") {
			$msg 		= "Gagal add data, Lokasi ini sudah pernah di input";
			$stats 		= '0';
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('road_index/add');
		} else {
			$rs = $this->road_index_model->add_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('road_index/');
		}
	}

	public function edit($id)
	{
		$this->data['list_operator'] 		= $this->master_employee_model->getAllDataOperator(); 
		$this->data["list_problem_road"]    = $this->master_kerusakan_model->getAllDataRoad();
		$this->data["list_sta_lokasi"]    	= $this->Master_sta_model->getAllDataStaLokasi();
		$this->data["list_sta_meter"]    	= $this->Master_sta_model->getAllDataStaMeter();
		$this->data["list_shift"]    		= $this->Master_shift_model->getAlldata();
		$this->data["list_severity"]    	= $this->Master_severity_model->getAllDataSeverity();

		//$this->data['list_problem_road'] 	= $this->mylib->generate2darray("master_problem_road" , "kode","kode","jenis_kerusakan");

		$this->data['rs'] 			= $this->road_index_model->getRowData($id);

		$this->data['title'] 		= "Edit Data Road Condition Index";
		$this->data['js'] 			= 'road_index/js_edit';
		$this->data['sview'] 		= 'road_index/edit';
		$this->load->view(_TEMPLATE , $this->data);
	}


	public function edit_data()
	{
			$rs = $this->road_index_model->edit_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('road_index/');
	}

	public function import_action()
	{
		$config['upload_path'] = './assets/tmp/';
		$config['allowed_types'] = 'xls|xlsx';

		$this->load->library('upload', $config);


		if (! $this->upload->do_upload("userfile")) {
			$err_msg = array('error' => $this->upload->display_errors());
				echo "Masuk sini: ".print_r($err_msg);
				exit;
			$view_success = "fail";
		} else {
			$upload_data = array('upload_data' => $this->upload->data());
			$this->load->library('PHPExcel/IOFactory');
			$this->load->library('PHPExcel');

			$objPHPExcel = new PHPExcel_Reader_Excel5();

			$objFile = $objPHPExcel->load($upload_data["upload_data"]["full_path"]);

			$objWorksheet = $objFile->setActiveSheetIndex(0);
			$tmp_iterate = 0;
			foreach ($objWorksheet->getRowIterator() as $row) {
				$row_index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				if ($row_index > 1) {
					$tmp_iterate += 1;

					foreach($cellIterator as $cell) {
						$column_index = $cell->getColumn();

						$val= trim($cell->getValue());
						if(PHPExcel_Shared_Date::isDateTime($cell)) {
							$format = "";
							if ($column_index == 'B') {
								$format = "Y-m-d";
								$val = date($format, PHPExcel_Shared_Date::ExcelToPHP($val));
							}
							if ($column_index == 'G') {
								$format = "Y-m-d";
								$val = date($format, PHPExcel_Shared_Date::ExcelToPHP($val));
							}
							if ($column_index == 'I') {
								$format = "Y-m-d";
								$val = date($format, PHPExcel_Shared_Date::ExcelToPHP($val));
							}
							if ($column_index == 'H') {
								$val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm');
							}
							if ($column_index == 'J') {
								$val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm');
							}
						}

						$tmp_data[$tmp_iterate][$column_index] =  $val;
					}
				}
			}
			unlink($upload_data["upload_data"]["full_path"]);

			# upload to database
			$rs = $this->road_index_model->import_data($tmp_data);
		}

		if($rs == ""){
			$msg = 'Data berhasil diimport';
			$stats = '1';
		} else {
			$msg = 'Import data di baris : '. $rs;
			$stats = '0';
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('road_index/');
	}

	public function eksport_action()
	{
		$header = "Report Data Road Condition Index \n";
		$header .= "Start\t".$this->input->post("start")."\n";
		$header .= "Stop\t".$this->input->post("end")."\n";

		$data			= $this->road_index_model->getDataPerDate($this->input->post("start"),$this->input->post("end"));

		$colnames 		= array("NRP", "NAMA","DATE_OPEN","DATE_CLOSED","SHIFT","STA_LOKASI","STA_METER","KERUSAKAN","SEVERITY","DATE_INSERT","STATUS");
		$colfields 		= array("nip", "nama","date_in","date_out","master_shift_id","sta_lokasi","sta_meter","kerusakan",'severity',"date_insert","status");
		//$collnames = array("nrp");
		//$colfields = array("nip");

		$this->road_index_model->export_to_excel($colnames, $colfields, $data, $header ,"rpt_rci"); 
	}
	
}
