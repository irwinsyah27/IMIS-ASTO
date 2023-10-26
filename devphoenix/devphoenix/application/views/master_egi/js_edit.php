		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() { 
					if ($("#keterangan").val() =="") { alert('Keterangan harus diisi'); return false; } 
					if ($("#status").val() =="") { alert('Status harus diisi'); return false; }
				  	$("#frmData" ).submit();
				});
				$(".chosen-select").chosen(); 
				
			})
		</script>