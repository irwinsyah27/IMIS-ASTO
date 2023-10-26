		<script type="text/javascript">
			jQuery(function($) {  
				  
				$("#bruto").keyup(function() { 
					if (isNaN($("#bruto").val())) $("#bruto").val(0);
					if (isNaN($("#tara").val())) $("#tara").val(0);
					if ($("#tara").val() =='') $("#tara").val(0);
					var x = eval($("#bruto").val()) - eval($("#tara").val());
					$("#netto").val(x);
				});
				$("#tara").keyup(function() { 
					if (isNaN($("#bruto").val())) $("#bruto").val(0);
					if (isNaN($("#tara").val())) $("#tara").val(0);
					var x = eval($("#bruto").val()) - eval($("#tara").val());
					$("#netto").val(x);
				});

				$( "#SubmitData" ).click(function() {  
					if ($("#equipment_id").val() =="") { alert('Unit harus diisi'); return false; } 
					if ($("#shift").val() =="") { alert('Shift harus diisi'); return false; } 
					if ($("#bruto").val() =="") { alert('Bruto harus diisi'); return false; } 
					if ($("#date_weigher").val() =="") { alert('Tgl harus diisi'); return false; } 
					if ($("#time_weigher").val() =="") { alert('Jam harus diisi'); return false; }  
					if ($("#no_doket").val() =="") { alert('No doket harus diisi'); return false; }  
					if ($("#material_id").val() =="") { alert('Kode material harus diisi'); return false; }  
				  	$("#frmData" ).submit();
				}); 
				 
				$('#date_weigher').datepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				});
				/*
				$('#time_weigher').timepicker({showSeconds: false,showMeridian: false}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				});
				*/



				$(".chosen-select").chosen();  
				$('.date-picker').datepicker({
						autoclose: true,
						todayHighlight: true
					})

				$( "#equipment_id" ).change(function() {  
				  	//alert(document.getElementById("equipment_id").value);return false;
				  	data = "equipment_id=" + document.getElementById("equipment_id").value;
				  	$.ajax({
			          type:"post",
			          data: data,
			          url:"<?php echo site_url('timbangan_cpp/getTaraKemarin');?>",
			          datatype:"json",
			          success:function(data)
			          {
			          		var obj = JSON.parse(data);   
			          		document.getElementById("tara_kemarin").value  = obj.tara; 
			          }
			        });	
				}); 
			})
		</script>