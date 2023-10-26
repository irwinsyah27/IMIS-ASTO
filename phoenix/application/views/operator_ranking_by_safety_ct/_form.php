<div id="modal-form" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    TAMBAH/EDIT CYCLE TIME
                </div>
            </div>

            <div class="modal-body">
                <form class="form form-horizontal" role="form">
                    <div class="form-group">
                        <label class="control-label col-md-3">NIP</label>
                        <div class="col-md-4">
                            <input type="text" ng-model="nrp" ng-change="updateNama()" id="nrp" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <input type="text" ng-model="nama" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">UNIT</label>
                        <div class="col-md-8">
                            <input type="text" ng-model="unit" id="unit" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">DATE</label>
                        <div class="col-md-8">
                            <input type="text" ng-model="date" data-date-format="yyyy-mm-dd" class="form-control date-picker">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">SHIFT</label>
                        <div class="col-md-8">
                            <input type="radio" ng-model="shift" ng-value="1"> 1 <br>
                            <input type="radio" ng-model="shift" ng-value="2"> 2
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">DATE TIME START</label>
                        <div class="col-md-8">
                            <input type="text" ng-model="time_start" class="form-control datetime-picker" placeholder="yyyy-mm-dd hh:mm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">DATE TIME END</label>
                        <div class="col-md-8">
                            <input type="text" ng-model="time_stop" class="form-control datetime-picker" placeholder="yyyy-mm-dd hh:mm">
                        </div>
                    </div>
                    <?php for ($i=1;$i<=3;$i++): ?>
                    <div class="form-group">
                        <label class="control-label col-md-3">CT <?= $i ?></label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Date Time Start</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" style="margin-bottom:2px;" ng-model="ct<?= $i ?>_start" class="form-control datetime-picker" placeholder="yyyy-mm-dd hh:mm">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Date Time End</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" style="margin-bottom:2px;" ng-model="ct<?= $i ?>_end" class="form-control datetime-picker" placeholder="yyyy-mm-dd hh:mm">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Idle Time</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" style="margin-bottom:2px;" ng-model="ct<?= $i ?>_idle" class="form-control" placeholder="hh:mm:ss">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">FPI</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" style="margin-bottom:2px;" ng-model="fpi<?= $i ?>" class="form-control" placeholder="FPI">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endfor ?>
                </form>
            </div>

            <div class="modal-footer no-margin-top">
                <button class="btn btn-danger pull-left" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    BATAL
                </button>
                <button class="btn btn-primary pull-right" ng-click="save()">
                    <i class="ace-icon fa fa-floppy-o"></i>
                    SIMPAN
                </button>
            </div>
        </div>
    </div>
</div>
