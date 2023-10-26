<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller { 
	public function __construct()
	{
		parent::__construct();   
		
		$this->load->model("login_model");  
	}
	public function index()
	{          
		$this->load->view(_TEMPLATE_LOGIN.'login');  
	} 
	public function auth()
	{ 
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('userpasswd', 'Password', 'required');
		
		if ($this->form_validation->run() == true) {
			$identity = $this->input->post("username");
			$password = $this->input->post("userpasswd");
			$remember = $this->input->post("remember_me");
			$rs 	  = $this->login_model->auth($this->input->post("username"), $this->input->post("userpasswd"));
			 
			if (isset($rs["user_id"])) {   
				/*
				$rs_level = $this->login_model->akses_level($rs["user_id"]);
				if (count($rs_level) > 0) {
					FOREACH ($rs_level AS $r) {  
						$this->session->set_userdata($menu[$r["module_name"]]["view"], $r["view"]);
						$this->session->set_userdata($menu[$r["module_name"]]["add"], $r["add"]);
						$this->session->set_userdata($menu[$r["module_name"]]["del"], $r["dell"]);
						$this->session->set_userdata($menu[$r["module_name"]]["edit"], $r["edit"]); 
					}
				}
				*/

				$this->session->set_userdata('id', $rs["user_id"]); 
				$this->session->set_userdata('username', $rs["username"]); 
				$this->session->set_userdata('nama', $rs["nama"]);

				redirect('welcome','refresh');
			} else { 
				$this->session->set_flashdata('message', '<center><font color=red>Username/password salah</font></center><br>');
				redirect('login/');
			} 
		} else {
			redirect('login');
		}
	} 
	public function logout()
	{	    
		unset($_SESSION['id'] ,$_SESSION['username'] ,$_SESSION['nama']);
		$msg 		= "sukses logout";
		$stats 		= '1'; 

		//$this->session->set_flashdata('msg',$msg);
		//$this->session->set_flashdata('stats',$stats);
		redirect('login/');
	}   
	/*
	public function create_user()
	{
		$username = 'Aldo Geovanny';
		$password = 'aldo1945';
		$email = 'aldo.geovanny@wunderman.com';
		$additional_data = array(
				'first_name' => 'Aldo',
				'last_name' => 'Geovanny',
		);
		$group = array('1'); // Sets user to admin.
		
		$this->ion_auth->register($username, $password, $email, $additional_data, $group);

		$username = 'Wedha Anggandhie';
		$password = 'wedha1908';
		$email = 'wedha.anggandhie@wunderman.com';
		$additional_data = array(
				'first_name' => 'Wedha',
				'last_name' => 'Anggandhie',
		);
		$group = array('1'); // Sets user to admin.
		
		$this->ion_auth->register($username, $password, $email, $additional_data, $group);
	}  
	*/
	
	
	
}
