		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() {  
					if ($("#nrp").val() =="") { alert('NRP harus diisi'); return false; }  
					if ($("#nama").val() =="") { alert('Nama harus diisi'); return false; }
					//if ($("#master_posisi_id").val() =="") { alert('Jabatan harus diisi'); return false; }
					//if ($("#master_departemen_id").val() =="") { alert('Departemen harus diisi'); return false; }
					//if ($("#master_owner_id").val() =="") { alert('Perusahaan harus diisi'); return false; }
				  	$("#frmData" ).submit();
				});
				
			})
		</script>