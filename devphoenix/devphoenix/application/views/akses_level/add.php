								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('akses_level/view');?>">
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
												<form action="<?php echo base_url('akses_level/add_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
											 		
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Nama </label>
														<div class="col-sm-9">
														<input id="form-field-1"  name="nama" id="nama" class="col-xs-10 col-sm-5" type="text" placeholder="">
														</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Username </label>
														<div class="col-sm-9">
														<input id="form-field-1"  name="username" id="username" class="col-xs-10 col-sm-5" type="text" placeholder="">
														</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Password </label>
														<div class="col-sm-9">
														<input id="form-field-1"  name="passwd" id="passwd" class="col-xs-10 col-sm-5" type="password" placeholder="">
														</div>
													</div> 
											 		<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label> 
				 										<div class="col-sm-1"> <input type="checkbox" name="view" id="view" value="1"> View </div>
				 										<div class="col-sm-1"> <input type="checkbox" name="add" id="add" value="1"> Add </div>
				 										<div class="col-sm-1"> <input type="checkbox" name="update" id="update" value="1"> Update </div>
				 										<div class="col-sm-1"> <input type="checkbox" name="delete" id="delete" value="1"> Delete </div>
				 										<div class="col-sm-1"> <input type="checkbox" name="import" id="import" value="1"> Import </div>
				 										<div class="col-sm-1"> <input type="checkbox" name="eksport" id="eksport" value="1"> Eksport </div>
													</div> 
													<?php 
													$sql = "SELECT * FROM user_menu WHERE parent_id = 0 and show_menu = '1' ORDER BY um_order ASC";
													$res = $this->db->query($sql);
													$rs = $res->result_array();
													if (count($rs) > 0) {
														FOREACH ($rs AS $r) { 
															$title = "";

															$sql_1 = "SELECT * FROM user_menu WHERE parent_id = ".$r["user_menu_id"]." AND show_menu = '1'  ORDER BY um_order ASC"; 
															$res_1 = $this->db->query($sql_1);
															$rs_1 = $res_1->result_array();

															if (count($rs_1) > 0) { 
																$title = "<b>".$r["title"]."</b>";
															}else {
																$title = $r["title"];
															}
															?>
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right"><?php echo $title;?></label> 
				 										<div class="col-sm-1"><input type="checkbox" class="checkbox_view" name="view_<?php echo $r["module_name"];?>" id="view_<?php echo $r["module_name"];?>" value="1"></div>
				 										<div class="col-sm-1"><input type="checkbox" class="checkbox_add" name="add_<?php echo $r["module_name"];?>" id="add_<?php echo $r["module_name"];?>" value="1"></div>
				 										<div class="col-sm-1"><input type="checkbox" class="checkbox_update"  name="update_<?php echo $r["module_name"];?>" id="update_<?php echo $r["module_name"];?>" value="1"></div>
				 										<div class="col-sm-1"><input type="checkbox" class="checkbox_delete"  name="delete_<?php echo $r["module_name"];?>" id="delete_<?php echo $r["module_name"];?>" value="1"></div>
				 										<div class="col-sm-1"><input type="checkbox" class="checkbox_import"  name="import_<?php echo $r["module_name"];?>" id="import_<?php echo $r["module_name"];?>" value="1"></div>
				 										<div class="col-sm-1"><input type="checkbox" class="checkbox_eksport"  name="eksport_<?php echo $r["module_name"];?>" id="eksport_<?php echo $r["module_name"];?>" value="1"></div>
													</div> 
															<?php 
															if (count($rs_1) > 0) {
																FOREACH ($rs_1 AS $r_1) {
																	?>
																	<div class="form-group">
																		<label class="col-sm-3 control-label no-padding-right"><i><?php echo $r_1["title"];?></i></label> 
								 										<div class="col-sm-1"><input type="checkbox" class="checkbox_view" name="view_<?php echo $r_1["module_name"];?>" id="view_<?php echo $r_1["module_name"];?>" value="1"></div>
								 										<div class="col-sm-1"><input type="checkbox" class="checkbox_add" name="add_<?php echo $r_1["module_name"];?>" id="add_<?php echo $r_1["module_name"];?>" value="1"></div>
								 										<div class="col-sm-1"><input type="checkbox" class="checkbox_update" name="update_<?php echo $r_1["module_name"];?>" id="update_<?php echo $r_1["module_name"];?>" value="1"></div>
								 										<div class="col-sm-1"><input type="checkbox" class="checkbox_delete" name="delete_<?php echo $r_1["module_name"];?>" id="delete_<?php echo $r_1["module_name"];?>" value="1"></div>
								 										<div class="col-sm-1"><input type="checkbox" class="checkbox_import" name="import_<?php echo $r_1["module_name"];?>" id="import_<?php echo $r_1["module_name"];?>" value="1"></div>
								 										<div class="col-sm-1"><input type="checkbox" class="checkbox_eksport" name="eksport_<?php echo $r_1["module_name"];?>" id="eksport_<?php echo $r_1["module_name"];?>" value="1"></div>
																	</div> 
																<?php
																}
															}

														}
													}
													?>
													
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