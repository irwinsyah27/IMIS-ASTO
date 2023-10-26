<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
			<th>
				ID
				<span class="pull-right">
					<a class="order" href="#" data-field="id" data-order="desc" title="Order by ID desc">
						<i class="fa fa-caret-up"></i>
					</a>
					<a class="order" href="#" data-field="id" data-order="asc" title="Order by ID asc">
						<i class="fa fa-caret-down"></i>
					</a>
				</span>
			</th>
			<th>
				Task
				<span class="pull-right">
					<a class="order" href="#" data-field="title" data-order="desc" title="Order by title desc">
						<i class="fa fa-caret-up"></i>
					</a>
					<a class="order" href="#" data-field="title" data-order="asc" title="Order by title asc">
						<i class="fa fa-caret-down"></i>
					</a>
				</span>
			</th>
			<th>
				Creator
				<span class="pull-right">
					<a class="order" href="#" data-field="creator" data-order="desc" title="Order by creator desc">
						<i class="fa fa-caret-up"></i>
					</a>
					<a class="order" href="#" data-field="creator" data-order="asc" title="Order by creator asc">
						<i class="fa fa-caret-down"></i>
					</a>
				</span>
			</th>
			<th>
				PIC
				<span class="pull-right">
					<a class="order" href="#" data-field="pic" data-order="desc" title="Order by PIC desc">
						<i class="fa fa-caret-up"></i>
					</a>
					<a class="order" href="#" data-field="pic" data-order="asc" title="Order by PIC asc">
						<i class="fa fa-caret-down"></i>
					</a>
				</span>
			</th>
			<th>
				Scope
				<span class="pull-right">
					<a class="order" href="#" data-field="is_scope" data-order="desc" title="Order by scope desc">
						<i class="fa fa-caret-up"></i>
					</a>
					<a class="order" href="#" data-field="is_scope" data-order="asc" title="Order by scope asc">
						<i class="fa fa-caret-down"></i>
					</a>
				</span>
			</th>
			<th>
				Priority
				<span class="pull-right">
					<a class="order" href="#" data-field="priority" data-order="desc" title="Order by priority desc">
						<i class="fa fa-caret-up"></i>
					</a>
					<a class="order" href="#" data-field="priority" data-order="asc" title="Order by priority asc">
						<i class="fa fa-caret-down"></i>
					</a>
				</span>
			</th>
			<th>
				Created
				<span class="pull-right">
					<a class="order" href="#" data-field="ctime" data-order="desc" title="Order by created desc">
						<i class="fa fa-caret-up"></i>
					</a>
					<a class="order" href="#" data-field="ctime" data-order="asc" title="Order by created asc">
						<i class="fa fa-caret-down"></i>
					</a>
				</span>
			</th>
			<th>
				Updated
				<span class="pull-right">
					<a class="order" href="#" data-field="utime" data-order="desc" title="Order by updated desc">
						<i class="fa fa-caret-up"></i>
					</a>
					<a class="order" href="#" data-field="utime" data-order="asc" title="Order by updated asc">
						<i class="fa fa-caret-down"></i>
					</a>
				</span>
			</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php $descPriority 	= ['Low', 'Medium', 'High']; ?>
		<?php $priorityCssClass = ['default', 'warning', 'danger']; ?>

		<?php foreach ($tasks as $t) : ?>
			<tr>
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
				<td>
					<span class="label label-<?= $priorityCssClass[$t->priority] ?>">
						<?= $descPriority[$t->priority] ?>
					</span>
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
