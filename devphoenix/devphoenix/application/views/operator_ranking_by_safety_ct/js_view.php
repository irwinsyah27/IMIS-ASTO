<script type="text/javascript">

jQuery(function($) {
    $('.date-picker, .input-daterange').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    $('.datetime-picker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
    });
});

myApp.controller('MyCtrl', function($scope, $http, $interval) {
	$scope.date_filter = '<?= date('Y-m-d') ?>';
	$scope.date = '<?= date('Y-m-d') ?>';

    $http.get('<?= site_url('api/getUnit')?>')
    .then(function(res) {
        $scope.units = [];
        res.data.forEach(function(i) {
            $scope.units.push(i.name);
        });

        $('#unit').bs_typeahead({source:$scope.units});
    });

    $http.get('<?= site_url('api/getEmployee')?>')
    .then(function(res) {
        $scope.employees = res.data;
        $scope.nips = [];
        $scope.names = [];
        res.data.forEach(function(i) {
            $scope.nips.push(i.nrp);
            $scope.names.push(i.nama);
        });

        $('#nrp').bs_typeahead({source:$scope.nips});
    });

    $scope.updateNama = function() {
        $scope.nama = $scope.names[$scope.nips.indexOf($scope.nrp)];
    };

    var updateData = function() {
		$scope.busy = true;
		$http.get('<?= site_url('api/getCtFpi') ?>?date='+$scope.date_filter)
        .then(function(res) {
			$scope.busy = false;
			$scope.data = res.data;
		});
	};

	$scope.updateData = updateData;
	updateData();

    $scope.add = function() {
        $scope.daily_absent_id = 0;
        $scope.ct_id = 0;
        $scope.shift = 1;
        $scope.time_start = '<?= date('Y-m-d') ?> 06:00';
        $scope.time_stop = '<?= date('Y-m-d') ?> 18:00';
        $scope.ct1_idle = '00:00:00';
        $scope.ct2_idle = '00:00:00';
        $scope.ct3_idle = '00:00:00';
        $scope.nama = "";
        $scope.nrp = "";

        $('#modal-form').modal({show:true});
    };

    $scope.edit = function(daily_absent_id, ct_id) {
        $scope.daily_absent_id = daily_absent_id;
        $scope.ct_id = ct_id ? ct_id : 0;

        $http.get('<?= site_url('api/getSingleCtFpi')?>/' + daily_absent_id + "/" + ct_id)
        .then(function(res) {
            $scope.date = res.data.date;
            $scope.shift = res.data.shift;
            $scope.time_start = res.data.date + " " + res.data.time_start_position_station;
            $scope.time_stop = res.data.date_out + " " + res.data.time_stop_position_station;
            $scope.nrp = res.data.nip;
            $scope.unit = res.data.unit;
            $scope.nama = res.data.nama;

            if (res.data.ct != null) {
                $scope.ct1_start = res.data.ct.ct1_start;
                $scope.ct2_start = res.data.ct.ct2_start;
                $scope.ct3_start = res.data.ct.ct3_start;
                $scope.ct1_end = res.data.ct.ct1_end;
                $scope.ct2_end = res.data.ct.ct2_end;
                $scope.ct3_end = res.data.ct.ct3_end;
                $scope.ct1_idle = res.data.ct.ct1_idle;
                $scope.ct2_idle = res.data.ct.ct2_idle;
                $scope.ct3_idle = res.data.ct.ct3_idle;
                $scope.fpi1 = res.data.ct.fpi1;
                $scope.fpi2 = res.data.ct.fpi2;
                $scope.fpi3 = res.data.ct.fpi3;
            }

            $('#modal-form').modal({show:true});
        });
    };

    $scope.save = function() {
        if (!$scope.nips.includes($scope.nrp)) {
            alert('NRP tidak terdaftar!');
            return;
        }

        if (!$scope.units.includes($scope.unit)) {
            alert('UNIT tidak terdaftar!');
            return;
        }

        if ($scope.ct1_start == null || $scope.ct1_end == null || $scope.fpi1 == null) {
            alert("Mohon lengkapi CT 1");
            return;
        }

        var data = {
            daily_absent_id: $scope.daily_absent_id,
            ct_id: $scope.ct_id,
            date: $scope.date,
            shift: $scope.shift,
            time_start: $scope.time_start,
            time_stop: $scope.time_stop,
            nrp: $scope.nrp,
            unit: $scope.unit,
            ct1_start: $scope.ct1_start,
            ct2_start: $scope.ct2_start,
            ct3_start: $scope.ct3_start,
            ct1_end: $scope.ct1_end,
            ct2_end: $scope.ct2_end,
            ct3_end: $scope.ct3_end,
            ct1_idle: $scope.ct1_idle,
            ct2_idle: $scope.ct2_idle,
            ct3_idle: $scope.ct3_idle,
            fpi1: $scope.fpi1,
            fpi2: $scope.fpi2,
            fpi3: $scope.fpi3,
        };

        $http.post('<?= site_url('api/saveCtFpi') ?>', data)
        .then(function(res) {
            if (res.data.status > 0) {
                alert('Data BERHASIL diedit');
                $('#modal-form').modal('hide');
                updateData();
            } else {
                alert('Data GAGAL diedit! Pesan error: ' + res.data.message);
            }
        });
    };

    $scope.delete = function(daily_absent_id, ct_id) {
        if (!confirm('Anda yakin akan menghapus data ini?')) {
            return;
        }

        var data = {
            daily_absent_id: daily_absent_id,
            ct_id: ct_id
        };

        $http.post('<?= site_url('api/deleteCtFpi') ?>', data)
        .then(function(res) {
            if (res.data.status > 0) {
                console.log('SUCCESS!');
            } else {
                console.log('FAILED!');
            }
            updateData();
        });
    };

    $scope.import = function() {
        $('#modal-import').modal({show:true});
    };

    $scope.export = function() {
        $scope.export_start = '<?= date('Y-m-d') ?>';
        $scope.export_end = '<?= date('Y-m-d') ?>';
        $('#modal-export').modal({show:true});
    };

    $scope.doExport = function() {
        window.location = '<?= site_url('operator_ranking_by_safety_ct/export') ?>/' + $scope.export_start + '/' + $scope.export_end;
    }

	$interval(function() {
		if ($scope.date_filter == '<?= date('Y-m-d') ?>') {
			updateData();
		}
	}, 10000);
});

</script>
