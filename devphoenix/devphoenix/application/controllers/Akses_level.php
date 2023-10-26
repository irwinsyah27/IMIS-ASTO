<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akses_level extends CI_Controller {
 	private $folder_name			= "akses_level";
 	private $table_name 			= "user";
 	private $primary_key 			= "user_id";
 	private $label_modul			= "Daftar User";
 	private $model_name				= "akses_level_model";
 	private $label_list_data		= "List Data";
 	private $label_add_data			= "Add Data";
 	private $label_sukses_dihapus 	= "Data berhasil dihapus";
 	private $label_gagal_dihapus 	= "Gagal menghapus data";
 	private $label_sukses_ditambah 	= "Data berhasil ditambah";
 	private $label_gagal_ditambah 	= "Data gagal ditambah";
 
   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Akses Level</li>';
 

      	$this->data["folder_name"]	= $this->folder_name;
		$this->load->model( $this->model_name );


		$menu["parent_menu"] 		= "master_data";
		$menu["sub_menu"] 			= "akses_level"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->akses_level_model->user_akses("akses_level");
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
		$this->data['title'] 		= "List Data Akses Level"; 
		$this->data['js'] 			= 'akses_level/js_view';
		$this->data['sview'] 		= 'akses_level/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->akses_level_model->get_list_data();
	}

	public function delete($id){  
		$rs = $this->{$this->model_name}->db_delete($this->table_name, array($this->primary_key => $id)); 
		if($rs){
			$rs1 = $this->{$this->model_name}->db_delete("user_akses", array("user_id" => $id)); 

			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('akses_level/');
	}

	public function add()
	{					 
		$this->data['title'] 		= "Tambah Data Akses Level"; 
		$this->data['js'] 			= 'akses_level/js_add';
		$this->data['sview'] 		= 'akses_level/add'; 
		$this->load->view(_TEMPLATE , $this->data);
	}


	public function add_data()
	{	 
		$rs = $this->akses_level_model->add_data();
		if($rs){
			$msg = 'Data berhasil disimpan.';
			$stats = '1';
		} else {
			$msg = 'Gagal menyimpan data. Pastikan username belum ada yang memakai';
			$stats = '0';
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('akses_level/'); 
	}

	public function edit($id)
	{					 
		$this->data['rs_user'] 		= $this->akses_level_model->getRowDataUser($id);
		$rs 	= $this->akses_level_model->getRowDataUserAkses($id);
		if (count($rs) > 0) {
			FOREACH ($rs AS $r) {
				$dt[$r["user_menu_id"]]["view"] = $r["view"];
				$dt[$r["user_menu_id"]]["add"] = $r["add"];
				$dt[$r["user_menu_id"]]["edit"] = $r["edit"];
				$dt[$r["user_menu_id"]]["del"] = $r["del"]; ;
				$dt[$r["user_menu_id"]]["import"] = $r["import"]; ;
				$dt[$r["user_menu_id"]]["eksport"] = $r["eksport"]; ;
			}
		}
		$this->data['rs_akses'] 		= $dt;

		$this->data['title'] 		= "Edit Data Akses Level"; 
		$this->data['js'] 			= 'akses_level/js_edit';
		$this->data['sview'] 		= 'akses_level/edit'; 
		$this->load->view(_TEMPLATE , $this->data);
	}


	public function edit_data()
	{	 
		$rs = $this->akses_level_model->edit_data();
		if($rs){
			$msg = 'Data berhasil disimpan';
			$stats = '1';
		} else {
			$msg = 'Gagal menyimpan data';
			$stats = '0';
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('akses_level/');  
	}
 
}
