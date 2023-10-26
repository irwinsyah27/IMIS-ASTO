								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('daily_absent/view');?>">
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
												<form action="<?php echo base_url('daily_absent/add_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
											 		<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nama </label>
														<div class="col-sm-9">
															 <?php $this->mylib->selectbox("nip","",$list_operator,"",'chosen-select');?>
														</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status </label>
														<div class="col-sm-2">
															 <?php $this->mylib->selectbox("status","",array("M"=>"Masuk","I" => "Ijin", "S" => "Sakit", "C" => "Cuti","A" => "Alva")," style=\"width: 200px;\"");?>
														</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Shift </label>
														<div class="col-sm-1">
															 <?php $this->mylib->selectbox("shift","", $list_shift," style=\"width: 200px;\"");?>
														</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tanggal Masuk</label>
														<div class="col-sm-2">
														 <?php $this->mylib->inputdate("tgl",date("Y-m-d"));?>
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Jam Masuk </label>
														<div class="col-sm-2">
														 <?php $this->mylib->textbox("time_in",""," style=\"width: 200px;\"");?>
														</div>
													</div>  

													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tanggal Keluar</label>
														<div class="col-sm-2">
														 <?php $this->mylib->inputdate("tgl_out",date("Y-m-d"));?>
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Jam Keluar </label>
														<div class="col-sm-2">
														 <?php $this->mylib->textbox("time_out",""," style=\"width: 200px;\"");?>
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> BPM </label>
														<div class="col-sm-2">
														 <?php $this->mylib->textbox("bpm_in",""," style=\"width: 200px;\"");?>
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> SPO </label>
														<div class="col-sm-2">
														 <?php $this->mylib->textbox("spo_in",""," style=\"width: 200px;\"");?>
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