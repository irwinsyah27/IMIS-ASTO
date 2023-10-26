								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>   
										</div>

										<div class="input-group col-sm-4">
											<span class="input-group-addon">
												<i class="ace-icon fa fa-check"></i>
											</span>

											<input type="text" id="txtFilter" name="txtFilter"  class="form-control search-query  date-picker" data-date-format="yyyy-mm-dd"  placeholder="" value="<?php echo date("Y-m-d");?>" />
											<span class="input-group-btn">
												<button id="btnFilter" name="btnFilter"  type="button" class="btn btn-purple btn-sm">
													<span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
													Filter
												</button>
												<button id="btnDownload" name="btnFilter"  class="btn btn-info btn-sm">
													<i class="ace-icon fa  fa-cloud-download  bigger-120 blue"></i>
													Download
												</button>
											</span>
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											 <!-- PAGE CONTENT BEGINS -->   
								<div class="row">
									<div class="col-sm-12"> 
										<table id="dynamic-table" class="table table-striped table-bordered table-hover">
											<thead class="thin-border-bottom">
												<tr>
													<th align="center" rowspan="2">NO</th>
													<th align="center" rowspan="2">NAMA</th>
													<th align="center" rowspan="2">UNIT</th>
													<th align="center" rowspan="2">FIT TO WORK</th>
													<th align="center" colspan="2">OP IN</th>
													<th align="center" rowspan="2">WORKING HOUR</th>
													<th align="center" colspan="2">OP ON</th>
													<th align="center" rowspan="2">AVG NETTO PER CT</th> 
													<th align="center" rowspan="2">OVER SPEED</th>
													<th align="center" rowspan="2">RITASE</th> 
													<th align="center" rowspan="2">SPO</th> 
													<th align="center" rowspan="2">BPM</th>
													<th align="center" colspan="2">JAM KRITIS</th>
												</tr>
												<tr> 
													<th align="center">IN</th> 
													<th align="center">OUT</th> 
													<th align="center">IN</th> 
													<th align="center">OUT</th> 
													<th align="center" rowspan="2">PENGAWASAN</th>
													<th align="center" rowspan="2">STOP</th>
												</tr>
											</thead>

											<tbody id="list_body_data"> 
											</tbody>
										</table>  
									</div><!-- /.col -->
								</div><!-- /.row -->   
 