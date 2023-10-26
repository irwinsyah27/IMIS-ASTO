<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpt_absensi extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Report Data Absensi</li>';

		//$this->load->model('proto_model');
		$this->load->model("report_entry_data_model");  

		$menu["parent_menu"] 		= "report";
		$menu["sub_menu"] 			= "rpt_absensi"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->report_entry_data_model->user_akses("rpt_absensi");
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',''); 
   	}

	public function index()
	{ 
			$this->view();
	}

	public function view()
	{					 
		$this->data['title'] 		= "Report Data Absensi"; 
		$this->data['js'] 			= 'rpt_absensi/js_view';
		$this->data['sview'] 		= 'rpt_absensi/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}   

	public function export_to_excel()
	{ 
		$data 			= $this->report_entry_data_model->getReportAbsensi($this->input->post("start"),$this->input->post("end"));
	   
		$header = "Report Absensi Periode ".$this->input->post("start")." s/d ".$this->input->post("end");

		$colnames 			= array("NIP","NAMA", "UNIT","BPM","SPO","CYCLE TIME", "OVERSPEED","TGL","IN ABSEN","OUT ABSEN","IN MANCAL","OUT MANCAL");
		$colfields 			= array("nip","nama", "unit","bpm_in","spo_in","total_cycle_time","total_over_speed","date","time_in","time_out","time_in_mancal","time_out_mancal");
  
		$this->report_entry_data_model->export_to_excel($colnames,$colfields, $data, $header,"rpt_absensi"); 
	} 
}
