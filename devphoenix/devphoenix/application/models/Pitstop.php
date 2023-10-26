<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pitstop extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Pitstop</li>';

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "pitstop"; 
		$this->data['check_menu']	= $menu;

		$this->load->model('pitstop_model');
		$this->load->model('master_equipment_model');
		$this->load->model('sync_station_model');

		# akses level
		$akses 			= $this->pitstop_model->user_akses("Pitstop");
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
		$this->data['title'] 		= "List Data Pitstop"; 
		$this->data['js'] 			= 'pitstop/js_view';
		$this->data['sview'] 		= 'pitstop/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->pitstop_model->get_list_data();
	}

	public function delete($id){ 
		$rs = $this->pitstop_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('pitstop/');
	}

	public function add()
	{					
		$this->data['list_unit'] 	= $this->master_equipment_model->getAllData();
		$this->data['list_station'] = $this->sync_station_model->getAllDataStation();

		$this->data['title'] 		= "Tambah Data Pitstop"; 
		$this->data['js'] 			= 'pitstop/js_add';
		$this->data['sview'] 		= 'pitstop/add'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function add_data()
	{	
		/*				 
		$rules = $this->pitstop_model->rules; 
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() == TRUE) {
			$data = $this->pitstop_model->array_from_post(array('grNama','grNIP','grNUPTK','grJabatan','grEmail','grJK','grTpLahir','grTgLahir','grAgama','grThMasuk','grPendidikan','grJurusan','grThLulus','grAlamat','grNPWP','grTelp','grStatus','grTingkat','grPenataran'));
			if(!$id){
				$data['grPswd'] = $this->modAuth->hash($this->input->post('grNIP'));
			}
			*/
			$rs = $this->pitstop_model->add_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Data gagal disimpan';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('pitstop/');
		/*
		} else {
			$this->data['sview'] = 'pitstop/add';
			$this->load->view(_TEMPLATE , $this->data);
		}	
		*/ 
	}

	public function edit($id)
	{					
		$this->data['list_unit'] 	= $this->master_equipment_model->getAllData();
		$this->data['list_station'] = $this->sync_station_model->getAllDataStation();

		$this->data['rs'] 			= $this->pitstop_model->getRowData($id);

		$this->data['title'] 		= "Update Data Pitstop"; 
		$this->data['js'] 			= 'pitstop/js_update';
		$this->data['sview'] 		= 'pitstop/update'; 
		$this->load->view(_TEMPLATE , $this->data);
	}
	public function update_data()
	{	
		/*				 
		$rules = $this->pitstop_model->rules; 
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() == TRUE) {
			$data = $this->pitstop_model->array_from_post(array('grNama','grNIP','grNUPTK','grJabatan','grEmail','grJK','grTpLahir','grTgLahir','grAgama','grThMasuk','grPendidikan','grJurusan','grThLulus','grAlamat','grNPWP','grTelp','grStatus','grTingkat','grPenataran'));
			if(!$id){
				$data['grPswd'] = $this->modAuth->hash($this->input->post('grNIP'));
			}
			*/
			$rs = $this->pitstop_model->update_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Data gagal disimpan';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('pitstop/');
		/*
		} else {
			$this->data['sview'] = 'pitstop/add';
			$this->load->view(_TEMPLATE , $this->data);
		}	
		*/ 
	}
	/*
	public function truncate(){
		$this->db->where('grID',$id);
		$rs = $this->db->delete('guru');
		if($rs){
			$msg = 'Data Guru berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data Guru.';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('prot');
	}
	*/
}
