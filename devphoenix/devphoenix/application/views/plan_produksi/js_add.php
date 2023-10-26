		<script type="text/javascript">
			jQuery(function($) {  
				  
				$( "#SubmitData" ).click(function() {   
					if ($("#plan_category_id").val() =="") { alert('Plan harus diisi'); return false; } 
					if ($("#date").val() =="") { alert('Date harus diisi'); return false; } 
					if ($("#time_start").val() =="") { alert('Time start harus diisi'); return false; } 
					if ($("#time_end").val() =="") { alert('Time end harus diisi'); return false; } 
					if ($("#delay").val() =="") { alert('Delay harus diisi'); return false; }  

				  	$("#frmData" ).submit();
				}); 
				 
				$('#date').datepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 


				$(".chosen-select").chosen();  
				$('.date-picker').datepicker({
						autoclose: true,
						todayHighlight: true
				}) 
			})
		</script>