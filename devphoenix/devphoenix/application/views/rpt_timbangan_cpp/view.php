								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											 
										</div>
									</div>
								</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											 <!-- PAGE CONTENT BEGINS -->  
								<div class="row">
									<div class="col-sm-12"> 
										<form action="<?php echo base_url('rpt_timbangan_cpp/export_to_excel');?>" method="post" name="frmData" id="frmData"   class="form-horizontal" role="form">
											
			 								<div class="form-group">
			 									<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Periode Tanggal</label> 
												<div class="col-sm-4">
													<div class="input-daterange input-group">
														<input type="text" class="input-sm form-control" name="start" value="<?php echo date("Y-m-d");?>"  />
														<span class="input-group-addon">
															<i class="fa fa-exchange"></i>
														</span>

														<input type="text" class="input-sm form-control" name="end" value="<?php echo date("Y-m-d");?>"  />
													</div>
												</div>  
											</div>
											<div class="clearfix form-actions">
												<div class="col-md-offset-3 col-md-9">
													<?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
													<button class="btn btn-info" type="submit">
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
								</div><!-- /.row -->   
 