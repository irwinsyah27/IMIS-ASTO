<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpt_prajob extends CI_Controller {
 
   	public function __construct() {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Report Data Pra Job</li>';

		//$this->load->model('proto_model');
		$this->load->model("report_entry_data_model");  

		$menu["parent_menu"] 		= "report";
		$menu["sub_menu"] 			= "rpt_prajob"; 
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->report_entry_data_model->user_akses("rpt_prajob");
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
		$this->data['title'] 		= "Report Data Pra Job & Fatique Check"; 
		$this->data['js'] 			= 'rpt_prajob/js_view';
		$this->data['sview'] 		= 'rpt_prajob/view'; 
		$this->load->view(_TEMPLATE , $this->data);
	}   

	public function export_to_excel()
	{ 
		$header = "Report Data Pra Job & Fatique Check\n";
		$header .= "Start\t".$this->input->post("start")."\n";
		$header .= "Stop\t".$this->input->post("end")."\n";

		$data 			= $this->report_entry_data_model->getPrajob($this->input->post("start"),$this->input->post("end")); 

		$colnames 		= array("TGL","NRP", "NAMA","MULAI TIDUR","BANGUN TIDUR","X1","X2","X3","X4","X5","X6","X7","X8","X9","X10","X11","X12","X13","DISETUJUI");
		$colfields 		= array("tanggal_pra_job","nrp", "nama","mulai_tidur_hari_ini","bangun_tidur_hari_ini","apakah_sedang_minum_obat","apakah_sedang_ada_masalah","apakah_siap_bekerja","apakah_mempunyai_apd_yang_sesuai","apakah_dalam_kondisi_fit","apakah_memerlukan_ijin_khusus","apakah_memahami_prosedur","apakah_mempunyai_peralatan_yang_benar","apakah_ada_aktivitas_lain_disekitar_saya","apakah_mengenali_bahaya","apakah_focus","apakah_atasan_mengetahui","apakah_pekerjaan_bisa_dilanjutkan","status_persetujuan");

		$footer = "";
		$footer .= "X1\tApakah anda sedang minum obat yang menyebabkan kantuk ? \n";
		$footer .= "X2\tApakah anda sedang ada masalah yang mempengaruhi konsentrasi ?  \n";
		$footer .= "X3\tApakah anda siap & mampu untuk bekerja ?  \n";
		$footer .= "X4\tApakah saya mempunyai APD yang sesuai \n";
		$footer .= "X5\tApakah saya dalam kondisi fit\n";
		$footer .= "X6\tApakah pekerjaan ini memerlukan ijin kerja khusus \n";
		$footer .= "X7\tApakah saya memahami prosedur \n";
		$footer .= "X8\tApakah saya mempunyai peralatan yang benar \n";
		$footer .= "X9\tApakah ada aktifitas lain disekitar saya \n";
		$footer .= "X10\tApakah saya mengenali, mengendalikan bahaya & resiko \n";
		$footer .= "X11\tApakah saya focus dalam pekerjaan ini \n";
		$footer .= "X12\tApakah atasan saya mengetahui pekerjaan ini \n";
		$footer .= "X13\tApakah pekerjaan ini bisa dilanjutkan \n";

		$this->report_entry_data_model->export_to_excel($colnames,$colfields, $data, $header ,"rpt_prajob",$footer); 
 
	} 
}
