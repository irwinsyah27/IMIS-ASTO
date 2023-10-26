		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() {  
					 
					if ($("#mulai_tidur_hari_ini").val() =="") { alert('Jam Mulai tidur hari ini harus diisi'); return false; } 
					if ($("#bangun_tidur_hari_ini").val() =="") { alert('Jam bangun tidur hari ini harus diisi'); return false; } 
   
					if ($("input[name='apakah_sedang_minum_obat']:checked").val() == undefined) { alert('Ada pertanyaan yang belum diisi'); return false; } 
					if ($("input[name='apakah_sedang_ada_masalah']:checked").val() == undefined) { alert('Ada pertanyaan yang belum diisi'); return false; } 
					if ($("input[name='apakah_siap_bekerja']:checked").val() == undefined) { alert('Ada pertanyaan yang belum diisi'); return false; } 
					
					/*
					if ($("input[name='apakah_mempunyai_apd_yang_sesuai']:checked").val() == undefined) { alert('Ada pertanyaan yang belum diisi'); return false; } 
					if ($("input[name='apakah_dalam_kondisi_fit']:checked").val() == undefined) { alert('Ada pertanyaan yang belum diisi'); return false; } 
					if ($("input[name='apakah_memerlukan_ijin_khusus']:checked").val() == undefined) { alert('Ada pertanyaan yang belum diisi'); return false; } 
					if ($("input[name='apakah_memahami_prosedur']:checked").val() == undefined) { alert('Ada pertanyaan yang belum diisi'); return false; } 
					if ($("input[name='apakah_mempunyai_peralatan_yang_benar']:checked").val() == undefined) { alert('Ada pertanyaan yang belum diisi'); return false; } 
					if ($("input[name='apakah_ada_aktivitas_lain_disekitar_saya']:checked").val() == undefined) { alert('Ada pertanyaan yang belum diisi'); return false; } 
					if ($("input[name='apakah_mengenali_bahaya']:checked").val() == undefined) { alert('Ada pertanyaan yang belum diisi'); return false; } 
					if ($("input[name='apakah_focus']:checked").val() == undefined) { alert('Ada pertanyaan yang belum diisi'); return false; } 
					if ($("input[name='apakah_atasan_mengetahui']:checked").val() == undefined) { alert('Ada pertanyaan yang belum diisi'); return false; } 
					if ($("input[name='apakah_pekerjaan_bisa_dilanjutkan']:checked").val() == undefined) { alert('Ada pertanyaan yang belum diisi'); return false; } 
					*/
				  	$("#frmData" ).submit();
				}); 
				  /*
				$('#mulai_tidur_hari_ini').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 
				
				$('#bangun_tidur_hari_ini').datetimepicker().next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 
				*/


				$(".chosen-select").chosen();  
				$('.date-picker').datepicker({
						autoclose: true,
						todayHighlight: true,
						format: 'yyyy-mm-dd'
					})
			})
 
		</script>