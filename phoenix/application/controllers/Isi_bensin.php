<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Isi_bensin extends CI_Controller {

   	public function __construct()
	{
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Instruksi Pengisian Solar</li>';

		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "isi_bensin";
		$this->data['check_menu']	= $menu;

		$this->load->model('isi_bensin_model');
		$this->load->model('master_equipment_model');
		$this->load->model('operator_model');

		# akses level
		$akses = $this->isi_bensin_model->user_akses("isi_bensin");
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
		$this->view();
	}

	public function view()
	{
		$this->data['title'] 		= "List Data Pengisian Solar";
		$this->data['js'] 			= 'isi_bensin/js_view';
		$this->data['sview'] 		= 'isi_bensin/view';
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data()
	{
		$this->isi_bensin_model->get_list_data();
	}

	public function add()
	{
		if ($post = $this->input->post())
		{
			$data = [
				'nrp' 				=> trim($post['nrp']),
				'fuel_tank_id' 		=> trim($post['fuel_tank_id']),
				'equipment_id' 		=> trim($post['equipment_id']),
				'shift' 			=> trim($post['shift']),
				'total_liter' 		=> trim($post['total_liter']),
				'total_realisasi' 	=> trim($post['total_realisasi']),
				'date_fill' 		=> trim($post['date_fill']),
				'time_fill_start' 	=> trim($post['time_fill_start']),
				'time_fill_end' 	=> trim($post['time_fill_end']),
				'hm' 				=> trim($post['hm']) ,
				'km' 				=> trim($post['km']) ,
				'hm_last' 			=> trim($post['hm_last']) ,
				'km_last' 			=> trim($post['km_last']) ,
				'date_realisasi' 	=> date('Y-m-d H:i:s'),
				'realisasi_by' 		=> $_SESSION["username"]
			];

			$rs = $this->db->insert('fuel_refill', $data);

			if ($rs) {
				$msg 	= 'Data berhasil disimpan';
				$stats 	= '1';
			} else {
				$msg 	= 'Data gagal disimpan';
				$stats 	= '0';
			}

			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('isi_bensin/add');
		}

		else
		{
			$this->data['list_nrp'] 	= $this->operator_model->getAllData();
			$this->data['list_unit'] 	= $this->master_equipment_model->getAllData();
			$this->data['title'] 		= "Add Data Pengisian Solar";
			$this->data['js'] 			= 'isi_bensin/js_add';
			$this->data['sview'] 		= 'isi_bensin/add';
			$this->load->view(_TEMPLATE , $this->data);
		}
	}

	public function edit($id)
	{
		if ($post = $this->input->post())
		{
			$data = [
				'nrp' 				=> trim($post['nrp']),
				'fuel_tank_id' 		=> $post['fuel_tank_id'],
				'equipment_id' 		=> trim($post['equipment_id']),
				'shift' 			=> trim($post['shift']),
				'total_liter' 		=> trim($post['total_liter']),
				'total_realisasi' 	=> trim($post['total_realisasi']),
				'date_fill' 		=> trim($post['date_fill']),
				'time_fill_start' 	=> trim($post['time_fill_start']),
				'time_fill_end' 	=> trim($post['time_fill_end']),
				'hm' 				=> trim($post['hm']) ,
				'km' 				=> trim($post['km']) ,
				'hm_last' 			=> trim($post['hm_last']) ,
				'km_last' 			=> trim($post['km_last']) ,
				'realisasi_by' 		=> $_SESSION["username"]
			];

			$rs = $this->db->update('fuel_refill', $data, ['fuel_refill_id' => $id]);

			if ($rs) {
				$msg 	= 'Data berhasil disimpan';
				$stats 	= '1';
			} else {
				$msg 	= 'Data gagal disimpan';
				$stats 	= '0';
			}

			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('isi_bensin/');
		}

		else
		{
			$this->data['rs'] 			= $this->db->where(['fuel_refill_id' => $id])->get('fuel_refill')->row();
			$this->data['list_nrp'] 	= $this->operator_model->getAllData();
			$this->data['list_unit'] 	= $this->master_equipment_model->getAllData();
			$this->data['title'] 		= "Edit Data Solar";
			$this->data['js'] 			= 'isi_bensin/js_update';
			$this->data['sview'] 		= 'isi_bensin/update';
			$this->load->view(_TEMPLATE , $this->data);
		}
	}

	public function delete($id)
	{
		$rs = $this->db->where(['fuel_refill_id' => $id])->delete('fuel_refill');

		if ($rs) {
			$msg 	= 'Data berhasil dihapus';
			$stats	= '1';
		} else {
			$msg 	= 'Data gagal dihapus';
			$stats 	= '0';
		}

		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('isi_bensin/');
	}

	public function antrian()
	{
		$this->data['rs'] 			= $this->isi_bensin_model->antrian_instruksi();
		$this->data['title'] 		= "Daftar Antrian Pengisian Solar :: ". date("d M Y");
		$this->data['js'] 			= 'isi_bensin/js_view_antrian';
		$this->data['sview'] 		= 'isi_bensin/view_antrian';
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data_antrian()
	{
		$rs 			= $this->isi_bensin_model->antrian_instruksi();
		$list_data 		= '';

		$no = 0;

		if (count($rs)>0) {
			foreach ($rs as $l) {
				$list_data .= '
				<tr>
					<td>'.$l["unit"].'</td>
					<td>'.$l["shift"].'</td>
					<td>'.$l["date_instruksi"].'</td>
					<td>'.$l["total_liter"].'</td>
					<td><a href="'. _URL.'isi_bensin/edit/'.$l["fuel_refill_id"].'">ISI SOLAR</a></td>';

				$list_data .= ' </tr> ';
			}
		}

		$list_data .= '';
		echo $list_data;
	}

	public function import()
	{
		$this->data['title'] 		= "Import Data Fuel";
		$this->data['js'] 			= 'isi_bensin/js_import';
		$this->data['sview'] 		= 'isi_bensin/import';
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function import_action()
	{
		/*
			$fp = fopen($_FILES['uploadFile']['tmp_name'], 'rb');
		    while ( ($line = fgets($fp)) !== false) {
		      echo "$line<br>";
		    }
			exit;
*/
		$config['upload_path'] = './assets/tmp/';
		$config['allowed_types'] = 'xls|xlsx';

		$this->load->library('upload', $config);


		if (! $this->upload->do_upload("userfile")) {
			$err_msg = array('error' => $this->upload->display_errors());
				echo "Masuk sini: ".print_r($err_msg);
				exit;
			$view_success = "fail";
		} else {
			$upload_data = array('upload_data' => $this->upload->data());
			$this->load->library('PHPExcel/IOFactory');
			$this->load->library('PHPExcel');

			$objPHPExcel = new PHPExcel_Reader_Excel5();

			$objFile = $objPHPExcel->load($upload_data["upload_data"]["full_path"]);

			$objWorksheet = $objFile->setActiveSheetIndex(0);
			$tmp_iterate = 0;
			foreach ($objWorksheet->getRowIterator() as $row) {
				$row_index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				if ($row_index > 1) {
					$tmp_iterate += 1;

					foreach($cellIterator as $cell) {
						$column_index = $cell->getColumn();

						$val= trim($cell->getValue());
						if(PHPExcel_Shared_Date::isDateTime($cell)) {
							$format = "";
							if ($column_index == 'B') {
								$format = "Y-m-d";
								$val = date($format, PHPExcel_Shared_Date::ExcelToPHP($val));
							}
							if ($column_index == 'L') {
								$val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm');
							}
							if ($column_index == 'M') {
								$val = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm');
							}
						}

						$tmp_data[$tmp_iterate][$column_index] =  $val;
					}
				}
			}
			unlink($upload_data["upload_data"]["full_path"]);

			# upload to database
			$rs = $this->isi_bensin_model->import_data($tmp_data);
		}

		if($rs == ""){
			$msg = 'Data berhasil diimport';
			$stats = '1';
		} else {
			$msg = 'Import data di baris : '. $rs;
			$stats = '0';
		}
		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('isi_bensin/');
	}

	public function getCycleTime()
	{
		#echo "eq id" . $_POST["equipment_id"];
		$rs = $this->isi_bensin_model->getCycleTime($_POST["equipment_id"]);
		$total_cycle_time = $rs["total_cycle_time"];

		$rs = $this->isi_bensin_model->getSettingCycleTime();
		$standart_cycle_time = $rs["cycle_time_standart"];
		$standart_solar = $rs["bensin_dalam_cycle_standart"];

		$solar_terpakai = ($total_cycle_time / $standart_cycle_time) * $standart_solar;
		$solar_tersedia = $standart_solar - $solar_terpakai;

		$pengisian = $standart_solar - $solar_tersedia;
		$data["cycle_time"] = $total_cycle_time;
		$data["pengisian"] = $pengisian;
		echo json_encode($data);
	}

	public function gethmterakhir()
	{
		$rs = $this->isi_bensin_model->gethmterakhir($_POST["equipment_id"]);
		$data["hm_last"] = $rs["hm"];
		$data["km_last"] = $rs["km"];
		echo json_encode($data);
	}

	public function eksport_action()
	{
		$header = "Report Data Pengisian Fuel\n";
		$header .= "Start\t".$this->input->post("start")."\n";
		$header .= "Stop\t".$this->input->post("end")."\n";
		$data 			= $this->isi_bensin_model->getDataPengisianSolar($this->input->post("start"),$this->input->post("end"));
		$colnames 		= array("DATE","FUEL TANK", "UNIT","TYPE UNIT", "SHIFT","QTY","HM","KM","HM LAST","KM LAST","EMPLOYEE ID","START TIME","FINISH TIME","DURASI","INSERT BY");
		$colfields 		= array("date_fill", "name", "new_eq_num", "alokasi","shift","total_realisasi","hm","km","hm_last","km_last","nrp","time_fill_start","time_fill_end","durasi","realisasi_by");
		$this->isi_bensin_model->export_to_excel($colnames,$colfields, $data, $header ,"rpt_pengisian_solar");
	}
}
