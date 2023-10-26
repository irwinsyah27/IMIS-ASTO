<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hourly_monitoring_cpp extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Hourly Monitoring CPP</li>';

		//$this->load->model('proto_model');
		$this->load->model("hourly_monitoring_model");  

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "hourly_monitoring_cpp"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->hourly_monitoring_model->user_akses("hourly_monitoring_cpp");
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
		$this->data['tgl'] 			= $tgl ; 
		$this->data['title'] 		= "Hourly Monitoring Port"; 
		$this->data['js'] 			= 'hourly_monitoring_cpp/js_view';
		$this->data['sview'] 		= 'hourly_monitoring_cpp/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
 
	public function get_data_timbangan_netto_per_hari()
	{					  
		$tgl = $_GET['id'];

		$total_unit  			= $this->hourly_monitoring_model->get_total_unit(); 

		$ct					= $this->hourly_monitoring_model->getcycletimeperjam($tgl,"1"); 
		$rs_port			= $this->hourly_monitoring_model->gettonperjamperstasiun($tgl,"1");   

		$trip_per_hour          = "";
		$ton_per_hour           = "";
		$ton_per_hour_cpp       = "";
		$ton_per_hour_port      = "";
		$production_per_shift   = "";

		$rows['name'] 		= 'Trip/Hour'; 
		$rows['type'] 		= 'line'; 
		$rows['yAxis'] 		= '1'; 

		$rows1['name'] 		= 'Ton/Hour';
		$rows1['type'] 		= 'column';
		$rows1['color'] 	= '#FFA07A'; 

		$arr_jam = array ("05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","00","01","02","03","04");

		// Ritase
		if (count($ct) > 0) {
		    FOREACH ($ct AS $r) {
		        $jam            = $r["jam"];
		        $data_ct[$jam]  = $r["berat"];
		    }
		}
		$summary_total = 0;
		FOR ($i = 0; $i < count($arr_jam);$i++) {
		    $jam 			= $arr_jam[$i];
		    $jam_berikut 	= $arr_jam[$i+1];

		    if (empty($data_ct[$jam])) {
		    	$ton = 0;  
		    }else {
		    	$ton = $data_ct[$jam] ;  
			    $summary_total += $ton;

			    if ($i < 13) {
			    	$tgl_1 = $tgl;
			    } else {
			    	$tmp = explode("-", $tgl); 
			   		 $tgl_1 = date("Y-m-d",mktime(0, 0, 0, $tmp[1], $tmp[0] + 1, $tmp[0]  ));
			    }
			   
		    	$datetime_end	= $tgl_1." ".$jam_berikut.":00";
 
				$total_unit_breakdown 	= $this->hourly_monitoring_model->get_total_unit_breakdown($datetime_end);
				$total_unit_active 		= $total_unit - $total_unit_breakdown; 

				if ($total_unit_active > 0) {
			    	$ton = number_format($summary_total / $total_unit_active , 2); 
			    } else {
			    	$ton = 0;
			    }
		    }

			$rows['data'][] = $ton;  
		}   

		// Tonase
		if (count($rs_port) > 0) {
		    FOREACH ($rs_port AS $r) {
		        $jam                = $r["jam"];
		        $data_port[$jam]    = $r["berat"] / 1000;
		    }
		}
		FOR ($i = 0; $i < count($arr_jam);$i++) {
		    $jam = $arr_jam[$i];
		    if (empty($data_port[$jam])) $ton = 0; else  $ton = $data_port[$jam];  

			$rows1['data'][] 	= $ton; 
		}   

		$result = array();
		array_push($result,$rows1);
		array_push($result,$rows);

		echo json_encode($result, JSON_NUMERIC_CHECK);
 
	} 

	public function get_total_produksi_per_shift()
	{					  
		$tgl = $_GET['id'];
 
		$total_per_shift	= $this->hourly_monitoring_model->gettotaltonpershift($tgl,"1");  

		if (count($total_per_shift) > 0) {
		    FOREACH ($total_per_shift AS $r) {
		        $jam    = $r["jam"];
		        $data_shift[$jam] = $r["berat"] / 1000;
		    }
		} 

		if (empty($data_shift["1"]) ) $data_shift_1 = 0; else  $data_shift_1 = $data_shift["1"]; 
		if (empty($data_shift["2"]) ) $data_shift_2 = 0; else  $data_shift_2 = $data_shift["2"]; 

		$rows1['name'] 		= 'Shift 1';
		$rows1['type'] 		= 'bar'; 
		$rows1['data'][] 	= $data_shift_1;   

		$rows2['name'] 		= 'Shift 2';
		$rows2['type'] 		= 'bar'; 
		$rows2['color'] 	= '#FFA07A'; 
		$rows2['data'][] 	= $data_shift_2;  
 
		$result = array(); 
		array_push($result,$rows1);
		array_push($result,$rows2);

		echo json_encode($result, JSON_NUMERIC_CHECK);
 
	} 
}
