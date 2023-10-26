								<div class="row">
									<div class="table-header col-sm-8"> 
											Production Achievement Port 
									</div> 
									<div class="table-header col-sm-4">  
										<div style="padding-right:0px">
											<form method="post" id="frmData"  action="<?php echo base_url('production_achievement/');?>" >
												<select name="txtFilter1" id="txtFilter1" class="chosen-select">
													<?php
													FOR ($i=1; $i<=12;$i++) {
														$selected = "";
														if ($i == $txtFilter1) $selected = " selected";
														echo "<option value=\"".$i."\" ".$selected.">".$month[$i]."</option>";
													} 
													?>
												</select>  
												<input  style="width: 60px; height:30px" type="text" id="txtFilter2" name="txtFilter2"  placeholder="" value="<?php echo $txtFilter2;?>" />
												<button id="btnFilterTotalProduksi" name="btnFilterTotalProduksi"  type="button" class="btn btn-purple btn-sm">
													<span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
													Filter
												</button>  
											</form>
										</div>
									</div>
								</div>  

								<div class="row">
									<div class="col-sm-12">
										<div class="widget-box">
											<div class="widget-header widget-header-flat widget-header-small">
												<h5 class="widget-title">
													<i class="ace-icon fa fa-signal"></i>
													Production Achievement
												</h5> 
												<div class="widget-toolbar no-border"> 
												</div> 
											</div>

											<div class="widget-body">
												<div class="widget-main"> 
													  <div id="hormon_wb_kpp"> </div> 
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
													Total Production Achivement
												</h5> 
												<div class="widget-toolbar no-border"> 
												</div> 
											</div>

											<div class="widget-body">
												<div class="widget-main"> 
													  <table class="table table-striped table-bordered table-hover">
														<thead class="thin-border-bottom">
														  	<tr>
														  		<th class="table-header">Today</th>
														  		<th class="table-header">Month to Date</th>
														  		<th class="table-header">Monthly</th>
														  		<th class="table-header">Periodic to Date</th>
														  		<th class="table-header">Periodicly</th>
														  	</tr> 
														  </thead>
														<tbody>
														  	<tr>
														  		<td><?php echo number_format($total_today["berat"] / 1000,2);?></td> 
														  		<td><?php echo number_format($total_month_to_today["berat"]/ 1000,2);?></td> 
														  		<td><?php echo number_format($total_monthly["berat"]/ 1000,2);?></td> 
														  		<td><?php echo number_format($periodic_to_date["berat"]/ 1000,2);?></td> 
														  		<td><?php echo number_format($periodicly["berat"]/ 1000,2);?></td> 
														  	</tr> 
														  </tbody>
													  </table>
												</div><!-- /.widget-main -->
											</div><!-- /.widget-body -->
										</div><!-- /.widget-box -->
									</div><!-- /.col -->
								</div><!-- /.row -->
 
								</div>   