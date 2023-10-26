<?php
  $var_refresh            = "5000";  
?>
		<script type="text/javascript">
			jQuery(function($) {   
				 
				$( "#SubmitData" ).click(function() {  
					
					if ($("#equipment_id").val() =="") { alert('Unit harus diisi'); return false; } 
					if ($("#master_breakdown_id").val() =="") { alert('Jenis breakdown harus diisi'); return false; } 
					if ($("#status_breakdown_id").val() =="") { alert('Status breakdown harus diisi'); return false; } 
					if ($("#master_lokasi_id").val() =="") { alert('Lokasi breakdown harus diisi'); return false; } 
					//if ($("#hm").val() =="") { alert('HM harus diisi'); return false; }  
					//if ($("#km").val() =="") { alert('KM harus diisi'); return false; }  
					if ($("#date_time_in").val() =="") { alert('Date time harus diisi'); return false; }  
					if ($("#diagnosa").val() =="") { alert('Problem harus diisi'); return false; }  
				  	$("#frmData" ).submit();
				}); 
				 
				$('#date_time_in').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 
				$('#date_time_out').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 


				jsonGetListDataScheduleServiceByTgl();
				setInterval(jsonGetListDataScheduleServiceByTgl, <?php echo $var_refresh;?>); //time in milliseconds  

			    function jsonGetListDataScheduleServiceByTgl () {
			        var   id = '<?php echo date("Y-m-d");?>'; 
			        var   createdHTML = '';
			        var   j = 0;
			        var   masuk = '';
			        $.getJSON("<?php echo site_url('breakdown/schedule_breakdown');?>", {id: id} ,function(responseData) {
			          for(item in responseData) {
			              j = eval(item) + 1;
			              formData = responseData[item];  
			              if (formData.keterangan_ps == null ) formData.keterangan_ps = '';
			              if (formData.masuk == null ) formData.masuk = ''; 
			              createdHTML += "<tr>"; 
			              createdHTML += "<td>"+formData.unit+"</td>";
			              createdHTML += "<td>"+formData.keterangan_ps+"</td>";
			              createdHTML += "<td>"+formData.masuk+"</td>"; 
			              createdHTML += "</tr>";
			          }

			          $("#list_schedule_breakdown").html(createdHTML); 
			        });
			    }


				$(".chosen-select").chosen();  

			})
		</script>