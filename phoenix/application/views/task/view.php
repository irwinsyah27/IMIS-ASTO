<?php $statusCssClass 	= ['danger', 'warning', 'info', 'success']; ?>
<?php $descStatus 		= ['Open', 'On Progress', 'Testing', 'Completed']; ?>
<?php $descPriority 	= ['Low', 'Medium', 'High']; ?>
<?php $priorityCssClass = ['default', 'warning', 'danger']; ?>

<?php if ($task->status < 3) : ?>
<div class="pull-right">
	<a href="<?= base_url('task/edit/'.$task->id) ?>" class="btn btn-primary btn-sm">
		<i class="fa fa-edit"></i> EDIT
	</a>
</div>
<?php endif ?>

<h3>
	<?= $task->title ?>
	<span class="label label-<?= $priorityCssClass[$task->priority] ?>">
		<?= $descPriority[$task->priority] ?>
	</span>
	<span class="label label-<?= $task->is_scope ? 'success' : 'danger' ?>">
		<?= $task->is_scope ? 'Scope' : 'Non-Scope' ?>
	</span>
	<span class="label label-<?= $statusCssClass[$task->status] ?>">
		<?= $descStatus[$task->status] ?>
	</span> <br>
	<small><?= date('d-M-Y H:i', $task->ctime) ?></small>
</h3>
<hr>

<div class="well">
	<strong>
		<?= $task->creator ?> &bull; <?= date('d-M-Y H:i', $task->ctime) ?>
	</strong>
	<p><?= nl2br($task->description) ?></p>

	<?php if ($task->file !== NULL && file_exists('.'.$task->file)): ?>
	<p>
		<a href="<?= base_url($task->file) ?>" target="_blank">
			<i class="fa fa-paperclip"></i>
			Attachment
		</a>
	</p>
	<?php endif ?>
</div>

<?php foreach ($comments as $c) : ?>
	<div class="well">
		<?php if ($c->user_id == $_SESSION['id']): ?>
			<!-- <a href="<?= base_url('task/edit_comment/'.$c->id) ?>" class="pull-right btn btn-info btn-xs">
				<i class="fa fa-edit"></i> Edit
			</a> -->
		<?php endif ?>
		<strong>
			<?= $c->user ?> &bull; <?= date('d-M-Y H:i', $c->ctime) ?>
		</strong>
		<p><?= nl2br($c->comment)?></p>

		<?php if ($c->file !== '' && file_exists('.'.$c->file)): ?>
		<p>
			<a href="<?= base_url($c->file) ?>" target="_blank">
				<i class="fa fa-paperclip"></i>
				Attachment
			</a>
		</p>
		<?php endif ?>

		<span class="label label-<?= $priorityCssClass[$c->priority] ?>">
			<?= $descPriority[$c->priority] ?>
		</span>
		<span class="label label-<?= $c->is_scope ? 'success' : 'danger' ?>">
			<?= $c->is_scope ? 'Scope' : 'Non-Scope' ?>
		</span>
		<span class="label label-<?= $statusCssClass[$c->status] ?>">
			<?= $descStatus[$c->status] ?>
		</span>
	</div>
<?php endforeach ?>

<?php if ($task->status < 3) : ?>
<?php $this->load->view('task/_form-comment') ?>
<?php endif ?>
