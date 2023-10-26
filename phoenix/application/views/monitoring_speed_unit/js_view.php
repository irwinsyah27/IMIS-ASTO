<script type="text/javascript">
/*
jQuery(function($) {

    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    }).on('changeDate',function(e){
        console.log($(this).val());
        angular.element($(this))
    });
});
*/
// myApp.controller('MonitoringSpeedUnitCtrl', function($scope, $http, $interval) {

// 	$scope.date = '<?= date('Y-m-d') ?>';

// 	var updateData = function() {
// 		$scope.busy = true;
// 		$http.get('api/monitoringSpeedUnit?date='+$scope.date).then(function(res) {
// 			$scope.busy = false;
// 			$scope.data = res.data;
// 		});
// 	};

// 	$scope.updateData = updateData;

// 	updateData();

// 	$interval(function() {

// 		if ($scope.date == '<?= date('Y-m-d') ?>') {
// 			updateData();
// 		}

// 	}, 60000);

// });

angular.module("app", []).controller("MonitoringSpeedUnitCtrl", function($scope,$http,$interval) {  
   
    $scope.date = '<?= date('Y-m-d') ?>';

    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    }).on('changeDate',function(e){
        $scope.date = $(this).val();
    });


	var updateData = function() {
		$scope.busy = true;
		$http.get('api/monitoringSpeedUnit?date='+$scope.date).then(function(res) {
			$scope.busy = false;
			$scope.data = res.data;
                        console.log('tgl: ',$scope.date);
		});
	};

	$scope.updateData = updateData;

	updateData();

	$interval(function() {

		if ($scope.date == '<?= date('Y-m-d') ?>') {
			updateData();
		}

	}, 60000);
   
   // $scope.message="Hello World" 
});

</script>
