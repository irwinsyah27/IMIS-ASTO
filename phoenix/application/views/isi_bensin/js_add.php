		<script type="text/javascript">
			jQuery(function($) {

				$("#total_realisasi").keyup(function() {  if (isNaN($("#total_realisasi").val())) $("#total_realisasi").val('');  });
				$("#hm").keyup(function() {  if (isNaN($("#hm").val())) $("#hm").val('');  });

				$( "#SubmitData" ).click(function() {

					if ( eval($("#hm").val()) < eval($("#hm_last").val()) ) {
						alert("HM hari ini harus lebih besar dari HM kemarin");
						return false;
					}

					if ($("#date_fill").val() =="") { alert('Tanggal harus diisi'); return false; }
					if ($('#fuel-tank-id').val() == '') { alert('Fuel Tank harus diisi'); return false; }
					if ($("#equipment_id").val() =="") { alert('Unit harus diisi'); return false; } 
					if ($("#shift").val() =="") { alert('Shift harus diisi'); return false; }
					if ($("#total_realisasi").val() =="") { alert('Total pengisian harus diisi'); return false; }
					//if ($("#hm").val() =="") { alert('hm harus diisi'); return false; }
					// if ($("#km").val() =="") { alert('km harus diisi'); return false; }
					if ($("#nrp").val() =="") { alert('NRP harus diisi'); return false; }
					if ($("#time_fill_start").val() =="") { alert('Jam mulai harus diisi'); return false; }
					if ($("#time_fill_end").val() =="") { alert('Jam selesai harus diisi'); return false; }


					var valueStart = $("#time_fill_start").val();
					var valueStop = $("#time_fill_end").val();

					var diff =
				        new Date( '01/01/1970 ' + valueStop) -
				        new Date( '01/01/1970 ' + valueStart );

				    var sec_numb=(diff /1000)+"";
				    var hours   = Math.floor(sec_numb / 3600);
				    var minutes = Math.floor((sec_numb - (hours * 3600)) / 60);
				    var seconds = sec_numb - (hours * 3600) - (minutes * 60);

				    if ((hours != 0) || (minutes > 10) || (minutes <0)) {
				    	alert("Waktu pengisian maksimal 10 menit.")
				    	return false;
				    }

				  	$("#frmData" ).submit();
				});

				$('#date_fill').datepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				});

				$('#time_fill').timepicker({showSeconds: false,showMeridian: false}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				});



				$(".chosen-select").chosen();

				$( "#equipment_id" ).change(function() {
				  	//alert('test');
				  	data = "equipment_id=" + document.getElementById("equipment_id").value;
				  	$.ajax({
			          type:"post",
			          data: data,
			          url:"<?php echo site_url('isi_bensin/getCycleTime');?>",
			          datatype:"json",
			          success:function(data)
			          {
			          		var obj = JSON.parse(data);
			          		document.getElementById("cycle_time_terakhir").value  = obj.cycle_time;
			          		document.getElementById("total_liter").value  = obj.pengisian;
			          }
			        });
			        $.ajax({
			          type:"post",
			          data: data,
			          url:"<?php echo site_url('isi_bensin/gethmterakhir');?>",
			          datatype:"json",
			          success:function(data)
			          {
			          		var obj = JSON.parse(data);
			          		document.getElementById("hm_last").value  = obj.hm_last;
			          		document.getElementById("km_last").value  = obj.km_last;
			          }
			        });
				});

			})
		</script>
