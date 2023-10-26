								<div class="row"> 
									<div class="widget-box"> 
										<?php /*
										<div class="widget-header">
											<h4 class="widget-title">Default</h4>
										</div> */ ?> 
										<div class="table-header">
											Grafik SPO-BPM per Employee
										</div>
										<div class="widget-body">
											<div class="widget-main no-padding">
												<form class="form-horizontal" role="form">
													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Nama Pegawai</label>
														<div class="col-sm-9">
															<select name="nrp" id="nrp" class="chosen-select" style="width: 400px;" >
				 												<option value="">- Pilih NRP / Nama -</option>
																<?php
																FOREACH ($list_nrp AS $l) {
																	$selected  = ""; 
																?>
																<option value="<?php echo $l["nrp"];?>" ><?php echo $l["nrp"]." - ". $l["nama"];?></option>
																<?php
																}
																?> 
															</select> 
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Periode</label>
														<div class="col-sm-9">
															<select name="txtFilter1" id="txtFilter1" class="chosen-select" style="width: 200px;" >
				 												<option value="1">Januari</option> 
				 												<option value="2">Februari</option> 
				 												<option value="3">Maret</option> 
				 												<option value="4">April</option> 
				 												<option value="5">Mei</option> 
				 												<option value="6">Juni</option> 
				 												<option value="7">Juli</option> 
				 												<option value="8">Agustus</option> 
				 												<option value="9">September</option> 
				 												<option value="10">Oktober</option> 
				 												<option value="11">November</option> 
				 												<option value="12">Desember</option> 
															</select>  
															<input  style="width: 60px;" type="text" id="txtFilter2" name="txtFilter2"  placeholder="" value="<?php echo date("Y");?>" />
														</div>
													</div> 
													<div class="form-actions center">
														<button class="btn btn-sm btn-success" type="button" id="btnFilter" name="btnFilter">
														Filter
														<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
														</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="widget-box">
										<div class="table-header">
											Output Grafik SPO-BPM per Employee
										</div>
										<?php /*
										<div class="widget-header">
											<h4 class="widget-title ">Output Grafik SPO-BPM per Employee</h4>
										</div>
										*/ ?>
										<div class="widget-body">
											<div class="widget-main no-padding">
												<div id="graph_spo_bpm"> </div> 
											</div>
										</div>
									</div>
								</div>


 

