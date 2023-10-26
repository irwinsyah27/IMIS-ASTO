<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spo_bpm extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">SPO_BPM</li>';
 
		$this->load->model("spo_bpm_model");  
		$this->load->model("operator_model");  

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "spo_bpm"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->spo_bpm_model->user_akses("spo_bpm");
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
		$this->data['title'] 		= "SPO_BPM"; 
		$this->data['js'] 			= 'spo_bpm/js_view';
		$this->data['sview'] 		= 'spo_bpm/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
 
	public function get_data_spo_bpm_pegawai()
	{				
		$nrp 	= $_GET['nrp'];
		$start 	= $_GET['start'];
		$end 	= $_GET['end'];
 
		$rs_spo_bpm				= $this->spo_bpm_model->get_data_spo_bpm_pegawai($nrp, $start, $end); 
		#echo "<pre>"; print_r($rs_port);echo "</pre>"; 
		   

		$trip_per_hour          = "";
		$ton_per_hour           = "";
		$ton_per_hour_cpp       = "";
		$ton_per_hour_port      = "";
		$production_per_shift   = "";

		
		$rows1['name'] 		= 'SPO';
		$rows1['type'] 		= 'column';
		$rows1['color'] 	= '#FFA07A'; 

		$rows['name'] 		= 'BPM'; 
		$rows['type'] 		= 'line'; 
		$rows['yAxis'] 		= '1';  
 
		$jml_hari = date("t",mktime(0, 0, 0, $start, 1, $end )); 
		$label_x = "";
		FOR ($i=1;$i<=$jml_hari;$i++) {
			if (isset($label_x) && $label_x <> "") $label_x .= ",";
			$label_x .= (strlen($i)<2)?'"0'.$i.'"':'"'.$i.'"';
		}  
		$arr_jam = array ($label_x);


		# spo
		if (count($rs_spo_bpm) > 0) {
		    FOREACH ($rs_spo_bpm AS $r) {
		        $tgl               	= $r["tgl"];
		        $data_bpm[$tgl]  	= $r["bpm_in"]; 
		        $data_spo[$tgl]  	= $r["spo_in"]; 
		    }
		} 
		# echo "<pre>".print_r($rs_spo_bpm)."</pre>";exit;
		FOR ($i=1;$i<=$jml_hari;$i++) {
		    if (empty($data_bpm[$i])) $data_bpm[$i] = 0;  
		    $rows['data'][] 	= $data_bpm[$i]; 
		}  
		#echo "<pre>".print_r($rows['data'])."</pre>";exit;
		FOR ($i=1;$i<=$jml_hari;$i++) {
		    if (empty($data_spo[$i])) $data_spo[$i] = 0;  
		    $rows1['data'][] 	= $data_spo[$i]; 
		}  

		$result = array(); 
		array_push($result,$rows1);
		array_push($result,$rows);

		echo json_encode($result, JSON_NUMERIC_CHECK);
 
	}  
}
