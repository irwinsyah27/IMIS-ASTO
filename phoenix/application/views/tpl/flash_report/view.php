
	<div id="modal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">FLASH REPORT</h4>
			</div>
			<div class="modal-body">
				<img id="img-report" src="assets/img-report/report.jpg" alt="img-report" class="img-responsive">
			</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			<div class="pull-right tableTools-container"></div>
		</div>

		<div class="clearfix">
			<div class="pull-right tableTools-container"></div>
		</div>

		<div class="row">
			<div>
				<form enctype="multipart/form-data" action="<?php echo base_url('flash_report/import_action');?>" method="post" name="frmData" id="frmData"  class="form-horizontal" role="form">
			 		<div class="form-group">
						<div class="col-sm-12">
								<input  name="userfile" id="userfile" class="col-xs-10 col-sm-2 entry_data" type="file">
						</div>
					</div>
					</div>
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>
							<button class="btn btn-info" type="button" id="SubmitData">
								<i class="ace-icon fa fa-check bigger-110"></i>
								Upload
							</button>
							<?php } ?>
							<button class="btn btn-warning" type="button" id="testFlashReport">
								<i class="ace-icon fa fa-undo bigger-110"></i>
								Test Flash Report
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>

	</div>
</div>
