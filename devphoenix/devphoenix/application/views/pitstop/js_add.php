		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() {  
					if ($("#equipment_id").val() =="") { alert('Unit harus diisi'); return false; } 
					if ($("#shift").val() =="") { alert('Shift harus diisi'); return false; } 
					if ($("#date_time_in").val() =="") { alert('Tgl masuk harus diisi'); return false; } 
					if ($("#date_time_out").val() =="") { alert('Tgl Keluar harus diisi'); return false; } 
					if ($("#description").val() =="") { alert('Deskripsi harus diisi'); return false; } 
				  	$("#frmData" ).submit();
				}); 
				 
				$('#date_time_in').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 



				$(".chosen-select").chosen();  
			})
		</script>