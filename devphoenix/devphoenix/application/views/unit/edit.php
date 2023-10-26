								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('unit/view');?>">
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
												<form action="<?php echo base_url('unit/edit_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
											 		<div class="form-group">
                                                        <label class="control-label col-md-3">Kode Unit</label>
                                                        <div class="col-md-9">
                                                            <?php $this->mylib->textbox("new_eq_num",$rs["new_eq_num"]);?> 
                                                        </div>
                                                    </div>  
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Type</label>
                                                        <div class="col-md-9">
                                                            <?php $this->mylib->selectbox("master_alokasi_id",$rs["master_alokasi_id"],$list_type,"style=\"width: 200px;\"");?> 
                                                        </div>
                                                    </div>  
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Egi</label>
                                                        <div class="col-md-9">
                                                            <?php $this->mylib->selectbox("master_egi_id",$rs["master_egi_id"],$list_egi,"style=\"width: 200px;\"");?> 
                                                        </div>
                                                    </div>  
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Owner</label>
                                                        <div class="col-md-9">
                                                            <?php $this->mylib->selectbox("master_owner_id",$rs["master_owner_id"],$list_owner,"style=\"width: 200px;\"");?> 
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
															<input type="hidden" id="old_id" name="old_id" value="<?php echo $rs["master_equipment_id"];?>">
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