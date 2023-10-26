<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leadtime_breakdown extends CI_Controller {

   	public function __construct() {
      	parent::__construct();
      	if (empty($_SESSION["id"]))  header("location:login");

		$this->data['breadcrumb'] = '<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Status & Leadtime B/D Unit</li>';

		//$this->load->model('proto_model');
		$this->load->model("leadtime_breakdown_model");
		$this->load->model('setting_service_model');
		$this->load->model('breakdown_model');
		$this->load->model('running_text_model');

		$menu["parent_menu"] 		= "dashboard";
		$menu["sub_menu"] 			= "leadtime_breakdown";
		$this->data['check_menu']	= $menu;

		# akses level
		$akses 			= $this->leadtime_breakdown_model->user_akses("leadtime_breakdown");
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
		$dt_running = "";
		$tmp_running_text = $this->running_text_model->getRunningText("leadtime_breakdown");
		if (count($tmp_running_text) > 0) {
			FOREACH ($tmp_running_text AS $r) {
				if (isset($dt_running ) && $dt_running  <> "") $dt_running .= "... ";
				$dt_running .= $r["keterangan"];
			}
		}

		$this->data['running_text'] 		= $dt_running;
		$this->data['list_master_alokasi'] 	= $this->breakdown_model->getMasterAlokasi();
		$this->data['list_master_breakdown'] 	= $this->breakdown_model->getMasterBreakdown2();

		$this->data['hari']		= array ("1" => "Senin",
							"2" => "Selasa",
							"3" => "Rabu",
							"4" => "Kamis",
							"5" => "Jumat",
							"6" => "Sabtu",
							"7" => "Minggu",
	);
		$this->data['rs'] 		= $this->leadtime_breakdown_model->getAllDataBreakdown();
		$this->data['title'] 		= "Status & Leadtime B/D Unit";
		$this->data['js'] 			= 'leadtime_breakdown/js_view';
		$this->data['sview'] 		= 'leadtime_breakdown/view';
		$this->load->view(_TEMPLATE , $this->data);
	}
	public function get_data()
	{
		if (empty($_POST["type"])) $_POST["type"] = "";
		if (empty($_POST["breakdown"])) $_POST["breakdown"] = "";
		$rs 		= $this->leadtime_breakdown_model->getAllDataBreakdown($_POST["type"],  $_POST["breakdown"]);
		$list_data = '<table class="table table-striped table-bordered table-hover">
											<thead class="thin-border-bottom">
												<tr>
													<th rowspan="1" align="center">NO</th>
													<th rowspan="1" align="center">NO. WO</th>
													<th rowspan="1" align="center">UNIT</th>
													<th rowspan="1" align="center">TYPE</th>
													<th rowspan="1" align="center">JENIS B/D</th>
													<th rowspan="1" align="center">LOKASI B/D</th>
													<th rowspan="1" align="center">PROBLEM</th>
													<th colspan="1" align="center">DATE IN</th>
													<th colspan="1" align="center">TIME IN</th>
													<th colspan="1" align="center">ETA RFU UNIT</th>
													<th rowspan="1" align="center">ETA WAITING PART</th>
													<th rowspan="1" align="center">DOWNTIME</th>
												
												</tr>
											</thead>

											<tbody>';

												$no = 0;

												$color = [
													'ICM' => 'danger',
													'USM' => 'warning',
													'SCM' => 'info',
													'TRM' => 'warning'
												];

												if (count($rs)>0) {
													FOREACH ($rs AS $l) {
														$no += 1;
												$list_data .= '
												<tr class="'.$color[$l["kode"]].'">
													<td>'.$no.'</td>
													<td>'.$l["no_wo"].'</td>
													<td>'.$l["new_eq_num"].'</td>
													<td>'.$l["alokasi"].'</td>
													<td>'.$l["kode"].'</td>
													<td>'.$l["lokasi"].'</td>
													<td>'.$l["diagnosa"].'</td>
													<td>'.$l["date_in"].'</td>
													<td>'.$l["time_in"].'</td>
													<td>'.$l["eta_rfu_unit"].'</td>
													<td>'.$l["eta_waiting_part"].'</td>
													<td>'.$l["durasi"].'</td>
												</tr>
												';
													}
												}
												$list_data .= '
											</tbody>
										</table> ';
		echo $list_data;
	}

	public function getSummaryByType()
	{
		$rs 		= $this->leadtime_breakdown_model->getSummaryByType();
		$list_data = '<table class="table table-striped table-bordered table-hover">
						<thead class="thin-border-bottom">
							<tr>
								<td class="table-header" colspan="2">Unit B/D by Type</td>
							</tr>
						</head>
						<tbody>' ;
		if (count($rs)>0) {
			FOREACH ($rs AS $l) {
				if (empty($l["type"]) && $l["type"] == "") $l["type"] = "N/A";
				$list_data .= '
				<tr>
					<td>'.$l["type"].'</td>
					<td>'.$l["total"].'</td>
				</tr>
				';
			}
		}
		$list_data .= '</tbody></table> ';
		echo $list_data;
	}
	public function getSummaryByTypeReady()
	{
		$rs_summary_type = $this->leadtime_breakdown_model->getSummaryByType();
		if (count($rs_summary_type)>0) {
			FOREACH ($rs_summary_type AS $l) {
				if (empty($l["type"]) && $l["type"] == "") $l["type"] = "NA";
				if (empty($l["total"]) && $l["total"] == "") $l["total"] = 0;
				$breakdown[$l["type"]] = $l["total"];
			}
		}



		$list_data = '<table class="table table-striped table-bordered table-hover">
						<thead class="thin-border-bottom">
							<tr>
								<td class="table-header" colspan="4">Remark Unit by Type</td>
							</tr>
						</head>
						<thead class="thin-border-bottom">
							<tr>
								<td class="table-header">Type</td>
								<td class="table-header">Pop</td>
								<td class="table-header">RFU</td>
								<td class="table-header">B/D</td>
							</tr>
						</head>
						<tbody>' ;
		$rs_summary_typea = $this->leadtime_breakdown_model->getTotalUnitByType();

		$houling_non_kpp = $this->leadtime_breakdown_model->getTotalUnitHaulingNonKpp();
		$no = 0;
		if (count($rs_summary_typea)>0) {
			FOREACH ($rs_summary_typea AS $l) {
				if (empty($l["type"]) && $l["type"] == "" ) $l["type"] = "NA";
				if (empty($l["total"]) && $l["total"] == "") $l["total"] = 0;

				if ($breakdown[$l["type"]] == "") $breakdown[$l["type"]] = 0;


				if ($l["type"] == "Coal Hauling ABB") {
					$total = $l["total"] - $breakdown[$l["type"]] - $houling_non_kpp ;
					$totalunit = $l["total"] + $houling_non_kpp;
				} else {
					$total = $l["total"] - $breakdown[$l["type"]];
					$totalunit = $l["total"];
				}
				$list_data .= "<tr>
					<td>".$l["type"]."</td>
					<td>".$totalunit."</td>
					<td>".$total."</td>
					<td>".$breakdown[$l["type"]]."</td>
				</tr>";
			}
		}
		$list_data .= '</tbody></table> ';
		echo $list_data;
	}

	public function getSummaryByEGIReady()
	{
		$rs_summary_egi = $this->leadtime_breakdown_model->getSummaryByEGI();
		if (count($rs_summary_egi)>0) {
			FOREACH ($rs_summary_egi AS $l) {
				if (empty($l["egi"]) && $l["egi"] == "") $l["egi"] = "NA";
				if (empty($l["total"]) && $l["total"] == "") $l["total"] = 0;
				$breakdown[$l["egi"]] = $l["total"];
			}
		}



		$list_data = '<table class="table table-striped table-bordered table-hover">
						<thead class="thin-border-bottom">
							<tr>
								<td class="table-header" colspan="4">Remark Unit by EGI</td>
							</tr>
						</head>
						<thead class="thin-border-bottom">
							<tr>
								<td class="table-header">Type</td>
								<td class="table-header">EGI</td>
								<td class="table-header">RFU</td>
								<td class="table-header">B/D</td>
							</tr>
						</head>
						<tbody>' ;
		$rs_summary_typea = $this->leadtime_breakdown_model->getTotalUnitByEGI();
		$no = 0;
		if (count($rs_summary_egia)>0) {
			FOREACH ($rs_summary_egia AS $l) {
				if (empty($l["egi"]) && $l["egi"] == "" ) $l["egi"] = "NA";
				if (empty($l["total"]) && $l["total"] == "") $l["total"] = 0;

				if ($breakdown[$l["egi"]] == "") $breakdown[$l["type"]] = 0;


				if ($l["egi"] == "P360") {
					$total = $l["total"] - $breakdown[$l["egi"]];
					$totalunit = $l["total"];
				} else {
					$total = $l["total"] - $breakdown[$l["egi"]];
					$totalunit = $l["total"];
				}
				$list_data .= "<tr>
					<td>".$l["egi"]."</td>
					<td>".$totalunit."</td>
					<td>".$total."</td>
					<td>".$breakdown[$l["egi"]]."</td>
				</tr>";
			}
		}
		$list_data .= '</tbody></table> ';
		echo $list_data;
	}

	public function getUnitReadyToday()
	{
		$result 	= $this->leadtime_breakdown_model->getUnitReadyToday();
		echo json_encode($result, JSON_NUMERIC_CHECK);
	}

}
