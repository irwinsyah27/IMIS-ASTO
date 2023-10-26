<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">EDIT TASK</h3>
	</div>
	<form class="form-horizontal" method="POST" enctype="multipart/form-data">
		<div class="panel-body">
			<div class="form-group <?= form_error('task[title]') ? 'has-error' : '' ?>">
				<label for="" class="control-label col-md-3">Title</label>
				<div class="col-md-5">
					<input type="text" name="task[title]" value="<?= set_value('task[title]', $task->title) ?>" class="form-control" placeholder="Task Title">
					<?= form_error('task[title]', '<span class="text-danger">', '</span>') ?>
				</div>
			</div>
			<div class="form-group <?= form_error('task[description]') ? 'has-error' : '' ?>">
				<label for="" class="control-label col-md-3">Description</label>
				<div class="col-md-5">
					<textarea name="task[description]" rows="4" class="form-control" placeholder="Description"><?= set_value('task[description]', $task->description) ?></textarea>
					<?= form_error('task[description]', '<span class="text-danger">', '</span>') ?>
				</div>
			</div>
			<div class="form-group <?= form_error('file') ? 'has-error' : '' ?>">
				<label for="" class="control-label col-md-3">Attach File</label>
				<div class="col-md-5">
					<input type="file" name="file" class="form-control">
					<?= form_error('file', '<span class="text-danger">', '</span>') ?>
				</div>
			</div>
			<div class="form-group <?= form_error('task[priority]') ? 'has-error' : '' ?>">
				<label for="" class="control-label col-md-3">Priority</label>
				<div class="col-md-5">
					<?php $priorities = ['Low', 'Medium', 'High'] ?>
					<select class="form-control" name="task[priority]">
						<option value="">-- Select Priority --</option>
						<?php foreach ($priorities as $i => $v) : ?>
						<option value="<?= $i ?>" <?= set_select('task[priority]', $i, $i == $task->priority) ?>><?= $v ?></option>
						<?php endforeach ?>
					</select>
					<?= form_error('task[priority]', '<span class="text-danger">', '</span>') ?>
				</div>
			</div>
			<div class="form-group <?= form_error('task[owner_id]') ? 'has-error' : '' ?>">
				<label for="" class="control-label col-md-3">PIC</label>
				<div class="col-md-5">
					<?php $pic = $this->db->order_by('nama', 'ASC')->get('user'); ?>
					<select class="chosen-select form-control" name="task[owner_id]" data-placeholder="Pilih PIC">
						<option value="">-- Select PIC --</option>
						<?php foreach ($pic->result() as $p) : ?>
						<option value="<?= $p->user_id ?>" <?= set_select('task[owner_id]', $p->user_id, $p->user_id == $task->owner_id) ?>>
							<?= $p->nama ?>
						</option>
						<?php endforeach ?>
					</select>
					<?= form_error('task[owner_id]', '<span class="text-danger">', '</span>') ?>
				</div>
			</div>
			<div class="form-group <?= form_error('task[is_scope]') ? 'has-error' : '' ?>">
				<label for="" class="control-label col-md-3">Is Scope</label>
				<div class="col-md-5">
					<?php $scopes = ['No', 'Yes'] ?>
					<select class="form-control" name="task[is_scope]">
						<option value="">-- Is Scope --</option>
						<?php foreach ($scopes as $i => $v) : ?>
						<option value="<?= $i ?>" <?= set_select('task[is_scope]', $i, $i == $task->is_scope) ?>><?= $v ?></option>
						<?php endforeach ?>
					</select>
					<?= form_error('task[is_scope]', '<span class="text-danger">', '</span>') ?>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<div class="form-group">
				<div class="col-md-9 col-md-offset-3">
					<button type="submit" name="submit" class="btn btn-primary btn-sm">
						<i class="fa fa-save"></i> SIMPAN
					</button>
					<a href="<?= base_url('task') ?>" class="btn btn-info btn-sm">
						KEMBALI
					</a>
				</div>
			</div>
		</div>
	</form>
</div>
