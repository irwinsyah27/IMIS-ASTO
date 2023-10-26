<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpt_timbangan_cpp extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Report Data Timbangan CPP</li>';

		//$this->load->model('proto_model');
		$this->load->model("report_entry_data_model");  

		$menu["parent_menu"] 		= "report";
		$menu["sub_menu"] 			= "rpt_timbangan_cpp"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->report_entry_data_model->user_akses("rpt_timbangan_cpp");
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
		$this->data['title'] 		= "Report Data Timbangan CPP"; 
		$this->data['js'] 			= 'rpt_timbangan_cpp/js_view';
		$this->data['sview'] 		= 'rpt_timbangan_cpp/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}   

	public function export_to_excel()
	{ 
		$header = "Report Data Timbangan CPP\n";
		$header .= "Start\t".$this->input->post("start")."\n";
		$header .= "Stop\t".$this->input->post("end")."\n";

		$data 			= $this->report_entry_data_model->getTimbanganCpp($this->input->post("start"),$this->input->post("end")); 

		$colnames 		= array("SHIFT","TANGGAL", "NO TRUK","OWNER","NETTO","TIME","RITASI","TYPE");
		$colfields 		= array("shift","date_weigher", "new_eq_num","owner","netto","time_weigher","ritase","egi");

		$this->report_entry_data_model->export_to_excel($colnames,$colfields, $data, $header ,"rpt_timbangan_cpp"); 
 
	} 
}
