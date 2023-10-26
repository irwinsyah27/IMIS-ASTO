								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('timbangan_cpp/view');?>">
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
												<form action="<?php echo base_url('timbangan_cpp/add_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
											 		<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Unit </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('equipment_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="equipment_id" id="equipment_id" class="chosen-select" style="width: 300px;">
				 												<option value="">Pilih Unit</option>
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
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Bruto </label>
														<div class="col-sm-9">
														<input  name="bruto" id="bruto" class="col-xs-10 col-sm-2" type="text"  style="background-color: #e5f62a" placeholder="Bruto">
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tara </label>
														<div class="col-sm-3">
														<input name="tara" id="tara" class="" type="text" style="background-color: #e5f62a" placeholder="tara">
														</div>
														<div class="col-sm-3">
														<input   name="tara_kemarin" id="tara_kemarin" class="" disabled type="text" placeholder="tara">
														</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Netto </label>
														<div class="col-sm-9">
														<input    name="netto" id="netto" class="col-xs-10 col-sm-2" type="text" placeholder="netto" readonly>
														</div>
													</div> 
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Tgl Timbang</label> 
														<?php   
														 if (date("G") >= 0 AND date("G") < 5) {
														 	$tgl = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d") - 1, date("Y") ));
														 } else $tgl = date("Y-m-d");
														?>
														<div class=" col-sm-2">
															<div class=" input-group ">
				 											<input class="form-control date-picker" name="date_weigher"  style="background-color: #e5f62a" id="date_weigher" type="text" data-date-format="yyyy-mm-dd" value="<?php echo $tgl;?>" />
															<span class="input-group-addon">
																<i class="fa fa-calendar bigger-110"></i>
															</span>
															</div>
														</div> 
													</div>
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Jam Timbang</label> 
														  
														<div class=" col-sm-2">
															<div class=" input-group ">
				 											<input class="form-control  bootstrap-timepicker"  style="background-color: #e5f62a" name="time_weigher" id="time_weigher" type="text" data-date-format="HH:mm" />
															<span class="input-group-addon">
																<i class="fa fa-clock-o bigger-110"></i>
															</span>
															</div>
														</div> 
													</div> 
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">No doket</label> 
														  
														<div class=" col-sm-2">
															<div class=" input-group ">
				 											<input class="form-control" name="no_doket" id="no_doket" type="text" style="background-color: #e5f62a"/>
															</div>
														</div> 
													</div> 
													<div class="form-group">
                                                        <label class="control-label col-md-3">Kode material</label>
                                                        <div class="col-md-9">
                                                            <?php $this->mylib->selectbox("material_id","",$list_material,"style=\"width: 200px;\"");?> 
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