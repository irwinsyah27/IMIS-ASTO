<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_update_track extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Leadtime Daily Check & PS</li>';

		//$this->load->model('proto_model');
		$this->load->model("test_update_track_model");  

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "leadtime_daily_check"; 
		$this->data['check_menu']	= $menu;
   	}

	public function index()
	{ 
			$this->view();
	}

	public function view()
	{					 
		$this->data['rs'] 			= $this->test_update_track_model->update_table_current_location();
		$this->data['title'] 		= "Update Table Current Unit Location"; 
		$this->data['js'] 			= 'test_update_track/js_view';
		$this->data['sview'] 		= 'test_update_track/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
	public function update_data()
	{					 
		$rs 		= $this->test_update_track_model->update_table_current_location();  
		echo "OK updated";
	} 
}
