<div class="row">
		<div class="table-header">
			Fuel Ratio

		</div>

		<!-- div.table-responsive -->

		<!-- div.dataTables_borderWrap -->
	<div class="row">
		<div class="col-sm-12">
			<div class="widget-box">
				<div class="widget-header widget-header-flat widget-header-small">
					<h5 class="widget-title col-sm-9">
						<i class="ace-icon fa fa-signal"></i>
						Fuel Ratio
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
						  <div id="graph_fuel_per_type"> </div>
					</div><!-- /.widget-main -->
				</div><!-- /.widget-body -->
			</div><!-- /.widget-box -->
		</div><!-- /.col -->
	</div><!-- /.row -->

	<div class="row">
		<div class="col-sm-12">
			<div class="widget-box">
				<div class="widget-header widget-header-flat widget-header-small">
					<h5 class="widget-title col-sm-9">
						<i class="ace-icon fa fa-signal"></i>
						Fuel Consumtion
					</h5>
				</div>

				<div class="widget-body">
					<div class="widget-main">
						  <table class="table table-striped table-bordered table-hover">
							<thead class="thin-border-bottom">
								<tr>
									<td class="table-header">EGI</td>
									<td class="table-header">FC Today</td>
									<td class="table-header">FC Month to date</td>
								</tr>
							</head>
							<tbody id="table_liter_per_hm">
							</tbody>
						</table>
					</div><!-- /.widget-main -->
				</div><!-- /.widget-body -->
			</div><!-- /.widget-box -->
		</div><!-- /.col -->
	</div><!-- /.row -->

	<div class="row">
		<div class="col-sm-12">
			<div class="widget-box">
				<div class="widget-header widget-header-flat widget-header-small">
					<h5 class="widget-title col-sm-9">
						<i class="ace-icon fa fa-signal"></i>
						FUEL STOCK
					</h5>
				</div>
				<div class="widget-body">
					<div class="widget-main">
						  <div id="fuel-stock-chart"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
