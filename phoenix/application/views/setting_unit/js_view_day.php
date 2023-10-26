<script type="text/javascript">
			jQuery(function($) {  

			    $('.input-daterange').datepicker(
				    {  
				    	autoclose:true,
				      	format: 'yyyy-mm-dd'
				    }
			    );

				$("#btnEksportData").click(function() {
					$('#modal-eksport-data').modal({show:true});
				});
				$("#submit-eksport-data").click(function() {
					var data_post = $('#btnEksportData').serialize();
					// #data_post.push({ name: "action", value: "save" });
					$.ajax({ 
					    type: "POST",
					    url: form.attr( 'action' ),
					    data: data_post,
					    success: function( response ) {
					        console.log( response );
					        $('#modal-eksport-data').modal({show:false});
					    },	
					    error: function() {
					        alert('error handing here');
					    }
				    }); 
					return false;
				});


				$('#userfile').ace_file_input({
					style: 'well',
					btn_choose: 'Drop files here or click to choose',
					btn_change: null,
					no_icon: 'ace-icon fa fa-cloud-upload',
					droppable: true,
					thumbnail: 'small' 
					,
					preview_error : function(filename, error_code) { 
					}
				}).on('change', function(){ 
				});

				$("#btnImportData").click(function() {
					$('#modal-import-data').modal({show:true});
				});
				$("#submit-import-data").click(function() {
					var data_post = $('#frmImportData').serialize();
					// #data_post.push({ name: "action", value: "save" });
					$.ajax({
						xhr: function () {
					        var xhr = new window.XMLHttpRequest();
					        xhr.upload.addEventListener("progress", function (evt) {
					            if (evt.lengthComputable) {
					                var percentComplete = evt.loaded / evt.total;
					                console.log(percentComplete);
					                $('.progress').css({
					                    width: percentComplete * 100 + '%'
					                });
					                if (percentComplete === 1) {
					                    $('.progress').addClass('hide');
					                }
					            }
					        }, false);
					        xhr.addEventListener("progress", function (evt) {
					            if (evt.lengthComputable) {
					                var percentComplete = evt.loaded / evt.total;
					                console.log(percentComplete);
					                $('.progress').css({
					                    width: percentComplete * 100 + '%'
					                });
					            }
					        }, false);
					        return xhr;
					    },
					    type: "POST",
					    url: form.attr( 'action' ),
					    data: data_post,
					    success: function( response ) {
					        console.log( response );
					    },	
					    error: function() {
					        alert('error handing here');
					    }
				    }); 
					return false;
				});
				 
			})
		</script>