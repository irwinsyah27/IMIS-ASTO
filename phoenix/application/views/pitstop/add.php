								<div class="row"> 
									<div class="col-sm-2"> 
										<table class="table table-striped table-bordered table-hover">
													<thead class="thin-border-bottom">
														<tr>
															<td class="table-header">Today Plan Dailycheck</td>  
														</tr>
													</head>
													<tbody> 
																<?php
																$today = $this->setting_dailycheck_model->getListDataBerdasarkanHari(date("N"));
																 
																	if (isset($today) && count($today) >0) { 
																		?>
																		<tr><td><table>
																		<?php
																		FOREACH ($today AS $s) {
																			echo "<tr><td>".$s["unit"]."</td></tr>";
																		} 
																		echo "</table></td>";
																	}  
																?> 
													</tbody>
												</table> 
									</div>
									<div class="col-xs-10"> 
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
												<form action="<?php echo base_url('pitstop/add_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
											 		<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Lokasi Pitstop </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('station_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="station_id" id="station_id" class="chosen-select" style="width: 200px;">
																<?php
																# print_r($list_station);
																FOREACH ($list_station AS $l => $k) {
																?>
																<option value="<?php echo $l;?>"><?php echo $k;?></option>
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
																?>
																<option value="<?php echo $l["master_equipment_id"];?>"><?php echo $l["unit"]." - ".$l["alokasi"];?></option>
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
																<option value="1">1</option> 
																<option value="2">2</option> 
															 </select> 
				 										</div>
													</div> 
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Tgl Masuk</label> 
														  
														<div class=" col-sm-3">
															<div class=" input-group ">
				 											<input class="form-control date-picker" name="date_time_in" id="date_time_in" type="text" data-date-format="YYYY-MM-DD HH:mm" value="<?php echo date("Y-m-d H:i");?>" />
															<span class="input-group-addon">
																<i class="fa fa-calendar bigger-110"></i>
															</span>
															</div>
														</div> 
													</div>  
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">HM</label>  
														<div class=" col-sm-3">
															<div class=" input-group ">
				 											<input class="form-control" name="hm" id="hm" type="text"  value="" /> 
															</div>
														</div> 
													</div>  
													
			 									</div>
													<div class="clearfix form-actions">
														<div class="col-md-offset-3 col-md-9">
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