								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('plan_produksi/view');?>">
												<i class="ace-icon fa fa-list-alt bigger-120 blue"></i>
												List Data
											</a>
											<?php } ?>
										</div>

										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
 
										<div class="row">
											<div > 
												<form action="<?php echo base_url('plan_produksi/edit_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
											 		<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Plan </label> 
				 										<div class="col-sm-9" >
				 											<?php echo form_error('plan_category_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="plan_category_id" id="plan_category_id" class="chosen-select" style="width: 300px;" >
				 												<option value="">Pilih Plan</option>
																<?php
																FOREACH ($list_plan AS $l) {
																	$selected  = "";
																	if ($l["plan_category_id"] == $rs["plan_category_id"]) $selected = " selected";
																?>
																<option value="<?php echo $l["plan_category_id"];?>" <?php echo $selected;?>><?php echo $l["plan_category"];?></option>
																<?php
																}
																?> 
															 </select> 
				 										</div>
													</div>  
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Date</label> 
														<div class=" col-sm-2">
															<div class=" input-group ">
				 											<input   style="background-color: #e5f62a" class="form-control date-picker" name="date" id="date" type="text" data-date-format="yyyy-mm-dd" value="<?php echo $rs["date"];?>"  style="background-color: #e5f62a" />
															<span class="input-group-addon">
																<i class="fa fa-calendar bigger-110"></i>
															</span>
															</div>
														</div> 
													</div>
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Time Start</label> 
														  
														<div class=" col-sm-2">
															<div class=" input-group ">
				 											<input class="form-control  bootstrap-timepicker" name="time_start" id="time_start" type="text" data-date-format="HH:mm" value="<?php echo $rs["time_start"];?>"  style="background-color: #e5f62a"/>
															<span class="input-group-addon">
																<i class="fa fa-clock-o bigger-110"></i>
															</span>
															</div>
														</div> 
													</div> 
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Time End</label> 
														  
														<div class=" col-sm-2">
															<div class=" input-group ">
				 											<input class="form-control  bootstrap-timepicker" name="time_end" id="time_end" type="text" data-date-format="HH:mm" value="<?php echo $rs["time_end"];?>"  style="background-color: #e5f62a"/>
															<span class="input-group-addon">
																<i class="fa fa-clock-o bigger-110"></i>
															</span>
															</div>
														</div> 
													</div> 
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Delay</label> 
														  
														<div class=" col-sm-2">
															<div class=" input-group ">
				 											<input class="form-control" name="delay" id="delay" type="text" style="background-color: #e5f62a"  value="<?php echo $rs["delay"];?>" />
															</div>
														</div> 
													</div>  
			 									</div>
													<div class="clearfix form-actions">
														<div class="col-md-offset-3 col-md-9">
															<?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
															<input type="hidden" id="old_id" name="old_id" value="<?php echo $rs["plan_produksi_id"];?>">
															<button class="btn btn-info" type="button" id="SubmitData">
																<i class="ace-icon fa fa-check bigger-110"></i>
																Submit
															</button>
															<?php } ?>
															<button class="btn" type="reset">
																<i class="ace-icon fa fa-undo bigger-110"></i>
																Reset
															</button>
														</div>
													</div> 
												</form>
											</div>
										</div>

									</div>
								</div>