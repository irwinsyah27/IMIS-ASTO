<div ng-app="app" ng-controller="fatigueMonitorCtrl">

	<div ng-if="busy" style="background-color:yellow;padding:5px;width:300px;position:absolute;z-index:10000;top:47px;right:0;left:0;margin-left:auto;margin-right:auto;border:1px solid #999;text-align:center;">Getting data...</div>

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

				<input type="text" ng-model="date" class="form-control search-query  date-picker" data-date-format="yyyy-mm-dd"  placeholder="select date..." />
				<span class="input-group-btn">
					<button ng-click="updateData()" class="btn btn-purple btn-sm">
						<span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
						Filter
					</button>
				</span>

			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<table id="dynamic-table" class="table table-striped table-bordered table-hover">
				<thead class="thin-border-bottom">
					<tr>
						<th rowspan="2" align="center">NO</th>
						<th rowspan="2" align="center">TGL</th>
						<th rowspan="2" align="center">NAMA GL</th>
						<th rowspan="2" align="center">NAMA OPERATOR</th>
						<th rowspan="2" align="center">SHIFT</th>
						<th rowspan="2" align="center">LOKASI</th>
						<th rowspan="2" align="center">UNIT</th>
						<th rowspan="2" align="center" class="text-center">PENGAWASAN</th>
						<th rowspan="2" align="center" class="text-center">STOP KERJA</th>
						<th rowspan="2" align="center" class="text-center">STATUS</th>
					</tr>
				</thead>

				<!-- <tbody id="list_body_data">
					<tr ng-repeat="d in data | orderBy:'d.total_over_speed':true" ng-class="{danger:d.total_over_speed > 0, success:d.total_over_speed == 0}">
						<td>{{$index + 1}}</td>
						<td>{{d.nama_operator}}</td>
						<td class="text-center">{{d.shift}}</td>
						<td>{{d.unit}}</td>
						<td class="text-center">{{d.average_speed}}</td>
						<td class="text-center">{{d.max_speed}}</td>
						<td class="text-center">{{d.overspeed1}}</td>
						<td class="text-center">{{d.overspeed2}}</td>
						<td class="text-center">{{d.overspeed3}}</td>
						<td class="text-center">{{d.overspeed4}}</td>
						<td class="text-center">{{d.overspeed5}}</td>
						<td class="text-center">{{d.total_over_speed}}</td>
					</tr>
				</tbody> -->
			</table>
		</div>
	</div>
</div>

