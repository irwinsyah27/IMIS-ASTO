<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-list"></i> TASK LIST
		</h3>
	</div>
	<div class="panel-body">
		<a href="<?= base_url('task/add') ?>" class="btn btn-primary btn-sm">
			<i class="fa fa-plus"></i> CREATE TASK
		</a>
		<form class="form-inline pull-right" action="" method="get">
			<select class="form-control" name="is_scope">
				<option value="">-- Scope --</option>
				<option value="0">Non-Scope</option>
				<option value="1">Is Scope</option>
			</select>
			<select class="form-control" name="priority">
				<option value="">-- Priority --</option>
				<option value="0">Low</option>
				<option value="1">Medium</option>
				<option value="2">High</option>
			</select>
			<!-- <select class="form-control" name="status">
				<option value="">-- Status --</option>
				<option value="0">Open</option>
				<option value="1">On Progress</option>
				<option value="2">Testing</option>
				<option value="3">Completed</option>
			</select> -->

			<input type="text" name="q" placeholder="Search Task" class="form-control" value="<?= $this->input->get('q') ?>">

			<button type="submit" name="search" class="btn btn-info btn-sm">
				<i class="fa fa-search"></i>
			</button>

			<a href="<?= base_url('/task') ?>" class="btn btn-info btn-sm" id="reset">
				<i class="fa fa-refresh"></i> Reset
			</a>
        </form>
	</div>

	<div class="tabbable">
		<ul class="nav nav-tabs padding-12 tab-color-blue background-blue">
			<li class="active">
				<a data-toggle="tab" href="#open" aria-expanded="true">
					Open <span class="badge badge-danger" id="total-0"></span>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#progress" aria-expanded="false">
					On Progress <span class="badge badge-warning" id="total-1"></span>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#testing" aria-expanded="false">
					Testing <span class="badge badge-info" id="total-2"></span>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#completed" aria-expanded="false">
					Completed <span class="badge badge-success" id="total-3"></span>
				</a>
			</li>
		</ul>

		<div class="tab-content">
			<div id="open" class="tab-pane active">
				<div id="table-0"> </div>
				<div class="text-right" id="pager-0"> </div>
				<div class="clearfix"> </div>
			</div>
			<div id="progress" class="tab-pane">
				<div id="table-1"> </div>
				<div class="text-right" id="pager-1"> </div>
			</div>
			<div id="testing" class="tab-pane">
				<div id="table-2"> </div>
				<div class="text-right" id="pager-2"> </div>
			</div>
			<div id="completed" class="tab-pane">
				<div id="table-3"> </div>
				<div class="text-right" id="pager-3"> </div>
			</div>
		</div>
	</div>
</div>
