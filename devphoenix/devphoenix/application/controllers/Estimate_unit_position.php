<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estimate_unit_position extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Estimate Unit Position</li>';

		//$this->load->model('proto_model');
		$this->load->model("estimate_unit_position_model");  
		$this->load->model("sync_station_model");  

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "estimate_unit_position"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->estimate_unit_position_model->user_akses("estimate_unit_position");
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
		$this->data['rs'] 			= $this->estimate_unit_position_model->getAllDataPerDay(); 
		$this->data['list_station'] = $this->sync_station_model->getAllDataStation();
		$this->data['title'] 		= "Estimate Unit Position :: ". date("d M Y"); 
		$this->data['js'] 			= 'estimate_unit_position/js_view';
		$this->data['sview'] 		= 'estimate_unit_position/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
	public function get_data()
	{					 
		$rs 			= $this->estimate_unit_position_model->getAllDataPerDay(); 
		$list_station 	= $this->sync_station_model->getAllDataStation();

		$tmp 				= $this->sync_station_model->getStationCPP();
		$latitude_cpp 		= $tmp["latitude"];
		$longitude_cpp 		= $tmp["longitude"];
		$tmp 				= $this->sync_station_model->getStationPort();
		$latitude_port 		= $tmp["latitude"];
		$longitude_port 	= $tmp["longitude"];

		$list_data 		= '';

												$no = 0;
												if (count($rs)>0) {
													FOREACH ($rs AS $l) {
														$latitude 	= $l["latitude"];
														$longitude 	= $l["longitude"];
														$speed 		= $l["speed"];


														$status_cycle_time 	= $this->estimate_unit_position_model->getStatusCycleTime($l["date"],$l["unit"]);
														$time_stasiun_cpp  	= $status_cycle_time["time_stasiun_cpp"];
														$time_stasiun_port 	= $status_cycle_time["time_stasiun_port "];

														$no += 1;  
												$list_data .= ' 
												<tr>
													<td>'.$no.'</td>   
													<td>'.$l["unit"].'</td> 
													<td>'.$l["speed"].'</td>';

													if (count($list_station) > 0) {
														FOREACH ($list_station AS $ls) { 
															$latitude_station 	= $ls["latitude"];
															$longitude_station 	= $ls["longitude"];

															$distance = "";
															$time_req 	  = "";
															if ($latitude_station <> "" && $longitude_station <> "" && $latitude <> "" && $longitude <> "") {
																$distance = $this->geolocation->distance($latitude,$longitude,$latitude_station,$longitude_station,'K');
																$time_req     = $distance / $speed ;
															}
															
															/*
															if ($time_stasiun_cpp == null && $time_stasiun_port == null) {

															}else if ($time_stasiun_cpp != null && $time_stasiun_port == null) {

															}else if ($time_stasiun_cpp == null && $time_stasiun_port != null) {

															}
															*/

															$list_data .= '<th>'.number_format($distance,2).'</th>';
															$list_data .= '<th>'.number_format($time_req,2).'</th>';
														}
													}
													 
												$list_data .= '	 
												</tr> 
												'; 	
													}
												} 
												$list_data .= ' 
											';
		echo $list_data;
	} 
}
