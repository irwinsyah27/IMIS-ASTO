<script type="text/javascript">

angular.module("app", []).controller("fatigueMonitorCtrl", function($scope,$http,$interval) {  

	jQuery(function($) {

		$('#txDate').datepicker({
			autoclose: true,
			todayHighlight: true,
		}).datepicker('setDate', 'today');;

		$(".chosen-select").chosen();  

	});

	

	var updateData = function() {
		var d = new Date();
		var day = d.getDate();
		var month = d.getMonth() + 1;
		var year = d.getFullYear();
		var curr_date = year + "-" + month + "-" + day ;
		$scope.busy = true;
		$scope.date = $("#txDate").val() == '' ? curr_date : $("#txDate").val() ;
		$scope.lokasi = $("#txLokasi").val() == '' ? '' : $("#txLokasi").val();
		$scope.nrp_opr = $("#txNrp").val() == '' ? '' : $("#txNrp").val();
		$scope.status = $("#txStatus").val() == '' ? '' : $("#txStatus").val();

		//alert($scope.date);

		console.log( $scope.date + '-' + $scope.lokasi + '-' + $scope.nrp_opr);

		$http.get('api/fatigueMonitoring?date='+$scope.date+'&lokasi='+$scope.lokasi+'&nrp_opr='+$scope.nrp_opr+'&status='+$scope.status).then(function(res) {
			$scope.busy = false;
			$scope.data = res.data;

			//Pagination

			// $scope.curPage = 1,
  			// $scope.itemsPerPage = 3,
  			// $scope.maxSize = 3;

	        // this.items = $scope.data;

			// $scope.numOfPages = function () {
			// 	return Math.ceil($scope.data.length / $scope.itemsPerPage);
				
			// };
			
			// $scope.$watch('curPage + numPerPage', function() {
			// 	var begin = (($scope.curPage - 1) * $scope.itemsPerPage),
			// 	end = begin + $scope.itemsPerPage;
				
			// 	$scope.filteredItems = $scope.data.slice(begin, end);
			// });
		});

	};

	$scope.updateData = updateData;

	updateData();

	$interval(function() {

		// if ($scope.date == '<?= date('Y-m-d') ?>') {
		// 	updateData();
		// }

		updateData();

	}, 60000);
	
});

function goToFormFatigueTest() {
	window.open('http://10.13.130.55/Reports/Pages/Report.aspx?ItemPath=%2fIMIS%2fRpt_fatigue_test', '_blank');
};

</script>
