<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator_performance extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Operator Performance</li>';

		//$this->load->model('proto_model');
		$this->load->model("operator_performance_model");  

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "operator_performance"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->operator_performance_model->user_akses("operator_performance");
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
		$this->data['title'] 		= "Operator Performance"; 
		$this->data['js'] 			= 'operator_performance/js_view';
		$this->data['sview'] 		= 'operator_performance/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}  
	public function get_data()
	{					    
		$id 		= $_GET['id'];
		$rs 		= $this->operator_performance_model->getAllDataPerPeriode($id);  
		echo json_encode($rs, JSON_NUMERIC_CHECK); 
	} 
	public function download()
	{					    
		$id 		= $this->uri->segment(3,0); ; 
		$data 		= $this->operator_performance_model->getAllDataPerPeriode($id);  
		#print_r($data);exit;


		$colnames 		= array("NAMA","UNIT","FIT TO WORK","FINGER IN","FINGER OUT", "WORKING HOUR","TAB IN","TAB OUT", "AVG CT","OVER SPEED","RITASE","SPO","BPM","JAM KRITIS PENGAWASAN", "JAM KRITIS STOP");
		$colfields 		= array("nama_operator","unit",'label_status_persetujuan',"time_in","time_out","durasi_in","time_in_mancal","time_out_mancal","avg_ct","total_over_speed","ritasi","spo_in","bpm_in","titik_jam_pengawasan","titik_jam_stop");

		$this->operator_performance_model->export_to_excel($colnames,$colfields, $data, $header ,"rpt_operator_performance");  
		/*
		$string_to_export = "";
		if ($header <> "") $string_to_export .= $header."\n\n";
		
		foreach ($colnames AS $k=>$v) {
			$string_to_export .= $v. "\t";
		} 
		$string_to_export .= "\n";

		foreach ($data AS $key => $value)
		{  
			foreach ($colfields AS $k=>$v) {
				if (empty($value[$v])) $value[$v] = ""; 
				$string_to_export .= $this->_trim_export_string($value[$v])."\t"; 
			}
			$string_to_export .= "\n";
		} 
		if ($footer <> "") $string_to_export .= "\n\n".$footer."\n\n";

		// Convert to UTF-16LE and Prepend BOM
		$string_to_export = "\xFF\xFE" .mb_convert_encoding($string_to_export, 'UTF-16LE', 'UTF-8');

		$filename = "rpt_operator_performance_".date("Y-m-d_H:i:s").".xls";

		header('Content-type: application/vnd.ms-excel;charset=UTF-16LE');
		header('Content-Disposition: attachment; filename='.$filename);
		header("Cache-Control: no-cache");
		echo $string_to_export;
		die();
		*/
	} 
	/*
	public function _trim_export_string($value)
	{
		$value = str_replace(array("&nbsp;","&amp;","&gt;","&lt;"),array(" ","&",">","<"),$value);
		return  strip_tags(str_replace(array("\t","\n","\r"),"",$value));
	}
	*/
}
