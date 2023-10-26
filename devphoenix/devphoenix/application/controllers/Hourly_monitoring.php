<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hourly_monitoring extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Hourly Monitoring Port</li>';

		//$this->load->model('proto_model');
		$this->load->model("hourly_monitoring_model");  
		
		$this->load->model('running_text_model');

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "hourly_monitoring"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->hourly_monitoring_model->user_akses("hourly_monitoring");
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
		$dt_running = "";
		$tmp_running_text = $this->running_text_model->getRunningText("hourly_monitoring");
		if (count($tmp_running_text) > 0) {
			FOREACH ($tmp_running_text AS $r) {
				if (isset($dt_running ) && $dt_running  <> "") $dt_running .= "... ";
				$dt_running .= $r["keterangan"];
			}
		}

		$this->data['running_text'] 	= $dt_running;
		$this->data['list_owner'] 		= $this->hourly_monitoring_model->get_data_owner();

		$this->data['title'] 		= "Hourly Monitoring Port"; 
		$this->data['js'] 			= 'hourly_monitoring/js_view';
		$this->data['sview'] 		= 'hourly_monitoring/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
 
	public function get_data_timbangan_netto_per_hari()
	{					  
		$tgl = $_GET['id'];

		$total_unit  			= $this->hourly_monitoring_model->get_total_unit(); 

		$ct					= $this->hourly_monitoring_model->getcycletimeperjam($tgl,"2"); 
		$rs_port			= $this->hourly_monitoring_model->gettonperjamperstasiun($tgl,"2");   

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
 
		$total_per_shift	= $this->hourly_monitoring_model->gettotaltonpershift($tgl,"2");  

		if (count($total_per_shift) > 0) {
		    FOREACH ($total_per_shift AS $r) {
		        $jam    = $r["jam"];
		        $data_shift[$jam] = $r["berat"] / 1000;
		    }
		} 

		if (empty($data_shift["1"]) ) $data_shift_1 = 0; else  $data_shift_1 = $data_shift["1"]; 
		if (empty($data_shift["2"]) ) $data_shift_2 = 0; else  $data_shift_2 = $data_shift["2"]; 

		$rows1['name'] 		= 'Shift 1';
		$rows1['type'] 		= 'column'; 
		$rows1['data'][] 	= $data_shift_1;   
		$rows1['data'][] 	= $data_shift_2;  
		$rows1['data'][] 	= $data_shift_1 + $data_shift_2; 

		/*
		$rows2['name'] 		= 'Shift 2';
		$rows2['type'] 		= 'column'; 
		$rows2['color'] 	= '#FFA07A'; 
		$rows2['data'][] 	= $data_shift_2;  

		$rows3['name'] 		= 'Total';
		$rows3['type'] 		= 'column'; 
		$rows3['color'] 	= '#6cf24d'; 
		$rows3['data'][] 	= $data_shift_1 + $data_shift_2;  
 		*/
		$result = array(); 
		array_push($result,$rows1);
		//array_push($result,$rows2);
		//array_push($result,$rows3);

		echo json_encode($result, JSON_NUMERIC_CHECK);
 
	} 

	public function get_data_hourly_tonase_hauling()
	{					  
		$tgl = $_GET['id'];
 
		$rs_port			= $this->hourly_monitoring_model->get_total_payload_per_hour($tgl,"2","KPP");     

		$rows1['name'] 		= 'Ton/Hour';
		$rows1['type'] 		= 'column';
		$rows1['color'] 	= '#FFA07A'; 

		$arr_jam = array ("05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","00","01","02","03","04");
 
 		$arr_egi = array ("1" => "P360" , "2" => "P380", "3" => "P410");
 		$value = array ("P360" => "1" , "P380" => "2", "P410" => "3");
		 
		if (count($rs_port) > 0) {
			$i = 1;
		    FOREACH ($rs_port AS $r) {
		    	$egi					= trim($r["egi"]);
		    	$owner					= trim($r["kode"]); 
		        $jam                	= $r["jam"];
		        $data_port[$jam][$egi ]["payload"]  	= number_format($r["payload"],2) ;  
		    }
		} 

		FOR ($loop=1; $loop<=count($arr_egi); $loop++) {
			$egi						= $arr_egi[$loop];
			${"rows_".$egi}['name'] 	= $egi;
			${"rows_".$egi}['type'] 	= 'column';

			FOR ($i = 0; $i < count($arr_jam);$i++) {
			    $jam = $arr_jam[$i]; 
			    if (empty($data_port[$jam][$egi]["payload"])) $ton = 0; else  $ton = $data_port[$jam][$egi]["payload"];  

				${"rows_".$egi}['data'][] 	= $ton; 
			}  	
		}
		 

		$result = array();
		FOR ($i=1; $i<=count($arr_egi); $i++) {  
			$egi = $arr_egi[$i]; 
			array_push($result, ${"rows_".$egi});
		} 

		echo json_encode($result, JSON_NUMERIC_CHECK); 
	} 
	public function get_data_timbangan_per_3_jam()
	{					 
		$tgl 		= $_GET['id'];
		$rs 		= $this->hourly_monitoring_model->get_data_owner_and_egi($tgl );  

		$no = 1;
		$total_data = count($rs);
		if (count($rs)>0) { 
			FOREACH ($rs AS $l) {
				$owner				= $l["owner"]; 
				$egi				= $l["egi"];  

				if ($old_owner <> $owner && $no > 1) {  
					$data[$no]["kode"] 			= "Sub Total ". $old_owner; 
					$data[$no]["egi"] 			= ""; 
					$data[$no]["ritase_09"] 	= $sub_total["ritase_09"];
					$data[$no]["produksi_09"] 	= $sub_total["produksi_09"]; 
					$data[$no]["unit_09"] 		= $sub_total["unit_09"];

					$data[$no]["ritase_12"] 	= $sub_total["ritase_12"];
					$data[$no]["produksi_12"] 	= $sub_total["produksi_12"]; 
					$data[$no]["unit_12"] 		= $sub_total["unit_12"];

					$data[$no]["ritase_15"] 	= $sub_total["ritase_15"];
					$data[$no]["produksi_15"] 	= $sub_total["produksi_15"]; 
					$data[$no]["unit_15"] 		= $sub_total["unit_15"];

					$data[$no]["ritase_18"] 	= $sub_total["ritase_18"];
					$data[$no]["produksi_18"] 	= $sub_total["produksi_18"]; 
					$data[$no]["unit_18"] 		= $sub_total["unit_18"];

					$data[$no]["ritase_21"] 	= $sub_total["ritase_21"];
					$data[$no]["produksi_21"] 	= $sub_total["produksi_21"]; 
					$data[$no]["unit_21"] 		= $sub_total["unit_21"];

					$data[$no]["ritase_00"] 	= $sub_total["ritase_00"];
					$data[$no]["produksi_00"] 	= $sub_total["produksi_00"]; 
					$data[$no]["unit_00"] 		= $sub_total["unit_00"];

					$data[$no]["ritase_03"] 	= $sub_total["ritase_03"];
					$data[$no]["produksi_03"] 	= $sub_total["produksi_03"]; 
					$data[$no]["unit_03"] 		= $sub_total["unit_03"];

					$data[$no]["ritase_05"] 	= $sub_total["ritase_05"];
					$data[$no]["produksi_05"] 	= $sub_total["produksi_05"]; 
					$data[$no]["unit_05"] 		= $sub_total["unit_05"];

					$total_data += 1;
					$no += 1;
				}

				$arr_hour_1 	= "'05','06','07','08'";
				$rs1		= $this->hourly_monitoring_model->get_total_payload_per_periode_hour($arr_hour_1,$tgl,"2",$owner, $egi);
				if (count($rs1) > 0) {
					FOREACH ($rs1 AS $r1) {
						$data[$no]["kode"] 			= $owner; 
						$data[$no]["egi"] 			= $egi; 
						$data[$no]["ritase_09"] 	= ($r1["ritase"]==null)?"":$r1["ritase"];
						$data[$no]["produksi_09"] 	= ($r1["produksi"]==null)?"":$r1["produksi"]; 
						$data[$no]["unit_09"] 		= ($r1["unit"]==null)?"":$r1["unit"];
					}
				}

				$arr_hour_1 	= "'05','06','07','08','09','10','11'";
				$rs1		= $this->hourly_monitoring_model->get_total_payload_per_periode_hour($arr_hour_1,$tgl,"2",$owner, $egi);
				if (count($rs1) > 0) {
					FOREACH ($rs1 AS $r1) {
						$data[$no]["kode"] 			= $owner; 
						$data[$no]["egi"] 			= $egi; 
						$data[$no]["ritase_12"] 	= ($r1["ritase"]==null)?"":$r1["ritase"];
						$data[$no]["produksi_12"] 	= ($r1["produksi"]==null)?"":$r1["produksi"]; 
						$data[$no]["unit_12"] 		= ($r1["unit"]==null)?"":$r1["unit"];
					}
				}

				$arr_hour_1 	= "'05','06','07','08','09','10','11','12','13','14'";
				$rs1		= $this->hourly_monitoring_model->get_total_payload_per_periode_hour($arr_hour_1,$tgl,"2",$owner, $egi);
				if (count($rs1) > 0) {
					FOREACH ($rs1 AS $r1) {
						$data[$no]["kode"] 			= $owner; 
						$data[$no]["egi"] 			= $egi; 
						$data[$no]["ritase_15"] 	= ($r1["ritase"]==null)?"":$r1["ritase"];
						$data[$no]["produksi_15"] 	= ($r1["produksi"]==null)?"":$r1["produksi"]; 
						$data[$no]["unit_15"] 		= ($r1["unit"]==null)?"":$r1["unit"];
					}
				}

				$arr_hour_1 	= "'05','06','07','08','09','10','11','12','13','14','15','16','17'";
				$rs1		= $this->hourly_monitoring_model->get_total_payload_per_periode_hour($arr_hour_1,$tgl,"2",$owner, $egi);
				if (count($rs1) > 0) {
					FOREACH ($rs1 AS $r1) {
						$data[$no]["kode"] 			= $owner; 
						$data[$no]["egi"] 			= $egi; 
						$data[$no]["ritase_18"] 	= ($r1["ritase"]==null)?"":$r1["ritase"];
						$data[$no]["produksi_18"] 	= ($r1["produksi"]==null)?"":$r1["produksi"]; 
						$data[$no]["unit_18"] 		= ($r1["unit"]==null)?"":$r1["unit"];


						$total["ritase_18"] 	+= ($r1["ritase"]==null)?"":$r1["ritase"];
						$total["produksi_18"] 	+= ($r1["produksi"]==null)?"":$r1["produksi"]; 
						$total["unit_18"] 		+= ($r1["unit"]==null)?"":$r1["unit"];
					}
				}

				$arr_hour_1 	= "'05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20'";
				$rs1		= $this->hourly_monitoring_model->get_total_payload_per_periode_hour($arr_hour_1,$tgl,"2",$owner, $egi);
				if (count($rs1) > 0) {
					FOREACH ($rs1 AS $r1) {
						$data[$no]["kode"] 			= $owner; 
						$data[$no]["egi"] 			= $egi; 
						$data[$no]["ritase_21"] 	= ($r1["ritase"]==null)?"":$r1["ritase"];
						$data[$no]["produksi_21"] 	= ($r1["produksi"]==null)?"":$r1["produksi"]; 
						$data[$no]["unit_21"] 		= ($r1["unit"]==null)?"":$r1["unit"];
					}
				}

				$arr_hour_1 	= "'05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'";
				$rs1		= $this->hourly_monitoring_model->get_total_payload_per_periode_hour($arr_hour_1,$tgl,"2",$owner, $egi);
				if (count($rs1) > 0) {
					FOREACH ($rs1 AS $r1) {
						$data[$no]["kode"] 			= $owner; 
						$data[$no]["egi"] 			= $egi; 
						$data[$no]["ritase_00"] 	= ($r1["ritase"]==null)?"":$r1["ritase"];
						$data[$no]["produksi_00"] 	= ($r1["produksi"]==null)?"":$r1["produksi"]; 
						$data[$no]["unit_00"] 		= ($r1["unit"]==null)?"":$r1["unit"];
					}
				}

				$arr_hour_1 	= "'05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','00','01','02'";
				$rs1		= $this->hourly_monitoring_model->get_total_payload_per_periode_hour($arr_hour_1,$tgl,"2",$owner, $egi);
				if (count($rs1) > 0) {
					FOREACH ($rs1 AS $r1) {
						$data[$no]["kode"] 			= $owner; 
						$data[$no]["egi"] 			= $egi; 
						$data[$no]["ritase_03"] 	= ($r1["ritase"]==null)?"":$r1["ritase"];
						$data[$no]["produksi_03"] 	= ($r1["produksi"]==null)?"":$r1["produksi"]; 
						$data[$no]["unit_03"] 		= ($r1["unit"]==null)?"":$r1["unit"];
					}
				}

				$arr_hour_1 	= "'05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','00','01','02','03','04'";
				$rs1		= $this->hourly_monitoring_model->get_total_payload_per_periode_hour($arr_hour_1,$tgl,"2",$owner, $egi);
				if (count($rs1) > 0) {
					FOREACH ($rs1 AS $r1) {
						$data[$no]["kode"] 			= $owner; 
						$data[$no]["egi"] 			= $egi; 
						$data[$no]["ritase_05"] 	= ($r1["ritase"]==null)?"":$r1["ritase"];
						$data[$no]["produksi_05"] 	= ($r1["produksi"]==null)?"":$r1["produksi"]; 
						$data[$no]["unit_05"] 		= ($r1["unit"]==null)?"":$r1["unit"];

						$total["ritase_05"] 	+= ($r1["ritase"]==null)?"":$r1["ritase"];
						$total["produksi_05"] 	+= ($r1["produksi"]==null)?"":$r1["produksi"]; 
						$total["unit_05"] 		+= ($r1["unit"]==null)?"":$r1["unit"];
					}
				}

				if ($no ==1) $old_owner = $owner;

				if ($old_owner == $owner) {
					$sub_total["ritase_09"]		+= $data[$no]["ritase_09"];
					$sub_total["produksi_09"]	+= $data[$no]["produksi_09"]; 
					$sub_total["unit_09"]		+= $data[$no]["unit_09"];

					$sub_total["ritase_12"]		+= $data[$no]["ritase_12"];
					$sub_total["produksi_12"]	+= $data[$no]["produksi_12"]; 
					$sub_total["unit_12"]		+= $data[$no]["unit_12"];

					$sub_total["ritase_15"]		+= $data[$no]["ritase_15"];
					$sub_total["produksi_15"]	+= $data[$no]["produksi_15"]; 
					$sub_total["unit_15"]		+= $data[$no]["unit_15"];

					$sub_total["ritase_18"]		+= $data[$no]["ritase_18"];
					$sub_total["produksi_18"]	+= $data[$no]["produksi_18"]; 
					$sub_total["unit_18"]		+= $data[$no]["unit_18"];

					$sub_total["ritase_21"]		+= $data[$no]["ritase_21"];
					$sub_total["produksi_21"]	+= $data[$no]["produksi_21"]; 
					$sub_total["unit_21"]		+= $data[$no]["unit_21"];

					$sub_total["ritase_00"]		+= $data[$no]["ritase_00"];
					$sub_total["produksi_00"]	+= $data[$no]["produksi_00"]; 
					$sub_total["unit_00"]		+= $data[$no]["unit_00"];

					$sub_total["ritase_03"]		+= $data[$no]["ritase_03"];
					$sub_total["produksi_03"]	+= $data[$no]["produksi_03"]; 
					$sub_total["unit_03"]		+= $data[$no]["unit_03"];

					$sub_total["ritase_05"]		+= $data[$no]["ritase_05"];
					$sub_total["produksi_05"]	+= $data[$no]["produksi_05"]; 
					$sub_total["unit_05"]		+= $data[$no]["unit_05"];

				} else {
					$sub_total["ritase_09"]		= 0;
					$sub_total["produksi_09"]	= 0; 
					$sub_total["unit_09"]		= 0;


					$sub_total["ritase_12"]		= 0;
					$sub_total["produksi_12"]	= 0;
					$sub_total["unit_12"]		= 0;

					$sub_total["ritase_15"]		= 0;
					$sub_total["produksi_15"]	= 0;
					$sub_total["unit_15"]		= 0;

					$sub_total["ritase_18"]		= 0;
					$sub_total["produksi_18"]	= 0;
					$sub_total["unit_18"]		= 0;

					$sub_total["ritase_21"]		= 0;
					$sub_total["produksi_21"]	= 0;
					$sub_total["unit_21"]		= 0;

					$sub_total["ritase_00"]		= 0;
					$sub_total["produksi_00"]	= 0; 
					$sub_total["unit_00"]		= 0;

					$sub_total["ritase_03"]		= 0;
					$sub_total["produksi_03"]	= 0;
					$sub_total["unit_03"]		= 0;

					$sub_total["ritase_05"]		= 0;
					$sub_total["produksi_05"]	= 0;
					$sub_total["unit_05"]		= 0;



					$sub_total["ritase_09"]		+= $data[$no]["ritase_09"];
					$sub_total["produksi_09"]	+= $data[$no]["produksi_09"]; 
					$sub_total["unit_09"]		+= $data[$no]["unit_09"];

					$sub_total["ritase_12"]		+= $data[$no]["ritase_12"];
					$sub_total["produksi_12"]	+= $data[$no]["produksi_12"]; 
					$sub_total["unit_12"]		+= $data[$no]["unit_12"];

					$sub_total["ritase_15"]		+= $data[$no]["ritase_15"];
					$sub_total["produksi_15"]	+= $data[$no]["produksi_15"]; 
					$sub_total["unit_15"]		+= $data[$no]["unit_15"];

					$sub_total["ritase_18"]		+= $data[$no]["ritase_18"];
					$sub_total["produksi_18"]	+= $data[$no]["produksi_18"]; 
					$sub_total["unit_18"]		+= $data[$no]["unit_18"];

					$sub_total["ritase_21"]		+= $data[$no]["ritase_21"];
					$sub_total["produksi_21"]	+= $data[$no]["produksi_21"]; 
					$sub_total["unit_21"]		+= $data[$no]["unit_21"];

					$sub_total["ritase_00"]		+= $data[$no]["ritase_00"];
					$sub_total["produksi_00"]	+= $data[$no]["produksi_00"]; 
					$sub_total["unit_00"]		+= $data[$no]["unit_00"];

					$sub_total["ritase_03"]		+= $data[$no]["ritase_03"];
					$sub_total["produksi_03"]	+= $data[$no]["produksi_03"]; 
					$sub_total["unit_03"]		+= $data[$no]["unit_03"];

					$sub_total["ritase_05"]		+= $data[$no]["ritase_05"];
					$sub_total["produksi_05"]	+= $data[$no]["produksi_05"]; 
					$sub_total["unit_05"]		+= $data[$no]["unit_05"];
				}

				if ($total_data == $no) {  
					$data[$no+1]["kode"] 			= "Sub Total ". $old_owner; 
					$data[$no+1]["egi"] 			= ""; 
					$data[$no+1]["ritase_09"] 		= $sub_total["ritase_09"];
					$data[$no+1]["produksi_09"] 	= $sub_total["produksi_09"]; 
					$data[$no+1]["unit_09"] 		= $sub_total["unit_09"]; 

					$data[$no+1]["ritase_12"] 		= $sub_total["ritase_12"];
					$data[$no+1]["produksi_12"] 	= $sub_total["produksi_12"]; 
					$data[$no+1]["unit_12"] 		= $sub_total["unit_12"];

					$data[$no+1]["ritase_15"] 		= $sub_total["ritase_15"];
					$data[$no+1]["produksi_15"] 	= $sub_total["produksi_15"]; 
					$data[$no+1]["unit_15"] 		= $sub_total["unit_15"];

					$data[$no+1]["ritase_18"] 		= $sub_total["ritase_18"];
					$data[$no+1]["produksi_18"] 	= $sub_total["produksi_18"]; 
					$data[$no+1]["unit_18"] 		= $sub_total["unit_18"];

					$data[$no+1]["ritase_21"] 		= $sub_total["ritase_21"];
					$data[$no+1]["produksi_21"] 	= $sub_total["produksi_21"]; 
					$data[$no+1]["unit_21"] 		= $sub_total["unit_21"];

					$data[$no+1]["ritase_00"] 		= $sub_total["ritase_00"];
					$data[$no+1]["produksi_00"] 	= $sub_total["produksi_00"]; 
					$data[$no+1]["unit_00"] 		= $sub_total["unit_00"];

					$data[$no+1]["ritase_03"] 		= $sub_total["ritase_03"];
					$data[$no+1]["produksi_03"] 	= $sub_total["produksi_03"]; 
					$data[$no+1]["unit_03"] 		= $sub_total["unit_03"];

					$data[$no+1]["ritase_05"] 		= $sub_total["ritase_05"];
					$data[$no+1]["produksi_05"] 	= $sub_total["produksi_05"]; 
					$data[$no+1]["unit_05"] 		= $sub_total["unit_05"]; 


					$data[$no+2]["kode"] 			= "Total "; 

					$data[$no+2]["ritase_18"] 		= $total["ritase_18"];
					$data[$no+2]["produksi_18"] 	= $total["produksi_18"]; 
					$data[$no+2]["unit_18"] 		= $total["unit_18"];

					$data[$no+2]["ritase_05"] 		= $total["ritase_05"];
					$data[$no+2]["produksi_05"] 	= $total["produksi_05"]; 
					$data[$no+2]["unit_05"] 		= $total["unit_05"]; 
				}

				$old_owner = $owner;
				$no += 1; 
				/*
				$rs_payload		= $this->hourly_monitoring_model->get_total_payload_per_hour($tgl,"2",$tmp_kode);
				if (count($rs_payload) > 0) {
					$j = 1; 
					unset($tmp_no_urut , $egi);
					FOREACH ($rs_payload AS $rp) {
						unset($tmp_egi, $tmp_jam, $tmp_produksi, $tmp_payload);
						$tmp_egi		= $rp["egi"];
						$tmp_jam 		= $rp["jam"];
						$tmp_produksi 	= $rp["produksi"];
						$tmp_payload 	= $rp["payload"];
						$tmp_ritase 	= $rp["ritase"];
						$tmp_unit 		= $rp["unit"]; 

						if ($tmp_no_urut[$tmp_egi] == "") { 
							$egi[$j]				= $tmp_egi;
							$tmp_no_urut[$tmp_egi] 	= $j;
							$no_urut = $j;
							$j += 1;
						} else {
							$no_urut = $tmp_no_urut[$tmp_egi];
						}

						switch ($tmp_jam ) {
							case '05': 
							case '06': 
							case '07': 
							case '08':
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["ritase_09"]	+= $tmp_ritase;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["produksi_09"]	+= $tmp_produksi;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["unit_09"]		+= $tmp_unit;
								//$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["payload_09"]	+= $tmp_payload;
								//break;
							case '09':
							case '10': 
							case '11':
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["ritase_12"]	+= $tmp_ritase;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["produksi_12"]	+= $tmp_produksi;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["unit_12"]		+= $tmp_unit;
								//$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["payload_12"]	+= $tmp_payload;
								//break; 
							case '12': 
							case '13': 
							case '14':
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["ritase_15"]	+= $tmp_ritase;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["produksi_15"]	+= $tmp_produksi;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["unit_15"]		+= $tmp_unit;
								//$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["payload_15"]	+= $tmp_payload;
								//break; 
							case '15': 
							case '16': 
							case '17':
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["ritase_18"]	+= $tmp_ritase;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["produksi_18"]	+= $tmp_produksi;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["unit_18"]		+= $tmp_unit;
								//$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["payload_18"]	+= $tmp_payload;
								//break; 
							case '18': 
							case '19': 
							case '20':
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["ritase_21"]	+= $tmp_ritase;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["produksi_21"]	+= $tmp_produksi;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["unit_21"]		+= $tmp_unit;
								//$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["payload_21"]	+= $tmp_payload;
								//break; 
							case '21': 
							case '22': 
							case '23':
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["ritase_00"]	+= $tmp_ritase;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["produksi_00"]	+= $tmp_produksi;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["unit_00"]		+= $tmp_unit;
								//$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["payload_00"]	+= $tmp_payload;
								//break; 
							case '24': 
							case '00': 
							case '01': 
							case '02':
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["ritase_03"]	+= $tmp_ritase;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["produksi_03"]	+= $tmp_produksi;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["unit_03"]		+= $tmp_unit;
								//$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["payload_03"]	+= $tmp_payload;
								//break; 
							case '03': 
							case '04':  
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["ritase_06"]	+= $tmp_ritase;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["produksi_06"]	+= $tmp_produksi;
								$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["unit_06"]		+= $tmp_unit;
								//$tmp_data_egi[$no_urut][$tmp_kode][$tmp_egi]["payload_06"]	+= $tmp_payload;
								break; 
						} 
					}
					FOR ($k = 1; $k<$j; $k++) {
						if ($egi[$k] <> "") { 
							$tmp_egi_1					= $egi[$k];

							$data[$no]["kode"] 			= $tmp_kode; 
							$data[$no]["egi"] 			= $tmp_egi_1; 
							$data[$no]["ritase_09"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_09"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_09"];
							$data[$no]["produksi_09"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_09"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_09"]; 
							$data[$no]["payload_09"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_09"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_09"]; 
							$data[$no]["unit_09"] 		= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_09"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_09"]; 

							$data[$no]["ritase_12"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_12"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_12"];
							$data[$no]["produksi_12"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_12"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_12"]; 
							$data[$no]["payload_12"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_12"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_12"]; 
							$data[$no]["unit_12"] 		= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_12"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_12"]; 

							$data[$no]["ritase_15"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_15"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_15"];
							$data[$no]["produksi_15"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_15"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_15"]; 
							$data[$no]["payload_15"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_15"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_15"];
							$data[$no]["unit_15"] 		= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_15"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_15"];  

							$data[$no]["ritase_18"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_18"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_18"];
							$data[$no]["produksi_18"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_18"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_18"]; 
							$data[$no]["payload_18"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_18"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_18"];
							$data[$no]["unit_18"] 		= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_18"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_18"]; 

							$data[$no]["ritase_21"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_21"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_21"]; 
							$data[$no]["produksi_21"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_21"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_21"]; 
							$data[$no]["payload_21"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_21"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_21"]; 
							$data[$no]["unit_21"] 		= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_21"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_21"]; 

							$data[$no]["ritase_00"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_00"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_00"];
							$data[$no]["produksi_00"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_00"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_00"]; 
							$data[$no]["payload_00"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_00"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_00"]; 
							$data[$no]["unit_00"] 		= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_00"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_00"]; 

							$data[$no]["ritase_03"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_03"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_03"];
							$data[$no]["produksi_03"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_03"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_03"]; 
							$data[$no]["payload_03"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_03"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_03"]; 
							$data[$no]["unit_03"] 		= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_03"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_03"]; 

							$data[$no]["ritase_06"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_06"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["ritase_06"];
							$data[$no]["produksi_06"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_06"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["produksi_06"]; 
							$data[$no]["payload_06"] 	= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_06"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["payload_06"];  
							$data[$no]["unit_06"] 		= ($tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_06"]==null)?"":$tmp_data_egi[$k][$tmp_kode][$tmp_egi_1]["unit_06"]; 
							$no += 1;	
						}
					}
				} 
					*/ 
			} 
		}   
		echo json_encode($data, JSON_NUMERIC_CHECK); 
	} 
}
