<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class antrian extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
      	
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Report Antrian</li>';
 
		$this->load->model("report_entry_data_model");  

		$menu["parent_menu"] 		= "report";
		$menu["sub_menu"] 			= "antrian"; 

		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->report_entry_data_model->user_akses("antrian"); 
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
		$this->data['title'] 		= "Report Data Antrian"; 
		$this->data['js'] 			= 'antrian/js_view';
		$this->data['sview'] 		= 'antrian/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}  
	/*
	public function action()
	{					 
		$this->data['rs'] 			= $this->report_entry_data_model->getAllDataPerPeriode($this->input->post("start"),$this->input->post("end"));
		$this->data['title'] 		= "Report Data Timbangan CPP"; 
		$this->data['js'] 			= 'rpt_timbangan_cpp/js_view';
		$this->data['sview'] 		= 'rpt_timbangan_cpp/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}  
	*/

	public function export_to_excel()
	{  
		if ($this->input->post("station_id") == 1) $station = "CPP"; else $station = "PORT";

		$data 			= $this->report_entry_data_model->getAntrian($this->input->post("station_id"),$this->input->post("tgl"));
	  
		$colnames 			= array("NAMA","UNIT", "DATE","IN","OUT");
		$colfields 			= array("nama","unit", "date_in","time_in","time_out");

		$string_to_export = "";
		
		$string_to_export .= "Report Antrian di ".$station."\t";
		$string_to_export .= "\n";
		
		foreach ($colnames AS $k=>$v) {
			$string_to_export .= $v. "\t";
		} 
		$string_to_export .= "\n";

		foreach ($data AS $key => $value)
		{  
			foreach ($colfields AS $k=>$v) {
				$string_to_export .= $this->_trim_export_string($value[$v])."\t"; 
			}
			$string_to_export .= "\n";
		} 

		// Convert to UTF-16LE and Prepend BOM
		$string_to_export = "\xFF\xFE" .mb_convert_encoding($string_to_export, 'UTF-16LE', 'UTF-8');

		$filename = "export-Antrian-".date("Y-m-d_H:i:s").".xls";

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

	public function _trim_print_string($value)
	{
		$value = str_replace(array("&nbsp;","&amp;","&gt;","&lt;"),array(" ","&",">","<"),$value);

		//If the value has only spaces and nothing more then add the whitespace html character
		if(str_replace(" ","",$value) == "")
			$value = "&nbsp;";

		return strip_tags($value);
	}

	function escape_str($value)
    {
    	return $this->db->escape_str($value);
    }
}
