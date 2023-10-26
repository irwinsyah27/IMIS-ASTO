								<div class="row"> 
										<div class="table-header">
											HOURLY Monitoring CPP
											 
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap --> 
								 
								<div class="row">
									<div class="col-sm-12">
										<div class="widget-box">
											<div class="widget-header widget-header-flat widget-header-small">
												<h5 class="widget-title col-sm-9">
													<i class="ace-icon fa fa-signal"></i>
													Total Produksi CPP
												</h5>  
												 <div class="input-group">
													<span class="input-group-addon">
														<i class="ace-icon fa fa-check"></i>
													</span>

													<input type="text" id="txtFilterTotalProduksi" name="txtFilterTotalProduksi"  class="form-control search-query  date-picker" data-date-format="yyyy-mm-dd"  placeholder="" value="<?php echo date("Y-m-d");?>" />
													<span class="input-group-btn">
														<button id="btnFilterTotalProduksi" name="btnFilterTotalProduksi"  type="button" class="btn btn-purple btn-sm">
															<span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
															Filter
														</button>
													</span>
												</div>
											</div>

											<div class="widget-body">
												<div class="widget-main"> 
													  <div id="hormon_wb_kpp_port"> </div> 
												</div><!-- /.widget-main -->
											</div><!-- /.widget-body -->
										</div><!-- /.widget-box -->
									</div><!-- /.col -->
								</div><!-- /.row -->
								
  
								<div class="row">
									<div class="col-sm-12">
										<div class="widget-box">
											<div class="widget-header widget-header-flat widget-header-small">
												<h5 class="widget-title">
													<i class="ace-icon fa fa-signal"></i>
													Total Produksi Berdasarkan Shift
												</h5> 
												<div class="widget-toolbar no-border"> 
												</div> 
											</div>

											<div class="widget-body">
												<div class="widget-main"> 
													  <div id="chartProduksiPerShift"> </div> 
												</div><!-- /.widget-main -->
											</div><!-- /.widget-body -->
										</div><!-- /.widget-box -->
									</div><!-- /.col -->
 
								</div><!-- /.row -->
  
 
								</div>   


