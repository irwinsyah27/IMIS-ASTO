<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pitstop extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Pitstop</li>';

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "pitstop"; 
		$this->data['check_menu']	= $menu;

		$this->load->model('pitstop_model');
		$this->load->model('master_equipment_model');
		$this->load->model('sync_station_model');
		$this->load->model('setting_dailycheck_model');

		# akses level
		$akses 			= $this->pitstop_model->user_akses("pitstop");
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
		$this->data['title'] 		= "List Data Pitstop"; 
		$this->data['js'] 			= 'pitstop/js_view';
		$this->data['sview'] 		= 'pitstop/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}
	public function view_antrian()
	{					
		$this->data['rs'] 		= $this->pitstop_model->get_list_data_antrian();
		$this->data['title'] 		= "List Data Antrian Pitstop"; 
		$this->data['js'] 			= 'pitstop/js_view_antrian';
		$this->data['sview'] 		= 'pitstop/view_antrian'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->pitstop_model->get_list_data();
	} 

	public function get_data_antrian_reload()
	{					   
		$rs 			= $this->pitstop_model->get_list_data_antrian(); 
 
		$list_data 		= '';

												$no = 0;
												if (count($rs)>0) {
													FOREACH ($rs AS $l) { 
														$no += 1;
												$list_data .= ' 
												<tr> 
													<td>'.$no.'</td> 
													<td>'.$l["lokasi"].'</td>
													<td>'.$l["new_eq_num"].'</td> 
													<td>'.$l["shift"].'</td>
													<td>'.$l["description"].'</td>
													<td>'.$l["hm"].'</td>
													<td>'.$l["date_time_in"].'</td>
													<td>'.$l["durasi"].'</td> 
													<td><a href="'. _URL.'pitstop/edit/'.$l["pitstop_id"].'/1">close</a></td>'; 
													 
												$list_data .= '	 
												</tr> 
												'; 	
													}
												} 
												$list_data .= ' 
											';
		echo $list_data;
	} 

	public function delete($id){ 
		$rs = $this->pitstop_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('pitstop/');
	}

	public function add()
	{					
		$this->data['list_unit'] 	= $this->master_equipment_model->getAllData();
		#$this->data['list_station'] = $this->sync_station_model->getAllDataStation();
		$this->data['list_station'] 	= $this->pitstop_model->get_list_ws(); 

		$this->data['title'] 		= "Add Data Pitstop"; 
		$this->data['js'] 			= 'pitstop/js_add';
		$this->data['sview'] 		= 'pitstop/add'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function add_data()
	{	 
			$rs = $this->pitstop_model->add_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Data gagal disimpan';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('pitstop/add'); 
	}

	public function edit($id, $type="0")
	{					
		$this->data['list_unit'] 	= $this->master_equipment_model->getAllData();
		#$this->data['list_station'] = $this->sync_station_model->getAllDataStation();
		$this->data['list_station'] 	= $this->pitstop_model->get_list_ws(); 

		$this->data['rs'] 			= $this->pitstop_model->getRowData($id);

		$this->data['next'] 		= $type; 
		$this->data['title'] 		= "Update Data Pitstop"; 
		$this->data['js'] 			= 'pitstop/js_update';
		$this->data['sview'] 		= 'pitstop/update'; 
		$this->load->view(_TEMPLATE , $this->data);
	}
	public function update_data()
	{	   
			$rs = $this->pitstop_model->update_data();
			if($rs){
				if ($this->input->post('SubmitData') == "breakdown") {
					$rs = $this->pitstop_model->add_data_to_breakdown();
				}
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Data gagal disimpan';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);

			if ($this->input->post('next') == 1) {
				redirect('pitstop/view_antrian'); 
			} else {
				redirect('pitstop/'); 
			} 
	} 

	public function import()
	{	
		$this->data['title'] 		= "Import Data Pitstop"; 
		$this->data['js'] 			= 'pitstop/js_import';
		$this->data['sview'] 		= 'pitstop/import'; 
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
							if ($column_index == 'E') {
								$format = "Y-m-d"; 
								$val = date($format, PHPExcel_Shared_Date::ExcelToPHP($val)); 
							}
							if ($column_index == 'G') {
								$format = "Y-m-d"; 
								$val = date($format, PHPExcel_Shared_Date::ExcelToPHP($val)); 
							}
							if ($column_index == 'F') { 
								$val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm');
							} 
							if ($column_index == 'H') { 
								$val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm');
							}  
						}

						$tmp_data[$tmp_iterate][$column_index] =  $val; 
					}
				}
			}
			unlink($upload_data["upload_data"]["full_path"]);
			 
			# upload to database
			$rs = $this->pitstop_model->import_data($tmp_data); 
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
		redirect('pitstop/'); 
	}

	public function eksport_action()
	{	 
		$header = "Report Data Pitstop\n";
		$header .= "Start\t".$this->input->post("start")."\n";
		$header .= "Stop\t".$this->input->post("end")."\n";

		$data 			= $this->pitstop_model->getDataPistop($this->input->post("start"),$this->input->post("end"));
	    

		$colnames 		= array("LOKASI PITSTOP","UNIT", "SHIFT","DATE IN","TIME IN","DATE OUT","TIME OUT","DESC","HM");
		$colfields 		= array("station_name","new_eq_num", "shift","date_in","time_in","date_out","time_out","description","hm");

		$this->pitstop_model->export_to_excel($colnames,$colfields, $data, $header ,"rpt_data_pitstop"); 


	} 
}
