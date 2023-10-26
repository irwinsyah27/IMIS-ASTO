<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plan_produksi extends CI_Controller {
 	private $folder_name			= "plan_produksi";
 	private $table_name 			= "weigher";
 	private $primary_key 			= "weigher_id";
 	private $label_modul			= "Plan Produksi";
 	private $model_name				= "plan_produksi_model";
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
							<li class="active">Plan Produksi</li>';

      	$this->data["folder_name"]	= $this->folder_name;
		$this->load->model( $this->model_name );

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "plan_produksi"; 
		$this->data['check_menu']	= $menu;
  

		# akses level
		$akses 			= $this->plan_produksi_model->user_akses("plan_produksi");
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
			$this->view();
	}

	public function view()
	{					
		$this->data['title'] 		= "List Data Plan Produksi"; 
		$this->data['js'] 			= 'plan_produksi/js_view';
		$this->data['sview'] 		= 'plan_produksi/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->plan_produksi_model->get_list_data();
	}

	public function delete($id){ 
		$rs = $this->plan_produksi_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('plan_produksi/');
	}

	public function add()
	{					
		$this->data['list_plan']	= $this->plan_produksi_model->getListPlan(); 
		

		$this->data['title'] 		= "Tambah Data Plan Produksi"; 
		$this->data['js'] 			= 'plan_produksi/js_add';
		$this->data['sview'] 		= 'plan_produksi/add'; 
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
							if ($column_index == 'C') {
								$format = "Y-m-d"; 
								$val = date($format, PHPExcel_Shared_Date::ExcelToPHP($val)); 
							}
							if ($column_index == 'D') { 
								$val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm');
							} 
							if ($column_index == 'E') { 
								$val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm');
							} 
						}

						$tmp_data[$tmp_iterate][$column_index] =  $val; 
					}
				}
			}
			unlink($upload_data["upload_data"]["full_path"]);
			 
			# upload to database
			$rs = $this->plan_produksi_model->import_data($tmp_data); 
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
		redirect('plan_produksi/'); 
	}

	public function add_data()
	{	  
			$rs = $this->plan_produksi_model->add_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('plan_produksi/add'); 
	}

	public function edit($id)
	{					 
		$this->data['list_plan']	= $this->plan_produksi_model->getListPlan(); 
		$this->data['rs'] 				= $this->plan_produksi_model->getRowData($id);

		$this->data['title'] 		= "Edit Data Plan Produksi"; 
		$this->data['js'] 			= 'plan_produksi/js_edit';
		$this->data['sview'] 		= 'plan_produksi/edit'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function edit_data()
	{	 
			$rs = $this->plan_produksi_model->edit_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('plan_produksi/'); 
	} 
	public function eksport_action() 
	{	 
		$header = "Report Data Plan Produksi\n";
		$header .= "Start\t".$this->input->post("start")."\n";
		$header .= "Stop\t".$this->input->post("end")."\n";

		$data 			= $this->plan_produksi_model->getDataByTgl($this->input->post("start"),$this->input->post("end"));
	   

		$colnames 		= array("PLAN","DATE", "TIME START","TIME END","delay");
		$colfields 		= array("plan_category","date", "time_start","time_end","delay");

		$this->plan_produksi_model->export_to_excel($colnames,$colfields, $data, $header ,"rpt_plan_produksi"); 


	}
}
