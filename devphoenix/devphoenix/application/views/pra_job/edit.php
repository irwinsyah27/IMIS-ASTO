								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('pra_job/view');?>">
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
												<form action="<?php echo base_url('pra_job/edit_data');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
											 		<div class="form-group">
														<label class="col-sm-6 control-label no-padding-left" for="form-field-1"> Nama </label> 
				 										<div class="col-sm-6"> 
															<?php $this->mylib->textbox("nama", $rs["nama"],"readonly","","","");?>  
				 										</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-left" for="form-field-1"> Tgl Pra job </label>
														<div class="col-sm-6">
															<?php $this->mylib->textbox("tanggal_pra_job",$rs["tanggal_pra_job"],"readonly","","","date-picker");?> 
														</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Shift </label> 
				 										<div class="col-sm-6">
				 											<?php echo form_error('shift', '<div class="col-sm-12 error">', '</div>'); ?>
				 											<select name="shift" id="shift" class="chosen-select" style="width: 200px;">
																<?php
																$selected_1 = ""; $selected_2 = "";
				 												if ($rs["shift"] =="1") $selected_1 = " selected";
				 												if ($rs["shift"] =="2") $selected_2 = " selected";
				 												?>
																<option value="1" <?php echo $selected_1;?>>1</option> 
																<option value="2" <?php echo $selected_2;?>>2</option> 
															 </select> 
				 										</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Mulai tidur kemarin </label>
														<div class="col-sm-6">
															<?php $this->mylib->textbox("mulai_tidur_kemarin",$rs["mulai_tidur_kemarin"],"readonly","","","");?> 
														</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Bangun tidur kemarin </label>
														<div class="col-sm-6">
															<?php $this->mylib->textbox("bangun_tidur_kemarin",$rs["bangun_tidur_kemarin"],"readonly","","","");?> 
														</div>
													</div> 
													<?php /*
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Mulai tidur hari ini </label>
														<div class="col-sm-3">
															<div class=" input-group ">
															<?php $this->mylib->textbox("mulai_tidur_hari_ini",$rs["mulai_tidur_hari_ini"],"","data-date-format=\"YYYY-MM-DD HH:mm\"");?> 
															<span class="input-group-addon">
																<i class="fa fa-calendar bigger-110"></i>
															</span>
															</div>
														</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Bangun tidur hari ini </label>
														<div class="col-sm-3">
															<div class=" input-group ">
															<?php $this->mylib->textbox("bangun_tidur_hari_ini",$rs["bangun_tidur_hari_ini"],"","data-date-format=\"YYYY-MM-DD HH:mm\"");?> 
															<span class="input-group-addon">
																<i class="fa fa-calendar bigger-110"></i>
															</span>
															</div>
														</div>
													</div>  
													*/ ?>

													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Jam Mulai tidur hari ini </label>
														<div class="col-sm-3">
															<div class=" input-group ">
															<?php $this->mylib->textbox("mulai_tidur_hari_ini",$rs["mulai_tidur_hari_ini"],"","");?>  
															</div>
														</div>
													</div> 
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Jam Bangun tidur hari ini </label>
														<div class="col-sm-3">
															<div class=" input-group ">
															<?php $this->mylib->textbox("bangun_tidur_hari_ini",$rs["bangun_tidur_hari_ini"],"","");?>  
															</div>
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Apakah anda sedang minum obat yang menyebabkan kantuk ? </label>
														<div class="col-sm-6">
															<?php $this->mylib->radiobox("apakah_sedang_minum_obat",$rs["apakah_sedang_minum_obat"],array("Y"=>"Ya", "T" => "Tidak"));?> 
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Apakah anda sedang ada masalah yang mempengaruhi konsentrasi ? </label>
														<div class="col-sm-6">
															<?php $this->mylib->radiobox("apakah_sedang_ada_masalah",$rs["apakah_sedang_ada_masalah"],array("Y"=>"Ya", "T" => "Tidak"));?> 
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Apakah anda siap & mampu untuk bekerja ? </label>
														<div class="col-sm-6">
															<?php $this->mylib->radiobox("apakah_siap_bekerja",$rs["apakah_siap_bekerja"],array("Y"=>"Ya", "T" => "Tidak"));?> 
														</div>
													</div>  
													<?php /*
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Apakah saya mempunyai APD yang sesuai</label>
														<div class="col-sm-6">
															<?php $this->mylib->radiobox("apakah_mempunyai_apd_yang_sesuai",$rs["apakah_mempunyai_apd_yang_sesuai"],array("Y"=>"Ya", "T" => "Tidak"));?> 
														</div>
													</div>   
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Apakah saya dalam kondisi fit</label>
														<div class="col-sm-6">
															<?php $this->mylib->radiobox("apakah_dalam_kondisi_fit",$rs["apakah_dalam_kondisi_fit"],array("Y"=>"Ya", "T" => "Tidak"));?> 
														</div>
													</div>  
 													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Apakah pekerjaan ini memerlukan ijin kerja khusus</label>
														<div class="col-sm-6">
															<?php $this->mylib->radiobox("apakah_memerlukan_ijin_khusus",$rs["apakah_memerlukan_ijin_khusus"],array("Y"=>"Ya", "T" => "Tidak"));?> 
														</div>
													</div>   
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Apakah saya memahami prosedur</label>
														<div class="col-sm-6">
															<?php $this->mylib->radiobox("apakah_memahami_prosedur",$rs["apakah_memahami_prosedur"],array("Y"=>"Ya", "T" => "Tidak"));?> 
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Apakah saya mempunyai peralatan yang benar</label>
														<div class="col-sm-6">
															<?php $this->mylib->radiobox("apakah_mempunyai_peralatan_yang_benar",$rs["apakah_mempunyai_peralatan_yang_benar"],array("Y"=>"Ya", "T" => "Tidak"));?> 
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Apakah ada aktifitas lain disekitar saya</label>
														<div class="col-sm-6">
															<?php $this->mylib->radiobox("apakah_ada_aktivitas_lain_disekitar_saya",$rs["apakah_ada_aktivitas_lain_disekitar_saya"],array("Y"=>"Ya", "T" => "Tidak"));?> 
														</div>
													</div>   
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Apakah saya mengenali, mengendalikan bahaya & resiko</label>
														<div class="col-sm-6">
															<?php $this->mylib->radiobox("apakah_mengenali_bahaya",$rs["apakah_mengenali_bahaya"],array("Y"=>"Ya", "T" => "Tidak"));?> 
														</div>
													</div>   
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Apakah saya focus dalam pekerjaan ini</label>
														<div class="col-sm-6">
															<?php $this->mylib->radiobox("apakah_focus",$rs["apakah_focus"],array("Y"=>"Ya", "T" => "Tidak"));?> 
														</div>
													</div>  

													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Apakah atasan saya mengetahui pekerjaan ini</label>
														<div class="col-sm-6">
															<?php $this->mylib->radiobox("apakah_atasan_mengetahui",$rs["apakah_atasan_mengetahui"],array("Y"=>"Ya", "T" => "Tidak"));?> 
														</div>
													</div>  
													<div class="form-group">
														<label class="col-sm-6 control-label no-padding-right" for="form-field-1"> Apakah pekerjaan ini bisa dilanjutkan</label>
														<div class="col-sm-6">
															<?php $this->mylib->radiobox("apakah_pekerjaan_bisa_dilanjutkan",$rs["apakah_pekerjaan_bisa_dilanjutkan"],array("Y"=>"Ya", "T" => "Tidak"));?> 
														</div>
													</div>  
													*/ ?>
																										 
													
			 									</div>
													<div class="clearfix form-actions">
														<div class="col-md-offset-3 col-md-9">
															<?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
															<input type="hidden" id="old_id" name="old_id" value="<?php echo $rs["pra_job_id"];?>">
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