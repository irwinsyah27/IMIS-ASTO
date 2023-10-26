								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											 
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											 <!-- PAGE CONTENT BEGINS -->  
								<div class="row">
									<div class="col-sm-12"> 
										<table id="dynamic-table" class="table table-striped table-bordered table-hover">
											<thead class="thin-border-bottom">
												<tr>
													<th rowspan="2" align="center">NO</th>  
													<th rowspan="2" align="center">UNIT</th> 
													<th rowspan="2" align="center">SPEED</th> 
													<?php
													if (count($list_station) > 0) {
														FOREACH ($list_station AS $ls) {
															echo '<th colspan="2" align="center">'.$ls["station_name"].'</th> ';
														}
													}
													?>
												</tr>
												<tr> 
													<?php
													if (count($list_station) > 0) {
														FOREACH ($list_station AS $ls) {
															echo '<th>Distance</th> ';
															echo '<th>Time</th> ';
														}
													}
													?>
												</tr>
											</thead>

											<tbody id="list_body_data">
												<?php
												$tmp 				= $this->sync_station_model->getStationCPP();
												$latitude_cpp 		= $tmp["latitude"];
												$longitude_cpp 		= $tmp["longitude"];
												$tmp 				= $this->sync_station_model->getStationPort();
												$latitude_port 		= $tmp["latitude"];
												$longitude_port 	= $tmp["longitude"];

												$no = 0;
												if (count($rs)>0) {
													FOREACH ($rs AS $l) {
														$no += 1;  

														$latitude 	= $l["latitude"];
														$longitude 	= $l["longitude"];
														$speed 		= $l["speed"];

														$status_cycle_time = $this->estimate_unit_position_model->getStatusCycleTime($l["date"],$l["unit"]);
														$time_stasiun_cpp  = $status_cycle_time["time_stasiun_cpp"];
														$time_stasiun_port   = $status_cycle_time["time_stasiun_port "];
												?>
												<tr>
													<td><?php echo $no;?></td>   
													<td><?php echo $l["unit"];?></td>  
													<td><?php echo $l["speed"];?></td>  
													<?php
													if (count($list_station) > 0) {
														FOREACH ($list_station AS $ls) {
															$latitude_station 	= $ls["latitude"];
															$longitude_station 	= $ls["longitude"];

															$distance = "";
															$time_req = "";
															if ($latitude_station <> "" && $longitude_station <> "" && $latitude <> "" && $longitude <> "") {
																$distance = $this->geolocation->distance($latitude,$longitude,$latitude_station,$longitude_station,'K');
																$time_req     = $distance / $speed ;
															} 

															echo '<th>'.number_format($distance,2).'</th> ';
															echo '<th>'.number_format($time_req,2).'</th> ';
														}
													}
													?>
												</tr> 
												<?php	
													}
												}
												?> 
											</tbody>
										</table>  
									</div><!-- /.col -->
								</div><!-- /.row -->   
 