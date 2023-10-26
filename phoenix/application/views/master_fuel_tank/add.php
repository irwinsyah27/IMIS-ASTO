<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">TAMBAH FUEL TANK</h3>
	</div>
	<form class="form-horizontal" method="post">
		<div class="panel-body">
			<div class="form-group <?= form_error('ft[name]') ? 'has-error' : '' ?>">
				<label for="" class="control-label col-md-3">Fuel Tank Name</label>
				<div class="col-md-5">
					<input type="text" name="ft[name]" value="<?= set_value('ft[nama]') ?>" class="form-control" placeholder="Fuel Tank Name" id="name">
				</div>
			</div>
			<div class="form-group <?= form_error('ft[capacity]') ? 'has-error' : '' ?>">
				<label for="" class="control-label col-md-3">Capacity (Liter)</label>
				<div class="col-md-5">
					<input type="number" step="any" name="ft[capacity]" value="<?= set_value('ft[capacity]') ?>" class="form-control" placeholder="Capacity" id="capacity">
				</div>
			</div>
			<div class="form-group <?= form_error('ft[stock]') ? 'has-error' : '' ?>">
				<label for="" class="control-label col-md-3">Stock (Liter)</label>
				<div class="col-md-5">
					<input type="number" step="any" name="ft[stock]" value="<?= set_value('ft[stock]') ?>" class="form-control" placeholder="Stock">
				</div>
			</div>
			<div class="form-group <?= form_error('ft[show_on_chart]') ? 'has-error' : '' ?>">
				<label for="" class="control-label col-md-3">Show On Chart</label>
				<div class="col-md-5">
					<?php $show = ['No', 'Yes'] ?>
					<select class="form-control" name="ft[show_on_chart]">
						<?php foreach ($show as $i => $v) : ?>
						<option value="<?= $i ?>" <?= set_select('ft[show_on_chart]', $i) ?>><?= $v ?></option>
						<?php endforeach ?>
					</select>
					<?= form_error('ft[show_on_chart]', '<span class="text-danger">', '</span>') ?>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<div class="form-group">
				<div class="col-md-9 col-md-offset-3">
					<button type="submit" name="submit" class="btn btn-primary">
						<i class="fa fa-save"></i> SIMPAN
					</button>
					<a href="<?= base_url('master_fuel_tank') ?>" class="btn btn-info">
						KEMBALI
					</a>
				</div>
			</div>
		</div>
	</form>
</div>
