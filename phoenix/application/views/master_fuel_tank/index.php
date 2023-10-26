<div class="panel panel-primary">
	<div class="panel-heading">
		<div class="pull-right">
			<a href="<?= base_url('master_fuel_tank/add') ?>" class="btn btn-info btn-xs">ADD FUEL TANK</a>
		</div>
		<h3 class="panel-title">MASTER FUEL TANK</h3>
		<div class="clearfix"></div>
	</div>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Capacity</th>
				<th>Stock</th>
				<th>Show On Chart</th>
				<th>Last Update Stock</th>
				<th></th>
			</tr>
		</thead>
		<tbody>

			<?php foreach ($fuelTanks as $f) : $lastUpdate = new Datetime($f->last_update_volume); ?>
				<tr>
					<td><?= $f->id ?></td>
					<td><?= $f->name ?></td>
					<td><?= number_format($f->capacity, 3) ?></td>
					<td><?= number_format($f->stock, 3) ?></td>
					<td>
						<?php if ($f->show_on_chart == 1) : ?>
							<span class="label label-success">Yes</span>
						<?php else: ?>
							<span class="label label-danger">No</span>
						<?php endif ?>
					</td>
					<td><?= $lastUpdate->format('d-M-Y H:i') ?></td>
					<td class="text-center">
						<a href="<?= base_url() ?>master_fuel_tank/edit/<?= $f->id ?>" class="green">
							<i class="ace-icon fa fa-pencil bigger-130"></i>
						</a>

						<!-- <a href="<?= base_url() ?>master_fuel_tank/delete/<?= $f->id ?>" class="confirm red">
						<i class="ace-icon fa fa-trash bigger-130"></i>
					</a> -->
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
</div>
