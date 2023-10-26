		<script type="text/javascript">
			jQuery(function($) {  
				$("#view").click(function() {  
					if(this.checked){
			            $('.checkbox_view').each(function(){
			                this.checked = true;
			            });
			        }else{
			             $('.checkbox_view').each(function(){
			                this.checked = false;
			            });
			        }
				});
				$('.checkbox_view').on('click',function(){
			        if($('.checkbox_view:checked').length == $('.checkbox_view').length){
			            $('#view').prop('checked',true);
			        }else{
			            $('#view').prop('checked',false);
			        }
			    }); 

				$("#add").click(function() {  
					if(this.checked){
			            $('.checkbox_add').each(function(){
			                this.checked = true;
			            });
			        }else{
			             $('.checkbox_add').each(function(){
			                this.checked = false;
			            });
			        }
				});
				$('.checkbox_add').on('click',function(){
			        if($('.checkbox_add:checked').length == $('.checkbox_add').length){
			            $('#add').prop('checked',true);
			        }else{
			            $('#add').prop('checked',false);
			        }
			    });

				$("#update").click(function() {  
					if(this.checked){
			            $('.checkbox_update').each(function(){
			                this.checked = true;
			            });
			        }else{
			             $('.checkbox_update').each(function(){
			                this.checked = false;
			            });
			        }
				});
				$('.checkbox_update').on('click',function(){
			        if($('.checkbox_update:checked').length == $('.checkbox_update').length){
			            $('#update').prop('checked',true);
			        }else{
			            $('#update').prop('checked',false);
			        }
			    });

				$("#delete").click(function() {  
					if(this.checked){
			            $('.checkbox_delete').each(function(){
			                this.checked = true;
			            });
			        }else{
			             $('.checkbox_delete').each(function(){
			                this.checked = false;
			            });
			        }
				});
				$('.checkbox_delete').on('click',function(){
			        if($('.checkbox_delete:checked').length == $('.checkbox_delete').length){
			            $('#delete').prop('checked',true);
			        }else{
			            $('#delete').prop('checked',false);
			        }
			    });

			    $("#import").click(function() {  
					if(this.checked){
			            $('.checkbox_import').each(function(){
			                this.checked = true;
			            });
			        }else{
			             $('.checkbox_import').each(function(){
			                this.checked = false;
			            });
			        }
				});
				$('.checkbox_import').on('click',function(){
			        if($('.checkbox_import:checked').length == $('.checkbox_import').length){
			            $('#import').prop('checked',true);
			        }else{
			            $('#import').prop('checked',false);
			        }
			    });

				$("#eksport").click(function() {  
					if(this.checked){
			            $('.checkbox_eksport').each(function(){
			                this.checked = true;
			            });
			        }else{
			             $('.checkbox_eksport').each(function(){
			                this.checked = false;
			            });
			        }
				});
				$('.checkbox_eksport').on('click',function(){
			        if($('.checkbox_eksport:checked').length == $('.checkbox_eksport').length){
			            $('#eksport').prop('checked',true);
			        }else{
			            $('#eksport').prop('checked',false);
			        }
			    });
			    


				$( "#SubmitData" ).click(function() {  
				  	$("#frmData" ).submit();
				});  
			})
		</script>