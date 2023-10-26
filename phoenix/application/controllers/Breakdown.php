<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Breakdown extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Breakdown</li>';

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "breakdown"; 
		$this->data['check_menu']	= $menu;

		$this->load->model('breakdown_model');
		$this->load->model('master_equipment_model'); 
		$this->load->model('setting_service_model');

		# akses level
		$akses 			= $this->breakdown_model->user_akses("breakdown");
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',''); 
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);

		/*
		$this->data['hari']		=   array ("1" => "Senin",
							"2" => "Selasa",
							"3" => "Rabu",
							"4" => "Kamis",
							"5" => "Jumat",
							"6" => "Sabtu",
							"7" => "Minggu",
			);
		*/
   	}

	public function index()
	{ 
			$this->view();
	}

	public function view()
	{					
		$this->data['title'] 		= "List Data breakdown"; 
		$this->data['js'] 			= 'breakdown/js_view';
		$this->data['sview'] 		= 'breakdown/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->breakdown_model->get_list_data();
	}

	public function delete($id){ 
		$rs = $this->breakdown_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('breakdown/');
	}

	public function add()
	{					
		$this->data['list_unit'] 		= $this->master_equipment_model->getAllData(); 
		$this->data['list_breakdown'] 	= $this->breakdown_model->getMasterBreakdown();
 
		$this->data['list_lokasi_breakdown'] 	= $this->mylib->generate2darray("master_lokasi" , "master_lokasi_id","lokasi"); 
		$this->data['list_status_breakdown']	= $this->mylib->generate2darray("status_breakdown" , "status_breakdown_id","status_breakdown"); 

		$this->data['title'] 		= "Tambah Data Breakdown"; 
		$this->data['js'] 			= 'breakdown/js_add';
		$this->data['sview'] 		= 'breakdown/add'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function add_data()
	{	 
			$rs = $this->breakdown_model->add_data();

			// if($rs){
			// 	$msg = 'Data berhasil disimpan';
			// 	$stats = '1';
			// } else {
			// 	$msg = 'Data gagal disimpan';
			// 	$stats = '0';

			$this->session->set_flashdata('msg',$rs[1]);
			$this->session->set_flashdata('stats',$rs[0]);

			redirect('breakdown/add'); 
			
	}

	public function edit($id)
	{					
		$this->data['list_unit'] 		= $this->master_equipment_model->getAllData(); 
		$this->data['list_breakdown'] 	= $this->breakdown_model->getMasterBreakdown();
  
		$this->data['list_lokasi_breakdown'] 	= $this->mylib->generate2darray("master_lokasi" , "master_lokasi_id","lokasi"); 
		$this->data['list_status_breakdown']	= $this->mylib->generate2darray("status_breakdown" , "status_breakdown_id","status_breakdown");
		$this->data['list_kriteria_komponen']	= $this->breakdown_model->gelAllDataTable(); 

		$this->data['rs'] 			= $this->breakdown_model->getRowData($id);

		$this->data['title'] 		= "Update Data breakdown"; 
		$this->data['js'] 			= 'breakdown/js_update';
		$this->data['sview'] 		= 'breakdown/update'; 
		$this->load->view(_TEMPLATE , $this->data);
	}
	public function update_data()
	{	 
			$rs = $this->breakdown_model->update_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Data gagal disimpan';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('breakdown/'); 
	} 
	public function eksport_action() 
	{	 
		$header = "Report Data Breakdown\n";
		$header .= "Start\t".$this->input->post("start")."\n";
		$header .= "Stop\t".$this->input->post("end")."\n";

		$data1 			= $this->breakdown_model->getDataPerDate($this->input->post("start"),$this->input->post("end"));
		$data2			= $this->breakdown_model->getDataOpen($this->input->post("start"));
	     
	    $data = array_merge($data1, $data2); 

		$colnames 		= array("UNIT","TYPE", "JENIS B/D","LOKASI B/D","HM UNIT","KM UNIT","DATE IN","TIME IN","DATE OUT","TIME OUT","DOWNTIME","PROBLEM","KODE","KRITERIA KOMPONEN","TINDAKAN","NO WO");
		$colfields 		= array("new_eq_num","alokasi", "kode","lokasi","hm","km","date_in","time_in","date_out","time_out","durasi","diagnosa","kode_kriteria","kriteria_komponen","tindakan","no_wo");

		$this->breakdown_model->export_to_excel($colnames,$colfields, $data, $header ,"rpt_breakdown"); 


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
							if ($column_index == 'H') {
								$format = "Y-m-d"; 
								$val = date($format, PHPExcel_Shared_Date::ExcelToPHP($val)); 
							}
							if ($column_index == 'J') {
								$format = "Y-m-d"; 
								$val = date($format, PHPExcel_Shared_Date::ExcelToPHP($val)); 
							}
							if ($column_index == 'I') { 
								$val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm');
							} 
							if ($column_index == 'K') { 
								$val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm');
							} 
						}

						$tmp_data[$tmp_iterate][$column_index] =  $val; 
					}
				}
			}
			unlink($upload_data["upload_data"]["full_path"]);
			 
			# upload to database
			$rs = $this->breakdown_model->import_data($tmp_data); 
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
		redirect('breakdown/'); 
	}

	public function schedule_breakdown() 
	{	  
		$id 		= $_GET['id'];
		$data 		= $this->setting_service_model->jsonGetListDataScheduleServiceByTgl($id);
	}  
}
