<script type="text/javascript">
	jQuery(function($) {

		$( "#SubmitData").click(function() {
			if ($('#fuel-tank-id').val() == '') { alert('Fuel Tank harus diisi'); return false; }
			if ($('#status').val() == '') { alert('Status harus diisi'); return false; }
			if ($("#tgl").val() =="") { alert('Tgl harus diisi'); return false; }
		  	$("#frmData" ).submit();
		});

		$('#tgl').datepicker({
			autoclose: true,
			todayHighlight: true
		}).next().on(ace.click_event, function(){
			$(this).prev().focus();
		});

	});
</script>
