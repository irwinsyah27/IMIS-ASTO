<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_data extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Log Data</li>';
 
		$this->load->model("log_data_model");  
		$this->load->model("operator_model");  

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "Log Data"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->log_data_model->user_akses("Log Data");
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
		$this->data['list_nrp'] 	= $this->operator_model->getAllData(); 
		$this->data['title'] 		= "Log Data"; 
		$this->data['js'] 			= 'log_data/js_view';
		$this->data['sview'] 		= 'log_data/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
 
	public function get_log_overspeed()
	{					    
		$nrp 			= $this->uri->segment(3,0); ; 
		$date_start 	= $this->uri->segment(4,0); ; 
		$date_end 		= $this->uri->segment(5,0); ; 

		$data 		= $this->log_data_model->get_log_overspeed($nrp, $date_start, $date_end);   


		$colnames 		= array("NIP","NAMA","UNIT","DATE TIME","SPEED","LATITUDE", "LONGITUDE");
		$colfields 		= array("nip","nama",'unit',"time_stamp","speed","latitude","longitude");

		$this->log_data_model->export_to_excel($colnames,$colfields, $data, $header ,"log_data_over_speed_".$nrp);   
	} 
 
	public function get_log_cycle_time()
	{					    
		$nrp 			= $this->uri->segment(3,0); ; 
		$date_start 	= $this->uri->segment(4,0); ; 
		$date_end 		= $this->uri->segment(5,0); ; 

		$data 		= $this->log_data_model->get_log_cycle_time($nrp, $date_start, $date_end);   


		$colnames 		= array("NIP","NAMA","UNIT","DATE START","DATE END","DURASI","FPI");
		$colfields 		= array("nip","nama",'unit',"datetime_start","datetime_end","durasi","fpi");

		$this->log_data_model->export_to_excel($colnames,$colfields, $data, $header ,"log_data_cycletime_".$nrp);   
	} 
}
