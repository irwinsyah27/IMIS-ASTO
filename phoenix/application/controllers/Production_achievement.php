<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Production_achievement extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Production Achievement</li>';

		//$this->load->model('proto_model');
		$this->load->model("production_achievement_model");  

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "production_achievement"; 
		$this->data['check_menu']	= $menu;

		$this->data['month']		= array("1" => "Januari", 
										"2" => "Februari", 
										"3" => "Maret", 
										"4" => "April", 
										"5" => "Mei", 
										"6" => "Juni", 
										"7" => "Juli", 
										"8" => "Agustus", 
										"9" => "September", 
										"10" => "Oktober", 
										"11" => "November", 
										"12" => "Desember", 
									);

		# akses level
		$akses 			= $this->production_achievement_model->user_akses("production_achievement");
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
		if ($_POST["txtFilter2"] == "") $_POST["txtFilter2"]  = date("Y");
		if ($_POST["txtFilter1"] == "") $_POST["txtFilter1"]  = date("m");
		

		$tmp["0"]			= $_POST["txtFilter2"];
		$tmp["1"]			= $_POST["txtFilter1"];
		$tmp["2"]			= date("d");
		$tgl_awal 			= $tmp["0"]."-".$tmp["1"]."-01";			# tgl 1 awal bulan


		$tgl 			= $tmp["0"]."-".$tmp["1"]."-".date("d");			# tgl 1 awal bulan
		//echo "tgl Awal : ". $tgl_awal."<br>";

		$tgl_periode_awal	= date("Y-m-d",mktime(0, 0, 0, $tmp["1"]-1, '26',$tmp["0"] ));
		$tgl_periode 		= date("Y-m-d",mktime(0, 0, 0, $tmp["1"], '25',$tmp["0"] ));			# tgl 25 bulan lalu
		$tgl_akhir_bln_lalu = date("Y-m-t",mktime(0, 0, 0, $tmp["1"]-1, $tmp["2"], $tmp["0"] ));	# tgl akhir bulan lalu 
		$tgl_akhir 			= date("Y-m-t",mktime(0, 0, 0, $tmp["1"], $tmp["2"], $tmp["0"] ));		# tgl akhir bulan ini 


		$this->data['total_today']= $this->production_achievement_model->get_total_today($tgl,"2");

		$this->data['total_month_to_today']= $this->production_achievement_model->get_total_month_to_today($tgl_awal, $tgl,"2"); 
		$this->data['total_monthly']= $this->production_achievement_model->get_total_month_to_today($tgl_awal, $tgl_akhir,"2"); 
		$this->data['periodic_to_date']= $this->production_achievement_model->get_total_month_to_today($tgl_periode_awal, $tgl,"2"); 
		$this->data['periodicly']= $this->production_achievement_model->get_total_month_to_today($tgl_periode_awal, $tgl_periode,"2");

		$this->data['grafik_1']= $this->production_achievement_model->get_total_per_hari($tgl_periode_awal, $tgl_akhir_bln_lalu,"2");
		$this->data['grafik_2']= $this->production_achievement_model->get_total_per_hari($tgl_awal, $tgl_periode,"2");


		$this->data['txtFilter1'] 	= $_POST["txtFilter1"]; 
		$this->data['txtFilter2'] 	= $_POST["txtFilter2"]; 
		$this->data['tgl'] 			= $tgl; 
		$this->data['title'] 		= "Production Achievement"; 
		$this->data['js'] 			= 'production_achievement/js_view';
		$this->data['sview'] 		= 'production_achievement/view'; 
		$this->load->view(_TEMPLATE , $this->data); 
	} 
	/*
	public function get_product_achievement()
	{ 
		$tmp["0"]			= $_POST["txtFilter2"];
		$tmp["1"]			= $_POST["txtFilter1"];
		$tgl_awal 			= $tmp["0"]."-".$tmp["1"]."-01";			# tgl 1 awal bulan

		$tgl_periode_awal	= date("Y-m-d",mktime(0, 0, 0, $tmp["1"]-1, '26',$tmp["0"] ));
		$tgl_periode 		= date("Y-m-d",mktime(0, 0, 0, $tmp["1"], '25',$tmp["0"] ));			# tgl 25 bulan lalu
		$tgl_akhir_bln_lalu = date("Y-m-t",mktime(0, 0, 0, $tmp["1"]-1, $tmp["2"], $tmp["0"] ));	# tgl akhir bulan lalu 
		$tgl_akhir 			= date("Y-m-t",mktime(0, 0, 0, $tmp["1"], $tmp["2"], $tmp["0"] ));		# tgl akhir bulan ini 


		$this->data['total_today']= $this->production_achievement_model->get_total_today($tgl,"2");

		$this->data['total_month_to_today']= $this->production_achievement_model->get_total_month_to_today($tgl_awal, $tgl,"2"); 
		$this->data['total_monthly']= $this->production_achievement_model->get_total_month_to_today($tgl_awal, $tgl_akhir,"2"); 
		$this->data['periodic_to_date']= $this->production_achievement_model->get_total_month_to_today($tgl_periode_awal, $tgl,"2"); 
		$this->data['periodicly']= $this->production_achievement_model->get_total_month_to_today($tgl_periode_awal, $tgl_periode,"2");

		$this->data['grafik_1']= $this->production_achievement_model->get_total_per_hari($tgl_periode_awal, $tgl_akhir_bln_lalu,"2");
		$this->data['grafik_2']= $this->production_achievement_model->get_total_per_hari($tgl_awal, $tgl_periode,"2");


		$this->data['tgl'] 			= $tgl; 
		$this->data['title'] 		= "Production Achievement"; 
		$this->data['js'] 			= 'production_achievement/js_view';
		$this->data['sview'] 		= 'production_achievement/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
	*/
}
