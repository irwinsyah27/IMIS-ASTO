<?php
  $var_refresh            = "5000";  
?>		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() {  
					if ($("#nrp").val() =="") { alert('Nrp & Nama harus diisi'); return false; } 
					if ($("#date_rc").val() =="") { alert('Tanggal harus diisi'); return false; } 
					if ($("#shift").val() =="") { alert('Shift harus diisi'); return false; } 
					if ($("#master_lokasi_id").val() =="") { alert('Lokasi Kerusakan Jalan harus diisi'); return false; } 
					if ($("#master_problem_road_id").val() =="") { alert('Jenis Kerusakan harus diisi'); return false; }  
					if ($("#severity").val() =="") { alert('Severity harus diisi'); return false; }  

				  	$("#frmData" ).submit();
				}); 

				$('#date_rc').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				});  
				$(".chosen-select").chosen();  
			})
		</script>