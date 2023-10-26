		<script type="text/javascript">
			jQuery(function($) {  
				  
				$( "#SubmitData" ).click(function() {  
					if ($("#factor").val() =="") { alert('Factor harus diisi'); return false; }   
					if ($("#batas_hm_bawah").val() =="") { alert('Batas HM bawah harus diisi'); return false; }  
					if ($("#batas_hm_atas").val() =="") { alert('Batas HM atas harus diisi'); return false; }   
				  	$("#frmData" ).submit();
				}); 
				  
			})
		</script>