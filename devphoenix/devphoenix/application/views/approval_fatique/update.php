								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											Realisasi Pengisian Solar  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('isi_bensin/antrian');?>">
												<i class="ace-icon fa fa-list-alt bigger-120 blue"></i>
												List Antrian
											</a>
											<?php } ?>
										</div>

										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
 
										<div class="row">
											<div> 
												<form action="<?php echo base_url('isi_bensin/update_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
											 		<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Unit </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('equipment_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="equipment_id" id="equipment_id" class="chosen-select">
																<?php
																FOREACH ($list_unit AS $l) {
																	$selected  = "";
																	if ($l["master_equipment_id"] == $rs["equipment_id"]) $selected = " selected";
																?>
																<option value="<?php echo $l["master_equipment_id"];?>" <?php echo $selected;?>><?php echo $l["unit"];?></option>
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
				 											<select name="shift" id="shift" class="chosen-select">
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
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Tgl Instruksi</label> 
														  
														<div class=" col-sm-2">
															<div class=" input-group ">
				 											<input class="form-control date-picker" name="date_instruksi" id="date_instruksi" type="text" data-date-format="yyyy-mm-dd" value="<?php echo $rs["date_instruksi"];?>" readonly />
															<span class="input-group-addon">
																<i class="fa fa-calendar bigger-110"></i>
															</span>
															</div>
														</div> 
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Instruksi Pengisian </label>
														<div class="col-sm-9">
														<input id="form-field-1"  name="total_liter" id="total_liter" class="col-xs-10 col-sm-2" type="text" placeholder="Instruksi Isi"  value="<?php echo $rs["total_liter"];?>" readonly=true>
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Realisasi Pengisian </label>
														<div class="col-sm-9">
														<input id="form-field-1"  name="total_realisasi" id="total_realisasi" class="col-xs-10 col-sm-2" type="text" placeholder="Jml Isi"  value="">
														</div>
													</div>  
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Tgl Isi</label> 
														  
														<div class=" col-sm-2">
															<div class=" input-group ">
				 											<input class="form-control date-picker" name="date_fill" id="date_fill" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date("Y-m-d");?>" />
															<span class="input-group-addon">
																<i class="fa fa-calendar bigger-110"></i>
															</span>
															</div>
														</div> 
													</div>
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Jam Isi</label> 
														  
														<div class=" col-sm-2">
															<div class=" input-group ">
				 											<input class="form-control  bootstrap-timepicker" name="time_fill" id="time_fill" type="text" data-date-format="HH:mm"  value=""/>
															<span class="input-group-addon">
																<i class="fa fa-clock-o bigger-110"></i>
															</span>
															</div>
														</div> 
													</div> 
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> HM Awal </label>
														<div class="col-sm-2">
														<input id="form-field-1"  name="hm_before" id="hm_before" class="col-xs-10 col-sm-5" type="text" placeholder="HM Awal" value="">
														</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> HM Akhir </label>
														<div class="col-sm-2">
														<input id="form-field-1"  name="hm_after" id="hm_after" class="col-xs-10 col-sm-5" type="text" placeholder="HM Akhir" value="">
														</div>
													</div> 
													
			 									</div>
													<div class="clearfix form-actions">
														<div class="col-md-offset-3 col-md-9">
															<input type="hidden" id="old_id" name="old_id" value="<?php echo $rs["fuel_refill_id"];?>">
															<?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
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