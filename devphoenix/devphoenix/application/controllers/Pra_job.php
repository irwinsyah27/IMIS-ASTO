<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pra_job extends CI_Controller {
 	private $folder_name			= "";
 	private $table_name 			= "pra_job";
 	private $primary_key 			= "pra_job_id";
 	private $label_modul			= "";
 	private $model_name				= "pra_job_model";
 	private $label_list_data		= "List Data";
 	private $label_add_data			= "Add Data";
 	private $label_sukses_dihapus 	= "Data berhasil dihapus";
 	private $label_gagal_dihapus 	= "Gagal menghapus data";
 	private $label_sukses_ditambah 	= "Data berhasil ditambah";
 	private $label_gagal_ditambah 	= "Data gagal ditambah";
 	private $label_sukses_diedit 	= "Data berhasil diedit";
 	private $label_gagal_diedit 	= "Data gagal diedit";
 
   	public function __construct() {
      	parent::__construct();
      	
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Pra Job & Fatique Check</li>';
 
		$this->load->model( $this->model_name );

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "pra_job"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->pra_job_model->user_akses("pra_job");
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
		$this->data['title'] 		= "List Data Pra Job & Fatique Check"; 
		$this->data['js'] 			= 'pra_job/js_view';
		$this->data['sview'] 		= 'pra_job/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->pra_job_model->get_list_data();
	}

	public function delete($id){ 
		$rs = $this->pra_job_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('pra_job/');
	}

	public function add()
	{					 
		$this->data['title'] 		= "Tambah Data Pra Job & Fatique Check"; 
		$this->data['js'] 			= 'pra_job/js_add';
		$this->data['sview'] 		= 'pra_job/add'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function add2()
	{					 
		$tgl = date("Y-m-d"); 
		$tgl_kemarin = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d") - 1, date("Y") )); 

		$rs = $this->pra_job_model->apakah_data_sudah_ada($this->input->post("nrp"),$tgl);
		if ($rs["nrp"] <> "") { 
			$msg = 'NRP ini sudah melakukan prajob dan fatique check';
			$stats = '0';
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('pra_job/add');
		}

		$rs = $this->pra_job_model->get_row("master_employee","nrp", $this->input->post("nrp")); 
		if ($rs["nrp"] <> "") { 
			$tgl_kemarin = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d") - 1, date("Y") ));  
			$rs_kemarin = $this->pra_job_model->apakah_data_sudah_ada($this->input->post("nrp"),$tgl_kemarin);

			$this->data['nama'] 					= $rs["nama"]; 
			$this->data['mulai_tidur_kemarin'] 		= $rs_kemarin["mulai_tidur_hari_ini"]; 
			$this->data['bangun_tidur_kemarin'] 	= $rs_kemarin["bangun_tidur_hari_ini"]; 
			$this->data['nama'] 					= $rs["nama"]; 
			$this->data['nrp'] 						= $rs["nrp"]; 
			$this->data['title'] 					= "Tambah Data Pra Job & Fatique Check"; 
			$this->data['js'] 						= 'pra_job/js_add2';
			$this->data['sview'] 					= 'pra_job/add2'; 
			$this->load->view(_TEMPLATE , $this->data);
		} else {
			$msg = 'NRP tidak terdaftar';
			$stats = '0';
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('pra_job/add');
		}

	}


	public function add_data()
	{	
		$rs = $this->pra_job_model->apakah_data_sudah_ada($this->input->post("nrp"),$this->input->post("tanggal_pra_job"));
		if ($rs["nrp"] <>"") {
			$msg 		= "Gagal add data, data untuk NRP ini sudah ada";
			$stats 		= '0';
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
 
			redirect('pra_job/add');
		} else {  
			//$data = $this->{$this->model_name}->array_from_post(array('nrp','shift','tanggal_pra_job','mulai_tidur_kemarin','bangun_tidur_kemarin','mulai_tidur_hari_ini','bangun_tidur_hari_ini','apakah_sedang_minum_obat','apakah_sedang_ada_masalah','apakah_siap_bekerja','apakah_mempunyai_apd_yang_sesuai','apakah_dalam_kondisi_fit','apakah_memerlukan_ijin_khusus','apakah_memahami_prosedur','apakah_mempunyai_peralatan_yang_benar','apakah_ada_aktivitas_lain_disekitar_saya','apakah_mengenali_bahaya','apakah_focus','apakah_atasan_mengetahui','apakah_pekerjaan_bisa_dilanjutkan'));
			
			$tgl_kemarin = $this->mylib->tgl_kemarin($this->input->post("tanggal_pra_job"));

			$_POST["mulai_tidur_hari_ini"]  	= $tgl_kemarin ." ". $this->input->post("mulai_tidur_hari_ini");
			$_POST["bangun_tidur_hari_ini"]  	= $this->input->post("tanggal_pra_job") ." ". $this->input->post("bangun_tidur_hari_ini");
 
 			$_POST["insert_by"]					= $_SESSION["username"];

			$data = $this->{$this->model_name}->array_from_post(array('nrp','shift','tanggal_pra_job','mulai_tidur_kemarin','bangun_tidur_kemarin','mulai_tidur_hari_ini','bangun_tidur_hari_ini','apakah_sedang_minum_obat','apakah_sedang_ada_masalah','apakah_siap_bekerja','insert_by'));
			
			$rs = $this->{$this->model_name}->db_insert($this->table_name , $data);

			$tmp_data = $this->{$this->model_name}->get_data_operator_absensi( $this->input->post("nrp") , $this->input->post("tanggal_pra_job"));
			$_POST["rekomendasi"] 				= $this->mylib->rekomendasi_operator($tmp_data);
			$_POST["prediksi_butuh_pengawasan"] = $this->mylib->prediksi_butuh_pengawasan($tmp_data);
			$_POST["prediksi_stop_bekerja"] 	= $this->mylib->prediksi_stop_bekerja($tmp_data);

			$data1 = $this->{$this->model_name}->array_from_post(array('rekomendasi','prediksi_butuh_pengawasan','prediksi_stop_bekerja'));
			#print_r($data1);exit;
			$rs = $this->{$this->model_name}->db_update($this->table_name , $data1, array("pra_job_id" => $tmp_data["pra_job_id"]));

			# rekomendasi  
			# prediksi_butuh_pengawasan
			# prediksi_stop_bekerja

			 
			if($rs){ 
				$msg 		= $this->label_sukses_ditambah;
				$stats 		= '1';
			} else {
				$msg 		= $this->label_gagal_ditambah;
				$stats 		= '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
 
			redirect('pra_job/add');
		} 
	}

	public function edit($id)
	{					 
		$this->data['rs'] 			= $this->pra_job_model->getRowData($id);

		$this->data['title'] 		= "Edit Data Pra Job & Fatique Check"; 
		$this->data['js'] 			= 'pra_job/js_edit';
		$this->data['sview'] 		= 'pra_job/edit'; 
		$this->load->view(_TEMPLATE , $this->data);
	}


	public function edit_data()
	{	
		#$data = $this->{$this->model_name}->array_from_post(array('tanggal_pra_job','shift','mulai_tidur_kemarin','bangun_tidur_kemarin','mulai_tidur_hari_ini','bangun_tidur_hari_ini','apakah_sedang_minum_obat','apakah_sedang_ada_masalah','apakah_siap_bekerja','apakah_mempunyai_apd_yang_sesuai','apakah_dalam_kondisi_fit','apakah_memerlukan_ijin_khusus','apakah_memahami_prosedur','apakah_mempunyai_peralatan_yang_benar','apakah_ada_aktivitas_lain_disekitar_saya','apakah_mengenali_bahaya','apakah_focus','apakah_atasan_mengetahui','apakah_pekerjaan_bisa_dilanjutkan'));
		$tgl_kemarin = $this->mylib->tgl_kemarin($this->input->post("tanggal_pra_job"));

		$_POST["mulai_tidur_hari_ini"]  	= $tgl_kemarin ." ". $this->input->post("mulai_tidur_hari_ini");
		$_POST["bangun_tidur_hari_ini"]  	= $this->input->post("tanggal_pra_job") ." ". $this->input->post("bangun_tidur_hari_ini");
			
		$data = $this->{$this->model_name}->array_from_post(array('tanggal_pra_job','shift','mulai_tidur_kemarin','bangun_tidur_kemarin','mulai_tidur_hari_ini','bangun_tidur_hari_ini','apakah_sedang_minum_obat','apakah_sedang_ada_masalah','apakah_siap_bekerja'));
			
		$rs = $this->{$this->model_name}->db_update($this->table_name , $data,array("pra_job_id" => $this->input->post("old_id")));
		 
		if($rs){ 
			$msg 		= $this->label_sukses_diedit;
			$stats 		= '1';
		} else {
			$msg 		= $this->label_gagal_diedit;
			$stats 		= '0';
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
 
		redirect('pra_job/');
	}


	public function eksport_action()
	{	 
		$header = "Report Data Pra Job & Fatique\n";
		$header .= "Start\t".$this->input->post("start")."\n";
		$header .= "Stop\t".$this->input->post("end")."\n";

		$data 			= $this->{$this->model_name}->eksportDataPerTgl($this->input->post("start"),$this->input->post("end"));
	    
	    /*
		$colnames 		= array("TGL","SHIFT", "NRP","NAMA","TGL MULAI TIDUR KEMARIN","JAM MULAI TIDUR KEMARIN","TGL BANGUN TIDUR TIDUR KEMARIN","JAM BANGUN TIDUR KEMARIN","TGL MULAI TIDUR HARI INI","JAM MULAI TIDUR HARI INI","TGL BANGUN TIDUR HARI INI","JAM BANGUN TIDUR HARI INI","LAMA TIDUR KEMARIN","LAMA TIDUR HARI INI","PERT 1","PERT 2","PERT 3","PERT 4","PERT 5","PERT 6","PERT 7","PERT 8","PERT 9","PERT 10","PERT 11","PERT 12","PERT 13","STATUS PERSETUJUAN");
		$colfields 		= array("tanggal_pra_job","shift", "nrp","nama","tgl_mulai_tidur_kemarin","jam_mulai_tidur_kemarin","tgl_bangun_tidur_kemarin","jam_bangun_tidur_kemarin","tgl_mulai_tidur_skr","jam_mulai_tidur_skr","tgl_bangun_tidur_skr","jam_bangun_tidur_skr","lama_tdr_kemarin","lama_tdr_sekarang","apakah_sedang_minum_obat","apakah_sedang_ada_masalah","apakah_siap_bekerja","apakah_mempunyai_apd_yang_sesuai","apakah_dalam_kondisi_fit","apakah_memerlukan_ijin_khusus","apakah_memahami_prosedur","apakah_mempunyai_peralatan_yang_benar","apakah_ada_aktivitas_lain_disekitar_saya","apakah_mengenali_bahaya","apakah_focus","apakah_atasan_mengetahui","apakah_pekerjaan_bisa_dilanjutkan","label_status_persetujuan");
		*/
		$colnames 		= array("TGL","SHIFT", "NRP","NAMA","TGL MULAI TIDUR KEMARIN","JAM MULAI TIDUR KEMARIN","TGL BANGUN TIDUR TIDUR KEMARIN","JAM BANGUN TIDUR KEMARIN","TGL MULAI TIDUR HARI INI","JAM MULAI TIDUR HARI INI","TGL BANGUN TIDUR HARI INI","JAM BANGUN TIDUR HARI INI","LAMA TIDUR KEMARIN","LAMA TIDUR HARI INI","PERT 1","PERT 2","PERT 3","STATUS PERSETUJUAN","REKOMENDASI","PREDIKSI BUTUH PENGAWASAN", "PREDIKSI STOP BEKERJA");
		$colfields 		= array("tanggal_pra_job","shift", "nrp","nama","tgl_mulai_tidur_kemarin","jam_mulai_tidur_kemarin","tgl_bangun_tidur_kemarin","jam_bangun_tidur_kemarin","tgl_mulai_tidur_skr","jam_mulai_tidur_skr","tgl_bangun_tidur_skr","jam_bangun_tidur_skr","lama_tdr_kemarin","lama_tdr_sekarang","apakah_sedang_minum_obat","apakah_sedang_ada_masalah","apakah_siap_bekerja","label_status_persetujuan","label_status_rekomendasi","prediksi_pengawasan","prediksi_stop_bekerja");

		$this->{$this->model_name}->export_to_excel($colnames,$colfields, $data, $header ,"rpt_data_prajob_dan_fatique"); 
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
							if ($column_index == 'B') { $val = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($val));  }
							if ($column_index == 'F') {  $val = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($val));  }
							if ($column_index == 'H') {  $val = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($val));  }
							if ($column_index == 'J') {  $val = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($val));  }
							if ($column_index == 'L') {  $val = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($val));  }

							if ($column_index == 'G') {  $val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm'); } 
							if ($column_index == 'I') {  $val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm'); }  
							if ($column_index == 'K') {  $val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm'); } 
							if ($column_index == 'M') {  $val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm'); }  
						}

						$tmp_data[$tmp_iterate][$column_index] =  $val; 
					}
				}
			}
			unlink($upload_data["upload_data"]["full_path"]);
			 
			# upload to database
			$rs = $this->pra_job_model->import_data($tmp_data); 
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
		redirect('pra_job/'); 
	}
}
