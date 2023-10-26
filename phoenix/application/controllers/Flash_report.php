<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Flash_report extends CI_Controller {

   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Flash Report</li>';

		$menu["parent_menu"] 		= "master_data";
		$menu["sub_menu"] 			= "flash_report"; 
		$this->data['check_menu']	= $menu;

		$this->load->model('flash_report_model');  

		# akses level
		$akses = $this->flash_report_model->user_akses("flash_report");
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',''); 
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);
   	}

	public function index()
	{
		$this->view();
	}

	public function view()
	{
		$this->data['title'] 		= "Flash Report";
		$this->data['js'] 		= 'flash_report/js_view';
    	        $this->data['sview'] 		= 'flash_report/view';
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function import_action() {
		$config['upload_path'] = './assets/img-report/';
		$config['allowed_types'] = 'jpg';
		$config['file_name'] = 'report';
		$config['overwrite'] = TRUE;
		$config['encrypt_name'] = FALSE;
		$config['remove_spaces'] = TRUE;
		if (!is_dir($config['upload_path']) ) die("THE UPLOAD DIRECTORY DOES NOT EXIST");
		$this->load->library('upload', $config);

		if (! $this->upload->do_upload("userfile")) {
			
			$msg = "Gambar gagal di upload : " . $this->upload->display_errors();
			$stats = '0';

		} else {
			$msg 	= 'Gambar berhasil diupload !';
			$stats 	= '1';
		}

		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('flash_report/');					

	}
}