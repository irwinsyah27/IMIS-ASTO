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
		
				$('#dynamic-table').on('click','a[data-confirm]',function(ev){
					var href = $(this).attr('href');
					if (!$('#dataConfirmModal').length){
						$('body').append('<div id="dataConfirmModal" class="modal fade" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header no-padding"><div class="table-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="white">&times;</span></button>KONFIRMASI</div></div><div class="modal-body" style="height:100px;">Apakah anda yakin akan menghapus data ini?</div><div class="modal-footer no-margin-top"><button class="btn btn-sm btn-danger pull-left" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>Batal</button><a class="btn btn-danger" id="dataConfirmOK">OK</a></div></div></div></div>');
					}
					$('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
					$('#dataConfirmOK').attr('href', href);
					$('#dataConfirmModal').modal({show:true});
					return false;
				});

				var myTable = 
				$('#dynamic-table') 
				.DataTable( {
					bAutoWidth: false,
					"aoColumnDefs": [ 
					  { "bSortable": false, "aTargets": [ 8 ] }
					], 
					"aaSorting": [
					  	[0,'desc']
					],
					"sAjaxSource": "<?php echo base_url('pitstop/get_data');?>",
					"bProcessing": true,
			        "bServerSide": true,  
					select: {
						style: 'multi'
					}
			    } );
			
				
				
				myTable.on( 'select', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
					}
				} );
				myTable.on( 'deselect', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
					}
				} ); 
			
			
				/////////////////////////////////
				//table checkboxes
				$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
				
				//select/deselect all rows according to table header checkbox
				$('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$('#dynamic-table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) myTable.row(row).select();
						else  myTable.row(row).deselect();
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
					var row = $(this).closest('tr').get(0);
					if(this.checked) myTable.row(row).deselect();
					else myTable.row(row).select();
				}); 
			
				$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
					e.stopImmediatePropagation();
					e.stopPropagation();
					e.preventDefault();
				}); 
				 
				//add tooltip for small view action buttons in dropdown menu
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				
				//tooltip placement on right or left
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				} 
				 
				$('.show-details-btn').on('click', function(e) {
					e.preventDefault();
					$(this).closest('tr').next().toggleClass('open');
					$(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
				}); 

				<?php /*
				$.fn.dataTable.Buttons.swfPath = "<?php echo _ASSET_TEMPLATE;?>components/datatables.net-buttons-swf/index.swf"; //in Ace demo ../components will be replaced by correct assets path
				$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
				
				new $.fn.dataTable.Buttons( myTable, {
					buttons: [
					  {
						"extend": "colvis",
						"text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
						"className": "btn btn-white btn-primary btn-bold",
						columns: ':not(:first):not(:last)'
					  },
					  {
						"extend": "copy",
						"text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "csv",
						"text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "excel",
						"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "pdf",
						"text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "print",
						"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
						"className": "btn btn-white btn-primary btn-bold",
						autoPrint: false,
						message: 'This print was produced using the Print button for DataTables'
					  }		  
					]
				} );
				myTable.buttons().container().appendTo( $('.tableTools-container') );
				
				//style the message box
				var defaultCopyAction = myTable.button(1).action();
				myTable.button(1).action(function (e, dt, button, config) {
					defaultCopyAction(e, dt, button, config);
					$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
				});
				
				
				var defaultColvisAction = myTable.button(0).action();
				myTable.button(0).action(function (e, dt, button, config) {
					
					defaultColvisAction(e, dt, button, config);
					
					
					if($('.dt-button-collection > .dropdown-menu').length == 0) {
						$('.dt-button-collection')
						.wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
						.find('a').attr('href', '#').wrap("<li />")
					}
					$('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
				});
			
				////
			
				setTimeout(function() {
					$($('.tableTools-container')).find('a.dt-button').each(function() {
						var div = $(this).find(' > div').first();
						if(div.length == 1) div.tooltip({container: 'body', title: div.parent().text()});
						else $(this).tooltip({container: 'body', title: $(this).text()});
					});
				}, 500); 
				*/ ?>
			})
		</script>