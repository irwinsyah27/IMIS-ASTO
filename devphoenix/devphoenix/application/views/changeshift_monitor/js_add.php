		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() {  
					if ($("#tgl").val() 			== "") { alert('Tgl harus diisi'); return false; } 
					if ($("#equip_num").val() 		== "") { alert('Unit harus diisi'); return false; } 
					if ($("#nrp").val() 			== "") { alert('Nama harus diisi'); return false; } 
					if ($("#lokasi").val() 			== "") { alert('Lokasi harus diisi'); return false; } 
					//if ($("#status").val() 		== "") { alert('Status harus diisi'); return false; } 
					if ($("#shift").val() 			== "") { alert('Shift harus diisi'); return false; } 
					if ($("#time_in").val() 		== "") { alert('Jam masuk harus diisi'); return false; } 
					//if ($("#time_out").val() 		== "") { alert('Jam keluar harus diisi'); return false; } 
					//if ($("#bpm_in").val() 		== "") { alert('BPM harus diisi'); return false; } 
					//if ($("#spo_in").val() 		== "") { alert('SPO harus diisi'); return false; } 


				  	$("#frmData" ).submit();
				}); 

				$('.date-picker').datepicker({
				   autoclose: true,
				   todayHighlight: true,
				   format: 'yyyy-mm-dd'
				})
				$(".chosen-select").chosen();  
				 
				  
			})
		</script>