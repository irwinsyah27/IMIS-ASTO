<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proto extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Proto</li>';

		$this->load->model('proto_model');
   	}

	public function index()
	{ 
			$this->view();
	}

	public function view()
	{					
		$this->data['title'] 		= "List Data Proto"; 
		$this->data['js'] 	= 'proto/js_view';
		$this->data['sview'] 		= 'proto/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->proto_model->get_list_data();
	}

	public function delete($id){ 
		$rs = $this->proto_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('proto/');
	}

	public function add()
	{					
		$this->data['title'] 		= "Tambah Data Proto"; 
		$this->data['js'] 	= 'proto/js_add';
		$this->data['sview'] 		= 'proto/add'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function add_data()
	{					 
		$rules = $this->proto_model->rules; 
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() == TRUE) {
			$data = $this->proto_model->array_from_post(array('grNama','grNIP','grNUPTK','grJabatan','grEmail','grJK','grTpLahir','grTgLahir','grAgama','grThMasuk','grPendidikan','grJurusan','grThLulus','grAlamat','grNPWP','grTelp','grStatus','grTingkat','grPenataran'));
			if(!$id){
				$data['grPswd'] = $this->modAuth->hash($this->input->post('grNIP'));
			}
			$rs = $this->modGuru->save($data, $id);
			if($rs){
				$msg = 'Data Guru berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Gagal menyimpan data Guru';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('proto/');
		} else {
			$this->data['sview'] = 'proto/add';
			$this->load->view(_TEMPLATE , $this->data);
		}	 
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
