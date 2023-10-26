<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Changeshift_monitor extends CI_Controller {

   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">changeshift_monitor</li>';

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "changeshift_monitor";
		$this->data['check_menu']	= $menu;

		$this->load->model('Changeshift_monitor_model');
		$this->load->model('master_employee_model');
		$this->load->model('master_equipment_model'); 

		$this->data["list_shift"]  = ["1" => "1", "2"=>"2"];

		# akses level
		$akses 			= $this->Changeshift_monitor_model->user_akses("changeshift_monitor");


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
		$this->data['list_lokasi'] 	= $this->Changeshift_monitor_model->list_lokasi();
		$this->data['title'] 		= "List Data Changeshift";
		$this->data['js'] 			= 'changeshift_monitor/js_view';
		$this->data['sview'] 		= 'changeshift_monitor/view';
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->Changeshift_monitor_model->get_list_data();
	}

	public function delete($id){
		$rs = $this->Changeshift_monitor_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('changeshift_monitor/');
	}

	public function add()
	{
		$this->data['list_unit'] 		= $this->master_equipment_model->getAllData(); 
		$this->data['list_operator']		= $this->mylib->generate2darray("master_employee" , "nrp","nrp","","nama");

		$this->data['title'] 		= "Tambah Data Changeshift";
		$this->data['js'] 			= 'changeshift_monitor/js_add';
		$this->data['sview'] 		= 'changeshift_monitor/add';
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function add_data()
	{
		$rs = $this->Changeshift_monitor_model->apakah_data_sudah_ada($this->input->post("nrp"),$this->input->post("tgl"));
		if ($rs["nrp"] <>"") {
			$msg 		= "Gagal add data, NRP ini sudah melakukan changeshift";
			$stats 		= '0';
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);

			redirect('changeshift_monitor/add');
		} else {
			$rs = $this->Changeshift_monitor_model->add_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('changeshift_monitor/');
		}
	}

	public function edit($id)
	{
		$this->data['list_unit'] 	= $this->master_equipment_model->getAllData(); 
		$this->data['list_operator']	= $this->mylib->generate2darray("master_employee" , "nrp","nrp","","nama");

		$this->data['rs'] 			= $this->Changeshift_monitor_model->getRowData($id);

		$this->data['title'] 		= "Edit Data Changeshift";
		$this->data['js'] 			= 'changeshift/js_edit';
		$this->data['sview'] 		= 'changeshift/edit';
		$this->load->view(_TEMPLATE , $this->data);
	}


	public function edit_data()
	{
			$rs = $this->Changeshift_monitor_model->edit_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('changeshift_monitor/');
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
			$rs = $this->Changeshift_monitor_model->import_data($tmp_data);
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
		redirect('changeshift_monitor/');
	}
	public function eksport_action()
	{
		$header = "Report Data Changeshift In\n";
		$header .= "Start\t".$this->input->post("start")."\n";
		$header .= "Stop\t".$this->input->post("end")."\n";

		$data 			= $this->Changeshift_monitor_model->getDataPerDate($this->input->post("start"),$this->input->post("end"));


		$colnames 		= array("TGL","NRP","UNIT", "NAMA","SHIFT","LOKASI","TGL MASUK","JAM MASUK","TGL KELUAR","JAM KELUAR");
		$colfields 		= array("date","nrp","equip_num", "nama","shift","lokasi","date_in","time_in","date_out","time_out");

		$this->Changeshift_monitor_model->export_to_excel($colnames,$colfields, $data, $header ,"rpt_changeshift");


	}

	public function eksport_action_untuk_absensi()
	{
		$start 		= $this->input->post('start_ua');
		$stop  		= $this->input->post('end_ua');
		$terminal  	= $this->input->post('master_lokasi_id');

		$kode = [
			"A"	=> "01",
			"B"	=> "02",
			"C"	=> "03",
			"D"	=> "04",
			"E"	=> "05",
			"F"	=> "06",
			"G"	=> "07",
			"H"	=> "08",
			"I"	=> "09",
			"J"	=> "10",
			"K"	=> "11",
			"L"	=> "12",
			"M"	=> "13",
			"N"	=> "14",
			"O"	=> "15",
			"P"	=> "16",
			"Q"	=> "17",
			"R"	=> "18",
			"S"	=> "19",
			"T"	=> "20",
			"U"	=> "21",
			"V"	=> "22",
			"W"	=> "23",
			"X"	=> "24",
			"Y"	=> "25",
			"Z"	=> "26"
		];

		$lokasiParam = $lokasi == '' ? '1=1' : 'lokasi = '.$lokasi;

		$data = $this->db
			->where('`date` BETWEEN "'.$start.'" AND "'.$stop.'"')
			->where($terminalParam)
			->get('changeshift')->result();

		$changeshift = '';

		foreach ($data as $d)
		{

			if ($d->nrp == '') {
				continue;
			}

			// IN
			if ($d->time_in != NULL)
			{
				if (array_key_exists(strtoupper(substr($d->nrp, 0, 1)), $kode)
				&& array_key_exists(strtoupper(substr($d->nrp, 0, 1)), $kode)) {
					$changeshift .= $kode[substr($d->nrp, 0, 1)];
					$changeshift .= $kode[substr($d->nrp, 1, 1)];
					$changeshift .= trim(substr($d->nrp, 2));
				}

				else {
					$changeshift .= $d->nrp;
				}

				$changeshift .= date('Ymd', strtotime($d->time_in));
				$changeshift .= date('Hi', strtotime($d->time_in));
				$changeshift .= '1';
				$changeshift .= str_pad($d->master_lokasi_id, 2, '0', STR_PAD_LEFT)."\r\n";
			}

			// OUT
			if ($d->time_out != NULL)
			{
				if (array_key_exists(strtoupper(substr($d->nrp, 0, 1)), $kode)
				&& array_key_exists(strtoupper(substr($d->nrp, 0, 1)), $kode)) {
					$changeshift .= $kode[substr($d->nrp, 0, 1)];
					$changeshift .= $kode[substr($d->nrp, 1, 1)];
					$changeshift .= trim(substr($d->nrp, 2));
				}

				else {
					$changeshift .= $d->nrp;
				}

				$changeshift .= date('Ymd', strtotime($d->time_out));
				$changeshift .= date('Hi', strtotime($d->time_out));
				$changeshift .= '2';
				$changeshift .= str_pad($d->master_lokasi_id, 2, '0', STR_PAD_LEFT)."\r\n";
			}
		}

		$this->load->helper('download');
		force_download('changeshift-'.$start.'-to-'.$stop.'.txt', $changeshift);
	}

	public function testsubstr($string)
	{
		echo substr($string, 0,1); // string, start, length
	}
}
