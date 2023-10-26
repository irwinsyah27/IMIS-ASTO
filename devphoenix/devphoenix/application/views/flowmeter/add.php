<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			<div class="pull-right tableTools-container"></div>
		</div>
		<div class="table-header">
			<?php if (isset($title) && $title<>"") echo $title;?>
			<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
			<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('flowmeter/view');?>">
				<i class="ace-icon fa fa-list-alt bigger-120 blue"></i>
				List Data
			</a>
			<?php } ?>
		</div>

		<div class="clearfix">
			<div class="pull-right tableTools-container"></div>
		</div>

		<div class="row">
			<div>
				<form method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
			 		<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Tgl</label>
						<div class=" col-sm-2">
							<div class=" input-group ">
								<input style="background-color: #e5f62a" class="form-control date-picker" name="tgl" id="tgl" type="text" data-date-format="yyyy-mm-dd" value="<?= date('Y-m-d') ?>"  style="background-color: #e5f62a" />
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Status</label>
						<div class=" col-sm-2">
							<div class=" input-group ">
								<select class="form-control" name="status" id="status">
									<option value="">-- Pilih Status --</option>
									<?php foreach (['R' => 'Receive', 'T' => 'Transfer', 'I' => 'Issued'] as $k => $v): ?>
									<option value="<?= $k ?>"><?= $v ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Fuel Tank</label>
						<div class=" col-sm-2">
							<div class=" input-group ">
								<select class="form-control" name="fuel_tank_id" id="fuel-tank-id">
									<option value="">-- Pilih Fuel Tank --</option>
									<?php foreach ($this->db->order_by('name', 'ASC')->get('master_fuel_tank')->result() as $f): ?>
									<option value="<?= $f->id ?>"><?= $f->name ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Flowmeter awal</label>
						<div class=" col-sm-2">
							<div class=" input-group ">
								<input class="form-control" name="flowmeter_awal" id="flowmeter_awal" type="number" style="background-color: #e5f62a"/>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Flowmeter akhir</label>
						<div class=" col-sm-2">
							<div class=" input-group ">
								<input class="form-control" name="flowmeter_akhir" id="flowmeter_akhir" type="number" style="background-color: #e5f62a"/>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Sounding awal</label>
						<div class=" col-sm-2">
							<div class=" input-group ">
								<input class="form-control" name="sounding_awal" id="sounding_awal" type="number" style="background-color: #e5f62a"/>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Sounding akhir</label>
						<div class=" col-sm-2">
							<div class=" input-group ">
								<input class="form-control" name="sounding_akhir" id="sounding_akhir" type="number" style="background-color: #e5f62a"/>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Volume By Sounding (Liter)</label>
						<div class=" col-sm-2">
							<div class=" input-group ">
								<input class="form-control" name="volume_by_sounding" id="volume_by_sounding" type="number" style="background-color: #e5f62a"/>
							</div>
						</div>
					</div>

					</div>
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
							<button class="btn btn-info" type="button" id="SubmitData">
								<i class="ace-icon fa fa-check bigger-110"></i>
								Submit
							</button>
							<?php } ?>
							<button class="btn" type="reset">
								<i class="ace-icon fa fa-undo bigger-110"></i>
								Reset
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>

	</div>
</div>
