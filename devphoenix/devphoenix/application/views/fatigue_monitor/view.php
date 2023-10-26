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
						<div class="form-group">
							<label for="name">TANGGAL</label>
							<input type="text" id="txDate" ng-model="date" class="form-control search-query date-picker" data-date-format="yyyy-mm-dd"  placeholder="select date..." />
						</div>
						<div class="form-group">
							<label for="nrpgl">NRP GL</label>
							<select name="txNrpGl" id="txNrpGl" class="chosen-select" style="width: 300px;" >
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
						<div class="form-group">
						<label for="lokasics">Lokasi</label>
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
						<th align="center">ID</th>
						<th align="center">TGL</th>
						<th align="center">NRP OPR</th>
						<th align="center">NAMA OPR</th>
						<th align="center">NRP GL</th>
						<th align="center">NAMA GL</th>
						<th align="center">SHIFT</th>
						<th align="center">LOKASI</th>
						<th align="center">UNIT</th>
						<th align="center">HARI INI</th>
						<th align="center">KEMARIN</th>
						<th align="center" class="text-center">PENGAWASAN</th>
						<th align="center" class="text-center">STOP KERJA</th>
						<th align="center" class="text-center">STATUS</th>
					</tr>
				</thead>

				<tbody id="list_body_data">
					
				</tbody>
			</table>
			
		</div>
	</div>

</div>

