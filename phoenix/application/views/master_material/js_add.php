		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() {
					if ($("#material_id").val() =="") { alert('Kode harus diisi'); return false; }  
					if ($("#material").val() =="") { alert('Nama Material harus diisi'); return false; } 
					if ($("#status").val() =="") { alert('Status harus diisi'); return false; }
				  	$("#frmData" ).submit();
				}); 
				$(".chosen-select").chosen(); 
				  
			})
		</script>