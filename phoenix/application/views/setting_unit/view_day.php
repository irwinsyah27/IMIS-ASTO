<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											<?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
											
											<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('setting_unit/add');?>">
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
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											<form action="<?php echo base_url('setting_unit/truncate');?>" method="post" name="frmData" id="frmData">
												<table id="dynamic-table" class="table table-striped table-bordered table-hover">
													<thead>
														<tr> 
															<th>LIST UNIT</th>
														</tr>
													</thead>

													<tbody>  
														<?php
														$unitsetting = $this->setting_unit_model->getListDataBerdasarkanHari();
														?>

														<tr> 
															<td>
																<?php 
																	echo "<table>"
																	;
																	FOREACH ($unitsetting AS $s) {
																		echo "<tr><td>".$s["unit"]." &nbsp;&nbsp;&nbsp; <a href=\"".base_url('setting_unit/delete/' . $s["setting_unit_id"])."\">x</a></td></tr>";
																	}
																	echo "</table>";
																?>
															</td> 
														</tr>
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
								<div id="modal-import-data" class="modal fade" tabindex="-1">
			                        <div class="modal-dialog">
			                            <div class="modal-content">
			                                <form class="form-horizontal" method="POST" id="frmImportData" enctype="multipart/form-data" action="<?php echo base_url('setting_unit/import_action');?>">
			                                <div class="modal-header no-padding">
			                                    <div class="table-header " id="title_materi_paket_sesi">
			                                        Import data Setting Unit
			                                    </div>
			                                </div>

			                                <div class="modal-body" style="min-height:100px; margin:10px">
			                                	<div class="progress"></div>
			                                    <?php $this->mylib->inputfile("userfile");?> 
			                                </div>

			                                <div class="modal-footer no-margin-top">
			                                    <button class="btn btn-sm  btn-primary pull-left" data-dismiss="modal">
			                                        <i class="ace-icon fa fa-times"></i>
			                                        Tutup
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
			                                <form class="form-horizontal" method="POST" id="frmEksportData" enctype="multipart/form-data" action="<?php echo base_url('setting_unit/eksport_action');?>">
			                                <div class="modal-header no-padding">
			                                    <div class="table-header " id="title_materi_paket_sesi">
			                                        Eksport Data Setting Unit
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
