								<div class="row">
									<div class="col-xs-12">  
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<button type="button" class="btn btn-white btn-info btn-bold pull-right"  name="advanceFilter" id="advanceFilter" value="Advance Filter" >
															<span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
															Show Filter
														</button>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('breakdown_pcr/view');?>">
												<i class="ace-icon fa fa-floppy-o bigger-120 blue"></i>
												List Data
											</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<?php } ?> 
											 
										</div>
									</div>
								</div>
  
											 <!-- PAGE CONTENT BEGINS -->  
								<div class="row">
									<div class="col-sm-12">   
										<div class="widget-box" id="box_advance_filter">
											<div class="widget-header">
												<h4 class="widget-title">Advance Filter</h4>
											</div>

											<div class="widget-body">
												<form>
												<div class="widget-main no-padding"> 
													<table border="0" width="90%" align="center">
														<tr>
															<td width="100" valign="top">Type</td>
															<td valign="top"> 
																<?php
																	if (count($list_master_alokasi) > 0 ) {
																		FOREACH ($list_master_alokasi AS $l) {
																			?>
																			<input class="type_checkbox" type="checkbox" name="type[]" id="type[]" value="<?php echo $l["master_alokasi_id"];?>">&nbsp;&nbsp;<?php echo $l["alokasi"];?><br>
																			<?php
																		}
																	}
																	?>
															</td> 
															<td width="100">&nbsp;</td>
															<td width="130" valign="top">Jenis Breakdown</td>
															<td valign="top"> 
																<?php
																	if (count($list_master_breakdown) > 0 ) {
																		FOREACH ($list_master_breakdown AS $l) {
																			?>
																			<input class="breakdown_checkbox" type="checkbox" name="breakdown[]" id="breakdown[]" value="<?php echo $l["master_breakdown_id"];?>">&nbsp;&nbsp;<?php echo $l["kode"]." - ".substr($l["ket_en"],0,40);?><br> 
																			<?php
																		}
																	}
																	?>

															</td> 
														</tr> 
													</table>  
													<div class="form-actions center">  
														<button type="button" class="btn btn-sm btn-success"  name="hideFilter" id="hideFilter" value="Hide Filter" >
															Hide Filter
														</button>

														<button type="button" class="btn btn-sm btn-success"  name="Filter" id="Filter" value="Filter" >
															<span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
															Submit
														</button>
													</div>
												</div>
												</form>
											</div>
										</div> 
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12"> 
										<table id="dynamic-table" class="table table-striped table-bordered table-hover">
											<thead class="thin-border-bottom">
												<tr>
													<th>No</th> 
													<th>NO WO</th>
													<th>UNIT</th> 
													<th>TYPE</th>
													<th>JENIS B/D</th>   
													<th>LOKASI B/D</th>
													<th>HM UNIT</th>
													<th>KM UNIT</th>
													<th>DATETIME IN</th> 
													<th>DURASI</th> 
													<th>PROBLEM</th>
													<th align="center"></th>  
												</tr> 
											</thead>

											<tbody id="list_body_data">
												<?php 
												$no = 0;
												if (isset($rs) && count($rs)>0) {
													FOREACH ($rs AS $l) { 
														$no += 1;
 
												?>
												<tr> 
													<td><?php echo $no;?></td> 
													<td><?php echo $l["no_wo"];?></td>   
													<td><?php echo $l["new_eq_num"];?></td>   
													<td><?php echo $l["alokasi"];?></td>  
													<td><?php echo $l["kode"];?></td>  
													<td><?php echo $l["lokasi"];?></td>  
													<td><?php echo $l["hm"];?></td>  
													<td><?php echo $l["km"];?></td>  
													<td><?php echo $l["date_time_in"];?></td>   
													<td><?php echo $l["durasi"];?></td>   
													<td><?php echo $l["diagnosa"];?></td>  
													<td><a href="<?php echo  _URL.'breakdown_pcr/update/'.$l["breakdown_id"];?>">update</a>
													<br><br><a href="<?php echo _URL."breakdown_pcr/edit/".$l["breakdown_id"];?>">close</a></td>   
												</tr> 
												<?php	
													}
												}
												?> 
											</tbody>
										</table>  
									</div><!-- /.col -->
								</div><!-- /.row -->   
 