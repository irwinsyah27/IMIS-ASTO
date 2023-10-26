		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() {  
				  	$("#frmData" ).submit();
				}); 
				 
				$('#date_instruksi').datepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 



				$(".chosen-select").chosen();  

				$( "#equipment_id" ).change(function() {  
				  	//alert('test');
				  	data = "equipment_id=" + document.getElementById("equipment_id").value;
				  	$.ajax({
			          type:"post",
			          data: data,
			          url:"<?php echo site_url('instruksi_isi_bensin/getCycleTime');?>",
			          datatype:"json",
			          success:function(data)
			          {
			          		var obj = JSON.parse(data);   
			          		document.getElementById("cycle_time_terakhir").value  = obj.cycle_time;
			          		document.getElementById("total_liter").value  = obj.pengisian; 
			          }
			        });	
				}); 
			})
		</script>