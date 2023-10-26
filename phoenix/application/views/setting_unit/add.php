<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('setting_unit/');?>">
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
												<form action="<?php echo base_url('setting_unit/add_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Unit </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('equipment_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="unit" id="unit" class="chosen-select" style="width: 200px;">
																<?php
																FOREACH ($list_unit AS $l) {
																?>
																<option value="<?php echo $l["unit"];?>"><?php echo $l["unit"];?></option>
																<?php
																}
																?> 
															 </select> 
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