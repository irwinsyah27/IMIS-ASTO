		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() { 
					if ($("#kode").val() =="") { alert('Kode harus diisi'); return false; }  
					if ($("#keterangan").val() =="") { alert('Keterangan harus diisi'); return false; }
					if ($("#jam_mulai").val() =="") { alert('Start harus diisi'); return false; }
					if ($("#jam_selesai").val() =="") { alert('Stop harus diisi'); return false; }
					if ($("#durasi").val() =="") { alert('Durasi harus diisi'); return false; }
					if ($("#status").val() =="") { alert('Status harus diisi'); return false; }
				  	$("#frmData" ).submit();
				});
				$(".chosen-select").chosen(); 
				
			})
		</script>