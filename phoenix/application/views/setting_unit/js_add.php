<script type="text/javascript">
			jQuery(function($) {  
				 
				   
				$( "#SubmitData" ).click(function() {   
					if ($("#unit").val() =="") { alert('Unit harus diisi'); return false; }   

				  	$("#frmData" ).submit();
				}); 


				$(".chosen-select").chosen();  
				  
			})
		</script>