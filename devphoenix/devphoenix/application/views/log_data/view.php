								<div class="row"> 
									<div class="col-sm-6"> 
										<div class="widget-box"> 
											<div class="table-header">
												Over Speed
											</div>
											<div class="widget-body">
												<div class="widget-main no-padding">
													<form class="form-horizontal" role="form">
														<div class="form-group">
															<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Nama</label>
															<div class="col-sm-9">
																<select name="nrp_over_speed" id="nrp_over_speed" class="chosen-select" >
					 												<option value="">- Pilih NRP / Nama -</option>
																	<?php
																	FOREACH ($list_nrp AS $l) {
																		$selected  = ""; 
																	?>
																	<option value="<?php echo $l["nrp"];?>" ><?php echo $l["nrp"]." - ". $l["nama"];?></option>
																	<?php
																	}
																	?> 
																</select> 
															</div>
														</div>

														<div class="form-group">
															<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Periode</label>
															<div class="col-sm-9">
																<input type="text" id="txtFilterOverSpeed1" name="txtFilterOverSpeed1" class="date-picker" data-date-format="yyyy-mm-dd" placeholder="" value="<?php echo date("Y-m-d");?>" />
																s/d
																<input type="text" id="txtFilterOverSpeed2" name="txtFilterOverSpeed2" class="date-picker" data-date-format="yyyy-mm-dd" placeholder="" value="<?php echo date("Y-m-d");?>" />
															</div>
														</div> 
														<div class="form-actions center">
															<button class="btn btn-sm btn-success" type="button" id="btnFilterOverSpeed" name="btnFilterOverSpeed">
															Download
															<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
															</button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-6"> 
										<div class="widget-box"> 
											<div class="table-header">
												Cycle Time
											</div>
											<div class="widget-body">
												<div class="widget-main no-padding">
													<form class="form-horizontal" role="form">
														<div class="form-group">
															<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Nama</label>
															<div class="col-sm-9">
																<select name="nrp_cycle_time" id="nrp_cycle_time" class="chosen-select" >
					 												<option value="">- Pilih NRP / Nama -</option>
																	<?php
																	FOREACH ($list_nrp AS $l) {
																		$selected  = ""; 
																	?>
																	<option value="<?php echo $l["nrp"];?>" ><?php echo $l["nrp"]." - ". $l["nama"];?></option>
																	<?php
																	}
																	?> 
																</select> 
															</div>
														</div>

														<div class="form-group">
															<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Periode</label>
															<div class="col-sm-9">
																<input type="text" id="txtFilterCycleTime1" name="txtFilterCycleTime1" class="date-picker" data-date-format="yyyy-mm-dd" placeholder="" value="<?php echo date("Y-m-d");?>" />
																s/d
																<input type="text" id="txtFilterCycleTime2" name="txtFilterCycleTime2" class="date-picker" data-date-format="yyyy-mm-dd" placeholder="" value="<?php echo date("Y-m-d");?>" />
															</div>
														</div> 
														<div class="form-actions center">
															<button class="btn btn-sm btn-success" type="button" id="btnFilterCycleTime" name="btnFilterCycleTime">
															Download
															<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
															</button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>

 

