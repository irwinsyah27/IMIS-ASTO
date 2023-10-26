		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() {   
					if( $("#date_time_in").val() > $("#date_time_out").val() ) {
					    alert("Tgl Jam keluar harus lebih besar dari jam masuk");
					    return false;
					}

					if ($("#kriteria_komponen_id").val() =="") { alert('Kriteria komponen harus diisi'); return false; } 
					if ($("#date_time_out").val() =="") { alert('Tgl Keluar harus diisi'); return false; } 
					if ($("#tindakan").val() =="") { alert('Tindakan harus diisi'); return false; } 
				  	$("#frmData" ).submit();
				}); 
				/*
				 
				$('#date_time_in').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 
				$('#date_time_out').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 
	*/


				$('#date_time_out').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 

				$('#eta_rfu_unit').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 

				$('#eta_waiting_part').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 


				$(".chosen-select").chosen();  
			})
		</script>