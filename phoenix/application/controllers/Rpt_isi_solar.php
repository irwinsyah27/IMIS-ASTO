<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpt_isi_solar extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Report Pengisian Solar</li>';
 
		$this->load->model("rpt_isi_solar_model");  

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "rpt_isi_solar"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->rpt_isi_solar_model->user_akses("rpt_isi_solar");

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
		$this->data['title'] 		= "Report Pengisian Solar"; 
		$this->data['js'] 			= 'rpt_isi_solar/js_option';
		$this->data['sview'] 		= 'rpt_isi_solar/option'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 

	public function check()
	{					 
		$tgl = $this->input->post("tgl");

		$this->data['tgl']			= $this->input->post("tgl");
		$this->data['list_egi']		= $this->rpt_isi_solar_model->list_egi();
		$this->data['rs']			= $this->rpt_isi_solar_model->get_total_pengisian_per_egi($tgl); 

		$this->data['title'] 		= "Report Pengisian Solar"; 
		$this->data['js'] 			= 'rpt_isi_solar/js_view';
		$this->data['sview'] 		= 'rpt_isi_solar/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
}
