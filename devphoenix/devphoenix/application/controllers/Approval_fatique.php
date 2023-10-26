<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval_fatique extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Approval Fatique</li>';

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "approval_fatique"; 
		$this->data['check_menu']	= $menu;

		$this->load->model('approval_fatique_model'); 

		# akses level
		$akses 			= $this->approval_fatique_model->user_akses("approval_fatique");
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',''); 
   	}

	public function index()
	{ 	 
			$this->antrian();
	}
 


	public function edit()
	{				 
        $id = $this->uri->segment(3,0);
        $status = $this->uri->segment(4,0);

		$data = array( 
			'status_persetujuan' 	=> $status,  
			'approval_by' 			=> $_SESSION["username"],  
			'date_approval' 		=> date("Y-m-d H:i")
		);
		$where = "pra_job_id=".$id;
	
		$this->db->update('pra_job', $data, $where);

        
		redirect('approval_fatique/');
	} 

	public function antrian()
	{					  
		if (empty($_POST["terminals_id"])) $_POST["terminals_id"] = "";

		$this->data['list_zona'] 		= $this->approval_fatique_model->list_zona(); 
		$this->data['list_terminal'] 	= $this->approval_fatique_model->list_terminal(); 
		$this->data['rs'] 				= $this->approval_fatique_model->antrian_instruksi($_POST["terminals_id"]); 

		$this->data['title'] 		= "Daftar Antrian Approval Fatique :: ". date("d M Y"); 
		$this->data['js'] 			= 'approval_fatique/js_view_antrian';
		$this->data['sview'] 		= 'approval_fatique/view_antrian'; 
		$this->load->view(_TEMPLATE , $this->data);
	} 
	public function get_data_antrian()
	{					  
		if (empty($_POST["terminals_id"])) $_POST["terminals_id"] = "";

		$rs 			= $this->approval_fatique_model->antrian_instruksi($_POST["terminals_id"]); 

		$list_data 		= '';

												$no = 0;
												if (count($rs)>0) {
													FOREACH ($rs AS $l) {  
														$status_fatique 	= "";
														$bg_fatique 		= ""; 
														$bg_warna 			= "";
														$status_merah 		= "";  

	 													if ($l["apakah_sedang_minum_obat"]	=="Y") { 
	 														$status_merah .= "Minum obat, ";  
	 													}  
	 													if ($l["apakah_sedang_ada_masalah"]	=="Y") { 
	 														if ($status_merah <> "") $status_merah .= ",<br>";
	 														$status_merah .= "Ada masalah ";  
	 													} 
	 													if ($l["apakah_siap_bekerja"]		=="T") { 
	 														if ($status_merah <> "") $status_merah .= ",<br>";
	 														$status_merah .= "Tidak siap bekerja";  
	 													}   

														if ($l["rekomendasi"] == 3)$bg_fatique = "bgcolor=green";
														if ($l["rekomendasi"] == 2)$bg_fatique = "bgcolor=yellow";
														if ($l["rekomendasi"] == 1)$bg_fatique = "bgcolor=red";

	 													if ($status_merah <> "") $bg_warna = "bgcolor=red";

												$list_data .= ' 
												<tr> 
													<td>'.$l["nrp"].'</td> 
													<td>'.$l["nama"].'</td>
													<td>'.$l["tanggal_pra_job"].'</td>
													<td>'.$l["shift"].'</td>
													<td>'.$l["lama_tdr_kemarin"].'</td>
													<td>'.$l["lama_tdr_sekarang"].'</td>
													<td '.$bg_fatique.'>'.$l["label_rekomendasi"].'</td> 
													<td '.$bg_warna.'>'.$status_merah.'</td>
													<td><a href="'. _URL.'approval_fatique/edit/'.$l["pra_job_id"].'/3">Disetujui</a>
													<br><br>
													<a href="'. _URL.'approval_fatique/edit/'.$l["pra_job_id"].'/2">Butuh pengawasan </a>
													<br><br>
													<a href="'. _URL.'approval_fatique/edit/'.$l["pra_job_id"].'/1">Tidak Boleh Bekerja</a> </td>'; 
													 
												$list_data .= '	 
												</tr> 
												'; 	
													}
												} 
												$list_data .= ' 
											';
		echo $list_data;
	}
}
