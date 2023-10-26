<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator extends CI_Controller {

   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Employee</li>';

		$menu["parent_menu"] 		= "master_data";
		$menu["sub_menu"] 			= "operator";
		$this->data['check_menu']	= $menu;

		$this->load->model('operator_model');

		# akses level
		$akses 			= $this->operator_model->user_akses("operator");
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
		$this->data['title'] 		= "List Data Employee";
		$this->data['js'] 			= 'operator/js_view';
		$this->data['sview'] 		= 'operator/view';
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->operator_model->get_list_data();
	}

	public function delete($id){
		$rs = $this->operator_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('operator/');
	}

	public function add()
	{
		$this->data['list_posisi'] 	= $this->mylib->generate2darray("master_posisi" , "master_posisi_id","keterangan");
		$this->data['list_departemen'] 	= $this->mylib->generate2darray("master_departemen" , "master_departemen_id","keterangan");
		$this->data['list_perusahaan'] 	= $this->mylib->generate2darray("master_owner" , "master_owner_id","keterangan");

		$this->data['title'] 		= "Tambah Data Employee";
		$this->data['js'] 			= 'operator/js_add';
		$this->data['sview'] 		= 'operator/add';
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function add_data()
	{
			$rs = $this->operator_model->add_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('operator/');
	}

	public function edit($id)
	{
		$this->data['list_posisi'] 		= $this->mylib->generate2darray("master_posisi" , "master_posisi_id","keterangan");
		$this->data['list_departemen'] 	= $this->mylib->generate2darray("master_departemen" , "master_departemen_id","keterangan");
		$this->data['list_perusahaan'] 	= $this->mylib->generate2darray("master_owner" , "master_owner_id","keterangan");
		$this->data['rs'] 			= $this->operator_model->getRowData($id);

		$this->data['title'] 		= "Edit Data Employee";
		$this->data['js'] 			= 'operator/js_edit';
		$this->data['sview'] 		= 'operator/edit';
		$this->load->view(_TEMPLATE , $this->data);
	}


	public function edit_data()
	{
			$rs = $this->operator_model->edit_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('operator/');
	}
	public function eksport_action()
	{
		$header = "Report Data Employee\n";

		$data 			= $this->operator_model->getAlldata();


		$colnames 		= array("NRP","NAMA","POSISI","DEPARTEMEN","PERUSAHAAN","STATUS");
		$colfields 		= array("nrp","nama","posisi","departemen",'perusahaan',"status");

		$this->operator_model->export_to_excel($colnames,$colfields, $data, $header ,"rpt_employee");


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
						/*
						if(PHPExcel_Shared_Date::isDateTime($cell)) {
							$format = "";
							if ($column_index == 'B') {
								$format = "Y-m-d";
								$val = date($format, PHPExcel_Shared_Date::ExcelToPHP($val));
							}
						}
						*/
						$tmp_data[$tmp_iterate][$column_index] =  $val;
					}
				}
			}
			unlink($upload_data["upload_data"]["full_path"]);

			# upload to database
			$rs = $this->operator_model->import_data($tmp_data);
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
		redirect('operator/');
	}

	public function generateQrCode($id = null)
	{
		include('/usr/share/phpqrcode/qrlib.php');
		$this->load->library('zip');
		$targetDir = APPPATH.'../assets/images/qrcode/';

		if ($id == null) {
			$employees = $this->db
				->select('master_employee.*, master_posisi.keterangan AS jabatan')
				->join('master_posisi', 'master_posisi.master_posisi_id=master_employee.master_posisi_id', 'LEFT')
				->order_by('nama', 'ASC')
				->get('master_employee')->result();


			foreach ($employees as $e) {
				$filePath = $targetDir.$e->nrp.'-'.$e->nama.'-'.$e->jabatan.'.png';
				QRcode::png($e->nrp, $filePath);
			}

			$this->zip->read_dir($targetDir, false);
			$this->zip->download('operator_qrcode.zip');
		}

		else {
			$employee = $this->db
				->where('operator_id', $id)
				->get('master_employee')->row();

			if ($employee) {
				// $filePath = $targetDir.$employee->nrp.'-'.$employee->nama.'.png';
				// QRcode::png($e->nrp, $filePath);
				QRcode::png($e->nrp);
				// $this->zip->read_file($filePath);
				// $this->zip->download($employee->nrp.'-'.$employee->nama.'.zip');
			}

			else {
				echo "Data tidak ditemukan";
				exit();
			}
		}

	}

}
