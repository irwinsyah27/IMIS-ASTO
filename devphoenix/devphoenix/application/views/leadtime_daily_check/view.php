 
											 <!-- PAGE CONTENT BEGINS -->  
								<div class="row"> 
									<div class="col-sm-2"> 
										<table class="table table-striped table-bordered table-hover">
											<thead class="thin-border-bottom">
												<tr>
													<td class="table-header" colspan="3">Achievement Dailycheck</td>  
												</tr>
												<tr>
													<td class="table-header">Plan</td>  
													<td class="table-header">Actual</td>   
												</tr>
											</head>
											<tbody id="list_summary_daily_check">
											</tbody> 
										</table>

										<table class="table table-striped table-bordered table-hover">
											<thead class="thin-border-bottom">
												<tr>
													<td class="table-header" colspan="3">Today Plan Dailycheck</td>  
												</tr>
												<tr>
													<td class="table-header">Unit</td>  
													<td class="table-header">Durasi</td>  
													<td class="table-header">Time Out</td>  
												</tr>
											</head>
											<tbody id="list_plan_daily_check">
											</tbody> 
										</table>
									</div>
									<div class="col-xs-10"> 
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											 
										</div>
										
										<table id="dynamic-table" class="table table-striped table-bordered table-hover">
											<thead class="thin-border-bottom">
												<tr>
															<th>No</th>
															<th>Pitstop</th>
															<th>Unit</th>
															<th>Shift</th>
															<th>Date In</th>
															<th>Time In</th>
															<th>Durasi</th> 
															<th>Deskripsi</th> 
												</tr>
											</thead>

											<tbody id="list_body_data">
											</tbody>
										</table>  
									</div><!-- /.col -->
								</div><!-- /.row -->   
 