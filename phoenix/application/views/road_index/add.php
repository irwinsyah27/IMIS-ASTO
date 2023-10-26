<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('road_index/view');?>">
												<i class="ace-icon fa fa-list-alt bigger-120 blue"></i>
												List Data
											</a>
											<?php } ?>
										</div>

										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
 
										<div class="row">
											<div> 
												<form action="<?php echo base_url('road_index/add_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nama </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('nip', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="nip" id="nip" class="chosen-select" style="width: 300px;">
																<?php
																FOREACH ($list_operator AS $l) {
																?>
																<option value="<?php echo $l["nrp"];?>"><?php echo $l["nrp"]." - ".$l["nama"];?></option>
																<?php
																}
																?> 
															 </select> 
				 										</div>
													</div> 

													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Shift </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('master_shift_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="master_shift_id" id="master_shift_id" class="chosen-select" style="width: 300px;">
																<?php
																FOREACH ($list_shift AS $lsh) {
																?>
																<option value="<?php echo $lsh["master_shift_id"];?>"><?php echo $lsh["keterangan"];?></option>
																<?php
																}
																?> 
															 </select> 
				 										</div>
													</div> 

                                                    <div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status </label>
														<div class="col-sm-2">
															 <?php $this->mylib->selectbox("status","",array("Open"=>"Open","Closed" => "Closed")," style=\"width: 200px;\"");?>
														</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date Time Awal Temuan</label>
														<div class="col-sm-2">
															<div class=" input-group ">
														 		<?php $this->mylib->inputdate("date_awal",date("Y-m-d"));?>
																 <span class="input-group-addon">
																	<i class="fa fa-calendar bigger-110"></i>
																</span>
															</div>
														</div>
													</div>     
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Lokasi </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('sta_lokasi_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="sta_lokasi_id" id="sta_lokasi_id" class="chosen-select" style="width: 100px;">
																<?php
																FOREACH ($list_sta_lokasi AS $lsl) {
																?>
																<option value="<?php echo $lsl["sta_lokasi_id"];?>"><?php echo $lsl["sta_lokasi"];?></option>
																<?php
																}
																?> 
															</select>
															+
				 											<?php echo form_error('master_sta_meter_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="master_sta_meter_id" id="master_sta_meter_id" class="chosen-select" style="width: 100px;">
																<?php
																FOREACH ($list_sta_meter AS $lsm) {
																?>
																<option value="<?php echo $lsm["master_sta_meter_id"];?>"><?php echo $lsm["sta_meter"];?></option>
																<?php
																}
																?> 
															 </select> 
															 Meter
														</div> 
													</div> 

													<div class="form-group <?= form_error('file_awal') ? 'has-error' : '' ?>">
														<label for="" class="control-label col-md-3">Upload Foto Awal</label>
														<div class="col-md-5">
															<input type="file" name="file_awal" class="form-control" style="width: 300px;" >
															<?= form_error('file_awal', '<span class="text-danger">', '</span>') ?>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Jenis Kerusakan </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('master_kerusakan_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="master_kerusakan_id" id="master_kerusakan_id" class="chosen-select" style="width: 300px;">
																<?php
																FOREACH ($list_problem_road AS $lr) {
																?>
																<option value="<?php echo $lr["master_kerusakan_id"];?>"><?php echo $lr["kerusakan"];?></option>
																<?php
																}
																?> 
															 </select> 
				 										</div>
													</div> 
											
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Severity </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('master_severity_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="master_severity_id" id="master_severity_id" class="chosen-select" style="width: 300px;">
																<?php
																FOREACH ($list_severity AS $lt) {
																?>
																<option value="<?php echo $lt["master_severity_id"];?>"><?php echo $lt["severity"];?></option>
																<?php
																}
																?> 
															 </select> 
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