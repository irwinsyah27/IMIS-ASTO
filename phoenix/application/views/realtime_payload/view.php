								<div class="row">
									<div class="col-xs-12"> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											<?php if (isset($title) && $title<>"") echo $title;?>  
											 
										</div>
										<div class="input-group col-sm-3">
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

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											 <!-- PAGE CONTENT BEGINS -->  
								<div class="row">
									<div class="col-sm-12"> 
										<table id="dynamic-table" class="table table-striped table-bordered table-hover">
											<thead class="thin-border-bottom">
												<tr>
													<th align="center">NO</th>
													<th align="center">NAMA OPERATOR</th>
													<th align="center">UNIT</th>
													<th align="center">EGI</th>
													<th align="center">TONNAGE 1</th>
													<th align="center">TONNAGE 2</th>
													<th align="center">TONNAGE 3</th> 
												</tr>
											</thead>

											<tbody id="list_body_data"> 
											</tbody>
										</table>  
									</div><!-- /.col -->
								</div><!-- /.row -->   
 