		<script type="text/javascript">
			jQuery(function($) {  
				 
				  
				$("#flowmeter_awal").keyup(function() { 
					if (isNaN($("#flowmeter_awal").val())) $("#flowmeter_awal").val(0); 
				});
				$("#flowmeter_akhir").keyup(function() { 
					if (isNaN($("#flowmeter_akhir").val())) $("#flowmeter_akhir").val(0); 
				});
				$( "#SubmitData" ).click(function() {  
					if ($("#tgl").val() =="") { alert('Tgl harus diisi'); return false; }  
				  	$("#frmData" ).submit();
				}); 
				 
				$('#date_weigher').datepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				});
				/*
				$('#time_weigher').timepicker({showSeconds: false,showMeridian: false}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				});
				*/



				$(".chosen-select").chosen();  
				$('.date-picker').datepicker({
						autoclose: true,
						todayHighlight: true
					})
			})
		</script>