		<script type="text/javascript">
			jQuery(function($) {  
				 
				   
				$( "#SubmitData" ).click(function() {   
					if ($("#tgl").val() =="") { alert('Tgl harus diisi'); return false; }  
					if ($("#unit").val() =="") { alert('Unit harus diisi'); return false; }   

				  	$("#frmData" ).submit();
				}); 


				$(".chosen-select").chosen();  
				$('#tgl').datepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				});
				  
			})
		</script>