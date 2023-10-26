<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_owner extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Master owner</li>';

		$menu["parent_menu"] 		= "master_data";
		$menu["sub_menu"] 			= "master_owner"; 
		$this->data['check_menu']	= $menu;

		$this->load->model('master_owner_model');  

		# akses level
		$akses 			= $this->master_owner_model->user_akses("master_owner");
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
		$this->data['title'] 		= "List Data Master owner"; 
		$this->data['js'] 			= 'master_owner/js_view';
		$this->data['sview'] 		= 'master_owner/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->master_owner_model->get_list_data();
	}

	public function delete($id){ 
		$rs = $this->master_owner_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('master_owner/');
	}

	public function add()
	{					 
		$this->data['title'] 		= "Tambah Data Master owner"; 
		$this->data['js'] 			= 'master_owner/js_add';
		$this->data['sview'] 		= 'master_owner/add'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function add_data()
	{	 
			$rs = $this->master_owner_model->add_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('master_owner/'); 
	}

	public function edit($id)
	{					 
		$this->data['rs'] 			= $this->master_owner_model->getRowData($id);

		$this->data['title'] 		= "Edit Data Master owner"; 
		$this->data['js'] 			= 'master_owner/js_edit';
		$this->data['sview'] 		= 'master_owner/edit'; 
		$this->load->view(_TEMPLATE , $this->data);
	}


	public function edit_data()
	{	 
			$rs = $this->master_owner_model->edit_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('master_owner/'); 
	}
	public function eksport_action() 
	{	 
		$header = "Report Data Master owner\n"; 

		$data 			= $this->master_owner_model->getAlldata();
	   

		$colnames 		= array("KODE","NAMA PERUSAHAAN","STATUS");
		$colfields 		= array("kode","keterangan",'status');

		$this->master_owner_model->export_to_excel($colnames,$colfields, $data, $header ,"rpt_master_owner"); 


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
							/*
							$format = "";
							if ($column_index == 'B') {
								$format = "Y-m-d"; 
								$val = date($format, PHPExcel_Shared_Date::ExcelToPHP($val)); 
							} 
							if ($column_index == 'D') { 
								$val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm');
							} 
							if ($column_index == 'E') { 
								$val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm');
							} 
							*/
						}  
						$tmp_data[$tmp_iterate][$column_index] =  $val; 
					}
				}
			}
			unlink($upload_data["upload_data"]["full_path"]);
			 
			# upload to database
			$rs = $this->master_owner_model->import_data($tmp_data); 
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
		redirect('master_owner/'); 
	}
 
}
