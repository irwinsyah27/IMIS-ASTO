								<div class="row">
									<div class="col-sm-12"> 
										
										<!-- div.table-responsive -->
										<!--<div class="marquee">* Test 12345 dwi kuswarno 123</div>  -->
										<?php
										if ($running_text <> "") {  
										?>
										<div class="demo">
											<marquee behavior="scroll" scrollamount="1" direction="left" >* <?php echo $running_text;?></marquee>
										</div>
										<?php
											} 
										?>
									</div>
								</div>

								<div class="row"> 
									
										<div class="table-header col-sm-9"> 
												HOURLY Monitoring Port   
										</div> 
										<div class="table-header col-sm-3">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="ace-icon fa fa-search"></i>
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
								</div>  
										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap --> 
								 
								<div class="row">
									<div class="col-sm-9">
										<div class="widget-box">
											<div class="widget-header widget-header-flat widget-header-small">
												<h5 class="widget-title col-sm-9">
													<i class="ace-icon fa fa-signal"></i>
													Total Produksi Port
												</h5>  
												
											</div>

											<div class="widget-body">
												<div class="widget-main"> 
													  <div id="hormon_wb_kpp_port"> </div> 
												</div><!-- /.widget-main -->
											</div><!-- /.widget-body -->
										</div><!-- /.widget-box -->
									</div><!-- /.col -->
									<div class="col-sm-3">
										<div class="widget-box">
											<div class="widget-header widget-header-flat widget-header-small">
												<h5 class="widget-title">
													<i class="ace-icon fa fa-signal"></i>
													Total Produksi per Shift
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
								 

								<div class="row">  
									<div class="col-sm-12">
										<div class="widget-box">
											<div class="widget-header widget-header-flat widget-header-small">
												<h5 class="widget-title">
													<i class="ace-icon fa fa-signal"></i>
													Hourly Tonase Hauling Unit KPP
												</h5> 
												<div class="widget-toolbar no-border"> 
												</div> 
											</div>

											<div class="widget-body">
												<div class="widget-main"> 
													  <div id="chartHourlyTonaseHauling"> </div> 
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
													Tabel Hourly Tonase Hauling Unit KPP/SAM
												</h5> 
												<div class="widget-toolbar no-border"> 
												</div> 
											</div>

											<div class="widget-body">
												<div class="widget-main"> 
													  <div id="tableHourlyTonaseHauling">
													  	<table  class="table table-striped table-bordered table-hover">
													  		<thead class="thin-border-bottom"> 
														  		<tr>
														  			<td rowspan="2" class="table-header">Company</td>
														  			<td rowspan="2" class="table-header">EGI</td>  
														  			<td colspan="4" class="table-header">Jam 9:00</td>
														  			<td colspan="4" class="table-header">Jam 12:00</td>
														  			<td colspan="4" class="table-header">Jam 15:00</td>
														  			<td colspan="4" class="table-header">Akhir Shift 1</td>
														  			<td colspan="4" class="table-header">Jam 21:00</td>
														  			<td colspan="4" class="table-header">Jam 00:00</td>
														  			<td colspan="4" class="table-header">Jam 03:00</td>
														  			<td colspan="4" class="table-header">Akhir Shift 2</td>
														  		</tr>
														  		<tr>
														  			<td class="table-header">Unit</td>
														  			<td class="table-header">Rit</td>
														  			<td class="table-header">Produksi</td>
														  			<td class="table-header">Payload</td> 
														  			<td class="table-header">Unit</td>
														  			<td class="table-header">Rit</td>
														  			<td class="table-header">Produksi</td>
														  			<td class="table-header">Payload</td> 
														  			<td class="table-header">Unit</td>
														  			<td class="table-header">Rit</td>
														  			<td class="table-header">Produksi</td>
														  			<td class="table-header">Payload</td> 
														  			<td class="table-header">Unit</td>
														  			<td class="table-header">Rit</td>
														  			<td class="table-header">Produksi</td>
														  			<td class="table-header">Payload</td> 
														  			<td class="table-header">Unit</td>
														  			<td class="table-header">Rit</td>
														  			<td class="table-header">Produksi</td>
														  			<td class="table-header">Payload</td> 
														  			<td class="table-header">Unit</td>
														  			<td class="table-header">Rit</td>
														  			<td class="table-header">Produksi</td>
														  			<td class="table-header">Payload</td> 
														  			<td class="table-header">Unit</td>
														  			<td class="table-header">Rit</td>
														  			<td class="table-header">Produksi</td>
														  			<td class="table-header">Payload</td> 
														  			<td class="table-header">Unit</td>
														  			<td class="table-header">Rit</td>
														  			<td class="table-header">Produksi</td>
														  			<td class="table-header">Payload</td> 
														  		</tr>
														  	</thead>
														  	<tbody id="data_produksi_per_3_jam">
															</tbody> 
													  	</table>
													  </div> 
												</div><!-- /.widget-main -->
											</div><!-- /.widget-body -->
										</div><!-- /.widget-box -->
									</div><!-- /.col --> 
								</div><!-- /.row -->
 
								 


