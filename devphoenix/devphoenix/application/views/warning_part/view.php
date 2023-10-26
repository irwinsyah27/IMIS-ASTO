								<div class="row">
									<div class="col-sm-12"> 
										
										<!-- div.table-responsive -->
										<!--<div class="marquee">* Test 12345 dwi kuswarno 123</div>  -->
										<?php
										if ($running_text <> "") {  
										?>
										<div class="demo">
											<marquee behavior="scroll" scrollamount="1" direction="left" >* <?php echo $running_text;?></marquee>
										</div>
										<?php
											} 
										?>
										<!-- div.dataTables_borderWrap -->
										<div>
											 <!-- PAGE CONTENT BEGINS -->  
							 
								<div class="row">
									<?php /*
									<div class="col-sm-2"> 
										


										<div id="list_summary_type_ready">
											<table class="table table-striped table-bordered table-hover">
												<thead class="thin-border-bottom">
													<tr> 
														<td class="table-header" colspan="3">Remark Unit by Type</td>
													</tr>
												</head>
												<thead class="thin-border-bottom">
													<tr> 
														<td class="table-header">Type</td>
														<td class="table-header">Ready</td>
														<td class="table-header">B/D</td>
													</tr>
												</head>
												<tbody> 
													<?php  
													$rs_summary_type = $this->leadtime_breakdown_model->getSummaryByType(); 
													$no = 0;
													if (count($rs_summary_type)>0) {
														FOREACH ($rs_summary_type AS $l) { 
															if ($l["type"] == "") $l["type"] = "N/A";
															if (empty($l["total"]) && $l["total"] == "") $l["total"] = 0;
															$unit_breakdown[$l["type"]] = $l["total"]; 
														}
													} 

													$houling_non_kpp = $this->leadtime_breakdown_model->getTotalUnitHaulingNonKpp();  
													$rs_summary_typea = $this->leadtime_breakdown_model->getTotalUnitByType();  
													$no = 0;
													if (count($rs_summary_typea)>0) {
														FOREACH ($rs_summary_typea AS $l) { 
															if (empty($l["type"]) && $l["type"] == "") $l["type"] = "NA";
															if (empty($l["total"]) && $l["total"] == "") $l["total"] = 0;
															if ($l["type"] == "Coal Hauling ABB") {
																$total = $l["total"] - $unit_breakdown[$l["type"]] - $houling_non_kpp ;
															} else {
																$total = $l["total"] - $unit_breakdown[$l["type"]];
 															}
 															if ($unit_breakdown[$l["type"]] == "") $unit_breakdown[$l["type"]] = 0;
													?>
													<tr> 
														<td><?php echo $l["type"];?></td>  
														<td> <?php echo $total;?> </td>   
														<td> <?php echo $unit_breakdown[$l["type"]];?> </td>   
													</tr> 
													<?php	
														}
													}
													?>
												</tbody>
											</table>
										</div>
										 
										<div >
											<table class="table table-striped table-bordered table-hover">
												<thead class="thin-border-bottom">
													<tr> 
														<td class="table-header" colspan="2">Unit yang baru ready</td>
													</tr>
												</head>
												<tbody id="list_summary_ready_today">  
												</tbody>
											</table>
										</div> 

										<div id="div_today_plan_service">
											<table class="table table-striped table-bordered table-hover">
												<thead class="thin-border-bottom">
													<tr>
														<td class="table-header" colspan="3">Today Plan Service</td>  
													</tr>
												</head>
												<tbody id="list_schedule_breakdown">  
												</tbody>
											</table>
										</div>

										<table class="table table-striped table-bordered table-hover">
											<thead class="thin-border-bottom">
												<tr> 
													<td class="table-header" colspan="2">Tomorrow Plan Service</td>
												</tr>
											</head>
											<tbody> 
												<?php 
												$tmp_besok = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d") + 1, date("Y") )); 
												$tomorrow = $this->setting_service_model->getListDataBerdasarkanTgl($tmp_besok);

															if (isset($tomorrow) && count($tomorrow) >0) { 
																?> 
																<?php 
																FOREACH ($tomorrow AS $s) {
																	echo "<tr><td>".$s["unit"]."</td><td>&nbsp;&nbsp;".$s["keterangan_ps"]."</td></tr>";
																}  
															} 
												?> 
											</tbody>
										</table>


									</div>
									*/ ?>
									<div class="col-sm-12">  
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
										</div> 
										<table>
											<tr>
												<td width="100"><b>Type</b></td>
												<td>
													<select name="type" id="type" multiple class="multiselect">
														<option value="" selected>- Munculkan Semua -</option>
														<?php
														if (count($list_master_alokasi) > 0 ) {
															FOREACH ($list_master_alokasi AS $l) {
																?>
																<option value="<?php echo $l["master_alokasi_id"];?>"><?php echo $l["alokasi"];?></option>
																<?php
															}
														}
														?>
													</select>
												</td>
												<td width="20">&nbsp;</td>
												<td width="130"><b>Jenis Breakdown</b></td>
												<td>
													<select name="breakdown" id="breakdown" multiple class="multiselect">
														<option value="" selected>- Munculkan Semua -</option>
														<?php
														if (count($list_master_breakdown) > 0 ) {
															FOREACH ($list_master_breakdown AS $l) {
																?>
																<option value="<?php echo $l["master_breakdown_id"];?>"><?php echo $l["kode"]." - ".substr($l["ket_en"],0,40);?></option>
																<?php
															}
														}
														?>
													</select>
												</td>
												<td width="20">&nbsp;</td>
												<td width="100">
													<span class="input-group-btn">
														<button id="btnFilter" name="btnFilter"  type="button" class="btn btn-purple btn-sm">
															<span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
															Filter
														</button> 
													</span>
												</td>
												<?php /*
												<td width="130"><input type="button" name="Filter" id="Filter" value="Filter"></td>
												*/ ?>
											</tr>
										</table> 
										<div id="list_data">
											<?php /*
										<table class="table table-striped table-bordered table-hover">
											<thead class="thin-border-bottom">
												<tr>
													<th rowspan="1" align="center">NO</th>
													<th rowspan="1" align="center">UNIT</th>
													<th rowspan="1" align="center">TYPE</th>
													<th rowspan="1" align="center">JENIS B/D</th> 
													<th rowspan="1" align="center">LOKASI B/D</th>
													<th rowspan="1" align="center">WARNING PART</th>
													<th colspan="1" align="center">DATE IN</th>
													<th colspan="1" align="center">TIME IN</th> 
													<th rowspan="1" align="center">DOWNTIME</th>
												</tr> 
											</thead>

											<tbody>
												<?php
												$no = 0;
												if (count($rs)>0) {
													FOREACH ($rs AS $l) {
														$no += 1; 
												?>
												<tr>
													<td><?php echo $no;?></td>  
													<td><?php echo $l["new_eq_num"];?></td>  
													<td><?php echo $l["alokasi"];?></td>  
													<td><?php echo $l["kode"];?></td>   
													<td><?php echo $l["lokasi"];?></td>   
													<td><?php echo $l["warning_part"];?></td>  
													<td><?php echo $l["date_in"];?></td>  
													<td><?php echo $l["time_in"];?></td>  
													<td><?php echo $l["durasi"];?></td>  
												</tr> 
												<?php	
													}
												}
												?> 
											</tbody>
										</table> 
										*/ ?>
										</div>
									</div><!-- /.col -->
								</div><!-- /.row --> 
 