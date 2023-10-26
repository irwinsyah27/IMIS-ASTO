<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chartfuel extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Fuel</li>';
 
		$this->load->model("chartfuel_model");  

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "chartfuel"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->chartfuel_model->user_akses("chartfuel");
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
		$this->data['title'] 		= "Fuel"; 
		$this->data['js'] 			= 'chartfuel/js_view';
		$this->data['sview'] 		= 'chartfuel/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
 
	public function get_data_fuel_per_hari()
	{				
		$tgl = $_GET['id'];
 
		$rs_port				= $this->chartfuel_model->gettonperjamperkpp($tgl); 
		#echo "<pre>"; print_r($rs_port);echo "</pre>"; 
		$rs_fuela				= $this->chartfuel_model->getfuelperjamkppcurrdate($tgl); 
		#echo "<pre>"; print_r($rs_fuela);echo "</pre>";

		#$tmp  					= explode("-", $tgl);
		#$next_date				= date("Y-m-d",mktime(0, 0, 0, $tmp[1], $tmp[2] + 1, $tmp[0] ));
		$rs_fuel_1a				= $this->chartfuel_model->getfuelperjamkppnextdate($tgl);   
		#echo "<pre>"; print_r($rs_fuel_1a);echo "</pre>";


		$rs_durasi				= $this->chartfuel_model->getdurasiperjam($tgl); 

		$trip_per_hour          = "";
		$ton_per_hour           = "";
		$ton_per_hour_cpp       = "";
		$ton_per_hour_port      = "";
		$production_per_shift   = "";

		
		$rows1['name'] 		= 'Fuel Ratio';
		$rows1['type'] 		= 'column';
		$rows1['color'] 		= '#FFA07A'; 

		$rows['name'] 		= 'Duration'; 
		$rows['type'] 		= 'line'; 
		$rows['yAxis'] 	= '1';  

		$arr_jam = array ("05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","00","01","02","03","04");


		# durasi
		if (count($rs_durasi) > 0) {
		    FOREACH ($rs_durasi AS $r) {
		        $jam               	= $r["jam"];
		        $data_durasi[$jam]  = $r["durasi"] ;
		    }
		} 
		FOR ($i = 0; $i < count($arr_jam);$i++) {
		    $jam = $arr_jam[$i];
		    if (empty($data_durasi[$jam])) $data_durasi[$jam] = 0;  
		    $rows['data'][] 	= number_format($data_durasi[$jam],2); 
		} 


		// Tonase
		if (count($rs_port) > 0) {
		    FOREACH ($rs_port AS $r) {
		        $jam                = $r["jam"];
		        $data_port[$jam]    = $r["berat"] / 1000;
		    }
		}

		if (count($rs_fuela) > 0) {
		    FOREACH ($rs_fuela AS $r) {
		        $jam                = $r["jam"];
		        $rs_fuel[$jam]    = $r["berat"];
		    }
		}
		if (count($rs_fuel_1a) > 0) {
		    FOREACH ($rs_fuel_1a AS $r) {
		        $jam                = $r["jam"];
		        $rs_fuel_1[$jam]    = $r["berat"];
		    }
		}
		

		FOR ($i = 0; $i < count($arr_jam);$i++) {
		    $jam = $arr_jam[$i];
		    if (empty($data_port[$jam])) $data_port[$jam] = 0;  
		}    

		#print_r($data_port);exit;

		// Fuel 
		for ($i = 0; $i < 19 ;$i++) {
		    $jam = $arr_jam[$i];
		    if (empty($rs_fuel[$jam])) {
		    	$data_fuel[$jam] = 0; 
		    } else {
		    	$data_fuel[$jam] = $rs_fuel[$jam];
		    }
		}    

		for ($i = 19; $i < count($arr_jam);$i++) {
		    $jam = $arr_jam[$i];
		    if (empty($rs_fuel_1[$jam])) {
		    	$data_fuel[$jam] = 0; 
		    } else {
		    	$data_fuel[$jam] = $rs_fuel_1[$jam];
		    }
		}     
		#echo "<pre>"; print_r($data_fuel);echo "</pre>";

		$total_berat 	= 0;
		$total_fuel 	= 0;

		#echo " ====================== <br>";

		FOR ($i = 0; $i < count($arr_jam);$i++) {
		    $jam = $arr_jam[$i];

		    $total_berat += $data_port[$jam] ;
		    $total_fuel += $data_fuel[$jam] ;
		    if ($data_port[$jam] > 0) {
		    	$tmp = $total_fuel / $total_berat ; 
		    } else {
		    	$tmp = 0; 
		    } 
		    #echo " fuel : ".$total_fuel .' = '. $total_berat." = ". $tmp. "<br>";

			$rows1['data'][] 	= number_format($tmp,2); 
		}    
		
		#echo "<pre>"; print_r($rows['data']);echo "</pre>";exit;

		$result = array(); 
		array_push($result,$rows1);
		array_push($result,$rows);

		echo json_encode($result, JSON_NUMERIC_CHECK);
 
	} 
	public function get_liter_per_hm()
	{				
		$tgl 	= $_GET['id']; 
		$rs		= $this->chartfuel_model->get_liter_per_hm($tgl, $tgl); 
		if (count($rs) > 0) {
			$loop = 0;
			FOREACH ($rs AS $r) {
				$tmp_egi 					= $r["egi"];
				$data[$loop]["egi"]	 		= $tmp_egi;
				$data[$loop]["avg_today"]	= $r["avg"];
				$data[$loop]["avg_todate"]	= 0;
				$loop_egi[$tmp_egi]			= $loop ;
				$loop += 1;
			}
		} 

		$tmp 		= explode("-",$tgl);
		$tgl_awal 	= $tmp[0]."-".$tmp[1]."-01";

		$rs_todate		= $this->chartfuel_model->get_liter_per_hm($tgl_awal, $tgl);  
		if (count($rs_todate) > 0) {
			$i = 0;
			FOREACH ($rs_todate AS $r) {
				$tmp_egi	= $r["egi"];
				$i 			= $loop_egi[$tmp_egi];

				if (isset($data[$i]["egi"]) && $data[$i]["egi"] <>"") {
					$new_loop = $i;
				} else { 
					$new_loop = $loop;
					$data[$new_loop]["egi"]	 		= $tmp_egi;
					$data[$new_loop]["avg_today"]	= 0;
				}
				$data[$new_loop]["avg_todate"]	= $r["avg"];
				$loop += 1; 
			}
		} 

		echo json_encode($data, JSON_NUMERIC_CHECK);
 
	} 
 
}
