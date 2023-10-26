<div id="modal-import" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" id="frmImportData" enctype="multipart/form-data" action="<?php echo base_url('operator_ranking_by_safety_ct/import');?>">
            <div class="modal-header no-padding">
                <div class="table-header">
                    Import data Flowmeter
                </div>
            </div>

            <div class="modal-body" style="min-height:100px; margin:10px">
            	<div class="progress"></div>
                <?php $this->mylib->inputfile("userfile");?>
            </div>

            <div class="modal-footer no-margin-top">
                <button class="btn btn-sm  btn-primary pull-left" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    Tutup
                </button>
                <button class="btn btn-sm btn-primary pull-right" id="submit-import-data">
                    <i class="ace-icon fa fa-times"></i>
                    Import
                </button>
            </div>
             </form>
        </div>
    </div>
</div>
