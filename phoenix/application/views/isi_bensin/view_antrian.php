<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			<div class="pull-right tableTools-container"></div>
		</div>
		<div class="table-header">
			<?php if (isset($title) && $title<>"") echo $title;?>

		</div>
	</div>
</div>

		<div>
			 <!-- PAGE CONTENT BEGINS -->
<div class="row">
	<div class="col-sm-12">
		<table id="dynamic-table" class="table table-striped table-bordered table-hover">
			<thead class="thin-border-bottom">
				<tr>
					<th align="center">UNIT</th>
					<th align="center">SHIFT</th>
					<th align="center">TGL INSTRUKSI</th>
					<th align="center">JML INSTRUKSI</th>
					<th align="center"></th>
				</tr>
			</thead>

			<tbody id="list_body_data">
				<?php
				$no = 0;
				if (count($rs)>0) {
					FOREACH ($rs AS $l) {

				?>
				<tr>
					<td><?php echo $l["unit"];?></td>
					<td><?php echo $l["shift"];?></td>
					<td><?php echo $l["date_instruksi"];?></td>
					<td><?php echo $l["total_liter"];?></td>
					<td><a href="<?php echo _URL."isi_bensin/edit/".$l["fuel_refill_id"];?>">ISI SOLAR</a></td>
				</tr>
				<?php
					}
				}
				?>
			</tbody>
		</table>
	</div><!-- /.col -->
</div><!-- /.row -->
