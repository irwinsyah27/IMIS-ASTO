<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_problem_productivity extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Jenis Problem Productivity</li>';

		$menu["parent_menu"] 		= "master_data";
		$menu["sub_menu"] 			= "master_problem_productivity"; 
		$this->data['check_menu']	= $menu;

		$this->load->model('master_problem_productivity_model'); 
		$this->load->model('master_equipment_model'); 
   	}

	public function index()
	{ 
			$this->view();
	}

	public function view()
	{					
		$this->data['title'] 		= "List Data Jenis Problem Productivity"; 
		$this->data['js'] 			= 'master_problem_productivity/js_view';
		$this->data['sview'] 		= 'master_problem_productivity/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->master_problem_productivity_model->get_list_data();
	}

	public function delete($id){ 
		$rs = $this->master_problem_productivity_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('master_problem_productivity/');
	}

	public function add()
	{					 
		$this->data['title'] 		= "Tambah Data Jenis Problem Productivity"; 
		$this->data['js'] 			= 'master_problem_productivity/js_add';
		$this->data['sview'] 		= 'master_problem_productivity/add'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function add_data()
	{	
		/*				 
		$rules = $this->master_problem_productivity_model->rules; 
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() == TRUE) {
			$data = $this->master_problem_productivity_model->array_from_post(array('grNama','grNIP','grNUPTK','grJabatan','grEmail','grJK','grTpLahir','grTgLahir','grAgama','grThMasuk','grPendidikan','grJurusan','grThLulus','grAlamat','grNPWP','grTelp','grStatus','grTingkat','grPenataran'));
			if(!$id){
				$data['grPswd'] = $this->modAuth->hash($this->input->post('grNIP'));
			}
			*/
			$rs = $this->master_problem_productivity_model->add_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('master_problem_productivity/');
		/*
		} else {
			$this->data['sview'] = 'master_problem_productivity/add';
			$this->load->view(_TEMPLATE , $this->data);
		}	
		*/ 
	}

	public function edit($id)
	{					
		$this->data['list_station'] 	= $this->master_equipment_model->getAllData();
		$this->data['rs'] 			= $this->master_problem_productivity_model->getRowData($id);

		$this->data['title'] 		= "Edit Data Jenis Problem Productivity"; 
		$this->data['js'] 			= 'master_problem_productivity/js_edit';
		$this->data['sview'] 		= 'master_problem_productivity/edit'; 
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
			$rs = $this->master_problem_productivity_model->edit_data();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('master_problem_productivity/');
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
