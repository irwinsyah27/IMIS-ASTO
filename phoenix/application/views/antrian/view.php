								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											 
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											 <!-- PAGE CONTENT BEGINS -->  
								<div class="row">
									<div class="col-sm-12"> 
										<form action="<?php echo base_url('antrian/export_to_excel');?>" method="post" name="frmData" id="frmData"   class="form-horizontal" role="form">
											
			 								<div class="form-group">
			 									<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">CPP/Port</label> 
												<div class="col-sm-4">
													<div class="input-group">
														<select name="station_id" id="station_id">
															<option value="1">CPP</option>
															<option value="2">PORT</option>
														</select>
													</div>
												</div>  
											</div>
			 								<div class="form-group">
			 									<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Tanggal</label> 
												<div class="col-sm-4"> 
														<?php $this->mylib->textbox("tgl",date("Y-m-d"));?> 
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
 