        <script type="text/javascript">
            jQuery(function($) {  
                 
                $( "#SubmitData" ).click(function() {  
                    $("#frmData" ).submit();
                });  
			    $('.date-picker').datepicker({
			      autoclose: true,
			      todayHighlight: true,
			      format: 'yyyy-mm-dd'
			    })
            })
        </script>