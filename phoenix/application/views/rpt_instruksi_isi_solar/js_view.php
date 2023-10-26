<script type="text/javascript"> 

jQuery(function($) {  
    $('.input-daterange').datepicker(
    {  
      autoclose:true,
      format: 'yyyy-mm-dd'
    }
    );
    $('.date-picker').datepicker({
      autoclose: true,
      todayHighlight: true ,
           format: 'yyyy-mm-dd'
    })
})
</script>