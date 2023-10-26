		<script type="text/javascript">
			jQuery(function($) {  
				 
				   
				$( "#SubmitData" ).click(function() {    
				  	$("#frmData" ).submit();
				}); 
				  


				$(".chosen-select").chosen();  
				$('.date-picker').datepicker({
						autoclose: true,
						todayHighlight: true
					})
			})
		</script>