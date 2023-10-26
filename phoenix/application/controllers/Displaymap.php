<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Displaymap extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
      	
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Report Antrian</li>';
 

		$menu["parent_menu"] 		= "report";
		$menu["sub_menu"] 			= "displaymap"; 

		$this->data['check_menu']	= $menu;
		$this->load->model("displaymap_model");  

		# akses level
		$akses 			= $this->displaymap_model->user_akses("displaymap"); 
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
		$this->data['unit'] = $this->displaymap_model->getdataunit();  

		$this->data['title'] 		= "Report Data Sebaran"; 
		$this->data['js'] 			= 'displaymap/js_view';
		//$this->data['sview'] 		= 'antrian/view'; 
		$this->load->view("displaymap/view" , $this->data);
	}  
	public function getallroutesformap()
	{      
		$json = $this->displaymap_model->getallroutesformap();  
   
	    header('Content-Type: application/json');
	    echo $json;
	} 
	public function getroutes()
	{ 
		$json = $this->displaymap_model->getroutes();  
   	
	    header('Content-Type: application/json');
	    echo $json;
	} 
	public function getrouteformap()
	{      
        $kendaraan 	= trim($this->uri->segment(3,0));
        $start_date = trim($this->uri->segment(4,0));
        $start_time = trim($this->uri->segment(5,0));
        $end_date 	= trim($this->uri->segment(6,0));
        $end_time 	= trim($this->uri->segment(7,0));
        $filter 	= trim($this->uri->segment(8,0));
        
        $start_date_time = $start_date ." ".$start_time.":00";
        $end_date_time 	= $end_date ." ".$end_time.":59";
        //$start_date_time = $start_date;
        //$end_date_time 	= $end_date;
        
 
		$json = $this->displaymap_model->getrouteformap($kendaraan, $start_date_time, $end_date_time, $filter);  
		//$json = $this->displaymap_model->getrouteformap($kendaraan, $start_date_time);
		 
	    header('Content-Type: application/json');
	    echo $json;
	}
	
}
