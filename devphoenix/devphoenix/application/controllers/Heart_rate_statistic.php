<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Heart_rate_statistic extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Heart Rate Statistic</li>';

		//$this->load->model('proto_model');
		$this->load->model("heart_rate_statistic_model");  

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "heart_rate_statistic"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->heart_rate_statistic_model->user_akses("heart_rate_statistic");
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
		$this->data['title'] 		= "Heart Rate Statistic"; 
		$this->data['js'] 			= 'heart_rate_statistic/js_option';
		$this->data['sview'] 		= 'heart_rate_statistic/option'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 

	public function check()
	{ 
		$tgl = $this->input->post("tgl"); 
		$this->data['rs']= $this->heart_rate_statistic_model->get_statistic_per_day($tgl);

 
		$this->data['title'] 		= "Heart Rate Statistic"; 
		$this->data['js'] 			= 'heart_rate_statistic/js_view';
		$this->data['sview'] 		= 'heart_rate_statistic/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
}
