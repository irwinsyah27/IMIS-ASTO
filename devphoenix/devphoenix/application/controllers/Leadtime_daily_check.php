<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leadtime_daily_check extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Leadtime Daily Check</li>';

		//$this->load->model('proto_model');
		$this->load->model("leadtime_daily_check_model");  
		$this->load->model('setting_dailycheck_model');

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "leadtime_daily_check"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->leadtime_daily_check_model->user_akses("leadtime_daily_check");
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
		$this->data['rs'] 			= $this->leadtime_daily_check_model->getAllData();
		$this->data['title'] 		= "Leadtime Daily Check"; 
		$this->data['js'] 			= 'leadtime_daily_check/js_view';
		$this->data['sview'] 		= 'leadtime_daily_check/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
	public function get_data()
	{					 
		$rs 		= $this->leadtime_daily_check_model->getAllData(); 
		echo json_encode($rs, JSON_NUMERIC_CHECK); 
	} 
	/*
	public function get_data_selesai_daily_check()
	{					 
		$rs 		= $this->leadtime_daily_check_model->get_data_selesai_daily_check();  
		return $rs;
	} 
	*/
	public function today_plan_daily_check()
	{					 
		$data = "";

		$rs = $this->leadtime_daily_check_model->get_data_selesai_daily_check();
		if (count($rs) > 0) {
			FOREACH ($rs AS $r) {
				$tmp 				= $r["new_eq_num"];
				$unit_selesai[$tmp] = $r["durasi_in_out"];
				$time_out[$tmp] 	= $r["time_out"];
			}
		} 
		#print_r($rs);exit;
		$today = $this->setting_dailycheck_model->getListDataBerdasarkanHari(date("N"));
		if (isset($today) && count($today) >0) {  
			FOREACH ($today AS $s) {
				if (isset($unit_selesai[$s["unit"]])) {
					$unitselesai = $unit_selesai[$s["unit"]]; 
					$timeout = $time_out[$s["unit"]]; 
					$bgcolor = "bgcolor=\"#6cf24d\"";
				} else { 
					$unitselesai = "";
					$timeout = "";
					$bgcolor = "";
				}
				$data .= "<tr><td ".$bgcolor .">".$s["unit"]."</td>";
				$data .= "<td ".$bgcolor .">".$unitselesai."</td>";
				$data .= "<td ".$bgcolor .">".$timeout."</td></tr>";
			}
		}  

		echo $data;
	} 

	public function get_summary_daily_check()
	{					 
		$rs = $this->leadtime_daily_check_model->get_total_selesai_daily_check();
		$total_selesai = $rs["total"];

		$rs = $this->leadtime_daily_check_model->getTotalPlanDailycheckBerdasarkanHari(date("N"));
		$total_plan 	= $rs["total"];

		#$rs = $this->leadtime_daily_check_model->getTotalPlanDailycheckMenjadibreakdown(date("N"));
		#$total_breakdown	= $rs["total"];

		$data[0]["plan"]	= $total_plan;
		$data[0]["actual"]		= $total_selesai; 

		echo json_encode($data, JSON_NUMERIC_CHECK); 
	} 
}
