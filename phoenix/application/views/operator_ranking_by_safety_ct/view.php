<div ng-app="myApp" ng-controller="MyCtrl">

    <div ng-if="busy" style="background-color:yellow;padding:5px;width:300px;position:absolute;z-index:10000;top:47px;right:0;left:0;margin-left:auto;margin-right:auto;border:1px solid #999;text-align:center;">Getting data...</div>

    <div class="row">
        <div class="col-xs-12">
            <div class="clearfix">
                <div class="pull-right tableTools-container"></div>
            </div>
            <div class="table-header">
                <?php if (isset($title) && $title<>"") echo $title;?>
                <?php if  (_USER_ACCESS_LEVEL_ADD == "1") { ?>

                <a class="btn btn-white btn-info btn-bold pull-right" href="#" ng-click="add()">
                    <i class="ace-icon fa fa-floppy-o bigger-120 blue"></i>
                    Tambah Data
                </a>
                <?php } ?>
                <?php if  (_USER_ACCESS_LEVEL_IMPORT == "1") { ?>
                <a class="btn btn-white btn-info btn-bold pull-right" ng-click="import()">
                    <i class="ace-icon fa bigger-120 blue"></i>
                    Import
                </a>
                <?php } ?>
                <?php if  (_USER_ACCESS_LEVEL_EKSPORT == "1") { ?>
                <a class="btn btn-white btn-info btn-bold pull-right" ng-click="export()">
                    <i class="ace-icon fa bigger-120 blue"></i>
                    Eksport
                </a>
                <?php } ?>
            </div>
            <div class="input-group col-sm-3">
                <span class="input-group-addon">
                    <i class="ace-icon fa fa-check"></i>
                </span>

                <input type="text" ng-model="date_filter" class="form-control search-query  date-picker" data-date-format="yyyy-mm-dd" placeholder="" />
                <span class="input-group-btn">
                    <button ng-click="updateData()"  type="button" class="btn btn-purple btn-sm">
                        <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                        Filter
                    </button>
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                <thead class="thin-border-bottom">
                    <tr>
                        <th align="center" rowspan="2">NO</th>
                        <th align="center" rowspan="2">NAMA OPERATOR</th>
                        <th align="center" rowspan="2">JAM IN OP</th>
                        <th align="center" rowspan="2">JAM OUT OP</th>
                        <th align="center" rowspan="2">UNIT</th>
                        <th align="center" colspan="2">1</th>
                        <th align="center" colspan="2">2</th>
                        <th align="center" colspan="2">3</th>
                        <th align="center" rowspan="2">Aksi</th>
                    </tr>
                    <tr>
                        <th align="center">CT</th>
                        <th align="center">FPI</th>
                        <th align="center">CT</th>
                        <th align="center">FPI</th>
                        <th align="center">CT</th>
                        <th align="center">FPI</th>
                    </tr>
                </thead>

                <tbody>
                    <tr ng-repeat="d in data">
                        <td>{{$index + 1}}</td>
                        <td>{{d.nama_operator}} (S{{d.shift}})</td>
                        <td>{{d.time_start_position_station}}</td>
                        <td>{{d.time_stop_position_station}}</td>
                        <td>{{d.unit}}</td>
                        <td>{{d.ct.ct1 > 0 ? d.ct.ct1.toFixed(2) : 0}}</td>
                        <td>{{d.ct.fpi1}}</td>
                        <td>{{d.ct.ct2 > 0 ? d.ct.ct2.toFixed(2) : 0}}</td>
                        <td>{{d.ct.fpi2}}</td>
                        <td>{{d.ct.ct3 > 0 ? d.ct.ct3.toFixed(2) : 0}}</td>
                        <td>{{d.ct.fpi3}}</td>
                        <td class="text-center">
                            <?php if  (_USER_ACCESS_LEVEL_UPDATE == "1") : ?>
                            <a href="#" class="btn btn-info btn-xs" title="Edit" ng-click="edit(d.daily_absent_id, d.ct.id)">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <?php endif ?>
                            <?php if  (_USER_ACCESS_LEVEL_DELETE == "1") : ?>
                            <a href="#" class="btn btn-danger btn-xs" title="Hapus" ng-click="delete(d.daily_absent_id, d.ct.id)">
                                <i class="fa fa-trash"></i>
                            </a>
                            <?php endif ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    if  (_USER_ACCESS_LEVEL_ADD == "1") {
        $this->load->view('operator_ranking_by_safety_ct/_form');
    }

    if  (_USER_ACCESS_LEVEL_EKSPORT == "1") {
        $this->load->view('operator_ranking_by_safety_ct/_export');
    }

    if  (_USER_ACCESS_LEVEL_IMPORT == "1") {
        $this->load->view('operator_ranking_by_safety_ct/_import');
    }
    ?>
</div>
