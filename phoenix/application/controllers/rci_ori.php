<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rci extends CI_Controller {

   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Road Maintenance Index</li>';

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "rci";
		$this->data['check_menu']	= $menu;

		$this->load->model('rci_model');
		$this->load->model('master_employee_model');

		$this->data["list_shift"]  = ["1" => "1", "2"=>"2"];
		$this->data["list_severity"]  = ["Low" => "Low", "Medium"=>"Medium", "High"=>"High" ];

		# akses level
		$akses 			= $this->rci_model->user_akses("rci");
		define('_USER_ACCESS_LEVEL_VIEW', $akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD', $akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE', $akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE', $akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL', '');

   	}

	public function index()
	{
			$this->view();
	}

	public function view()
	{
		$this->data['title'] 		= "List Road Condition Index";
		$this->data['js'] 			= 'rci/js_view';
		$this->data['sview'] 		= 'rci/view';
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->rci_model->get_list_data();
	}

	public function delete($id){
		$rs = $this->rci_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('rci/');
	}

	public function add()
	{
		$this->data['list_karyawan']		= $this->mylib->generate2darray("master_employee" , "nrp","nrp","","nama");
		$this->data['list_lokasi_rci'] 		= $this->mylib->generate2darray("master_lokasi" , "master_lokasi_id","lokasi");
		$this->data['list_problem_road'] 	= $this->RCI_model->getMasterProblemRoad();

		$this->data['title'] 		= "Tambah Data Road Condition Index";
		$this->data['js'] 			= 'rci/js_add';
		$this->data['sview'] 		= 'rci/add';
		$this->load->view(_TEMPLATE , $this->data);
	}


	public function edit($id)
	{
		$this->data['list_karyawan']	= $this->mylib->generate2darray("master_employee" , "nrp","nrp","","nama");
		$this->data['list_lokasi_rci'] 	= $this->mylib->generate2darray("master_lokasi" , "master_lokasi_id","lokasi");
		$this->data['list_problem_road'] 	= $this->RCI_model->getMasterProblemRoad();

		$this->data['rs'] 			= $this->RCI_model->getRowData($id);

		$this->data['title'] 		= "Edit Road Condition Index";
		$this->data['js'] 			= 'rci/js_edit';
		$this->data['sview'] 		= 'rci/edit';
		$this->load->view(_TEMPLATE , $this->data);
	}


	public function edit_data()
	{
			$rs = $this->RCI_model->edit_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('RCI/');
	}

	public function testsubstr($string)
	{
		echo substr($string, 0,1); // string, start, length
	}
}
