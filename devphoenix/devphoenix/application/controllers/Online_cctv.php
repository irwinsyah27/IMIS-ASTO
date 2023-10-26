<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Online_cctv extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Online CCTV</li>';

		//$this->load->model('proto_model');
		$this->load->model("online_cctv_model");  

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "online_cctv"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->online_cctv_model->user_akses("online_cctv");
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
		$this->data['rs'] 			= $this->online_cctv_model->getAllData();
		$this->data['title'] 		= "Online CCTV"; 
		$this->data['js'] 			= 'online_cctv/js_view';
		$this->data['sview'] 		= 'online_cctv/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}  
}
