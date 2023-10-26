		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() {  
				  	$("#frmData" ).submit();
				}); 
				 
				$('#date_fill').datepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 
				
				$('#time_fill').timepicker({showSeconds: false,showMeridian: false}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				});



				$(".chosen-select").chosen();  
			})
		</script>