<script type="text/javascript"> 

jQuery(function($) {  
    $('.input-daterange').datepicker(
    { 
    	locale: {
      		format: 'YYYY-MM-DD'
    	},
    	autoclose:true
    }
    );
    $('.date-picker').datepicker({
      autoclose: true,
      todayHighlight: true
    })
})
</script>