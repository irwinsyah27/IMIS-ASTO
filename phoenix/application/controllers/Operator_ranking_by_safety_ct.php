<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator_ranking_by_safety_ct extends CI_Controller {

   	public function __construct()
    {
      	parent::__construct();

      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '
        <li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="#">Home</a>
		</li>
		<li class="active">Operator Ranking by Safety CT</li>
        ';

		//$this->load->model('proto_model');
		$this->load->model("operator_ranking_by_safety_ct_model");

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "operator_ranking_by_safety_ct";
		$this->data['check_menu']	= $menu;

		# akses level
		$akses = $this->operator_ranking_by_safety_ct_model->user_akses("operator_ranking_by_safety_ct");
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);
		define('_USER_ACCESS_LEVEL_DETAIL','');
   	}

	public function index()
	{
		$this->view();
	}

	public function view()
	{
		$this->data['title'] 		= "Operator Ranking by Safety CT ";
		$this->data['js'] 			= 'operator_ranking_by_safety_ct/js_view';
		$this->data['sview'] 		= 'operator_ranking_by_safety_ct/view';
		$this->load->view(_TEMPLATE , $this->data);
	}

	private function convertJamMenit ($var) {
		$tmp = explode(":", $var);

        if (count($tmp) > 1) {
            return $tmp[0]  + number_format( ($tmp[1] / 60),2 );
        }

		return $var;
	}

	public function get_data()
	{
		$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
		$rs = $this->operator_ranking_by_safety_ct_model->getAllDataPerDay($date);
        $data = [];

		$no = 0;

        foreach ($rs as $l)
        {
            if (empty($l["nama_operator"])) {
                $l["nama_operator"] = "";
            }

            if (empty($l["unit"])) {
                $l["unit"] = "";
            }

            $data[$no]["nama_operator"] 	= $l["nama_operator"]." (S".$l["shift"].")";
            $data[$no]["unit"] 				= $l["unit"];
            $data[$no]["time_start_position_station"] = $l["time_start_position_station"];
            $data[$no]["time_stop_position_station"]  = $l["time_stop_position_station"];

            $tmp_time   = new DateTime($l["time_start_position_station"]);
            $tmp_jam    = $tmp_time->format('H');

            $end_mancal = "23:59:59" ;
            #$end_mancal = $l["time_stop_position_station"] ;

            if ($tmp_jam > 15 && $tmp_jam < 23) {
                $tmp_date = new DateTime($l["tgl_payload"]);
                $tmp_date->add(new DateInterval('P1D'));
                $tgl_besok = $tmp_date->format('Y-m-d');
            }

            else {
                $tgl_besok = $l["tgl_payload"];
            }

            $datetime_in = $l["tgl_payload"]." ". $l["time_start_position_station"];
            $datetime_out = $tgl_besok." ". $end_mancal;
            $tonnage_data = $this->operator_ranking_by_safety_ct_model->getCTDataPerDay($l["nip"],$l["unit"], $datetime_in, $datetime_out);

            $loop = 0;

            if (count($tonnage_data) > 0) {
                $avg = 0;

                foreach ($tonnage_data as $td) {
                    $loop++;
                    if (empty($td["durasi"]) || $td["durasi"] == "undefined") {
                        $td["durasi"] = "";
                    }

                    if (empty($td["fpi"]) || $td["fpi"] == "undefined") {
                        $td["fpi"] = "";
                    }

                    $data[$no]["durasi_".$loop] = $this->convertJamMenit($td["durasi"]);
                    $data[$no]["fpi_".$loop] = $td["fpi"];
                    $avg += $td["durasi"];
                }

                if ($loop < 3) {
                    $next_loop = $loop + 1;

                    for ($i = $next_loop ; $i <= 3; $i++) {
                        $data[$no]["durasi_".$i] 	= "";
                        $data[$no]["fpi_".$i] 		= "";
                        // $avg += 0;
                    }
                }

                $next_loop = 0;
                $loop = 0;
            }

            // else {
            //     for ($i = 1 ; $i <= 3; $i++) {
            //         $data[$no]["durasi_".$i] 	= "";
            //         $data[$no]["fpi_".$i] 		= "";
            //         // $avg += 0;
            //     }
            // }

            $no++;
        }

		echo json_encode($data, JSON_NUMERIC_CHECK);
	}

    public function export($start, $end)
	{
		$header	= "Report Cycle Time and FPI\n";
		$header	.= "Start\t".$start."\n";
		$header	.= "Stop\t".$end."\n";

		$colnames = [
            "NIP", "NAMA", "UNIT", "DATE", "SHIFT", "TIME START", "TIME END",
			"CT 1", "FPI 1", "CT 2", "FPI 2", "CT 3", "FPI 3"
		];

		$colfields = [
			"nip", "nama", "unit", "date", "shift", "time_start", "time_end",
			"ct1", "fpi1", "ct2", "fpi2", "ct3", "fpi3"
		];

        $query = "SELECT
                da.nip,
                da.unit,
                mem.nama AS `nama`,
                da.date,
                CONCAT(da.date, ' ', da.time_start_position_station) AS `time_start`,
                CONCAT(da.date, ' ', da.time_stop_position_station) AS `time_end`,
                da.shift
            FROM daily_absent da
            LEFT JOIN master_employee mem ON da.nip = mem.nrp
            WHERE da.date BETWEEN '{$start}' AND '{$end}'
            ORDER BY da.date ASC";

        $data = $this->db->query($query)->result_array();

        foreach ($data as $i => $d)
        {
            $query = "SELECT
                    `ct_fpi`.fpi1,
                    `ct_fpi`.fpi2,
                    `ct_fpi`.fpi3,
                    TIME_TO_SEC(TIMEDIFF(TIMEDIFF(`ct1_end`, `ct1_start`), `ct1_idle`)) / (60*60) AS `ct1`,
                    TIME_TO_SEC(TIMEDIFF(TIMEDIFF(`ct2_end`, `ct2_start`), `ct2_idle`)) / (60*60) AS `ct2`,
                    TIME_TO_SEC(TIMEDIFF(TIMEDIFF(`ct3_end`, `ct3_start`), `ct3_idle`)) / (60*60) AS `ct3`
                FROM `ct_fpi`
                WHERE
                    DATE(`ct1_start`) = '".$d['date']."'
                    AND `ct_fpi`.`unit` = '".$d['unit']."'
                    AND `ct_fpi`.`nrp` = '".$d['nip']."'
            ";

            $ct = $this->db->query($query)->row_array();

            if (!$ct)
            {
                $query1 = "SELECT
                    fpi,
                    TIME_TO_SEC(TIMEDIFF(TIMEDIFF(`datetime_end`, `datetime_start`), SEC_TO_TIME(`total_idle`))) / (60*60) AS `ct`
                    FROM `log_cycle_time`
                    WHERE
                        `nip` = '".$d['nip']."'
                        AND `unit` = '".$d['unit']."'
                        AND DATE(`datetime_start`) = '".$d['date']."'
                ";

                $result = $this->db->query($query1)->result();

                $ct_index = 1;
                $ct_temp = [];

                foreach ($result as $r)
                {
                    if ($ct_index == 1) {
                        $ct_temp += ['ct1' => $r->ct > 0 ? $r->ct : 0, 'fpi1' => $r->fpi];
                    }

                    if ($ct_index == 2) {
                        $ct_temp += ['ct2' => $r->ct > 0 ? $r->ct : 0, 'fpi2' => $r->fpi];
                    }

                    if ($ct_index == 3) {
                        $ct_temp += ['ct3' => $r->ct > 0 ? $r->ct : 0, 'fpi3' => $r->fpi];
                    }

                    $ct_index++;
                }

                if (count($result) < 3) {
                    $ct_temp += ['ct3' => '0', 'fpi3' => '0'];
                }

                if (count($result) < 2) {
                    $ct_temp += ['ct2' => '0', 'fpi2' => '0'];
                }

                if (count($result) == 0) {
                    $ct_temp += ['ct1' => '0', 'fpi1' => '0'];
                }

                $data[$i] += $ct_temp;
            }

            else {
                $ct['ct1'] = $ct['ct1'] > 0 ? $ct['ct1'] : 0;
                $ct['ct2'] = $ct['ct2'] > 0 ? $ct['ct2'] : 0;
                $ct['ct3'] = $ct['ct3'] > 0 ? $ct['ct3'] : 0;
                $data[$i] += $ct;
            }
        }

        // header('Content-Type:text/plain');
        // print_r($data);
        // exit();

		$this->operator_ranking_by_safety_ct_model
            ->export_to_excel($colnames, $colfields, $data, $header ,"rpt_ct_fpi");
	}

    public function import()
	{
		$config['upload_path'] = './assets/tmp/';
		$config['allowed_types'] = 'xls|xlsx';

		$this->load->library('upload', $config);


		if (! $this->upload->do_upload("userfile")) {
			$err_msg = array('error' => $this->upload->display_errors());
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
					$tmp_iterate++;

					foreach ($cellIterator as $cell) {
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
		redirect('operator_ranking_by_safety_ct/');
	}
}
