<div class="row">
	<div class="col-xs-12">
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

						<div class=" col-sm-2">
							<div class=" input-group ">
								<input class="form-control date-picker" name="date_fill" id="date_fill" type="text" data-date-format="yyyy-mm-dd" value="<?= $rs->date_fill ?>" />
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
									<option value="<?= $f->id ?>" <?= $rs->fuel_tank_id == $f->id ? 'selected' : '' ?>><?= $f->name ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>
			 		<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Unit </label>
							<div class="col-sm-9">
								<?php echo form_error('equipment_id', '<div class="col-sm-12 error">', '</div>'); ?>
								<select name="equipment_id" id="equipment_id" class="chosen-select" style="width: 200px;">
								<?php
								foreach ($list_unit as $l) {
									$selected  = "";
									if ($l["master_equipment_id"] == $rs->equipment_id) $selected = " selected";
								?>
								<option value="<?php echo $l["master_equipment_id"];?>" <?php echo $selected;?>><?php echo $l["unit"]." - ".$l["alokasi"];?></option>
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
								<select name="shift" id="shift" class="chosen-select" style="width: 170px;">
									<?php
									$selected_1 = ""; $selected_2 = "";
									if ($rs->shift =="1") $selected_1 = " selected";
									if ($rs->shift =="2") $selected_2 = " selected";
									?>
								<option value="1" <?= $selected_1 ?>>1</option>
								<option value="2" <?= $selected_2 ?>>2</option>
							 </select>
							</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Perintah Isi </label>
						<div class="col-sm-3">
						<input name="total_liter" id="total_liter" class="" type="text" placeholder="Jml Isi" value="<?= $rs->total_liter ?>" readonly>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Realisasi Pengisian </label>
						<div class="col-sm-3">
						<input   name="total_realisasi" id="total_realisasi" class="" type="text" placeholder="Jml Isi"  value="<?= $rs->total_realisasi ?>">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> HM</label>
						<div class="col-sm-2">
						<input  name="hm" id="hm_after" class="" type="text" placeholder="HM" value="<?= $rs->hm ?>">
						</div>
						<div class="col-sm-2">
						<input name="hm_last" id="hm_last" class="" type="text" placeholder="HM LAST" value="<?= $rs->hm_last ?>" >
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> KM</label>
						<div class="col-sm-2">
						<input name="km" id="km" class="" type="text" placeholder="KM" value="<?= $rs->km ?>">
						</div>
						<div class="col-sm-2">
						<input name="km_last" id="km_last" class="" type="text" placeholder="KM LAST" value="<?= $rs->km_last;?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> NRP / Nama </label>
							<div class="col-sm-9">
								<select name="nrp" id="nrp" class="chosen-select">
									<option value="">- Pilih NRP / Nama -</option>
								<?php
								FOREACH ($list_nrp AS $l) {
									$selected  = "";
									if ($l["nrp"] == $rs->nrp) $selected = " selected";
								?>
								<option value="<?php echo $l["nrp"];?>" <?php echo $selected;?>><?php echo $l["nrp"]." - ". $l["nama"];?></option>
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
								<input class="form-control  bootstrap-timepicker" name="time_fill_start" id="time_fill_start" type="text" data-date-format="HH:mm"  value="<?= $rs->time_fill_start ?>"/>
							<span class="input-group-addon">
								<i class="fa fa-clock-o bigger-110"></i>
							</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Finish Time</label>

						<div class=" col-sm-2">
							<div class=" input-group ">
								<input class="form-control  bootstrap-timepicker" name="time_fill_end" id="time_fill_end" type="text" data-date-format="HH:mm"  value="<?= $rs->time_fill_end ?>"/>
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
