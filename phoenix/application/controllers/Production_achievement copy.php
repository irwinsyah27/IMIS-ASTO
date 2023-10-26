<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Production_achievement extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

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
   	}

	public function index()
	{ 
			$this->view();
	}

	public function view()
	{					 
		$this->data['title'] 		= "Production Achievement"; 
		$this->data['js'] 			= 'production_achievement/js_option';
		$this->data['sview'] 		= 'production_achievement/option'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 

	public function check()
	{ 
		$tgl = $this->input->post("tgl");
		$tmp = explode("-", $tgl);
		$tgl_awal = $tmp["0"]."-".$tmp["1"]."-01";

		$tgl_periode_awal= date("Y-m-d",mktime(0, 0, 0, $tmp["1"]-1, '26',$tmp["0"] ));
		$tgl_periode = date("Y-m-d",mktime(0, 0, 0, $tmp["1"], '25',$tmp["0"] ));

		$this->data['total_today']= $this->production_achievement_model->get_total_today($tgl);

		$this->data['total_month_to_today']= $this->production_achievement_model->get_total_month_to_today($tgl_awal, $tgl);

		$akhir_tgl_bln_lalu = date("t",mktime(0, 0, 0, $tmp["1"]-1, $tmp["2"], $tmp["0"] ));
		$akhir_bln_lalu = date("m",mktime(0, 0, 0, $tmp["1"]-1, $tmp["2"], $tmp["0"] ));
		$akhir_thn_lalu = date("Y",mktime(0, 0, 0, $tmp["1"]-1, $tmp["2"], $tmp["0"] ));
		$tgl_akhir_bln_lalu = $akhir_thn_lalu."-".$akhir_bln_lalu."-".$akhir_tgl_bln_lalu;

		$akhir_tgl_bln_ini = date("t",mktime(0, 0, 0, $tmp["1"], $tmp["2"], $tmp["0"] ));
		$tgl_akhir = $tmp["0"]."-".$tmp["1"]."-".$akhir_tgl_bln_ini;

		$this->data['total_monthly']= $this->production_achievement_model->get_total_month_to_today($tgl_awal, $tgl_akhir);

		$this->data['periodic_to_date']= $this->production_achievement_model->get_total_month_to_today($tgl_periode_awal, $tgl);
		
		$this->data['periodicly']= $this->production_achievement_model->get_total_month_to_today($tgl_periode_awal, $tgl_periode);

		$this->data['grafik_1']= $this->production_achievement_model->get_total_per_hari($tgl_periode_awal, $tgl_akhir_bln_lalu);
		$this->data['grafik_2']= $this->production_achievement_model->get_total_per_hari($tgl_awal, $tgl_periode);


		$this->data['tgl'] 			= $tgl; 
		$this->data['title'] 		= "Production Achievement"; 
		$this->data['js'] 			= 'production_achievement/js_view';
		$this->data['sview'] 		= 'production_achievement/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
}
