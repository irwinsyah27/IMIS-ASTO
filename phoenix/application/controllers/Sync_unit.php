<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_unit extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Device - Unit</li>';

		$menu["parent_menu"] 		= "master_data";
		$menu["sub_menu"] 			= "sync_unit"; 
		$this->data['check_menu']	= $menu;

		$this->load->model('sync_unit_model'); 
		$this->load->model('master_equipment_model'); 
   	}

	public function index()
	{ 
			$this->view();
	}

	public function view()
	{					
		$this->data['title'] 		= "List Data Device - Unit"; 
		$this->data['js'] 			= 'sync_unit/js_view';
		$this->data['sview'] 		= 'sync_unit/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->sync_unit_model->get_list_data();
	}

	public function delete($id){ 
		$rs = $this->sync_unit_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('sync_unit/');
	}

	public function add()
	{					
		#$this->data['list_unit'] 	= $this->master_equipment_model->getAllData();

		$this->data['title'] 		= "Tambah Data Device - Unit"; 
		$this->data['js'] 			= 'sync_unit/js_add';
		$this->data['sview'] 		= 'sync_unit/add'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function add_data()
	{	
		/*				 
		$rules = $this->sync_unit_model->rules; 
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() == TRUE) {
			$data = $this->sync_unit_model->array_from_post(array('grNama','grNIP','grNUPTK','grJabatan','grEmail','grJK','grTpLahir','grTgLahir','grAgama','grThMasuk','grPendidikan','grJurusan','grThLulus','grAlamat','grNPWP','grTelp','grStatus','grTingkat','grPenataran'));
			if(!$id){
				$data['grPswd'] = $this->modAuth->hash($this->input->post('grNIP'));
			}
			*/
			$rs = $this->sync_unit_model->add_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('sync_unit/');
		/*
		} else {
			$this->data['sview'] = 'sync_unit/add';
			$this->load->view(_TEMPLATE , $this->data);
		}	
		*/ 
	}

	public function edit($id)
	{					
		$this->data['list_unit'] 	= $this->master_equipment_model->getAllData();
		$this->data['rs'] 			= $this->sync_unit_model->getRowData($id);

		$this->data['title'] 		= "Edit Data Device - Unit"; 
		$this->data['js'] 			= 'sync_unit/js_edit';
		$this->data['sview'] 		= 'sync_unit/edit'; 
		$this->load->view(_TEMPLATE , $this->data);
	}


	public function edit_data()
	{	
		/*				 
		$rules = $this->timbangan_cpp_model->rules; 
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() == TRUE) {
			$data = $this->timbangan_cpp_model->array_from_post(array('grNama','grNIP','grNUPTK','grJabatan','grEmail','grJK','grTpLahir','grTgLahir','grAgama','grThMasuk','grPendidikan','grJurusan','grThLulus','grAlamat','grNPWP','grTelp','grStatus','grTingkat','grPenataran'));
			if(!$id){
				$data['grPswd'] = $this->modAuth->hash($this->input->post('grNIP'));
			}
			*/
			$rs = $this->sync_unit_model->edit_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('sync_unit/');
		/*
		} else {
			$this->data['sview'] = 'timbangan_cpp/add';
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
