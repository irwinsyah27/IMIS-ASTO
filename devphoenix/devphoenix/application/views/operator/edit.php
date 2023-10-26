								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('operator/view');?>">
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
												<form action="<?php echo base_url('operator/edit_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
											 		<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> NRP </label>
														<div class="col-sm-9">
														<input  name="nrp" id="nrp" class="col-xs-10 col-sm-5" type="text" placeholder="" value="<?php echo $rs["nrp"];?>" readonly>
														</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nama </label>
														<div class="col-sm-9">
														<input name="nama" id="nama" class="col-xs-10 col-sm-5" type="text" placeholder="" value="<?php echo $rs["nama"];?>">
														</div>
													</div>   
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Jabatan </label>
														<div class="col-sm-9">
														<?php $this->mylib->selectbox("master_posisi_id",$rs["master_posisi_id"],$list_posisi," style=\"width: 200px;\"");?> 
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Departemen </label>
														<div class="col-sm-9">
														<?php $this->mylib->selectbox("master_departemen_id",$rs["master_departemen_id"],$list_departemen," style=\"width: 200px;\"");?>  
														</div>
													</div>     
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Perusahaan </label>
														<div class="col-sm-9">
														<?php $this->mylib->selectbox("master_owner_id",$rs["master_owner_id"],$list_perusahaan," style=\"width: 200px;\"");?>  
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status </label>
														<div class="col-sm-9">
														<select name="status" id="status" class="chosen-select" style="width: 170px;" >
				 												<option value="">- Pilih Status -</option>
				 												<?php
				 												$selected_1 = ""; $selected_2 = ""; 
				 												if ($rs["status"] == 1) $selected_1 = " selected";
				 												if ($rs["status"] == 0) $selected_2 = " selected";
				 												?>
																<option value="1" <?php echo $selected_1;?>>1- Aktif</option> 
																<option value="0" <?php echo $selected_2;?>>0 - Non Aktif</option> 
															 </select> 
													</div>  
			 									</div>
													<div class="clearfix form-actions">
														<div class="col-md-offset-3 col-md-9">
															<?php if  (_USER_ACCESS_LEVEL_UPDATE == "1") { ?>
															<input type="hidden" id="old_id" name="old_id" value="<?php echo $rs["operator_id"];?>">
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