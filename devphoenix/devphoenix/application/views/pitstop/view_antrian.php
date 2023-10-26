								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('pitstop/add');?>">
												<i class="ace-icon fa fa-floppy-o bigger-120 blue"></i>
												Tambah Data
											</a>
											<?php } ?>
											<?php if  (_USER_ACCESS_LEVEL_IMPORT == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" id="btnImportData">
												<i class="ace-icon fa bigger-120 blue"></i>
												Import
											</a>
											<?php } ?>
											<?php if  (_USER_ACCESS_LEVEL_EKSPORT == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" id="btnEksportData">
												<i class="ace-icon fa bigger-120 blue"></i>
												Eksport
											</a>
											<?php } ?> 
											<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('pitstop/');?>">
												<i class="ace-icon fa fa-floppy-o bigger-120 blue"></i>
												Log Data
											</a>
											<?php } ?>
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										
										<div class="row">
											<div class="col-sm-12">  
												<table id="dynamic-table" class="table table-striped table-bordered table-hover">
													<thead class="thin-border-bottom">
														<tr>
															<th>No</th> 
															<th>Lokasi Pitstop</th>
															<th>Unit</th> 
															<th>Shift</th>
															<th>Deskripsi</th>
															<th>HM</th>
															<th>Date Time In</th>   
															<th>Durasi</th> 
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
															<td><?php echo $l["lokasi"];?></td>   
															<td><?php echo $l["new_eq_num"];?></td>   
															<td><?php echo $l["shift"];?></td>  
															<td><?php echo $l["description"];?></td>  
															<td><?php echo $l["hm"];?></td> 
															<td><?php echo $l["date_time_in"];?></td>  
															<td><?php echo $l["durasi"];?></td>   
															<td><a href="<?php echo  _URL.'pitstop/edit/'.$l["pitstop_id"];?>/1">close</a> </td>   
														</tr> 
														<?php	
															}
														}
														?> 
													</tbody>
												</table>  
											</div><!-- /.col -->
										</div><!-- /.row -->   

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

								<div id="modal-import-data" class="modal fade" tabindex="-1">
			                        <div class="modal-dialog">
			                            <div class="modal-content">
			                                <form class="form-horizontal" method="POST" id="frmImportData" enctype="multipart/form-data" action="<?php echo base_url('pitstop/import_action');?>">
			                                <div class="modal-header no-padding">
			                                    <div class="table-header " id="title_materi_paket_sesi">
			                                        Import data Pitstop
			                                    </div>
			                                </div>

			                                <div class="modal-body" style="min-height:100px; margin:10px">
			                                	<div class="progress"></div>
			                                    <?php $this->mylib->inputfile("userfile");?> 
			                                </div>

			                                <div class="modal-footer no-margin-top">
			                                    <button class="btn btn-sm  btn-primary pull-left" data-dismiss="modal">
			                                        <i class="ace-icon fa fa-times"></i>
			                                        Batal
			                                    </button>
			                                    <button class="btn btn-sm btn-primary pull-right" id="submit-import-data">
			                                        <i class="ace-icon fa fa-times"></i>
			                                        Import
			                                    </button> 
			                                </div>
			                                 </form>
			                            </div>
			                        </div>
			                    </div>

								<div id="modal-eksport-data" class="modal fade" tabindex="-1">
			                        <div class="modal-dialog">
			                            <div class="modal-content">
			                                <form class="form-horizontal" method="POST" id="frmEksportData" enctype="multipart/form-data" action="<?php echo base_url('pitstop/eksport_action');?>">
			                                <div class="modal-header no-padding">
			                                    <div class="table-header " id="title_materi_paket_sesi">
			                                        Eksport Data Pitstop
			                                    </div>
			                                </div>

			                                <div class="modal-body" style="min-height:100px; margin:10px">
			                                	<div class="form-group">
			 									<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Periode Tanggal</label> 
												<div class="col-sm-8">
													<div class="input-daterange input-group">
														<input type="text" class="input-sm form-control" name="start" value="<?php echo date("Y-m-d");?>" />
														<span class="input-group-addon">
															<i class="fa fa-exchange"></i>
														</span>

														<input type="text" class="input-sm form-control" name="end" value="<?php echo date("Y-m-d");?>" />
													</div>
												</div>  
											</div>
			                                </div>

			                                <div class="modal-footer no-margin-top">
			                                    <button class="btn btn-sm  btn-primary pull-left" data-dismiss="modal">
			                                        <i class="ace-icon fa fa-times"></i>
			                                        Tutup
			                                    </button>
			                                    <button class="btn btn-sm btn-primary pull-right" id="submit-eksport-data">
			                                        <i class="ace-icon fa fa-times"></i>
			                                        Eksport
			                                    </button> 
			                                </div>
			                                 </form>
			                            </div>
			                        </div>
			                    </div>