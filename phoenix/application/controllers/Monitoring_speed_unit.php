<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_speed_unit extends CI_Controller {

   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Monitoring Speed Unit</li>';

		//$this->load->model('proto_model');
		$this->load->model("monitoring_speed_unit_model");

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "monitoring_speed_unit";
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->monitoring_speed_unit_model->user_akses("monitoring_speed_unit");
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
		$this->data['title'] 		= "Realtime Monitoring Speed Unit";
		$this->data['js'] 			= 'monitoring_speed_unit/js_view';
		$this->data['sview'] 		= 'monitoring_speed_unit/view';
		$this->load->view(_TEMPLATE , $this->data);
	}
}
