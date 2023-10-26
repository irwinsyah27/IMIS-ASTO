<script type="text/javascript"> 

jQuery(function($) { 
    setInterval(function()
    { 
        $.ajax({
          type:"post",
          url:"<?php echo site_url('test_update_track/update_data');?>",
          datatype:"html",
          success:function(data)
          {
              $("#list_body_data").html(data);
          }
        });
    }, 3000); //time in milliseconds 
})
</script>