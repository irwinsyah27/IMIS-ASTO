<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fatigue_monitor extends CI_Controller {

   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Monitoring Fatigue</li>';

		//$this->load->model('proto_model');
		$this->load->model("fatigue_monitor_model");

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "fatigue_monitor";
		$this->data['check_menu']	= $menu;

		# akses level
		$akses = $this->fatigue_monitor_model->user_akses("fatigue_monitor");
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

	public function get_data()
	{
		$this->fatigue_monitor_model->get_list_data();
	}

	public function view()
	{
		$this->data['list_nrp'] 	= $this->fatigue_monitor_model->getGl(); 
		$this->data['list_lokasi'] 	= $this->fatigue_monitor_model->getLokasi(); 
		$this->data['title'] 		= "Realtime Monitoring Fatigue";
		$this->data['js'] 			= 'fatigue_monitor/js_view';
		$this->data['sview'] 		= 'fatigue_monitor/view';
		$this->load->view(_TEMPLATE , $this->data);
	}
}
