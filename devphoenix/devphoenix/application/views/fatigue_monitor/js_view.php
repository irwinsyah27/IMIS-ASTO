<script type="text/javascript">

angular.module("app", []).controller("fatigueMonitorCtrl", function($scope,$http,$interval) {  


	jQuery(function($) {

		$('#txDate').datepicker({
			autoclose: true,
			todayHighlight: true,
		});

		$(".chosen-select").chosen();  

	});

	var updateData = function() {
		$scope.busy = true;
		$scope.date = $("#txDate").val() == '' ? '' : $("#txDate").val() ;
		$scope.lokasi = $("#txLokasi").val() == '' ? '' : $("#txLokasi").val();
		$scope.nrp_gl = $("#txNrpGl").val() == '' ? '' : $("#txNrpGl").val();

		console.log( $scope.date + '-' + $scope.lokasi + '-' + $scope.nrp_gl);

		// var myTable =
		// $('#dynamic-table').DataTable( {
		// 	"bAutoWidth": false,
		// 	"aoColumnDefs": [
		// 	  { "bSortable": false, "aTargets": [ 12,2,9,11 ] }
		// 	],
		// 	"aaSorting": [
		// 	  	[0,'desc']
		// 	],
		// 	"sAjaxSource": "<?php echo base_url('fatigue_monitor/get_data');?>",
		// 	"bProcessing": true,
	    //     "bServerSide": true,
		// 	select: {
		// 		style: 'multi'
		// 	}
	    // } );

		// $http.get('api/fatigueMonitoring?date='+$scope.date+'&lokasi='+$scope.lokasi+'&nrp_gl='+$scope.nrp_gl).then(function(res) {
		// 	$scope.busy = false;
		// 	$scope.data = res.data;

		// 	//Pagination

		// 	$scope.curPage = 1,
  		// 	$scope.itemsPerPage = 3,
  		// 	$scope.maxSize = 3;

	    //     this.items = $scope.data;

		// 	$scope.numOfPages = function () {
		// 		return Math.ceil($scope.data.length / $scope.itemsPerPage);
				
		// 	};
			
		// 	$scope.$watch('curPage + numPerPage', function() {
		// 		var begin = (($scope.curPage - 1) * $scope.itemsPerPage),
		// 		end = begin + $scope.itemsPerPage;
				
		// 		$scope.filteredItems = $scope.data.slice(begin, end);
		// 	});
		// });

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
