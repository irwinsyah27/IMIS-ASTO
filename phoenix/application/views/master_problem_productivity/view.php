								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('master_problem_productivity/add');?>">
												<i class="ace-icon fa fa-floppy-o bigger-120 blue"></i>
												Tambah Data
											</a>
											<?php } ?>
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											<form action="<?php echo base_url('master_problem_productivity/truncate');?>" method="post" name="frmData" id="frmData">
												<table id="dynamic-table" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<!--
															<th class="center" width="20px">
																<label class="pos-rel">
																	<input type="checkbox" class="ace" />
																	<span class="lbl"></span>
																</label>
															</th>
														-->
															<th>Kode</th>
															<th>Keterangan</th> 
															<th width="100px">
																<!--<input type="submit" id="truncate" data-toggle="modal" value="hapus" class="btn btn-danger" style="width: 55px; height: 22px; padding: 0px;">
															-->
															</th>
														</tr>
													</thead>

													<tbody>  
													</tbody>
												</table>
											</form>
										</div>
									</div>
								</div>  

								<div id="modal-table" class="modal fade" tabindex="-1">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>
													</button>
													KONFIRMASI
												</div>
											</div>

											<div class="modal-body" style="height:100px;">
												 Apakah anda yakin akan menghapus data ini?
											</div>

											<div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>
												<button class="btn btn-sm btn-danger pull-right">
													<i class="ace-icon fa fa-times"></i>
													<a class="btn btn-danger">Confirm</a>
												</button> 
											</div>
										</div>
									</div>
								</div>