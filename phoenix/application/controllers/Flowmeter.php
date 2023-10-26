<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Flowmeter extends CI_Controller
{
 	private $folder_name			= "flowmeter";
 	private $table_name 			= "flowmeter";
 	private $primary_key 			= "flowmeter_id";
 	private $label_modul			= "Flowmeter";
 	private $model_name				= "flowmeter_model";
 	private $label_list_data		= "List Data";
 	private $label_add_data			= "Add Data";
 	private $label_sukses_dihapus 	= "Data berhasil dihapus";
 	private $label_gagal_dihapus 	= "Gagal menghapus data";
 	private $label_sukses_ditambah 	= "Data berhasil ditambah";
 	private $label_gagal_ditambah 	= "Data gagal ditambah";

   	public function __construct()
	{
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");
		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Flowmeter</li>';

      	$this->data["folder_name"]	= $this->folder_name;
		$menu["parent_menu"] 		= "";
		$menu["sub_menu"] 			= "flowmeter";
		$this->data['check_menu']	= $menu;

		$this->load->model($this->model_name);

		# akses level
		$akses = $this->flowmeter_model->user_akses("flowmeter");
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
		$this->data['title'] 		= "List Data Flowmeter";
		$this->data['js'] 			= 'flowmeter/js_view';
		$this->data['sview'] 		= 'flowmeter/view';
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function get_data()
	{
		$this->flowmeter_model->get_list_data();
	}

	public function delete($id)
	{
		$rs = $this->db->where(['flowmeter_id' => $id])->delete('flowmeter');

		if ($rs) {
			$msg 	= 'Data berhasil dihapus';
			$stats 	= '1';
		} else {
			$msg 	= 'Gagal menghapus data';
			$stats 	= '0';
		}

		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('flowmeter/');
	}

	public function add()
	{
		if ($post = $this->input->post())
		{
			$data = [
				'tgl' 				=> trim($post['tgl']),
				'status' 			=> $post['status'],
				'fuel_tank_id' 		=> $post['fuel_tank_id'],
				'flowmeter_awal' 	=> trim($post['flowmeter_awal']),
				'flowmeter_akhir' 	=> trim($post['flowmeter_akhir']),
				'sounding_awal' 	=> trim($post['sounding_awal']),
				'sounding_akhir' 	=> trim($post['sounding_akhir']),
				'volume_by_sounding'=> trim($post['volume_by_sounding']),
				'insert_by' 		=> $_SESSION["username"]
			];

			$rs = $this->db->insert('flowmeter', $data);

			if ($rs) {
				$msg 	= 'Data berhasil disimpan';
				$stats 	= '1';

				// update volume master_fuel_tank
				$this->db->update('master_fuel_tank',
					[
						'stock' => $post['volume_by_sounding'],
						'last_update_volume' => date('Y-m-d H:i:s')
					],
					['id' => $post['fuel_tank_id']]
				);

			} else {
				$msg 	= 'Gagal menyimpan data';
				$stats 	= '0';
			}

			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('flowmeter/add');
		}

		else
		{
			$this->data['title'] 	= "Tambah Data Flowmeter";
			$this->data['js'] 		= 'flowmeter/js_form';
			$this->data['sview'] 	= 'flowmeter/add';
			$this->load->view(_TEMPLATE , $this->data);
		}
	}

	public function edit($id)
	{
		if ($post = $this->input->post())
		{
			$data = [
				'tgl' 				=> trim($post['tgl']),
				'status' 			=> $post['status'],
				'fuel_tank_id' 		=> $post['fuel_tank_id'],
				'flowmeter_awal' 	=> trim($post['flowmeter_awal']),
				'flowmeter_akhir' 	=> trim($post['flowmeter_akhir']),
				'sounding_awal' 	=> trim($post['sounding_awal']),
				'sounding_akhir' 	=> trim($post['sounding_akhir']),
				'volume_by_sounding'=> trim($post['volume_by_sounding'])
			];

			$rs = $this->db->update('flowmeter', $data, ['flowmeter_id' => $id]);

			if ($rs) {
				$msg 	= 'Data berhasil disimpan';
				$stats 	= '1';

				// update volume master_fuel_tank
				$this->db->update('master_fuel_tank',
					[
						'stock' => $post['volume_by_sounding'],
						'last_update_volume' => date('Y-m-d H:i:s')
					],
					['id' => $post['fuel_tank_id']]
				);

			} else {
				$msg 	= 'Gagal menyimpan data';
				$stats 	= '0';
			}

			$this->session->set_flashdata('msg',$msg);
			$this->session->set_flashdata('stats',$stats);
			redirect('flowmeter/');
		}

		else
		{
			$this->data['rs'] = $this->db->where(['flowmeter_id' => $id])->get('flowmeter')->row();
			$this->data['title'] 	= "Edit Data Flowmeter";
			$this->data['js'] 		= 'flowmeter/js_form';
			$this->data['sview'] 	= 'flowmeter/edit';
			$this->load->view(_TEMPLATE , $this->data);
		}
	}

	public function import()
	{
		$this->data['title'] 		= "Import Data Flowmeter";
		$this->data['js'] 			= 'flowmeter/js_import';
		$this->data['sview'] 		= 'flowmeter/import';
		$this->load->view(_TEMPLATE , $this->data);
	}

	public function import_action()
	{
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
						}

						$tmp_data[$tmp_iterate][$column_index] =  $val;
					}
				}
			}
			unlink($upload_data["upload_data"]["full_path"]);

			# upload to database
			$rs = $this->flowmeter_model->import_data($tmp_data);
		}

		if ($rs == "") {
			$msg 	= 'Data berhasil diimport';
			$stats 	= '1';
		} else {
			$msg 	= 'Import data di baris : '. $rs;
			$stats 	= '0';
		}

		$this->session->set_flashdata('msg',$msg);
		$this->session->set_flashdata('stats',$stats);
		redirect('flowmeter/');
	}

	public function eksport_action()
	{
		$header	= "Report Data Flowmeter\n";
		$header	.= "Start\t".$this->input->post("start")."\n";
		$header	.= "Stop\t".$this->input->post("end")."\n";
		$data 	= $this->flowmeter_model->eksportDataPerTgl($this->input->post("start"),$this->input->post("end"));

		$colnames = [
			"TGL",
			"TRX",
			"STATUS",
			"FUEL TANK",
			"FLOWMETER AWAL",
			"FLOWMETER AKHIR",
			"SOUNDING AWAL",
			"SOUNDING AKHIR",
			"VOLUME BY FLOWMETER",
			"VOLUME BY SOUNDING",
			"SELISIH VOLUME",
		];

		$colfields = [
			"tgl",
			"trx",
			"status",
			"fuel_tank",
			"flowmeter_awal",
			"flowmeter_akhir",
			"sounding_awal",
			"sounding_akhir",
			"volume_by_flowmeter",
			"volume_by_sounding",
			"selisih_volume",
		];

		$this->flowmeter_model->export_to_excel($colnames,$colfields, $data, $header ,"rpt_flowmeter");
	}
}
