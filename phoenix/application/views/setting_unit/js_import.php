<script type="text/javascript">
			jQuery(function($) {  
				 
				   
				$( "#SubmitData" ).click(function() {    
				  	$("#frmData" ).submit();
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