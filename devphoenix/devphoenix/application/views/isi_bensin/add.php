<div class="row">
	<div class="col-sm-12">
		<div class="clearfix">
			<div class="pull-right tableTools-container"></div>
		</div>
		<div class="table-header">
			Realisasi Pengisian Solar
			<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
			<a class="btn btn-white btn-info btn-bold pull-right" href="<?php echo base_url('isi_bensin/');?>">
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
							<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Tgl Isi</label>
						  <?php
						 if (date("G") >= 0 AND date("G") < 5) {
						 	$tgl = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d") - 1, date("Y") ));
						 } else $tgl = date("Y-m-d");
						?>
						<div class=" col-sm-3 ">
							<div class=" input-group ">
								<input class="form-control date-picker" name="date_fill" id="date_fill" type="text" data-date-format="yyyy-mm-dd" value="<?php echo $tgl;?>" />
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
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
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Unit </label>
							<div class="col-sm-9">
								<?php echo form_error('equipment_id', '<div class="col-sm-12 error">', '</div>'); ?>
								<select name="equipment_id" id="equipment_id" class="chosen-select" style="width: 300px;" >
									<option value="">- Pilih Unit -</option>
								<?php
								FOREACH ($list_unit AS $l) {
									$selected  = "";
								?>
								<option value="<?php echo $l["master_equipment_id"];?>" ><?php echo $l["unit"]." - ".$l["alokasi"];?></option>
								<?php
								}
								?>
							 </select>
							</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Shift </label>
							<div class="col-sm-9">
								<?php echo form_error('shift', '<div class="col-sm-12 error">', '</div>'); ?>
								<select name="shift" id="shift" class="chosen-select" style="width: 170px;" >
									<option value="">- Pilih Shift -</option>
									<?php
									$selected_1 = ""; $selected_2 = "";
									?>
								<option value="1" <?php echo $selected_1;?>>1</option>
								<option value="2" <?php echo $selected_2;?>>2</option>
							 </select>
							</div>
					</div>
					<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Cycle Time Unit Terakhir Kali</label>

						<div class=" col-sm-4">
							<div class=" input-group ">
								<input class="form-control " name="cycle_time_terakhir" id="cycle_time_terakhir" type="text" readonly />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Perintah Isi </label>
						<div class="col-sm-3">
						<input name="total_liter" id="total_liter" class="" type="text" placeholder="" readonly>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Realisasi Pengisian </label>
						<div class="col-sm-3">
						<input name="total_realisasi" id="total_realisasi" class="" type="text" placeholder=""  value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> HM</label>
						<div class="col-sm-2">
						<input name="hm" id="hm" class="" type="text" placeholder="" value="">
						</div>
						<div class="col-sm-2">
						<input name="hm_last" id="hm_last" class="" type="text" placeholder="" value="" >
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> KM</label>
						<div class="col-sm-2">
						<input name="km" id="km" class="" type="text" placeholder="" value="">
						</div>
						<div class="col-sm-2">
						<input name="km_last" id="km_last" class="" type="text" placeholder="" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> NRP / Nama </label>
							<div class="col-sm-9">
								<select name="nrp" id="nrp" class="chosen-select" style="width: 400px;" >
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
					</div>
					<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Start Time</label>

						<div class=" col-sm-2">
							<div class=" input-group ">
								<input class="form-control  bootstrap-timepicker" name="time_fill_start" id="time_fill_start" type="text" data-date-format="HH:mm"  value=""/>
							<span class="input-group-addon">
								<i class="fa fa-clock-o bigger-110"></i>
							</span>
							</div>
						</div>
					</div>
					<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Finish Time</label>

						<div class=" col-sm-2">
							<div class=" input-group ">
								<input class="form-control  bootstrap-timepicker" name="time_fill_end" id="time_fill_end" type="text" data-date-format="HH:mm"  value=""/>
							<span class="input-group-addon">
								<i class="fa fa-clock-o bigger-110"></i>
							</span>
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
