<div id="modal-export" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal">
            <div class="modal-header no-padding">
                <div class="table-header">
                    Eksport Data CT and FPI
                </div>
            </div>

            <div class="modal-body" style="min-height:100px; margin:10px">
            	<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Periode Tanggal</label>
				<div class="col-sm-8">
					<div class="input-daterange input-group">
						<input type="text" class="input-sm form-control" ng-model="export_start" />
						<span class="input-group-addon">
							<i class="fa fa-exchange"></i>
						</span>

						<input type="text" class="input-sm form-control" ng-model="export_end" />
					</div>
				</div>
			</div>
            </div>

            <div class="modal-footer no-margin-top">
                <button class="btn btn-sm  btn-primary pull-left" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    Tutup
                </button>
                <button class="btn btn-sm btn-primary pull-right" ng-click="doExport()">
                    <i class="ace-icon fa fa-times"></i>
                    Eksport
                </button>
            </div>
             </form>
        </div>
    </div>
</div>
