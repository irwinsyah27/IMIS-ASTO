		<script type="text/javascript">
			jQuery(function($) {  
				  
				$( "#SubmitData" ).click(function() {  
					if ($("#day").val() =="") { alert('Hari harus diisi'); return false; }  
					if ($("#unit").val() =="") { alert('Unit harus diisi'); return false; }   
				  	$("#frmData" ).submit();
				}); 
				  
			})
		</script>