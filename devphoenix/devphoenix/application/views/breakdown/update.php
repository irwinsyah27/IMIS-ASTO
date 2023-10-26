								<div class="row">
									<div class="col-sm-2"> 
										<table class="table table-striped table-bordered table-hover">
											<thead class="thin-border-bottom">
												<tr>
													<td class="table-header" colspan="3">TODAY</td>  
												</tr>
											</head>
											<tbody id="list_schedule_breakdown">  
											</tbody>
										</table>

										<table class="table table-striped table-bordered table-hover">
											<thead class="thin-border-bottom">
												<tr> 
													<td class="table-header" colspan="2">TOMORROW</td>
												</tr>
											</head>
											<tbody> 
												<?php 
												$tmp_besok = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d") + 1, date("Y") )); 
												$tomorrow = $this->setting_service_model->getListDataBerdasarkanTgl($tmp_besok);

															if (isset($tomorrow) && count($tomorrow) >0) { 
																?> 
																<?php 
																FOREACH ($tomorrow AS $s) {
																	echo "<tr><td>".$s["unit"]."<td>&nbsp;-&nbsp;".$s["keterangan_ps"]."</td></td></tr>";
																}  
															} 
												?> 
											</tbody>
										</table>
									</div>
									<div class="col-sm-10">  
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('breakdown/view');?>">
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
												<form action="<?php echo base_url('breakdown/update_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
										 		   <div class="form-group">
                                                    <label class="control-label col-md-3">No WO</label>
                                                    <div class="col-md-9">
                                                        <?php $this->mylib->textbox("no_wo",$rs["no_wo"],"style=\"background-color: #e5f62a\"");?> 
                                                    </div>
                                                </div>   
											 		<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Unit </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('equipment_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="equipment_id" id="equipment_id" class="chosen-select" style="width: 300px;">
																<?php
																FOREACH ($list_unit AS $l) {
																	$selected  = "";
																	if ($l["master_equipment_id"] == $rs["equipment_id"]) $selected = " selected";
																?>
																<option value="<?php echo $l["master_equipment_id"];?>" <?php echo $selected;?>><?php echo $l["unit"]." - ".$l["alokasi"];?></option>
																<?php
																}
																?> 
															 </select> 
				 										</div>
													</div> 
											 		<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Jenis Breakdown </label> 
				 										<div class="col-sm-9">
				 											<?php echo form_error('master_breakdown_id', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="master_breakdown_id" id="master_breakdown_id" class="chosen-select" style="width: 600px;">
																<?php
																FOREACH ($list_breakdown AS $l) {
																	$selected  = "";
																	if ($l["master_breakdown_id"] == $rs["master_breakdown_id"]) $selected = " selected";
																?>
																<option value="<?php echo $l["master_breakdown_id"];?>" <?php echo $selected;?>><?php echo $l["kode"]." - ".$l["ket_en"];?></option>
																<?php
																}
																?> 
															 </select> 
				 										</div>
													</div> 


													<div class="form-group">
                                                        <label class="control-label col-md-3">Status</label>
                                                        <div class="col-md-9">
                                                            <?php $this->mylib->selectbox("status_breakdown_id",$rs["status_breakdown_id"],$list_status_breakdown," style=\"width: 200px;\"");?> 
                                                        </div>
                                                    </div>  

													<div class="form-group">
                                                        <label class="control-label col-md-3">Lokasi Breakdown</label>
                                                        <div class="col-md-9">
                                                            <?php $this->mylib->selectbox("master_lokasi_id",$rs["master_lokasi_id"],$list_lokasi_breakdown," style=\"width: 200px;\"");?> 
                                                        </div>
                                                    </div>  
													<div class="form-group">
                                                        <label class="control-label col-md-3">HM Unit</label>
                                                        <div class="col-md-9">
                                                            <?php $this->mylib->textbox("hm",$rs["hm"]);?> 
                                                        </div>
                                                    </div>   
													<div class="form-group">
                                                        <label class="control-label col-md-3">KM Unit</label>
                                                        <div class="col-md-9">
                                                            <?php $this->mylib->textbox("km",$rs["km"]);?> 
                                                        </div>
                                                    </div>  
 
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Date Time IN</label> 
														  
														<div class=" col-sm-3">
															<div class=" input-group ">
				 											<input class="form-control date-picker" name="date_time_in" id="date_time_in" type="text" data-date-format="YYYY-MM-DD HH:mm"  value="<?php echo $rs["date_time_in"];?>" />
															<span class="input-group-addon">
																<i class="fa fa-calendar bigger-110"></i>
															</span>
															</div>
														</div> 
													</div> 
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Problem</label> 
														  
														<div class=" col-sm-4">
															<div class=" input-group ">
				 											<textarea name="diagnosa" id="diagnosa" rows="4" cols="40" /><?php echo $rs["diagnosa"];?></textarea>
															</div>
														</div> 
													</div>  
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Tindakan</label> 
														  
														<div class=" col-sm-4">
															<div class=" input-group ">
				 											<textarea name="tindakan" id="tindakan" rows="4" cols="40" style="background-color: #e5f62a"><?php echo $rs["tindakan"];?></textarea>
															</div>
														</div> 
													</div>  

													<div class="form-group">
                                                        <label class="control-label col-md-3">Kriteria Komponen</label>
                                                        <div class="col-md-9"> 
                                                        	<select name="kriteria_komponen_id" id="kriteria_komponen_id" class="chosen-select" style="width: 300px;">
                                                        		<option value="">- Pilih -</option>
																<?php
																FOREACH ($list_kriteria_komponen AS $l) {
																	$selected  = "";
																	if ($l["kriteria_komponen_id"] == $rs["kriteria_komponen_id"]) $selected = " selected";
																?>
																<option value="<?php echo $l["kriteria_komponen_id"];?>" <?php echo $selected;?>><?php echo $l["kode"]." - ". $l["kriteria_komponen"];?></option>
																<?php
																}
																?> 
															 </select> 

                                                        </div>
                                                    </div>  
 
													<div class="form-group">
				 										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Tgl Keluar</label> 
														  
														<?php 
														$tgl_keluar = $rs["date_time_out"];
														?>
														<div class=" col-sm-3">
															<div class=" input-group ">
				 											<input class="form-control date-picker" name="date_time_out" id="date_time_out" value="<?php echo $tgl_keluar;?>" type="text" data-date-format="YYYY-MM-DD HH:mm" value="" style="background-color: #e5f62a" />
															<span class="input-group-addon">
																<i class="fa fa-calendar bigger-110"></i>
															</span>
															</div>
														</div> 
													</div> 

 
													
			 									</div>
													<div class="clearfix form-actions">
														<div class="col-md-offset-3 col-md-9">
															<input type="hidden" id="old_id" name="old_id" value="<?php echo $rs["breakdown_id"];?>">
															<?php if  (_USER_ACCESS_LEVEL_UPDATE == "1") { ?>
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