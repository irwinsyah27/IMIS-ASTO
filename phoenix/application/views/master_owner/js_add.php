		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() {
					if ($("#kode").val() =="") { alert('Kode harus diisi'); return false; }  
					if ($("#keterangan").val() =="") { alert('Nama Perusahaan harus diisi'); return false; } 
					if ($("#status").val() =="") { alert('Status harus diisi'); return false; }
				  	$("#frmData" ).submit();
				}); 
				$(".chosen-select").chosen(); 
				  
			})
		</script>