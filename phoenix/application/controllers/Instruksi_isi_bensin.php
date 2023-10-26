<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instruksi_isi_bensin extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Instruksi Pengisian Bensin</li>';

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "instruksi_isi_bensin"; 
		$this->data['check_menu']	= $menu;

		$this->load->model('instruksi_isi_bensin_model');
		$this->load->model('master_equipment_model');

		# akses level
		$akses 			= $this->instruksi_isi_bensin_model->user_akses("instruksi_isi_bensin");
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
		$this->data['title'] 		= "List Data Instruksi Pengisian Solar"; 
		$this->data['js'] 			= 'instruksi_isi_bensin/js_view';
		$this->data['sview'] 		= 'instruksi_isi_bensin/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->instruksi_isi_bensin_model->get_list_data();
	}
	public function getCycleTime(){
		#echo "eq id" . $_POST["equipment_id"];
		$rs = $this->instruksi_isi_bensin_model->getCycleTime($_POST["equipment_id"]);
		$total_cycle_time = $rs["total_cycle_time"]; 

		$rs = $this->instruksi_isi_bensin_model->getSettingCycleTime();
		$standart_cycle_time = $rs["cycle_time_standart"];
		$standart_solar = $rs["bensin_dalam_cycle_standart"];

		$solar_terpakai = ($total_cycle_time / $standart_cycle_time) * $standart_solar;
		$solar_tersedia = $standart_solar - $solar_terpakai;

		$pengisian = $standart_solar - $solar_tersedia;
		$data["cycle_time"] = $total_cycle_time;
		$data["pengisian"] = $pengisian; 
		echo json_encode($data);
	}

	public function delete($id){ 
		$rs = $this->instruksi_isi_bensin_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('instruksi_isi_bensin/');
	}

	public function add()
	{					
		$this->data['list_unit'] 	= $this->master_equipment_model->getAllData();

		$this->data['title'] 		= "Tambah Data Instruksi Pengisian Solar"; 
		$this->data['js'] 			= 'instruksi_isi_bensin/js_add';
		$this->data['sview'] 		= 'instruksi_isi_bensin/add'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function add_data()
	{	 
			$rs = $this->instruksi_isi_bensin_model->add_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Data gagal disimpan';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('instruksi_isi_bensin/add'); 
	}

	public function edit($id)
	{					
		$this->data['list_unit'] 	= $this->master_equipment_model->getAllData();
		$this->data['rs'] 			= $this->instruksi_isi_bensin_model->getRowData($id);

		$this->data['title'] 		= "Edit Data Instruksi Pengisian Solar"; 
		$this->data['js'] 			= 'instruksi_isi_bensin/js_edit';
		$this->data['sview'] 		= 'instruksi_isi_bensin/edit'; 
		$this->load->view(_TEMPLATE , $this->data);
	}


	public function edit_data()
	{	 
			$rs = $this->instruksi_isi_bensin_model->edit_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('instruksi_isi_bensin/'); 
	} 
}
