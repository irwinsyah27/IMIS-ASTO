<script type="text/javascript">

jQuery(function($) {

    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true
    });
});

myApp.controller('MonitoringSpeedUnitCtrl', function($scope, $http, $interval) {

	$scope.date = '<?= date('Y-m-d') ?>';

	var updateData = function() {
		$scope.busy = true;
		$http.get('api/monitoringSpeedUnit?date='+$scope.date).then(function(res) {
			$scope.busy = false;
			$scope.data = res.data;
		});
	};

	$scope.updateData = updateData;

	updateData();

	$interval(function() {

		if ($scope.date == '<?= date('Y-m-d') ?>') {
			updateData();
		}

	}, 60000);

});

</script>
