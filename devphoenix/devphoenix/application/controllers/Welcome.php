<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller { 
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Welcome</li>';

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= ""; 
		$this->data['check_menu']	= $menu;

		$this->load->model('daily_absent_model');  
		$this->load->model('master_employee_model');  
		$this->data["list_shift"]  = ["1" => "1", "2"=>"2"]; 
   	} 

	public function index()
	{ 
			$this->view();
	}

	public function view()
	{					
		$this->data['title'] 		= "Selamat Datang";  
		$this->data['sview'] 		= 'welcome'; 
		$this->load->view(_TEMPLATE , $this->data);
	}
 
}
