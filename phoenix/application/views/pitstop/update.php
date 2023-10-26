								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('pitstop/view');?>">
												<i class="ace-icon fa fa-list-alt bigger-120 blue"></i>
												Log Data
											</a>
											<?php } ?>
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('pitstop/view_antrian');?>">
												<i class="ace-icon fa fa-list-alt bigger-120 blue"></i>
												 Antrian
											</a>
											<?php } ?>
										</div>

										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
 
										<div class="row">
											<div> 
												<form action="<?php echo base_url('pitstop/update_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
											 		<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Lokasi Pitstop </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('station_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="station_id" id="station_id" class="chosen-select" style="width: 200px;">
																<?php
																FOREACH ($list_station AS $l => $k) {
																	$selected  = "";
																	if ($l  == $rs["station_id"]) $selected = " selected";
																?>
																<option value="<?php echo $l ;?>"  <?php echo $selected;?>><?php echo $k;?></option>
																<?php
																}
																?> 
															 </select> 
				 										</div>
													</div> 
											 		<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Unit </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('equipment_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="equipment_id" id="equipment_id" class="chosen-select" style="width: 300px;">
																<?php
																FOREACH ($list_unit AS $l) {
																	$selected  = "";
																	if ($l["master_equipment_id"] == $rs["equipment_id"]) $selected = " selected";
																?>
																<option value="<?php echo $l["master_equipment_id"];?>" <?php echo $selected;?>><?php echo $l["unit"]." - ".$l["alokasi"];?></option>
																<?php
																}
																?> 
															 </select> 
				 										</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Shift </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('shift', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="shift" id="shift" class="chosen-select" style="width: 200px;">
																<?php
																$selected_1 = ""; $selected_2 = "";
				 												if ($rs["shift"] =="1") $selected_1 = " selected";
				 												if ($rs["shift"] =="2") $selected_2 = " selected";
				 												?>
																<option value="1" <?php echo $selected_1;?>>1</option> 
																<option value="2" <?php echo $selected_2;?>>2</option> 
															 </select> 
				 										</div>
													</div> 
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Tgl Masuk</label> 
														  
														<div class=" col-sm-3">
															<div class=" input-group ">
				 											<input class="form-control date-picker" name="date_time_in" id="date_time_in" type="text" data-date-format="YYYY-MM-DD HH:mm" value="<?php echo $rs["date_time_in"];?>" />
															<span class="input-group-addon">
																<i class="fa fa-calendar bigger-110"></i>
															</span>
															</div>
														</div> 
													</div> 

													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Tgl Keluar</label> 
														  
														<div class=" col-sm-3">
															<div class=" input-group ">
				 											<input class="form-control date-picker" name="date_time_out" id="date_time_out" type="text" data-date-format="YYYY-MM-DD H:m" value="<?php echo $rs["date_time_out"];?>" />
															<span class="input-group-addon">
																<i class="fa fa-calendar bigger-110"></i>
															</span>
															</div>
														</div> 
													</div> 

													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Keterangan </label>
														<div class="col-sm-9">
														<input id="description" name="description" class="col-xs-10 col-sm-6" type="text" placeholder="Deskripsi" value="<?php echo $rs["description"];?>" >
														</div>
													</div> 
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">HM</label>  
														<div class=" col-sm-3">
															<div class=" input-group ">
				 											<input class="form-control" name="hm" id="hm" type="text"  value="<?php echo $rs["hm"];?>" /> 
															</div>
														</div> 
													</div>  
													
			 									</div>
													<div class="clearfix form-actions">
														<div class="col-md-offset-3 col-md-9">
															<input type="hidden" id="next" name="next" value="<?php echo $next;?>">
															<input type="hidden" id="old_id" name="old_id" value="<?php echo $rs["pitstop_id"];?>">
															<?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
															<button class="btn btn-info" type="submit" name="SubmitData" id="SubmitData" value="pitstop">
																<i class="ace-icon fa fa-check bigger-110"></i>
																Submit
															</button>
															<button class="btn btn-info" type="submit" name="SubmitData"  id="SubmitData" value="breakdown">
																<i class="ace-icon fa fa-check bigger-110"></i>
																Submit & Masuk Breakdown
															</button>
															<?php } ?>
														</div>
													</div> 
												</form>
											</div>
										</div>

									</div>
								</div>