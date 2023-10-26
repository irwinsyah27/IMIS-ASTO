		<script type="text/javascript">
			jQuery(function($) {  
				 
				$( "#SubmitData" ).click(function() {  
					if ($("#new_eq_num").val() =="") { alert('Kode unit harus diisi'); return false; }  
					if ($("#master_alokasi_id").val() =="") { alert('Type harus diisi'); return false; }
					if ($("#master_egi_id").val() =="") { alert('EGI harus diisi'); return false; }
					if ($("#master_owner_id").val() =="") { alert('Owner harus diisi'); return false; }
					if ($("#status").val() =="") { alert('Status harus diisi'); return false; }
					if ($("#standby").val() =="") { alert('Standby harus diisi'); return false; }
				  	$("#frmData" ).submit();
				});
				
			})
		</script>