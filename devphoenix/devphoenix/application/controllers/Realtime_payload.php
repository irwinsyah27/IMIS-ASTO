<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Realtime_payload extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Realtime Payload</li>';

		//$this->load->model('proto_model');
		$this->load->model("realtime_payload_model");  

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "realtime_payload"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->realtime_payload_model->user_akses("realtime_payload");

		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',''); 
   	}

	public function index()
	{ 
			$this->view_from_timbangan();
	}

	public function download()
	{					    
		$tgl 		= $this->uri->segment(3,0); ; 
		$shift 		= $this->uri->segment(4,0); ; 
		
		$rs 		= $this->realtime_payload_model->getAllDataPerDayFromTimbangan($tgl, $shift); 
 		

		$no = 1;
		if (count($rs)>0) {
			FOREACH ($rs AS $l) { 
				$tmp_unit							= $l["unit"];

				if ($l["shift"] == 2) {
					$tmp_tgl = explode("-", $l["tgl_payload"]);
					$tmp_jam = explode(":", $l["time_weigher"]);
					if ($tmp_jam[0] >= 0 AND $tmp_jam[0] < 5) {
						$tgl_timbangan =  date("Y-m-d",mktime(0, 0, 0, $tmp_tgl["1"], $tmp_tgl["2"] + 1, $tmp_tgl["0"] ))." ". $l["time_weigher"];
					} else {
						$tgl_timbangan = $l["tgl_payload"]." ". $l["time_weigher"];
					} 
				} else {
					$tgl_timbangan = $l["tgl_payload"]." ". $l["time_weigher"];
				}

				if ($urutan[$tmp_unit] == "") {
					$urutan[$tmp_unit]				= $no;
					$tmp_data[$no]["unit"] 			= $l["unit"];
					$tmp_data[$no]["egi"] 			= $l["egi"];
					$tmp_data[$no]["tgl_payload"] 	= $l["tgl_payload"]; 
					$tmp_data[$no]["urutan"][1] 	= $l["netto"]; 
					$tmp_data[$no]["jam"][1] 		= $tgl_timbangan; 
					$no += 1;
				} else {
					$tmp_no							= $urutan[$tmp_unit];
					$tmp_count = count($tmp_data[$tmp_no]["urutan"]);
					$tmp_count += 1;
					$tmp_data[$tmp_no]["urutan"][$tmp_count] 	= $l["netto"]; 
					$tmp_data[$tmp_no]["jam"][$tmp_count] 		= $tgl_timbangan; 
				}
				
			}
		}  
		
		#print_r($tmp_data);exit;

		FOR ($i=1; $i<$no; $i++) {
			$data[$i]["nama_operator"] 	= ""; 
			$data[$i]["unit"] 				= $tmp_data[$i]["unit"]; 
			$data[$i]["egi"] 				= $tmp_data[$i]["egi"];  
			FOR ($j=1;$j<=3;$j++) {
				$data[$i]["netto_".$j] 		= number_format($tmp_data[$i]["urutan"][$j],0); 
			}  

			$tmp_date_1 = new DateTime($tmp_data[$i]["jam"][1]);
			$tmp_date_2 = new DateTime($tmp_data[$i]["jam"][2]);
			$tmp_date_3 = new DateTime($tmp_data[$i]["jam"][3]);

			if ($tmp_data[$i]["jam"][2] > $tmp_data[$i]["jam"][1]) {
				$tmp_int_1 	= $tmp_date_1->diff($tmp_date_2);
				$data[$i]["ct_1"] 		= $tmp_int_1->h.":".$tmp_int_1->i; 
			} else { $data[$i]["ct_1"]  = ""; }

			if ($tmp_data[$i]["jam"][3] > $tmp_data[$i]["jam"][2]) {
				$tmp_int_2 	= $tmp_date_2->diff($tmp_date_3);
				$data[$i]["ct_2"] 		= $tmp_int_2->h.":".$tmp_int_2->i;  
			} else { $data[$i]["ct_2"]  = ""; }  
		}


		$colnames 		= array("NO","UNIT","EGI","TONNAGE 1","TONNAGE 2", "TONNAGE 3","CT 1","CT 2");
		 
		$string_to_export = "";
		if ($header <> "") $string_to_export .= $header."\n\n";
		
		foreach ($colnames AS $k=>$v) {
			$string_to_export .= $v. "\t";
		} 
		$string_to_export .= "\n";

		FOR ($i=1; $i<=count($data); $i++) {
			$string_to_export .= $i."\t"; 
			$string_to_export .= $this->_trim_export_string($data[$i]["unit"])."\t"; 
			$string_to_export .= $this->_trim_export_string($data[$i]["egi"])."\t"; 
			$string_to_export .= $this->_trim_export_string($data[$i]["netto_1"])."\t"; 
			$string_to_export .= $this->_trim_export_string($data[$i]["netto_2"])."\t"; 
			$string_to_export .= $this->_trim_export_string($data[$i]["netto_3"])."\t"; 
			$string_to_export .= $this->_trim_export_string($data[$i]["ct_1"])."\t"; 
			$string_to_export .= $this->_trim_export_string($data[$i]["ct_2"])."\t"; 
			$string_to_export .= "\n";
		}


		// Convert to UTF-16LE and Prepend BOM
		$string_to_export = "\xFF\xFE" .mb_convert_encoding($string_to_export, 'UTF-16LE', 'UTF-8');

		$filename = "RPT_TONNAGE_AND_CT_".date("Y-m-d_H:i:s").".xls";

		header('Content-type: application/vnd.ms-excel;charset=UTF-16LE');
		header('Content-Disposition: attachment; filename='.$filename);
		header("Cache-Control: no-cache");
		echo $string_to_export;
		die(); 
	} 
	public function _trim_export_string($value)
	{
		$value = str_replace(array("&nbsp;","&amp;","&gt;","&lt;"),array(" ","&",">","<"),$value);
		return  strip_tags(str_replace(array("\t","\n","\r"),"",$value));
	}

	public function view_from_timbangan()
	{					  
		$this->data['title'] 		= "Realtime Payload"; 
		$this->data['js'] 			= 'realtime_payload/js_view_timbangan';
		$this->data['sview'] 		= 'realtime_payload/view_timbangan'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
	public function get_data_timbangan()
	{					 
		$id 		= $_GET['id']; 
		$shift 		= $_GET['shift']; 
		$rs 		= $this->realtime_payload_model->getAllDataPerDayFromTimbangan($id, $shift); 
 		

		$no = 1;
		if (count($rs)>0) {
			FOREACH ($rs AS $l) { 
				$tmp_unit							= $l["unit"];

				if ($l["shift"] == 2) {
					$tmp_tgl = explode("-", $l["tgl_payload"]);
					$tmp_jam = explode(":", $l["time_weigher"]);
					if ($tmp_jam[0] >= 0 AND $tmp_jam[0] < 5) {
						$tgl_timbangan =  date("Y-m-d",mktime(0, 0, 0, $tmp_tgl["1"], $tmp_tgl["2"] + 1, $tmp_tgl["0"] ))." ". $l["time_weigher"];
					} else {
						$tgl_timbangan = $l["tgl_payload"]." ". $l["time_weigher"];
					} 
				} else {
					$tgl_timbangan = $l["tgl_payload"]." ". $l["time_weigher"];
				}

				if ($urutan[$tmp_unit] == "") {
					$urutan[$tmp_unit]				= $no;
					$tmp_data[$no]["unit"] 			= $l["unit"];
					$tmp_data[$no]["egi"] 			= $l["egi"];
					$tmp_data[$no]["tgl_payload"] 	= $l["tgl_payload"]; 
					$tmp_data[$no]["urutan"][1] 	= $l["netto"]; 
					$tmp_data[$no]["jam"][1] 		= $tgl_timbangan; 
					$no += 1;
				} else {
					$tmp_no							= $urutan[$tmp_unit];
					$tmp_count = count($tmp_data[$tmp_no]["urutan"]);
					$tmp_count += 1;
					$tmp_data[$tmp_no]["urutan"][$tmp_count] 	= $l["netto"]; 
					$tmp_data[$tmp_no]["jam"][$tmp_count] 		= $tgl_timbangan; 
				}
				
			}
		}  
		
		#print_r($tmp_data);exit;

		FOR ($i=1; $i<$no; $i++) {
			$data[$i]["nama_operator"] 	= ""; 
			$data[$i]["unit"] 				= $tmp_data[$i]["unit"]; 
			$data[$i]["egi"] 				= $tmp_data[$i]["egi"];  
			FOR ($j=1;$j<=3;$j++) {
				$data[$i]["netto_".$j] 		= number_format($tmp_data[$i]["urutan"][$j],0); 
			}  

			$tmp_date_1 = new DateTime($tmp_data[$i]["jam"][1]);
			$tmp_date_2 = new DateTime($tmp_data[$i]["jam"][2]);
			$tmp_date_3 = new DateTime($tmp_data[$i]["jam"][3]);

			if ($tmp_data[$i]["jam"][2] > $tmp_data[$i]["jam"][1]) {
				$tmp_int_1 	= $tmp_date_1->diff($tmp_date_2); 
				$data[$i]["ct_1"] 		= ((strlen($tmp_int_1->h) < 2)?("0$tmp_int_1->h"):$tmp_int_1->h).":".((strlen($tmp_int_1->i) < 2)?("0$tmp_int_1->i"):$tmp_int_1->i); 
			} else { $data[$i]["ct_1"]  = ""; }
															#((trim($mulai_tidur_hari_ini)=='')?"NULL":("'".trim($mulai_tidur_hari_ini)."'"))
			if ($tmp_data[$i]["jam"][3] > $tmp_data[$i]["jam"][2]) {
				$tmp_int_2 	= $tmp_date_2->diff($tmp_date_3); 
				$data[$i]["ct_2"] 		= ((strlen($tmp_int_2->h) < 2)?("0$tmp_int_2->h"):$tmp_int_2->h).":".((strlen($tmp_int_2->i) < 2)?("0$tmp_int_2->i"):$tmp_int_2->i);  
			} else { $data[$i]["ct_2"]  = ""; }
			

			 
			
		}
		echo json_encode($data, JSON_NUMERIC_CHECK); 
	} 
	
	public function index_absensi()
	{ 
			$this->view();
	}
	public function view()
	{					  
		$this->data['title'] 		= "Realtime Payload"; 
		$this->data['js'] 			= 'realtime_payload/js_view';
		$this->data['sview'] 		= 'realtime_payload/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
	public function get_data()
	{					 
		$id 		= $_GET['id']; 
		$rs 		= $this->realtime_payload_model->getAllDataPerDay($id); 

		$no = 0;
		if (count($rs)>0) {
			FOREACH ($rs AS $l) { 
				if (empty($l["nama_operator"])) $l["nama_operator"] = "";
				if (empty($l["unit"])) $l["unit"] = "";
				if (empty($l["egi"])) $l["egi"] = "";
				if (empty($l["shift"])) $l["shift"] = "";

				$data[$no]["nama_operator"] 	= $l["nama_operator"]." (S".$l["shift"].")"; 
				$data[$no]["unit"] 				= $l["unit"]; 
				$data[$no]["egi"] 				= $l["egi"]; 
				$data[$no]["shift"] 			= $l["shift"]; 

				$tonnage_data = $this->realtime_payload_model->getTonnageDataPerDay($l["master_equipment_id"], $l["tgl_payload"], $l["shift"]); 
			 
				$loop = 0; $avg = 0;
				if (count($tonnage_data) > 0) {
					FOREACH ($tonnage_data AS $td) {
						$loop += 1; 
						if (empty($td["netto"]) || $td["netto"] == "undefined") $td["netto"] = ""; 
 
						$data[$no]["netto_".$loop] 		= number_format($td["netto"],0);  
						$avg += $td["netto"]; 
					}
					if ($loop <3 ) {
						$next_loop = $loop + 1;
						FOR ($i = $next_loop ; $i<=3; $i++) {
							$data[$no]["netto_".$i] 	= "";   
							$avg += 0;
						}
					}
					$next_loop = 0;
					$loop = 0;
				}  else {
					FOR ($i = 1 ; $i<=3; $i++) {
						$data[$no]["netto_".$i] 	= "";   
						$avg += 0;
					}
				}
				$no += 1;
			}
		}  
		echo json_encode($data, JSON_NUMERIC_CHECK); 
	} 
}
