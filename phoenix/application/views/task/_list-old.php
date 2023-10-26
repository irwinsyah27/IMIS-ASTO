<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
			<th>ID</th>
			<th>Task</th>
			<th>Creator</th>
			<th>PIC</th>
			<th>Scope</th>
			<th>Priority</th>
			<th>Status</th>
			<th>Created</th>
			<th>Updated</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php $statusCssClass 	= ['danger', 'warning', 'info', 'success']; ?>
		<?php $descStatus 		= ['Open', 'On Progress', 'Testing', 'Completed']; ?>
		<?php $descPriority 	= ['Low', 'Medium', 'High']; ?>
		<?php $priorityCssClass = ['text-default', 'text-warning', 'text-danger']; ?>

		<?php foreach ($tasks as $t) : ?>
			<tr class="<?= $statusCssClass[$t->status] ?>">
				<td><?= $t->id ?></td>
				<td>
					<a href="<?= base_url() ?>task/view/<?= $t->id ?>"><?= $t->title ?></a>
				</td>
				<td><?= $t->creator ?></td>
				<td><?= $t->pic ?></td>
				<td>
					<?php if ($t->is_scope) : ?>
						<span class="label label-success">Yes</span>
					<?php else: ?>
						<span class="label label-danger">No</span>
					<?php endif ?>
				</td>
				<td class="<?= $priorityCssClass[$t->priority] ?>">
					<strong><?= $descPriority[$t->priority] ?></strong>
				</td>
				<td class="text-<?= $statusCssClass[$t->status] ?>">
					<strong><?= $descStatus[$t->status] ?></strong>
				</td>
				<td><?= date('d-M-Y H:i', $t->ctime) ?></td>
				<td><?= date('d-M-Y H:i', $t->utime) ?></td>
				<td class="text-center">
					<a href="<?= base_url() ?>task/edit/<?= $t->id ?>" class="green">
						<i class="ace-icon fa fa-pencil bigger-130"></i>
					</a>

					<a href="<?= base_url() ?>task/delete/<?= $t->id ?>" class="confirm red">
						<i class="ace-icon fa fa-trash bigger-130"></i>
					</a>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
