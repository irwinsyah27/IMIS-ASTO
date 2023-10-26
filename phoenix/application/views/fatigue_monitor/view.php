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

		<br/>

			<div>

				<form class="form-inline" role="form">
					<div class="row">
						<div class="col-md-1">
							<label for="name">Tanggal</label>
						</div>
						<div class="col-md-4">
							<input type="text" id="txDate" ng-model="date" class="form-control search-query date-picker" data-date-format="yyyy-mm-dd"  style="width: 300px;"  placeholder="select date..." />	
						</div>

						<div class="col-md-1">
							<label for="lokasics">Lokasi</label>
						</div>
						<div class="col-md-4">
							<select name="txLokasi" id="txLokasi" class="chosen-select" style="width: 300px;" >
								<option value="">- Pilih Lokasi CS -</option>
									<?php
									FOREACH ($list_lokasi AS $l) {
										$selected  = ""; 
									?>
								<option value="<?php echo $l["master_lokasi_id"];?>" ><?php echo $l["lokasi"];?></option>
								<?php
								}
								?> 
							</select> 
						</div>
						<button ng-click="updateData()" class="btn btn-purple btn-sm">
							<span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
							Filter
						</button>
					</div>
				</form>

				<br/>

				<form class="form-inline" role="form">
					<div class="row">
						<div class="col-md-1">
							<label for="nrpgl">Operator</label>
						</div>
						<div class="col-md-4">
							<select name="txNrpGl" id="txNrp" class="chosen-select" style="width: 300px;" >
								<option value="">- Pilih NRP / Nama -</option>
									<?php
									FOREACH ($list_nrp AS $l) {
										$selected  = ""; 
									?>
								<option value="<?php echo $l["nrp"];?>" ><?php echo $l["nrp"]." - ". $l["nama"];?></option>
								<?php
								}
								?> 
					   		</select> 
						</div>
						<div class="col-md-1">
							<label for="lokasics">Status</label>
						</div>
						<div class="col-md-4">
						<select name="txStatus" id="txStatus" class="chosen-select" style="width: 300px;" >
								<option value="">- Pilih Status -</option>
								<option value="STOP BEKERJA">STOP BEKERJA</option>
								<option value="PENGAWASAN KHUSUS">PENGAWASAN KHUSUS</option>
								<option value="SIAP BEKERJA">SIAP BEKERJA</option>
							</select> 
						</div>
						<button onclick="goToFormFatigueTest()" class="btn btn-default btn-sm">
							<span class="ace-icon fa fa-print icon-on-right bigger-110"></span>
							Form Fatigue Test
						</button>
					</div>
				</form>

			</div>
		</div>
	</div>

	<br/>

	<div class="row">
		<div class="col-sm-12">
			<table id="dynamic-table" class="table table-striped table-bordered table-hover">
				<thead class="thin-border-bottom">
					<tr>
						<th rowspan="2" align="center">NO</th>
						<th rowspan="2" align="center">TGL</th>
						<th rowspan="2" align="center">NRP</th>
						<th rowspan="2" align="center">NAMA</th>
						<th rowspan="2" align="center">SHIFT</th>
						<th rowspan="2" align="center">LOKASI</th>
						<th rowspan="2" align="center">UNIT</th>
						<th rowspan="2" align="center" class="text-center">JAM KRITIS</th>
						<th rowspan="2" align="center" class="text-center">STATUS</th>
					</tr>
				</thead>

				<tbody id="list_body_data">
					<tr ng-repeat="d in data" ng-class="{danger:d.status == 'STOP BEKERJA', success:d.status == 'SIAP BEKERJA', warning:d.status == 'PENGAWASAN KHUSUS'}">
						<td>{{$index + 1}}</td>
						<td>{{d.date_prajob}}</td>
						<td>{{d.nrp_opr}}</td>
						<td>{{d.nama_opr}}</td>
						<td class="text-center">{{d.shift}}</td>
						<td>{{d.lokasi}}</td>
						<td>{{d.no_unit}}</td>
						<td class="text-center">{{d.jam_kritis}}</td>
						<td class="text-center">{{d.status}}</td>
					</tr>
				</tbody>
			</table>
			
		</div>
	</div>

</div>

