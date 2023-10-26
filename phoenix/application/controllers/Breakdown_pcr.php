<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Breakdown_pcr extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Breakdown PCR</li>';

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "breakdown_pcr"; 
		$this->data['check_menu']	= $menu;

		$this->load->model('breakdown_model');
		$this->load->model('master_equipment_model'); 

		# akses level
		$akses 			= $this->breakdown_model->user_akses("breakdown_pcr");
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
			$this->antrian();
	} 

	public function view()
	{					
		$this->data['title'] 		= "List Daily Breakdown Report"; 
		$this->data['js'] 			= 'breakdown_pcr/js_view';
		$this->data['sview'] 		= 'breakdown_pcr/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}
	public function antrian()
	{					
		$this->data['list_master_alokasi'] 	= $this->breakdown_model->getMasterAlokasi();
		$this->data['list_master_breakdown'] 	= $this->breakdown_model->getMasterBreakdown2();

		$this->data['rs'] 			= $this->breakdown_model->antrian_instruksi(); 

		$this->data['title'] 		= "Daily Breakdown Report"; 
		$this->data['js'] 			= 'breakdown_pcr/js_view_antrian';
		$this->data['sview'] 		= 'breakdown_pcr/view_antrian'; 
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data(){
		$this->breakdown_model->get_list_data_pcr_all();
	}
   

	public function edit($id)
	{					
		$this->data['list_unit'] 		= $this->master_equipment_model->getAllData(); 
		$this->data['list_breakdown'] 	= $this->breakdown_model->getMasterBreakdown();
 
		$this->data['list_lokasi_breakdown'] 	= $this->mylib->generate2darray("master_lokasi" , "master_lokasi_id","lokasi"); 
		$this->data['list_status_breakdown']	= $this->mylib->generate2darray("status_breakdown" , "status_breakdown_id","status_breakdown");
		$this->data['list_kriteria_komponen']	= $this->breakdown_model->gelAllDataTable(); 

		$this->data['rs'] 			= $this->breakdown_model->getRowData($id);

		$this->data['title'] 		= "Update Data Daily Breakdown Report"; 
		$this->data['js'] 			= 'breakdown_pcr/js_update';
		$this->data['sview'] 		= 'breakdown_pcr/update'; 
		$this->load->view(_TEMPLATE , $this->data);
	}
	public function update_data()
	{	 
			$rs = $this->breakdown_model->update_data_pcr();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Data gagal disimpan';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('breakdown_pcr/antrian'); 
	} 

	public function update($id)
	{					
		$this->data['list_unit'] 		= $this->master_equipment_model->getAllData(); 
		$this->data['list_breakdown'] 	= $this->breakdown_model->getMasterBreakdown();
 
		$this->data['list_lokasi_breakdown'] 	= $this->mylib->generate2darray("master_lokasi" , "master_lokasi_id","lokasi"); 
		$this->data['list_status_breakdown']	= $this->mylib->generate2darray("status_breakdown" , "status_breakdown_id","status_breakdown");
		$this->data['list_kriteria_komponen']	= $this->breakdown_model->gelAllDataTable(); 

		$this->data['rs'] 			= $this->breakdown_model->getRowData($id);

		$this->data['title'] 		= "Update Data Daily Breakdown Report"; 
		$this->data['js'] 			= 'breakdown_pcr/js_update_tanpa_close';
		$this->data['sview'] 		= 'breakdown_pcr/update_tanpa_close'; 
		$this->load->view(_TEMPLATE , $this->data);
	}
	public function update_data_tanpa_close()
	{	 
			$rs = $this->breakdown_model->update_data_pcr_tanpa_close();
			if($rs){
				$msg = 'Data berhasil disimpan';
				$stats = '1';
			} else {
				$msg = 'Data gagal disimpan';
				$stats = '0';
			}
			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('breakdown_pcr/antrian'); 
	} 


	public function get_data_antrian()
	{
		if (empty($_GET["type"])) $_GET["type"] = "";
		if (empty($_GET["breakdown"])) $_GET["breakdown"] = "";
		$rs 			= $this->breakdown_model->antrian_instruksi($_GET["type"],  $_GET["breakdown"]); 

		echo json_encode($rs, JSON_NUMERIC_CHECK);  
	} 
	public function delete($id){ 
		$rs = $this->breakdown_model->delete($id);
		if($rs){
			$msg = 'Data berhasil dihapus';
			$stats = '1';
		} else {
			$msg = 'Gagal menghapus data';
			$stats = '0';				
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('breakdown_pcr/');
	}
	public function eksport_action() 
	{	 
		$header = "Report Data Breakdown\n";
		$header .= "Start\t".$this->input->post("start")."\n";
		$header .= "Stop\t".$this->input->post("end")."\n";

		//$data 			= $this->breakdown_model->getDataPerDate($this->input->post("start"),$this->input->post("end"));

		$data1 			= $this->breakdown_model->getDataPerDate($this->input->post("start"),$this->input->post("end"));
		$data2			= $this->breakdown_model->getDataOpen($this->input->post("start"));
	     
	    $data = array_merge($data1, $data2); 
	     

		$colnames 		= array("UNIT","TYPE", "JENIS B/D","LOKASI B/D","HM UNIT","KM UNIT","DATE IN","TIME IN","DATE OUT","TIME OUT","DOWNTIME","KODE KRITERIA","KRITERIA KOMPONEN","PROBLEM","TINDAKAN","NO WO","DATE READY","TIME READY");
		$colfields 		= array("new_eq_num","alokasi", "kode","lokasi","hm","km","date_in","time_in","date_out","time_out","durasi","kode_kriteria","kriteria_komponen","diagnosa","tindakan","no_wo","date_ready","time_ready");

		$this->breakdown_model->export_to_excel($colnames,$colfields, $data, $header ,"rpt_breakdown"); 


	}  
}
