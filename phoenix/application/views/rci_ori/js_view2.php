<script type="text/javascript">
			jQuery(function($) {  


			    $('.multiselect').multiselect({
			         enableFiltering: true,
			         enableHTML: true,
			         buttonClass: 'btn btn-white btn-primary',
			         templates: {
			          button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"><span class="multiselect-selected-text"></span> &nbsp;<b class="fa fa-caret-down"></b></button>',
			          ul: '<ul class="multiselect-container dropdown-menu"></ul>',
			          filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
			          filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default btn-white btn-grey multiselect-clear-filter" type="button"><i class="fa fa-times-circle red2"></i></button></span>',
			          li: '<li><a tabindex="0"><label></label></a></li>',
			              divider: '<li class="multiselect-item divider"></li>',
			              liGroup: '<li class="multiselect-item multiselect-group"><label></label></li>'
			         }
			    });

			    $('.input-daterange').datepicker(
				    {  
				    	autoclose:true,
				      	format: 'yyyy-mm-dd'
				    }
			    );

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
					  { "bSortable": false, "aTargets": [ 10 ] }
					], 
					"aaSorting": [
					  	[0,'desc'] 
					],
					"sAjaxSource": "<?php echo base_url('rci/get_data');?>",
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
			})
		</script>