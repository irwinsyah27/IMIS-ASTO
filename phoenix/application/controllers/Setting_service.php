<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_service extends CI_Controller {
 	private $folder_name			= "setting_service";
 	private $table_name 			= "setting_service";
 	private $primary_key 			= "setting_service_id";
 	private $label_modul			= "setting_service";
 	private $model_name				= "setting_service_model";
 	private $label_list_data		= "List Data";
 	private $label_add_data			= "Add Data";
 	private $label_sukses_dihapus 	= "Data berhasil dihapus";
 	private $label_gagal_dihapus 	= "Gagal menghapus data";
 	private $label_sukses_ditambah 	= "Data berhasil ditambah";
 	private $label_gagal_ditambah 	= "Data gagal ditambah";
 
   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Setting Service</li>';

      	$this->data["folder_name"]	= $this->folder_name;
		$this->load->model( $this->model_name );
		$this->load->model('master_equipment_model');

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "setting_service"; 
		$this->data['check_menu']	= $menu; 

		# akses level
		$akses 			= $this->setting_service_model->user_akses("setting_service");
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',''); 
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);
 
   	}

	public function index()
	{ 
			$this->view_day();
	}

	public function view_day()
	{					
		$this->data['title'] 		= "List Data Setting Service"; 
		$this->data['js'] 			= 'setting_service/js_view_day';
		$this->data['sview'] 		= 'setting_service/view_day'; 
		$this->load->view(_TEMPLATE , $this->data);
	}
	public function view()
	{					
		$this->data['title'] 		= "List Data Setting Service"; 
		$this->data['js'] 			= 'setting_service/js_view';
		$this->data['sview'] 		= 'setting_service/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->setting_service_model->get_list_data();
	}

	public function delete($id){ 
		$rs = $this->setting_service_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('setting_service/');
	}

	public function add()
	{					 

		$this->data['list_unit'] 	= $this->master_equipment_model->getAllData();
		$this->data['title'] 		= "Tambah Data Setting Service"; 
		$this->data['js'] 			= 'setting_service/js_add';
		$this->data['sview'] 		= 'setting_service/add'; 
		$this->load->view(_TEMPLATE , $this->data);
	}
	public function import()
	{					 

		$this->data['title'] 		= "Import Data Setting Service"; 
		$this->data['js'] 			= 'setting_service/js_import';
		$this->data['sview'] 		= 'setting_service/import'; 
		$this->load->view(_TEMPLATE , $this->data);
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
						} 
						$tmp_data[$tmp_iterate][$column_index] =  $val; 
					}
				}
			}
			unlink($upload_data["upload_data"]["full_path"]);
			 
			# upload to database
			$rs = $this->setting_service_model->import_data($tmp_data); 
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
		redirect('setting_service/'); 
	}

	public function add_data()
	{	  
			$rs = $this->setting_service_model->add_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('setting_service/add'); 
	}

	public function edit($id)
	{					 
		$this->data['list_unit'] 	= $this->master_equipment_model->getAllData();
		$this->data['rs'] 			= $this->setting_service_model->getRowData($id);

		$this->data['title'] 		= "Edit Data Setting Service"; 
		$this->data['js'] 			= 'setting_service/js_edit';
		$this->data['sview'] 		= 'setting_service/edit'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function edit_data()
	{	 
			$rs = $this->setting_service_model->edit_data();

			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('setting_service/'); 
	} 
	public function eksport_action() 
	{	 
		$header = "Report Data Setting Service\n"; 

		$data 			= $this->setting_service_model->eksportDataPerTgl($this->input->post("start"),$this->input->post("end"));
	   

		$colnames 		= array("TGL","UNIT","KETERANGAN");
		$colfields 		= array("tgl","unit","keterangan_ps");

		$this->setting_service_model->export_to_excel($colnames,$colfields, $data, $header ,"rpt_setting_service"); 


	} 
}
