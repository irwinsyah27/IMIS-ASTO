		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() {   
				  	$("#frmData" ).submit();
				}); 
				/*
				 
				$('#date_time_in').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 
				$('#date_time_out').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 
	*/
				$('#date_time_out').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 

				$('#eta_rfu_unit').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 

				$('#eta_waiting_part').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 

				$(".chosen-select").chosen();  
			})
		</script>